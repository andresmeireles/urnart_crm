<?php
declare(strict_types=1);

namespace App\Config;

use Symfony\Component\Yaml\Yaml;
use App\Config\NotLoadedConfigException;
use App\Config\NotFoundParameterException;

final class Config
{
    /**
     * Arquivo de configuração parseado.
     *
     * @var object Yaml;
     */
    protected static $config;
    /**
     * Status se a configuração está ativa.
     *
     * @var boolean
     */
    protected static $status = false;

    private function __construct(){}

    /**
     * Inicia arquivo de configuração.
     *
     * @return void
     */
    public static function start(): void
    {
        self::$config = Yaml::parse(file_get_contents(__DIR__.'/system-config.yaml'));
        self::$status = true;
    }

    /**
     * Retorna um array com arquivo de configuração iniciado.
     *
     * @throws NotLoadedConfigException
     * 
     * @return array
     */
    public static function getConfig(): array
    {
        if (!self::getStatus()) {
            throw new NotLoadedConfigException('Configuração não iniciada, usar methodo Config::start');
        }

        return (array) self::$config;
    }

    public static function getProperty($param)
    {
        if (!isset(self::$config[$param])) {
            throw new NotFoundParameterException("Parametro {$param} não existe");
        }
        return self::$config[$param];
    }

    /**
     * Retorna status da confuração.
     *
     * @return boolean
     */
    public static function getStatus(): bool
    {
        return self::$status;
    }
}
