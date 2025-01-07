<?php

function fetchPagedMessages()
{
    $db = db();

    $res = mysqli_query($db, "SELECT COUNT(*) FROM `messages`");
    $row = mysqli_fetch_row($res);
    $total = $row[0];

    $count = 5;
    $totalPages = ceil($total / $count);

    $page = (isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0) ? min((int)$_GET['page'], $totalPages) : 1;

    $start = ($page * $count) - $count;

    renderPagination($page, $totalPages);

    return mysqli_query($db,
    "SELECT username, email, text, m.create_date AS 'created', filePath
            FROM `messages` m JOIN `user` u ON m.user_id = u.id
            ORDER BY 4 DESC
            LIMIT $start, $count;");
}

function renderPagination($currentPage, $totalPages)
{
    if ($currentPage > 1) {
        echo "<a href='index.php?page=" . ($currentPage - 1) . "' class='pageLink'>Prev</a>";
    }

    if ($currentPage > 2) {
        echo "<a href='index.php?page=1' class='pageLink'>1</a>";
        if ($currentPage > 3) {
            echo "<span class='pageLink'>...</span>";
        }
    }

    for ($i = max(1, $currentPage - 1); $i <= min($totalPages, $currentPage + 1); $i++) {
        if ($i === $currentPage) {
            echo "<a class='active pageLink'>$i</a>";
        } else {
            echo "<a href='index.php?page=$i' class='pageLink'>$i</a>";
        }
    }

    if ($currentPage < $totalPages - 1) {
        if ($currentPage < $totalPages - 2) {
            echo "<span class='pageLink'>...</span>";
        }
        echo "<a href='index.php?page=$totalPages' class='pageLink'>$totalPages</a>";
    }

    if ($currentPage < $totalPages) {
        echo "<a href='index.php?page=" . ($currentPage + 1) . "' class='pageLink'>Next</a>";
    }
}

function createMessage(string $message, string $savedfilePath = NULl): bool
{
    $db = db();
    $savedfilePath = mysqli_escape_string($db, $savedfilePath);
    $message = mysqli_escape_string($db, $message);
    return  mysqli_query($db, "INSERT INTO messages (text, user_id, filePath) VALUES ('$message', 1, '$savedfilePath');");
}

function addFile(&$error, &$savedfilePath): bool
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

    $storageFileName = time() . " - " . $fileName;
    $storageFilePath = $uploadPath . $storageFileName;
    if (!move_uploaded_file($fileTmpPath, $storageFilePath)) {
        $error .= "Error saving the file!";
        return false;
    } else {
        $savedfilePath = $storageFileName;
    }

    return true;
}
