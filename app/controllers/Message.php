<?php

namespace Controllers;

use Helpers\Tools;
use Models;

class Message
{
	public function index()
	{
		$username = $_SESSION['user_name'] ?? '';
		$email = $_SESSION['user_email'] ?? '';

		$sortField = $_GET['sort'] ?? 'created';
		$sortOrder = isset($_GET['order']) && $_GET['order'] === 'asc' ? 'asc'
			: 'desc';

		$model = new Models\Message();
		$result = $model->fetchPagedMessages($sortField, $sortOrder);

		$messages = $result['result'];
		$currentPage = $result['currentPage'];
		$totalPages = $result['totalPages'];

		$savedfilePath = null;

		if (!empty($_POST)) {
			$message = trim(htmlspecialchars($_POST['message'] ?? ''));

			if (!empty($message)) {
				if (isset($_FILES['file']) && empty($_FILES['file']['error'])) {
					$fileModel = new Models\FileModel();
					if ($fileModel->saveFile($savedfilePath)) {
						$model->createMessage(
							$message,
							$_SESSION['user_id'],
							Tools::get_ip(),
							$_SERVER['HTTP_USER_AGENT'],
							$savedfilePath
						);
						header('Location: /app/bootstrap.php');
						die;
					}
				} else {
					$model->createMessage(
						$message,
						$_SESSION['user_id'],
						Tools::get_ip(),
						$_SERVER['HTTP_USER_AGENT'],
					);
					header('Location: /app/bootstrap.php');
					die;
				}
			} else {
				flash('Please enter a message');
			}
		}

		require $_SERVER['DOCUMENT_ROOT'] . '/app/views/messages/index.php';
	}
}
