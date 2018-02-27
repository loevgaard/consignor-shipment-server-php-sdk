<?php
namespace Loevgaard\Consignor\ShipmentServer\Request;

use Loevgaard\Consignor\ShipmentServer\Response\GetProductsResponse;

class GetProductsRequest extends Request
{
    public function getCommand(): string
    {
        return RequestInterface::COMMAND_GET_PRODUCTS;
    }

    public function getResponseClass(): string
    {
        return GetProductsResponse::class;
    }
}
