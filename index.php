<?php
    require_once __DIR__ . '/header.php';

    $db = db();

    if (mysqli_connect_errno()) {

        die("Database connection failed: " . mysqli_connect_error());
    }

    $result = mysqli_query($db, "SELECT username, email, text, m.create_date AS 'created' FROM messages m JOIN user u ON m.user_id = u.id ORDER BY 4 DESC;");
?>
<table>
    <thead>
        <tr>
            <td>UserName</td>
            <td>Email</td>
            <td>Text</td>
            <td>File</td>
            <td>Created</td>
        </tr>
    </thead>
    <tbody>
        <?php

        while ($row = mysqli_fetch_assoc($result)) : ?>
            <tr>
                <td><?= $row["username"] ?></td>
                <td><?= $row["email"] ?></td>
                <td><?= $row["text"] ?></td>
                <td></td>
                <td><?= $row["created"] ?></td>
            </tr>
        <?php endwhile ?>
    </tbody>
</table>
<?php
    mysqli_close($db);
    require_once __DIR__ . '/footer.php';
?>