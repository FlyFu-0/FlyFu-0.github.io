<?php

namespace App\Models;

use App\Core\DB;

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

	public function saveFile(&$savedfilePath): bool
	{
		$file = $_FILES['file'];
		$fileName = $file['name'];
		$fileSize = $file['size'];
		$fileTmpPath = $file['tmp_name'];
		$fileExtension = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);

		if ($this->isExtensionValid($fileExtension)) {
			flash('Unexpected file type! Allowed: TXT, JPG, GIF, PNG.');
			return false;
		}

		if ($this->isSizeValid($fileSize)) {
			flash('File size exceeds limit of 100KB!');
			return false;
		}

		if ($this->isImage($fileExtension)
			&& $this->isImageResolutionValid(
				$fileTmpPath
			)
		) {
			flash(
				"Image exceeds allowed resolution. Allowed resolution: 320x240!"
			);
			return false;
		}

		$storageFileName = time() . "_" . $fileName;
		$storageFilePath = $this->uploadPath . $storageFileName;
		if (!move_uploaded_file($fileTmpPath, $storageFilePath)) {
			flash("Error saving the file!");
			return false;
		} else {
			$savedfilePath = $storageFileName;
		}

		return true;
	}

	private function isExtensionValid($fileExtension): bool
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
