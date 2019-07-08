<?php declare(strict_types = 1);
/**
 * TO-DO
 *
 * Image saver
 * config file saver
 */

namespace App\Config;

use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use Symfony\Component\Yaml\Yaml;

final class NonStaticConfig
{
    /**
     * @var array array associativo com strings de configuração;
     */
    private $config;

    public function __construct()
    {
        if (! file_exists(__DIR__ . '/system-config.yaml')) {
            throw new FileNotFoundException('Arquivo não enconrado.');
        }
        $this->config = Yaml::parse((string) file_get_contents(__DIR__ . '/system-config.yaml'));
    }

    /**
     * @return array
     */
    public function getConfig(): array
    {
        return $this->config;
    }

    /**
     * @param string $param
     * @return string
     * @throws NotFoundParameterException
     */
    public function getProperty(string $param)
    {
        if (! array_key_exists($param, $this->getConfig())) {
            throw new NotFoundParameterException("Configuração ${param} não existe");
        }

        return $this->config[$param];
    }

    /**
     * @return self
     */
    public function writeImage(): self
    {
        return $this;
    }
}
