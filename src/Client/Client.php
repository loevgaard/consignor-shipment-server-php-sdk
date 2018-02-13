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
use function GuzzleHttp\Psr7\build_query;
use function Loevgaard\Consignor\ShipmentServer\decodeJson;

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

        Assert::that($environment)->choice([self::ENV_DEV, self::ENV_PRODUCTION]);
        $this->environment = $environment;
    }

    public function doRequest(RequestInterface $request) : array
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

        // @todo use header plugin instead
        $headers = [];
        $headers['Content-Type'] = 'application/x-www-form-urlencoded';
        $headers['Accept'] = 'application/json';

        // create request
        $this->request = $this->requestFactory->createRequest('POST', $url, $headers, $body);

        // send request
        $this->response = $this->httpClient->sendRequest($this->request);

        return decodeJson((string)$this->response->getBody());
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
