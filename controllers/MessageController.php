<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/models/MessageModel.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/models/FileModel.php';

class MessageController
{
    public function index()
    {
        $model = new MessageModel();
        $result = $model->fetchPagedMessages();

        $messages = $result['result'];
        $currentPage = $result['currentPage'];
        $totalPages = $result['totalPages'];

        $error = '';
        $savedfilePath = NULL;

        if (!empty($_POST)) {
            $message = trim(htmlspecialchars($_POST['message'] ?? ''));

            if (!empty($message)) {
                if (isset($_FILES['file']) && empty($_FILES['file']['error'])) {
                    $fileModel = new FileModel();
                    if ($fileModel->saveFile($error, $savedfilePath)) {
                        $model->createMessage($message, $savedfilePath);
                        header('Location: index.php');
                        die;
                    }
                } else {
                    $model->createMessage($message);
                    header('Location: index.php');
                    die;
                }
            } else {

                $error = 'Please enter a message';
            }
        }

        require $_SERVER['DOCUMENT_ROOT'] . '/views/messages/index.php';
    }
}
