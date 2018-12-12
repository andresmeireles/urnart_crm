<?php
declare(strict_types=1);

namespace App\Config;

use Symfony\Component\Yaml\Yaml;
use App\Config\NotLoadedConfigException;
use App\Config\NotFoundParameterException;

final class NonStaticConfig
{
    /**
     * Arquivo de configuração parseado.
     *
     * @var array array associativo com strings de configuração;
     */
    private $config;

    public function __construct()
    {
        $fileString = (string) file_get_contents(__DIR__.'/system-config.yaml');
        $this->config = Yaml::parse($fileString);
    }

    /**
     * Retorna um array com arquivo de configuração iniciado.
     *
     * @throws NotLoadedConfigException
     * 
     * @return array array associativo com nomes das configurações e valor respoectivo.
     */
    public function getConfig(): array
    {
        return $this->config;
    }

    public function getProperty(string $param): array
    {
        if (!array_key_exists($param, $this->getConfig())) {
            throw new NotFoundParameterException("Configuração $param não existe");
        }
        return $this->config[$param];
    }
}
