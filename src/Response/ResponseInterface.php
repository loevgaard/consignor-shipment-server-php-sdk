<?php
namespace Loevgaard\Consignor\ShipmentServer\Response;

interface ResponseInterface
{
    /**
     * Must return the original JSON response
     *
     * @return string
     */
    public function __toString();

    /**
     * Returns true if the request was successful
     *
     * @return bool
     */
    public function wasSuccessful() : bool;

    /**
     * Returns an array of errors
     * Returns an empty array if there are not errors
     *
     * @return array
     */
    public function getErrors() : array;
}
