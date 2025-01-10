<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/models/MessageModel.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/models/FileModel.php';

class MessageController
{
    public function index()
    {
        $username = $_SESSION['user_name'] ?? '';
        $email = $_SESSION['user_email'] ?? '';

        $sortField = isset($_GET['sort']) ? $_GET['sort'] : 'created';
        $sortOrder = isset($_GET['order']) && $_GET['order'] === 'asc' ? 'asc' : 'desc';

        $model = new MessageModel();
        $result = $model->fetchPagedMessages($sortField, $sortOrder);

        $messages = $result['result'];
        $currentPage = $result['currentPage'];
        $totalPages = $result['totalPages'];

        $savedfilePath = NULL;

        if (!empty($_POST)) {
            $message = trim(htmlspecialchars($_POST['message'] ?? ''));

            if (!empty($message)) {
                if (isset($_FILES['file']) && empty($_FILES['file']['error'])) {
                    $fileModel = new FileModel();
                    if ($fileModel->saveFile($savedfilePath)) {
                        $model->createMessage($message, $_SESSION['user_id'], get_ip(), $_SERVER['HTTP_USER_AGENT'], $savedfilePath);
                        header('Location: /');
                        die;
                    }
                } else {
                    $model->createMessage($message, $_SESSION['user_id'], get_ip(), $_SERVER['HTTP_USER_AGENT'],);
                    header('Location: /');
                    die;
                }
            } else {

                flash('Please enter a message');
            }
        }

        require $_SERVER['DOCUMENT_ROOT'] . '/views/messages/index.php';
    }
}
