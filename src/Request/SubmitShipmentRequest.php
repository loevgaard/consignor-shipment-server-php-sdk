<?php
namespace Loevgaard\Consignor\ShipmentServer\Request;

use Loevgaard\Consignor\ShipmentServer\Response\SubmitShippingResponse;

class SubmitShipmentRequest extends Request
{
    public function __construct(array $data, array $options)
    {
        $this->data = $data;
        $this->options = $options;
    }

    public function getCommand(): string
    {
        return RequestInterface::COMMAND_SUBMIT_SHIPMENT;
    }

    public function getResponseClass(): string
    {
        return SubmitShippingResponse::class;
    }
}
