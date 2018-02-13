<?php
namespace Loevgaard\Consignor\Client;

use Assert\Assert;
use Http\Client\HttpClient;
use Http\Discovery\HttpClientDiscovery;
use Http\Discovery\MessageFactoryDiscovery;
use Http\Message\RequestFactory;
use Psr\Http\Message\RequestInterface;
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
     * @var RequestInterface
     */
    protected $request;

    /**
     * This is the last response
     *
     * @var ResponseInterface
     */
    protected $response;

    public function __construct(HttpClient $httpClient = null, RequestFactory $requestFactory = null, string $environment = 'production')
    {
        $this->httpClient = $httpClient ?: HttpClientDiscovery::find();
        $this->requestFactory = $requestFactory ?: MessageFactoryDiscovery::find();

        Assert::that($environment)->choice(['dev', 'production']);
        $this->environment = $environment;
    }

    public function doRequest()
    {
        // resetting last request and response
        $this->request = null;
        $this->response = null;

        // deduce url
        $url = $this->environment === 'dev' ? $this->testServerUrl : $this->productionServerUrl;

        // create headers array
        $headers = [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json'
        ];

        // create request
        $this->request = $this->requestFactory->createRequest('POST', $url, $headers);

        // send request
        $this->response = $this->httpClient->sendRequest($this->request);
    }
}