<?php
namespace Loevgaard\Consignor\ShipmentServer\Client;

use Assert\Assert;
use Http\Client\HttpClient;
use Http\Discovery\HttpClientDiscovery;
use Http\Discovery\MessageFactoryDiscovery;
use Http\Message\RequestFactory;
use Loevgaard\Consignor\ShipmentServer\Request\RequestInterface;
use Psr\Http\Message\RequestInterface as PsrRequestInterface;
use Psr\Http\Message\ResponseInterface;

class Client
{
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
     * This is the last response
     *
     * @var ResponseInterface|null
     */
    protected $response;

    public function __construct(string $actor, string $key, HttpClient $httpClient = null, RequestFactory $requestFactory = null, string $environment = 'production')
    {
        $this->actor = $actor;
        $this->key = $key;
        $this->httpClient = $httpClient ?: HttpClientDiscovery::find();
        $this->requestFactory = $requestFactory ?: MessageFactoryDiscovery::find();

        Assert::that($environment)->choice(['dev', 'production']);
        $this->environment = $environment;
    }

    public function doRequest(RequestInterface $request) : array
    {
        // resetting last request and response
        $this->request = null;
        $this->response = null;

        // deduce url
        $url = $this->environment === 'dev' ? $this->testServerUrl : $this->productionServerUrl;

        // convert body to post string and inject auth and command params
        // @todo ask consignor if it's true that ALL requests are POST requests
        $body = $request->getBody();
        $body['actor'] = $this->actor;
        $body['key'] = $this->key;
        $body['command'] = $request->getCommand();
        $body = http_build_query($body);

        // create request
        $this->request = $this->requestFactory->createRequest($request->getMethod(), $url, $request->getHeaders(), $body);

        // send request
        $this->response = $this->httpClient->sendRequest($this->request);

        return $this->decodeJson((string)$this->response->getBody());
    }

    /**
     * @return null|PsrRequestInterface
     */
    public function getRequest(): ?PsrRequestInterface
    {
        return $this->request;
    }

    /**
     * @return null|ResponseInterface
     */
    public function getResponse(): ?ResponseInterface
    {
        return $this->response;
    }
}