<?php
namespace Loevgaard\Consignor\ShipmentServer\Response;

use Loevgaard\Consignor\ShipmentServer\Exception\InvalidBase64Exception;

class SubmitShippingResponse extends Response
{
    /**
     * @param string $extension
     * @param string $prefix
     * @param string|null $dir
     * @return \SplFileObject[]
     * @throws InvalidBase64Exception
     */
    public function saveLabels(string $extension, string $prefix = 'label-', string $dir = null) : array
    {
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
