<?php

function fetchMessages()
{
    return mysqli_query(db(), "SELECT username, email, text, m.create_date AS 'created' FROM messages m JOIN user u ON m.user_id = u.id ORDER BY 4 DESC;");
}

function createMessage(string $message)
{
    $flag = mysqli_query(db(), "INSERT INTO messages (text, user_id) VALUES ('$message', 1);");
}

function addFile(&$error): bool
{
    $allowedTextExtensions = ['txt'];
    $allowedImageExtensions = ['jpg', 'jpeg', 'gif', 'png'];
    $maxFileSize = 100 * 1024;
    $uploadPath = $_SERVER['DOCUMENT_ROOT'] . '/userfiles/';


    $file = $_FILES['file'];
    $fileName = $file['name'];
    $fileSize = $file['size'];
    $fileTmpPath = $file['tmp_name'];
    $fileExtension = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);

    if (!in_array($fileExtension, array_merge($allowedTextExtensions, $allowedImageExtensions))) {
        $error .= 'Unexpected file type! Allowed: TXT, JPG, GIF, PNG.';
        return false;
    } elseif ($fileSize > $maxFileSize) {
        $error .= 'File size exceeds limit of 100KB!';
        return false;
    }

    if (in_array($fileExtension, $allowedImageExtensions)) {
        list($width, $height) = getimagesize($fileTmpPath);
        if ($width > 320 || $height > 240) {
            $error .= "Image exceeds allowed resolution. Allowed resolution: 320x240!";
            return false;
        }
    }

    $storageFilePath = $uploadPath . time() . " - " . $fileName;
    if (!move_uploaded_file($fileTmpPath, $storageFilePath)) {
        $error .= "Error saving the file!";
        return false;
    }

    return true;
}
