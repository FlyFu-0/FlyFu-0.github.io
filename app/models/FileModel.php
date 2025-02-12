<?php

namespace Models;

class FileModel
{
	private $allowedTextExtensions = ['txt'];
	private $allowedImageExtensions = ['jpg', 'jpeg', 'gif', 'png'];
	private $maxFileSize = 100 * 1024;
	private $uploadPath;

	public function __construct()
	{
		$this->uploadPath = $_SERVER['DOCUMENT_ROOT'] . '/upload/';
	}

	public function saveFile(): string
	{
		$file = $_FILES['file'];
		$fileName = $file['name'];
		$fileSize = $file['size'];
		$fileTmpPath = $file['tmp_name'];
		$fileExtension = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);

		if ($this->isExtensionNotValid($fileExtension)) {
			throw new \Exception('Unexpected file type! Allowed: TXT, JPG, GIF, PNG');
		}

		if ($this->isSizeValid($fileSize)) {
			throw new \Exception('File size exceeds limit of 100KB!');
		}

		if ($this->isImage($fileExtension)
			&& $this->isImageResolutionValid(
				$fileTmpPath
			)
		) {
			throw new \Exception('Image exceeds allowed resolution. Allowed resolution: 320x240!');
		}

		$storageFileName = time() . "_" . $fileName;
		$storageFilePath = $this->uploadPath . $storageFileName;
		if (!move_uploaded_file($fileTmpPath, $storageFilePath)) {
			throw new \Exception('Error saving the file!');
		}

		return $storageFilePath;
	}

	private function isExtensionNotValid($fileExtension): bool
	{
		return !in_array(
			$fileExtension,
			array_merge(
				$this->allowedTextExtensions,
				$this->allowedImageExtensions
			)
		);
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
