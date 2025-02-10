<?php

namespace Models;

use Core\DB;
use PDO;

class Message extends DB
{
    public function fetchPagedMessages(
        $sortingField = 'created',
        $order = 'DESC'
    ): array {

        $sortingField = htmlspecialchars($sortingField);
        $order = htmlspecialchars($order);

        $total = $this->count('messages');

        $messagesPerPage = 25;
        $totalPages = ceil($total / $messagesPerPage);

        $page = (isset($_GET['page']) && is_numeric($_GET['page'])
            && $_GET['page'] > 0) ? min((int)$_GET['page'], $totalPages) : 1;

        $startRecord = ($page * $messagesPerPage) - $messagesPerPage;

        $result = $this->get(
            'messages',
            ['username', 'email', 'text', 'messages.create_date as created', 'filePath'],
            $sortingField,
            $order,
            ['user' => 'messages.user_id = user.id'],
            $startRecord,
            $messagesPerPage
        );

        return [
            'totalPages' => $totalPages,
            'currentPage' => $page,
            'result' => $result,
            'sortOrder' => $order,
            'sortingField' => $sortingField,
        ];
    }

    public function createMessage(
        string $message,
        string $userId,
        string $ip,
        string $browser,
        string $savedFilePath = null
    ): bool
    {
        return $this->insert('messages', [
            'text' => $message,
            'user_id' => $userId,
            'filePath' => $savedFilePath,
            'sender_ip' => $ip,
            'browser' => $browser,
        ]);
    }
}
