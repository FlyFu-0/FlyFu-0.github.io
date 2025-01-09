<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/core/db.php';

class MessageModel
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = pdo();
    }

    public function fetchPagedMessages($sortingField = 'created', $order = 'DESC')
    {
        $sortField = htmlspecialchars($sortingField);
        $order =  htmlspecialchars($order);

        $stmt = $this->pdo->query("SELECT COUNT(*) as total FROM `messages`");
        $total = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

        $messagesPerPage = 25;
        $totalPages = ceil($total / $messagesPerPage);

        $page = (isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0) ? min((int)$_GET['page'], $totalPages) : 1;

        $startRecord = ($page * $messagesPerPage) - $messagesPerPage;

        $stmt = $this->pdo->prepare(
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

    public function createMessage(string $message, string $userId, string $ip, string $browser, string $savedfilePath = NULl): bool
    {
        $stmt = $this->pdo->prepare("INSERT INTO `messages` (`text`, `user_id`, `filePath`, `sender_ip`, `browser`) VALUES (:message, :userId, :savedfilePath, :ip, :browser)");
        return $stmt->execute([
            'message' => htmlspecialchars($message),
            'userId' => $userId,
            'savedfilePath' => $savedfilePath,
            'ip' => $ip,
            'browser' => $browser
        ]);
    }
}
