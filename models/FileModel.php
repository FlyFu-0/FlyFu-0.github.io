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

    public function saveFile(&$error, &$savedfilePath): bool
    {

        $file = $_FILES['file'];
        $fileName = $file['name'];
        $fileSize = $file['size'];
        $fileTmpPath = $file['tmp_name'];
        $fileExtension = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);

        if (!in_array($fileExtension, array_merge($this->allowedTextExtensions, $this->allowedImageExtensions))) {
            $error .= 'Unexpected file type! Allowed: TXT, JPG, GIF, PNG.';
            return false;
        } elseif ($fileSize > $this->maxFileSize) {
            $error .= 'File size exceeds limit of 100KB!';
            return false;
        }

        if (in_array($fileExtension, $this->allowedImageExtensions)) {
            list($width, $height) = getimagesize($fileTmpPath);
            if ($width > 320 || $height > 240) {
                $error .= "Image exceeds allowed resolution. Allowed resolution: 320x240!";
                return false;
            }
        }

        $storageFileName = time() . " - " . $fileName;
        $storageFilePath = $this->uploadPath . $storageFileName;
        if (!move_uploaded_file($fileTmpPath, $storageFilePath)) {
            $error .= "Error saving the file!";
            return false;
        } else {
            $savedfilePath = $storageFileName;
        }

        return true;
    }
}
