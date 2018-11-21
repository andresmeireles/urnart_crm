<?php
declare(strict_types=1);

namespace App\Config;

use Symfony\Component\Yaml\Yaml;

final class Config
{
	protected static $config;

	public static function start()
	{
        self::$config = Yaml::parse(file_get_contents(__DIR__.'/system-config.yaml'));
	}

	public static function getConfig()
	{
		return self::$config;
	}
}