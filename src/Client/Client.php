<?php

namespace Loevgaard\Consignor\ShipmentServer\Client;

use Assert\Assert;
use Buzz\Client\Curl;
use Loevgaard\Consignor\ShipmentServer\Request\RequestInterface;
use Loevgaard\Consignor\ShipmentServer\Response\ResponseInterface;
use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface as PsrRequestInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;
use Psr\Http\Message\StreamFactoryInterface;

class Client
{
    const ENV_DEV = 'dev';
    const ENV_PRODUCTION = 'production';

    /**
     * This URL is used for testing purposes
     *
     * @var string
     */
    protected $testServerUrl = 'http://sstest.consignor.com/ship/ShipmentServerModule.dll';

    /**
     * This URL is used for production requests
     *
     * @var string
     */
    protected $productionServerUrl = 'https://www.shipmentserver.com/ship/ShipmentServerModule.dll';

    /**
     * @var string
     */
    protected $actor;

    /**
     * @var string
     */
    protected $key;

    /**
     * Should be either 'dev' or 'production'
     *
     * @var string
     */
    protected $environment;

    /**
     * @var ClientInterface
     */
    protected $httpClient;

    /**
     * @var RequestFactoryInterface
     */
    protected $requestFactory;

    /**
     * @var StreamFactoryInterface|null
     */
    private $streamFactory;

    /**
     * This is the last psr request
     *
     * @var PsrRequestInterface|null
     */
    protected $request;

    /**
     * This is the last PSR response
     *
     * @var PsrResponseInterface|null
     */
    protected $response;

    public function __construct(
        string $actor,
        string $key,
        string $environment = 'production',
        ClientInterface $httpClient = null,
        ResponseFactoryInterface $responseFactory = null,
        RequestFactoryInterface $requestFactory = null,
        StreamFactoryInterface $streamFactory = null
    ) {
        $this->actor = $actor;
        $this->key = $key;

        $psr17Factory = new Psr17Factory();

        if(null === $responseFactory) {
            $responseFactory = $psr17Factory;
        }

        if(null === $requestFactory) {
            $requestFactory = $psr17Factory;
        }
        $this->requestFactory = $requestFactory;

        if(null === $streamFactory) {
            $streamFactory = new $psr17Factory;
        }
        $this->streamFactory = $streamFactory;

        if(null === $httpClient) {
            $httpClient = new Curl($responseFactory);
        }
        $this->httpClient = $httpClient;

        Assert::that($environment)->choice([self::ENV_DEV, self::ENV_PRODUCTION]);
        $this->environment = $environment;
    }

    public function doRequest(RequestInterface $request): ResponseInterface
    {
        // resetting last request and response
        $this->request = null;
        $this->response = null;

        // deduce url
        $url = $this->environment === self::ENV_DEV ? $this->testServerUrl : $this->productionServerUrl;

        // convert body to post string and inject auth and command params
        $body = $request->getBody();
        $body['actor'] = $this->actor;
        $body['key'] = $this->key;
        $body['command'] = $request->getCommand();

        // create request
        $psrRequest = $this->requestFactory->createRequest('POST', $url);
        $psrRequest = $psrRequest
            ->withHeader('Content-Type', 'application/x-www-form-urlencoded')
            ->withHeader('Accept', 'application/json')
            ->withBody($this->streamFactory->createStream(http_build_query($body)))
        ;
        $this->request = $psrRequest;

        // send request
        $this->response = $this->httpClient->sendRequest($this->request);

        $responseClass = $request->getResponseClass();

        return new $responseClass($this->response, $request);
    }

    public function getActor(): string
    {
        return $this->actor;
    }

    public function getRequest(): ?PsrRequestInterface
    {
        return $this->request;
    }

    public function getResponse(): ?PsrResponseInterface
    {
        return $this->response;
    }
}
