<?php

namespace Models;

use Core\DB;
use PDO;

class Message
{
	private PDO $db;

	public function __construct()
	{
		$this->db = DB::getInstance();
	}

	public function fetchPagedMessages(
		$sortingField = 'created',
		$order = 'DESC'
	) {
		$sortingField = htmlspecialchars($sortingField);
		$order = htmlspecialchars($order);

		$stmt = $this->db->query("SELECT COUNT(*) as total FROM `messages`");
		$total = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

		$messagesPerPage = 25;
		$totalPages = ceil($total / $messagesPerPage);

		$page = (isset($_GET['page']) && is_numeric($_GET['page'])
			&& $_GET['page'] > 0) ? min((int)$_GET['page'], $totalPages) : 1;

		$startRecord = ($page * $messagesPerPage) - $messagesPerPage;

		$stmt = $this->db->prepare(
			"SELECT `username`, `email`, `text`, `m`.`create_date` AS `created`, `filePath`
            FROM `messages` m
            JOIN `user` u ON m.user_id = u.id
            ORDER BY `$sortingField` $order
            LIMIT :start, :count"
		);
		$stmt->bindValue(':start', $startRecord, PDO::PARAM_INT);
		$stmt->bindValue(':count', $messagesPerPage, PDO::PARAM_INT);
		$stmt->execute();

		return [
			'totalPages' => $totalPages,
			'currentPage' => $page,
			'result' => $stmt->fetchAll(PDO::FETCH_ASSOC)
		];
	}

	public function createMessage(
		string $message,
		string $userId,
		string $ip,
		string $browser,
		string $savedfilePath = null
	): bool {
		$stmt = $this->db->prepare(
			"INSERT INTO `messages` (`text`, `user_id`, `filePath`, `sender_ip`, `browser`) VALUES (:message, :userId, :savedfilePath, :ip, :browser)"
		);
		return $stmt->execute([
			'message' => strip_tags(htmlspecialchars(($message))),
			'userId' => $userId,
			'savedfilePath' => $savedfilePath,
			'ip' => $ip,
			'browser' => $browser
		]);
	}
}
