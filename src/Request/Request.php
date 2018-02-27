<?php
namespace Loevgaard\Consignor\ShipmentServer\Request;

use Loevgaard\Consignor\ShipmentServer\Exception\EncodeJsonException;
use Loevgaard\Consignor\ShipmentServer\Response\Response;
use function Loevgaard\Consignor\ShipmentServer\encodeJson;

abstract class Request implements RequestInterface
{
    /*
     * Both $data and $options are only used in some requests, but we include them here to avoid code duplication
     */
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

    /**
     * @return array
     */
    public function getData(): array
    {
        return (array)$this->data;
    }

    /**
     * @param array $data
     * @return Request
     */
    public function setData(array $data)
    {
        $this->data = $data;
        return $this;
    }

    public function getOption(string $option)
    {
        if (!$this->options || !array_key_exists($option, $this->options)) {
            return null;
        }

        return $this->options[$option];
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return (array)$this->options;
    }

    /**
     * @param array $options
     * @return Request
     */
    public function setOptions(array $options)
    {
        $this->options = $options;
        return $this;
    }
}
