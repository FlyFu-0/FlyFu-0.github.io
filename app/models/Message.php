<?php

namespace Models;

use Core;
use PDO;

class Message extends Core\DBBuilder implements Core\haveTable
{

	public function fetchPagedMessages(
		$sortingField = 'created',
		$order = 'DESC'
	): array {
//		$db = (new Core\DBBuilder())
//			->setSelect(['username', 'email'])
//			->setFrom($this->getTable())
//			->setWhere([
//				['field' => 'username', 'operator' => '=', 'value' => 'user1'],
//                ['field' => 'email', 'operator' => '=', 'value' => 'user1@mail.com'],
//			])
//		;
//
//		echo '<pre>';
//		var_dump($db);

		$sortingField = htmlspecialchars($sortingField);
		$order = htmlspecialchars($order);

		$total = (new Core\DBBuilder())
			->setSelect(['COUNT(*)'])
			->setFrom($this->getTable())
			->execute()[0]['COUNT(*)'];

		$messagesPerPage = 25;
		$totalPages = ceil($total / $messagesPerPage);

		$page = (isset($_GET['page']) && is_numeric($_GET['page'])
			&& $_GET['page'] > 0) ? min((int)$_GET['page'], $totalPages) : 1;

		$startRecord = ($page * $messagesPerPage) - $messagesPerPage;

		$result = (new Core\DBBuilder())
			->setSelect(
				[
					'username',
					'email',
					'text',
					$this->getTable() . '.create_date as created',
					'filePath'
				]
			)
			->setFrom($this->getTable())
			->setJoin(['user' => 'messages.user_id = user.id'])
			->setOrder('created', Core\DB::ORDER_DESC)
			->setOrder('email')
			->setOrder('username')
			->setPaged($startRecord, $messagesPerPage)
			->execute();

		return [
			'totalPages' => $totalPages,
			'currentPage' => $page,
			'result' => $result,
			'sortOrder' => $order,
			'sortingField' => $sortingField,
		];
	}

	public function getTable(): string
	{
		return 'messages';
	}

	public function createMessage(
		string $message,
		string $userId,
		string $ip,
		string $browser,
		string $savedFilePath = null
	) {
		return (new Core\DBBuilder())
			->setInsert($this->getTable(), [
				'text' => $message,
				'user_id' => $userId,
				'filePath' => $savedFilePath,
				'sender_ip' => $ip,
				'browser' => $browser,
			])
			->execute()
		;
//		return $this->insert('messages', [
//			'text' => $message,
//			'user_id' => $userId,
//			'filePath' => $savedFilePath,
//			'sender_ip' => $ip,
//			'browser' => $browser,
//		]);
	}
}
