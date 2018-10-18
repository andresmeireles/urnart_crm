<?php
declare(strict_types=1);

namespace App\Utils\Andresmei;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class SimpleFileUpload
{
    /**
     * Tamanho maximo do arquivo
     */
    const MAX_SIZE_FILE = 400000;

    /**
     * @var string
     *
     * Caminho absoluto da imagem
     */
    protected static $logoDir = __DIR__ . '/../../../public/sys/logo_img';

    /**
     * @var array
     *
     * Array com tipos de imagem suportados.
     */
    protected static $supportedImages = array(
        'image/jpeg',
        'image/png',
        'image/svg+xml',
        'image/webp'
    );

    /**
     * @var string
     *
     * String com caminho da imagem
     */
    protected static $filePath;

    protected static $message;

    protected static $status = false;

    public static function uploadLogoImage(?UploadedFile $file): bool
    {
        if (is_null($file)) {
            //self::$filePath = "sys/logo_img/logo.png";
            self::$status = true;

            return true;
        }

        if (!self::checkImage($file->getClientMimeType()) && !self::checkFileSize((int) $file->getSize())) {
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
            self::$status = false;
            return false;
        }

        return true;
    }

    private static function checkFileSize(int $fileSize): bool
    {
        if ($fileSize > self::MAX_SIZE_FILE || $fileSize === 0) {
            dump($fileSize);
            self::$message = "Imagem maior que o suportado";
            return false;
        }

        return true;
    }
    
    private static function clearFolder(): void
    {
        $files = glob(self::$logoDir.'/custom/*');
        foreach($files as $file) {
            if (!is_dir($file) && file_exists($file)) {
                unlink($file);
            }
        }
    }
}
