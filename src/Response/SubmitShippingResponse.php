<?php
namespace Loevgaard\Consignor\ShipmentServer\Response;

use Loevgaard\Consignor\ShipmentServer\Exception\InvalidBase64Exception;
use Loevgaard\Consignor\ShipmentServer\Exception\InvalidLabelsOptionException;
use Loevgaard\Consignor\ShipmentServer\Request\SubmitShipmentRequest;

class SubmitShippingResponse extends Response
{
    /**
     * @var SubmitShipmentRequest
     */
    protected $request;

    /**
     * @param string $prefix
     * @param string|null $dir
     * @return \SplFileObject[]|null
     * @throws InvalidBase64Exception
     * @throws InvalidLabelsOptionException
     */
    public function saveLabels(string $prefix = 'label-', string $dir = null) : ?array
    {
        $labelsOption = $this->request->getOption('Labels');
        if (!$labelsOption) {
            throw new InvalidLabelsOptionException('The labels option is not set. This is unexpected.');
        }

        if ($labelsOption === 'none') {
            return null;
        }

        $extensionMapping = [
            'PNG' => 'png',
            'PDF' => 'pdf',
            'EPL' => 'epl',
            'ZPL' => 'zpl',
            'ZPLGK' => 'zplgk',
            'DATAMAXLP2' => 'datamaxlp2'
        ];

        if (!isset($extensionMapping[$labelsOption])) {
            throw new InvalidLabelsOptionException('The labels option `'.$labelsOption.'` was not found in: '.join(', ', array_keys($extensionMapping)));
        }

        $extension = $extensionMapping[$labelsOption];

        if (!$dir) {
            $dir = sys_get_temp_dir();
        }
        $dir = rtrim($dir, '/');

        $files = [];

        foreach ($this->data['Labels'] as $label) {
            $decoded = base64_decode($label['Content']);

            if ($decoded === false) {
                throw new InvalidBase64Exception('An error occurred during the base64_decode');
            }

            $file = $this->getFile($extension, $prefix, $dir);
            $file->fwrite($decoded);

            $files[] = $file;
        }

        return $files;
    }

    protected function getFile(string $extension, string $prefix, string $dir) : \SplFileObject
    {
        do {
            $filename = $dir.'/'.uniqid($prefix, true).'.'.$extension;
        } while (file_exists($filename));

        return new \SplFileObject($filename, 'w+');
    }
}
