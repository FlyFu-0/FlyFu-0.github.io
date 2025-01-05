<?php

function fetchMessages() {
    return mysqli_query(db(), "SELECT username, email, text, m.create_date AS 'created' FROM messages m JOIN user u ON m.user_id = u.id ORDER BY 4 DESC;");
}

function createMessage(string $message) {
    $flag = mysqli_query(db(), "INSERT INTO messages (text, user_id) VALUES ('$message', 1);");
    var_dump($flag);

    var_dump($_FILES);
}