<?php
namespace Loevgaard\Dandomain\Api;

use GuzzleHttp\Psr7\Response;
use Http\Mock\Client as HttpClient;
use Loevgaard\Consignor\ShipmentServer\Client\Client;
use Loevgaard\Consignor\ShipmentServer\Request\GetDraftCountRequest;
use PHPUnit\Framework\TestCase;
use function GuzzleHttp\Psr7\parse_query;

final class ClientTest extends TestCase
{
    /**
     * @throws \Loevgaard\Consignor\ShipmentServer\Exception\InvalidJsonException
     */
    public function testDoRequest()
    {
        $httpClient = new HttpClient();
        $response = new Response(200, [], '{"Count": 800}');
        $httpClient->addResponse($response);

        $client = new Client('63', 'sample', [], $httpClient, null, Client::ENV_DEV);
        $res = $client->doRequest(new GetDraftCountRequest());
        $request = $httpClient->getRequests()[0];

        $body = (string)$request->getBody();
        $params = parse_query($body);

        $this->assertSame('POST', $request->getMethod());
        $this->assertSame('application/x-www-form-urlencoded', $request->getHeaderLine('Content-Type'));
        $this->assertSame('application/json', $request->getHeaderLine('Accept'));
        $this->assertSame('63', $params['actor']);
        $this->assertSame('sample', $params['key']);
        $this->assertSame('GetDraftCount', $params['command']);
        $this->assertSame(800, $res['Count']);
    }
}
