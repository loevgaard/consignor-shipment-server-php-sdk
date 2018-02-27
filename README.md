# Consignor Shipment Server PHP SDK

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]

A library for making requests to the [Consignor Shipment Server](https://consignor.zendesk.com/hc/da/categories/115000066814-Consignor-Shipment-Server) API.

## Install

Via Composer

```bash
$ composer require loevgaard/consignor-shipment-server-php-sdk
```

## Usage
Here is the example also used on [Consignors own page](https://consignor.zendesk.com/hc/da/articles/115002124314-Sample-Code-PHP). It submits a shipment and saves the labels as PDF's.

```php
<?php
require_once 'vendor/autoload.php';

$actor = '63';
$key = 'sample';
$env = \Loevgaard\Consignor\ShipmentServer\Client\Client::ENV_DEV;
$client = new \Loevgaard\Consignor\ShipmentServer\Client\Client($actor, $key, [], null, null, $env);

$data = [
    'Kind' => 1,
    'ActorCSID' => $actor,
    'ProdConceptID' => 1032,

    'Addresses' => [
        [
            'Kind' => 2,
            'Name1' => 'Test sender',
            'Street1' => 'Test Address',
            'PostCode' => '0580',
            'City' => 'Oslo',
            'CountryCode' => 'NO'],
        [
            'Kind' => 1,
            'Name1' => 'Ola Testmann',
            'Street1' => 'Test Address 1',
            'PostCode' => '0580',
            'City' => 'Oslo',
            'CountryCode' => 'NO'
        ]
    ],

    'Lines' => [
        [
            'PkgWeight' => 5000,
            'Pkgs' => [
                [
                    'ItemNo' => 1
                ]
            ]
        ]
    ]
];

$options = [
    'Labels' => 'PDF'
];

$request = new \Loevgaard\Consignor\ShipmentServer\Request\SubmitShipmentRequest($data, $options);

/** @var \Loevgaard\Consignor\ShipmentServer\Response\SubmitShippingResponse $response */
$response = $client->doRequest($request);

if ($response->wasSuccessful()) {
    echo "The request was successful, labels saved in: ".getcwd()."\n";
    $response->saveLabels('label-', getcwd());
} else {
    echo "The request was not successful\n";
    print_r($response->getErrors());
}
```

### Framework integration
There is a Symfony bundle available that integrates this SDK:
- [Consignor Shipment Server Bundle](https://github.com/loevgaard/consignor-shipment-server-bundle)

## Testing

```bash
$ composer test
```

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.

[ico-version]: https://img.shields.io/packagist/v/loevgaard/consignor-shipment-server-php-sdk.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/loevgaard/consignor-shipment-server-php-sdk/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/loevgaard/consignor-shipment-server-php-sdk.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/loevgaard/consignor-shipment-server-php-sdk.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/loevgaard/consignor-shipment-server-php-sdk
[link-travis]: https://travis-ci.org/loevgaard/consignor-shipment-server-php-sdk
[link-scrutinizer]: https://scrutinizer-ci.com/g/loevgaard/consignor-shipment-server-php-sdk/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/loevgaard/consignor-shipment-server-php-sdk