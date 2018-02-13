<?php
namespace Loevgaard\Consignor\ShipmentServer\Client;

use Assert\Assert;
use Http\Client\Common\Plugin\ErrorPlugin;
use Http\Client\Common\PluginClient;
use Http\Client\HttpClient;
use Http\Discovery\HttpClientDiscovery;
use Http\Discovery\MessageFactoryDiscovery;
use Http\Message\RequestFactory;
use Loevgaard\Consignor\ShipmentServer\Request\RequestInterface;
use Loevgaard\Consignor\ShipmentServer\Response\ResponseInterface;
use Psr\Http\Message\RequestInterface as PsrRequestInterface;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;
use function GuzzleHttp\Psr7\build_query;

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
     * @var HttpClient
     */
    protected $httpClient;

    /**
     * @var RequestFactory
     */
    protected $requestFactory;

    /**
     * This is the last request
     *
     * @var PsrRequestInterface|null
     */
    protected $request;

    /**
     * This is the last PSR response (differs from the response returned form the doRequest method)
     *
     * @var PsrResponseInterface|null
     */
    protected $response;

    public function __construct(string $actor, string $key, array $plugins = [], HttpClient $httpClient = null, RequestFactory $requestFactory = null, string $environment = 'production')
    {
        $this->actor = $actor;
        $this->key = $key;

        $plugins[] = new ErrorPlugin();
        $this->httpClient = new PluginClient($httpClient ?: HttpClientDiscovery::find(), $plugins);
        $this->requestFactory = $requestFactory ?: MessageFactoryDiscovery::find();

        Assert::that($environment)->choice([self::ENV_DEV, self::ENV_PRODUCTION]);
        $this->environment = $environment;
    }

    /**
     * @param RequestInterface $request
     * @return ResponseInterface
     */
    public function doRequest(RequestInterface $request) : ResponseInterface
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
        $body = build_query($body);

        // set headers
        $headers = [
            'Content-Type' => 'application/x-www-form-urlencoded',
            'Accept' => 'application/json'
        ];

        // create request
        // @todo this is not necessarily the real request, since it can be manipulated by plugins, figure out a way to retrieve the correct one
        $this->request = $this->requestFactory->createRequest('POST', $url, $headers, $body);

        // send request
        $this->response = $this->httpClient->sendRequest($this->request);

        $responseClass = $request->getResponseClass();

        return new $responseClass($this->response);
    }

    /**
     * @return string
     */
    public function getActor(): string
    {
        return $this->actor;
    }

    /**
     * @return null|PsrRequestInterface
     */
    public function getRequest(): ?PsrRequestInterface
    {
        return $this->request;
    }

    /**
     * @return null|PsrResponseInterface
     */
    public function getResponse(): ?PsrResponseInterface
    {
        return $this->response;
    }
}
