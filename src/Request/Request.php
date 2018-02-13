<?php
namespace Loevgaard\Consignor\ShipmentServer\Request;

use function Loevgaard\Consignor\ShipmentServer\encodeJson;
use Loevgaard\Consignor\ShipmentServer\Exception\EncodeJsonException;

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

    public function getHeaders() : array
    {
        return [];
    }

    /**
     * @return array
     * @throws EncodeJsonException
     */
    public function getBody() : array
    {
        $body = [];
        if($this->data) {
            $body['data'] = encodeJson($this->data);
        }

        if($this->options) {
            $body['Options'] = encodeJson($this->options);
        }

        return $body;
    }
}