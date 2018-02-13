<?php
namespace Loevgaard\Consignor\ShipmentServer\Request;

class SubmitShipmentRequest extends Request
{
    public function __construct(array $data, array $options)
    {
        $this->data = $data;
        $this->options = $options;
    }

    public function getMethod(): string
    {
        return 'POST';
    }

    public function getCommand(): string
    {
        return RequestInterface::COMMAND_SUBMIT_SHIPMENT;
    }
}