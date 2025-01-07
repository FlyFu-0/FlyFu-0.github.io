<?php

    require_once __DIR__ . '/header.php';
    require_once __DIR__ . '/messages.php';

    $result = fetchPagedMessages();

    $error = '';
    $savedfilePath = NULL;

    if (!empty($_POST)) {
        $message = trim(htmlspecialchars($_POST['message'] ?? ''));

        if (!empty($message)) {
            if (isset($_FILES['file']) && empty($_FILES['file']['error'])) {
                if (addFile($error, $savedfilePath)) {
                    createMessage($message, $savedfilePath);
                    header('Location: index.php');
                    die;
                }
            } else {
                createMessage($message);
                header('Location: index.php');
                die;
            }
        } else {

            $error = 'Please enter a message';
        }
    }
?>
<div class="container">
    <!-- <form action="" method="post">
        <input type="text" placeholder="Username" />
        <input type="password" placeholder="Password" />

        <input type="submit" value="Authorize">
    </form> -->

    <!-- <div>
        <p>Username: </p>
        <p>Email: </p>
    </div> -->

    <p style="color: red;"><?= $error; ?></p>

    <form action="/index.php" method="post" enctype="multipart/form-data">
        <textarea name="message" placeholder="Your message..."></textarea>
        <br />

        <input type="hidden" name="MAX_FILE_SIZE" value="103000">
        <input type="file" name="file">

        <input type="submit" value="Send" name="Send"> <input type="reset" value="Reset">
    </form>

    <br />

    <!-- <?php for ($i = 1; $i <= $str_pag; $i++) : ?>
        <a href=index.php?page=".<?= $i ?>."> Страница ".<?= $i ?>." </a>;
    <?php endfor ?> -->

    <table>
        <thead style="text-align: center;">
            <tr>
                <td>#</td>
                <td>UserName</td>
                <td>Email</td>
                <td>Text</td>
                <td>File</td>
                <td>Created</td>
            </tr>
        </thead>
        <tbody>
            <?php

            for ($i = 1; $i <= mysqli_num_rows($result); $i++) :
                $row = mysqli_fetch_assoc($result) ?>
                <tr>
                    <td><?= $i ?></td>
                    <td><?= $row["username"] ?></td>
                    <td><?= $row["email"] ?></td>
                    <td><?= $row["text"] ?></td>
                    <td>
                        <?php if (!empty($row['filePath']) && file_exists($_SERVER['DOCUMENT_ROOT'] . '/userfiles/' . $row['filePath'])) : ?>
                            <a href="/userfiles/<?= htmlspecialchars($row['filePath']) ?>" target="_blank">file</a>
                        <?php endif ?>
                    </td>
                    <td><?= $row["created"] ?></td>
                </tr>
            <?php endfor ?>
        </tbody>
    </table>
</div>
<?php
    mysqli_close(db());
    require_once __DIR__ . '/footer.php';
?>