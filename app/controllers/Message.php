<?php

namespace Controllers;

use app\Application;
use Core\Controller;
use Helpers\Tools;
use Models;

class Message extends Controller
{
	public function index()
	{
		$this->title = 'Messages';

		$bbcode = Tools::bbCodeCustomizer();

		$username = $_SESSION['user_name'] ?? 'undefined';
		$email = $_SESSION['user_email'] ?? 'undefined';

		$sortField = $_GET['sort'] ?? 'created';
		$sortOrder = isset($_GET['order']) && $_GET['order'] === 'asc' ? 'asc'
			: 'desc';

		$model = new Models\Message();
		$result = $model->fetchPagedMessages($sortField, $sortOrder);

		$messages = $result['result'];
		$currentPage = $result['currentPage'];
		$totalPages = $result['totalPages'];

		if (!empty($_POST)) {
			try {
				$message = htmlspecialchars(trim($_POST['message'] ?? ''));

				if (empty($message)) {
					throw new \Exception('Please enter a message');
				}

				if (empty($_FILES['file']['name'])
					|| $_FILES['file']['error'] != UPLOAD_ERR_OK
				) {
					$model->createMessage(
						$message,
						$_SESSION['user_id'],
						Tools::get_ip(),
						$_SERVER['HTTP_USER_AGENT'],
					);
					header('Location: /');
					die;
				}

				$fileModel = new Models\FileModel();

				$savedFilePath = $fileModel->saveFile();

				if (!$savedFilePath) {
					die;
				}

				$model->createMessage(
					$message,
					$_SESSION['user_id'],
					Tools::get_ip(),
					$_SERVER['HTTP_USER_AGENT'],
					$savedFilePath
				);
				header('Location: /');
			} catch (\Exception $e) {
				if (isset($savedFilePath) && file_exists($savedFilePath)) {
					unlink($savedFilePath);
				}

				$this->error = $e->getMessage();
			}
		}

		return $this->render(
			'messages/index',
			[
				'messages' => $messages,
				'currentPage' => $currentPage,
				'totalPages' => $totalPages,
				'user_name' => $username,
				'user_email' => $email,
				'sortOrder' => $sortOrder,
				'bbCode' => $bbcode,
			],
			[
				[
					'type' => 'link',
					'selfClosing' => false,
					'attributes' => [
						'rel' => 'stylesheet',
						'href' => '/assets/js/lib/BBCodeEditor/minified/themes/default.min.css',
					]
				],
				[
					'type' => 'script',
					'selfClosing' => false,
					'attributes' => [
						'src' => '/assets/js/lib/BBCodeEditor/minified/sceditor.min.js',
					]
				],
				[
					'type' => 'script',
					'selfClosing' => false,
					'attributes' => [
						'src' => '/assets/js/lib/BBCodeEditor/minified/formats/bbcode.js',
					]
				],
				[
					'type' => 'script',
					'selfClosing' => false,
					'attributes' => [
						'src' => '/assets/js/lib/BBCodeEditor/languages/nl.js',
					]
				],
				[
					'type' => 'script',
					'selfClosing' => false,
					'attributes' => [
						'src' => '/assets/js/BBCodeCreator.js',
					]
				],
			]
		);
	}
}
