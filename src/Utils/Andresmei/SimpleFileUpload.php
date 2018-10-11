<?php
declare(strict_types=1);

namespace App\Utils\Andresmei;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class SimpleFileUpload
{
    protected static $logoDir = __DIR__ . '/../../../public/sys/logo_img';
    protected static $supportedImages = array(
        'image/jpeg',
        'image/jpg'
    );
    protected static $filePath;
    protected static $message;
    protected static $status = false;

    public static function uploadLogoImage(?UploadedFile $file): bool
    {
        if (is_null($file)) {
            self::$filePath = 'sys/logo_img/logo.jpg';
            self::$status = true;
            return true;
        }
        if (!self::checkImage($file->getMimeType())) {
            return false;
        }
        self::clearFolder();
        $uploadPath = self::$logoDir.'/custom/'.$file->getClientOriginalName();
        $uploadFile = move_uploaded_file($file->getRealPath(), $uploadPath);
        if (!$uploadFile) {
            self::$message = 'Erro ao enviar imagem.';
            return false;
        }
        self::$filePath = '/sys/logo_img/custom/'.$file->getClientOriginalName();
        self::$message = "Imagem {$file->getClientOriginalName()} enviada com sucesso";
        self::$status = true;
        return true;
    }

    public static function getStatus(): bool
    {
        return self::$status;
    }

    public static function getMessage(): ?string
    {
        return self::$message;
    }

    public static function getFilePath(): string
    {
        return self::$filePath;
    }

    private static function checkImage(string $type): bool
    {
        if (!in_array($type, self::$supportedImages)) {
            self::$message = "Tipo {$type} não supportado";
            return false;
        }
        return true;
    }
    
    private static function clearFolder(): void
    {
        $files = glob(self::$logoDir);
        foreach($files as $file) {
            unlink($file);
        }
    }
}
