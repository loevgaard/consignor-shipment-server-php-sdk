<?php
namespace Loevgaard\Consignor\ShipmentServer;

use Loevgaard\Consignor\ShipmentServer\Exception\EncodeJsonException;
use Loevgaard\Consignor\ShipmentServer\Exception\InvalidJsonException;

/**
 * @param string $json
 * @return array
 * @throws InvalidJsonException
 */
function decodeJson(string $json) : array
{
    $data = \json_decode($json, true);
    if (JSON_ERROR_NONE !== json_last_error()) {
        throw new InvalidJsonException('json_decode error: ' . json_last_error_msg());
    }

    return (array)$data;
}

/**
 * @param array $data
 * @return string
 * @throws EncodeJsonException
 */
function encodeJson(array $data) : string
{
    $json = \json_encode($data);
    if (JSON_ERROR_NONE !== json_last_error()) {
        throw new EncodeJsonException('json_encode error: ' . json_last_error_msg());
    }

    return $json;
}

