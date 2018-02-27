<?php
namespace Loevgaard\Consignor\ShipmentServer\Response;

use Loevgaard\Consignor\ShipmentServer\Exception\InvalidJsonException;
use Loevgaard\Consignor\ShipmentServer\Request\RequestInterface;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;
use function Loevgaard\Consignor\ShipmentServer\decodeJson;

class Response implements ResponseInterface, \ArrayAccess
{
    /**
     * @var PsrResponseInterface
     */
    protected $response;

    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * @var array
     */
    protected $data;

    /**
     * @param PsrResponseInterface $response
     * @param RequestInterface $request
     * @throws InvalidJsonException
     */
    public function __construct(PsrResponseInterface $response, RequestInterface $request)
    {
        $this->response = $response;
        $this->request = $request;
        $this->data = decodeJson((string)$this->response->getBody());
    }

    public function __toString()
    {
        return (string)$this->response->getBody();
    }

    public function wasSuccessful() : bool
    {
        return !isset($this->data['ErrorMessages']) && $this->response->getStatusCode() >= 200 && $this->response->getStatusCode() < 300;
    }

    public function getErrors() : array
    {
        if (!isset($this->data['ErrorMessages']) || !is_array($this->data['ErrorMessages'])) {
            return [];
        }

        return $this->data['ErrorMessages'];
    }

    public function offsetExists($offset)
    {
        return isset($this->data[$offset]);
    }

    public function offsetGet($offset)
    {
        return $this->data[$offset] ?? null;
    }

    public function offsetSet($offset, $value)
    {
        throw new \BadMethodCallException('The response data can not be manipulated');
    }

    public function offsetUnset($offset)
    {
        throw new \BadMethodCallException('The response data can not be manipulated');
    }
}
