<?php
declare(strict_types=1);

namespace App\Config;

use App\Utils\Andresmei\FlashResponse;
use App\Utils\Andresmei\SimpleFileUpload;
use Symfony\Component\Yaml\Yaml;

class Configuration
{
    private $config;

    /**
     * array para escrita do arquivo de configuração.
     *
     * @var array
     */
    private $writableConfig = array();

    /**
     * string com o caminho da imagem no sistema. 
     *
     * @var string
     */
    private static $imageNameAndPath;

    public function __construct()
    {
        $this->config = Yaml::parse(file_get_contents(__DIR__.'/system-config.yaml'));
    }

    public function writeConfigurationFile(array $check, array $images): FlashResponse
    {
        $imagePaths = $this->uploadImages($images);
        if (!SimpleFileUpload::getStatus()) {
            return FlashResponse::response(400, 'warning', SimpleFileUpload::getMessage());
        }

        $this->writeChecks($check);
        $this->writeImages($images, $imagePaths);

        $yaml = Yaml::dump($this->writableConfig);
        file_put_contents(__DIR__.'/system-config.yaml', $yaml);

        return FlashResponse::response(200, 'success', 'Configuração gravado com sucesso');
    }

    public function writeConfFile(array $config, array $image): array
    {
        foreach ($config as $keyConfig => $configValue) {
            switch ($keyConfig) {
                case 'images':
                    $imagePath = $this->uploadImages($image);
                    $this->writeImages($image, $imagePath);
                    if (!SimpleFileUpload::getStatus()) {
                        return FlashResponse::response(400, 'warning', SimpleFileUpload::getMessage());
                    }
                    break;
                case 'check':
                    $this->writeChecks($configValue);
                    break;
                case 'survey':
                    $this->writeSurvey($configValue);
                    break;
                default:
                    break;
            }
        }
        $yaml = Yaml::dump($this->writableConfig);
        file_put_contents(__DIR__.'/system-config.yaml', $yaml);

        return new FlashResponse(200, 'success', 'Configuração gravada com sucesso');
        //return $this->writableConfig;
    }

    public function writeSurvey(array $survey): void 
    {
        foreach ($survey as $options => $values) {
            if (!is_array($values)) {
                $booleanNumberConverter = $values === '1' ? true : false;
                $this->writableConfig[$options] = $booleanNumberConverter;
                continue;
            }
            foreach ($values as $key => $value) {
                if ($value['alternatives'] === '[]') {
                    $value['alternatives'] = [];    
                }
                $surveyQuestions[$key] = $value;
            }        
            $this->writableConfig[$options] = $surveyQuestions;    
        }
    }

    public function uploadImages(array $images): array
    {
        $result = array();
        foreach ($images as $name => $image) {
            if (SimpleFileUpload::uploadLogoImage($image)) {
                $result[$name] = SimpleFileUpload::getFilePath();
            }
        }
        return $result;
    }

    public function writeChecks(array $check): void
    {
        foreach ($check as $key => $value) {
            if (array_key_exists($key, $this->config)) {
                $this->writableConfig[$key] = ($check[$key] === '1' ? true : false);
                continue;
            }
            $this->writableConfig[$key] = false;
        }
    }

    public function writeImages(array $images, array $imagePaths): void
    {
        foreach ($images as $key => $value) {
            if (is_null($value)) {
                $this->writableConfig[$key] = $this->config[$key];
                continue;
            }
            if (array_key_exists($key, $imagePaths)) {
                $this->writableConfig[$key] = $imagePaths[$key];
                continue;
            }
            $this->writableConfig[$key] = false;
        }
    }

    public function writeImageAsync(array $images): FlashResponse
    {
        foreach ($images as $name => $image) {
            SimpleFileUpload::uploadLogoImage($image);
            self::$imageNameAndPath[$name] = SimpleFileUpload::getFilePath();
        }

        return new FlashResponse(200, 'success', 'Imagens enviadas com sucesso, para finalizar aperte para finalizar alterações aparete em salvar');
    }

    public function resetLogoImage(): FlashResponse
    {
        $yaml = $this->config;
        $yaml['logo_image_path'] = 'sys/logo_img/logo.png';
        $yaml = Yaml::dump($yaml);
        file_put_contents(__DIR__.'/system-config.yaml', $yaml);
        return new FlashResponse(200, 'success', 'Imagem resetada com sucesso :)');
    }
}
