<?php
declare(strict_types=1);

namespace App\Utils\Andresmei;


class FlashResponse
{
    protected static $http_code = 200;
    protected static $type = 'success';
    protected static $message = 'Sucesso';

    public static function response(int $http_code = null, string $type = null, string $message = null): array
    {
        $o = new Email;
        
        return array(
            'http_code' => $http_code ?? self::$http_code,
            'type' => $type ?? self::$type,
            'message' => $message ?? self::$message
        );
    }

    public static function stdResponse(): array
    {
        return array(
            'http_code' => self::$http_code,
            'type' => self::$type,
            'message' => self::$message
        );
    }
}