<?php
declare(strict_types=1);

namespace App\Model;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Yaml\Yaml;
use App\Utils\Andresmei\FlashResponse;

class FormModel
{
    public function saveReport(array $data, string $path)
    {
        unset($data['save']);
        $data['created'] = (new \DateTime('now', new \DateTimeZone('America/Sao_Paulo')))->format('d-m-Y h:m:s');
        $name = md5((string) rand());
        $data['name'] = $name;
        $filesystem = new Filesystem();
        $fileData = Yaml::dump($data);
        $fullPath = sprintf('%s/%s.yaml', $path, $name);
        $filesystem->dumpFile($fullPath, $fileData);

        return new FlashResponse(200, 'success', 'Arquivo salvo com sucesso!');
    }
}
