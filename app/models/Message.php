<?php

namespace Models;

use Core;

class Message extends Core\DBBuilder implements Core\haveTable
{
	public function fetchPagedMessages(
		$sortingField = 'created',
		$order = Core\DB::ORDER_DESC
	): array {
		$sortingField = htmlspecialchars($sortingField);
		$order = htmlspecialchars($order);

		$total = $this->getDB()
			->setSelect(['COUNT(*)'])
			->execute()[0]['COUNT(*)'];

		$messagesPerPage = 25;
		$totalPages = ceil($total / $messagesPerPage);

		$page = (isset($_GET['page']) && is_numeric($_GET['page'])
			&& $_GET['page'] > 0) ? min((int)$_GET['page'], $totalPages) : 1;

		$startRecord = ($page * $messagesPerPage) - $messagesPerPage;

		$result = $this->getDB()
			->setSelect(
				[
					'username',
					'email',
					'text',
					$this->getTable() . '.create_date as created',
					'filePath'
				]
			)
			->setJoin(['user' => 'messages.user_id = user.id'])
			->addOrder($sortingField, $order)
			->addOrder('email')
			->addOrder('username')
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
	): array {
		return $this->getDB()
			->setInsert()
			->setInsertData([
				'text' => $message,
				'user_id' => $userId,
				'filePath' => $savedFilePath,
				'sender_ip' => $ip,
				'browser' => $browser,
			])
			->execute();
	}
}
