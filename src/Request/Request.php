<?php
namespace Loevgaard\Consignor\ShipmentServer\Request;

use Loevgaard\Consignor\ShipmentServer\Exception\EncodeJsonException;
use Loevgaard\Consignor\ShipmentServer\Response\Response;
use function Loevgaard\Consignor\ShipmentServer\encodeJson;

abstract class Request implements RequestInterface
{
    /**
     * @var array
     */
    protected $data;

    /**
     * @var array
     */
    protected $options;

    /**
     * @return array
     * @throws EncodeJsonException
     */
    public function getBody() : array
    {
        $body = [];
        if ($this->data) {
            $body['data'] = encodeJson($this->data);
        }

        if ($this->options) {
            $body['Options'] = encodeJson($this->options);
        }

        return $body;
    }

    public function getResponseClass(): string
    {
        return Response::class;
    }
}
