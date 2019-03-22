<?php
declare(strict_types=1);

namespace App\Config;

use Symfony\Component\Yaml\Yaml;
use App\Config\NotLoadedConfigException;
use App\Config\NotFoundParameterException;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;

final class NonStaticConfig
{
    /**
     * Arquivo de configuração parseado.
     *
     * @var array array associativo com strings de configuração;
     */
    private $config;

    public $env = false;

    public function __construct()
    {
        if (!file_exists(__DIR__.'/system-config.yaml')) {
            throw new FileNotFoundException('Arquivo não enconrado.');
        }
        $this->config = Yaml::parse((string) file_get_contents(__DIR__.'/system-config.yaml'));
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

    /**
     * Função para recuperar o valor de alguma configuração.
     *
     * @param string $param
     * @return string
     */
    public function getProperty(string $param): string
    {
        if (!array_key_exists($param, $this->getConfig())) {
            throw new NotFoundParameterException("Configuração $param não existe");
        }
        return (string) $this->config[$param];
    }
}
