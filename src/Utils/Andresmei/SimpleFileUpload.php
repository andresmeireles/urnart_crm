<?php declare(strict_types = 1);

namespace App\Utils\Andresmei;

use Symfony\Component\HttpFoundation\File\UploadedFile;

final class SimpleFileUpload
{
    private const MAX_SIZE_FILE = 400000;

    /**
     * @var string
     */
    private static $logoDir = __DIR__ . '/../../../public/sys/logo_img';

    /**
     * @var array
     */
    private static $supportedImages = [
        'image/jpeg',
        'image/png',
        'image/svg+xml',
        'image/webp',
    ];

    /**
     * @var string
     */
    private static $filePath;

    /**
     * @var string
     */
    private static $message;

    /**
     * @var bool
     */
    private static $status = false;

    /**
     * @param UploadedFile|null $file
     * @return bool
     * @throws \Exception
     */
    public static function uploadLogoImage(?UploadedFile $file): bool
    {
        if ($file === null) {
            //self::$filePath = "sys/logo_img/logo.png";
            self::$status = true;

            return true;
        }

        if ($file->getClientMimeType() === null) {
            throw new \Exception('Formato não envidado', 1);
        }

        if (! self::checkImage($file->getClientMimeType()) && ! self::checkFileSize($file->getSize())) {
            return false;
        }
        self::clearFolder();
        $uploadPath = self::$logoDir . '/custom/' . $file->getClientOriginalName();

        if (! is_string($file->getRealPath())) {
            throw new \Exception('Path não enviado ou não existe', 1);
        }

        $uploadFile = move_uploaded_file($file->getRealPath(), $uploadPath);
        if (! $uploadFile) {
            self::$message = 'Erro ao enviar imagem.';
            return false;
        }
        self::$filePath = '/sys/logo_img/custom/' . $file->getClientOriginalName();
        self::$message = "Imagem {$file->getClientOriginalName()} enviada com sucesso";
        self::$status = true;

        return true;
    }

    /**
     * @return bool
     */
    public static function getStatus(): bool
    {
        return self::$status;
    }

    /**
     * @return string|null
     */
    public static function getMessage(): ?string
    {
        return self::$message;
    }

    /**
     * @return string|null
     */
    public static function getFilePath(): ?string
    {
        return self::$filePath;
    }

    /**
     * @param string $type
     * @return bool
     */
    private static function checkImage(string $type): bool
    {
        if (! in_array($type, self::$supportedImages, true)) {
            self::$message = "Tipo {$type} não supportado";
            self::$status = false;
            return false;
        }

        return true;
    }

    /**
     * @param int $fileSize
     * @return bool
     */
    private static function checkFileSize(int $fileSize): bool
    {
        if ($fileSize > self::MAX_SIZE_FILE || $fileSize === 0) {
            self::$message = 'Imagem maior que o suportado';
            return false;
        }

        return true;
    }

    private static function clearFolder(): void
    {
        $files = glob(self::$logoDir . '/custom/*');
        foreach ($files as $file) {
            if (! is_dir($file) && file_exists($file)) {
                unlink($file);
            }
        }
    }
}
