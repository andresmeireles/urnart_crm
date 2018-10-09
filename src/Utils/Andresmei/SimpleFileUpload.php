<?php

namespace App\Utils\Andresmei;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class SimpleFileUpload
{
    protected $logoDir = __DIR__.'/../../../public/sys';
    protected $supportedImages = array(
        'image/jpeg',
        'image/jpg'
    );
    public $imageExtension;
    private $message;

    private $status = false;

    static public function uploadLogoImage(UploadedFile $file): bool
    {
        if (!$this->checkImage($file->getMimeType())) {
           return false;
        }
        $uploadPath = $this->logoDir.'/logo'
    }

    static public function getStatus(): bool
    {
        return $this->status;
    }

    private function checkImage(string $type): bool
    {
        if (!in_array($type, $this->supportedImages)) {
            $this->message = "Tipo {$type} não supportado";
            return false;
        }
        return true;
    }
}