<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/core/db.php';

class FileModel
{
    private $allowedTextExtensions = ['txt'];
    private $allowedImageExtensions = ['jpg', 'jpeg', 'gif', 'png'];
    private $maxFileSize = 100 * 1024;
    private $uploadPath;

    public function __construct()
    {
        $this->uploadPath = $_SERVER['DOCUMENT_ROOT'] . '/userfiles/';
    }

    public function saveFile(&$error, &$savedfilePath)
    {
        $file = $_FILES['file'];
        $fileName = $file['name'];
        $fileSize = $file['size'];
        $fileTmpPath = $file['tmp_name'];
        $fileExtension = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);

        $error = '';

        if ($this->isExtensionValid($fileExtension)) {
            $error .= 'Unexpected file type! Allowed: TXT, JPG, GIF, PNG.' . PHP_EOL;
            return false;
        }

        if ($this->isSizeValid($fileSize)) {
            $error .= 'File size exceeds limit of 100KB!' . PHP_EOL;
            return false;
        }

        if ($this->isImage($fileExtension) && $this->isImageResolutionValid($fileTmpPath)) {
            $error .= "Image exceeds allowed resolution. Allowed resolution: 320x240!" . PHP_EOL;
            return false;
        }

        $storageFileName = time() . "_" . $fileName;
        $storageFilePath = $this->uploadPath . $storageFileName;
        if (!move_uploaded_file($fileTmpPath, $storageFilePath)) {
            $error .= "Error saving the file!" . PHP_EOL;
            return false;
        } else {
            $savedfilePath = $storageFileName;
        }

        return true;
    }

    private function isExtensionValid($fileExtension): bool
    {
        return !in_array($fileExtension, array_merge($this->allowedTextExtensions, $this->allowedImageExtensions));
    }

    private function isSizeValid($fileSize): bool
    {
        return $fileSize > $this->maxFileSize;
    }

    private function isImage($fileExtension): bool
    {
        return in_array($fileExtension, $this->allowedImageExtensions);
    }

    private function isImageResolutionValid($fileTmpPath): bool
    {
        list($width, $height) = getimagesize($fileTmpPath);
        return $width > 320 || $height > 240;
    }
}
