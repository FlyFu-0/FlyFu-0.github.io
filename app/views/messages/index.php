<div class="nav">
	<?php
	if (!isset($_SESSION['user_id'])) : ?>
        <a href="register/">Register</a>
        <a href="login/">Login</a>
	<?php
	else : ?>
        <a href="/logout/">Logout</a>
	<?php
	endif ?>
</div>

<?php
if (isset($_SESSION['user_id'])) : ?>
    <div class="profile-container">
        <h3>Profile</h3>
        <p>Username: <b><?= $user_name ?></b></p>
        <p>Email: <b><?= $user_email ?></b></p>
    </div>


    <form action="/messages/" method="post" enctype="multipart/form-data"
          class="message-send-container">
        <textarea name="message" placeholder="Your message..."
                  required></textarea>
        <br/>

        <!--        <input type="hidden" name="MAX_FILE_SIZE" value="103000">-->
        <input type="file" name="file">

        <input type="submit" value="Send" name="Send"> <input type="reset"
                                                              value="Reset">
    </form>
<?php
endif ?>

<table>
    <thead>
    <tr>
        <td><a href="?sort=username&order=<?= $sortOrder === 'desc' ? 'asc'
				: 'desc' ?>">UserName</a></td>
        <td><a href="?sort=email&order=<?= $sortOrder === 'desc' ? 'asc'
				: 'desc' ?>">Email</a></td>
        <td><a href="?sort=text&order=<?= $sortOrder === 'desc' ? 'asc'
				: 'desc' ?>">Text</a></td>
        <td><a href="?sort=filePath&order=<?= $sortOrder === 'desc' ? 'asc'
				: 'desc' ?>">File</a></td>
        <td><a href="?sort=created&order=<?= $sortOrder === 'desc' ? 'asc'
				: 'desc' ?>">Created</a></td>
    </tr>
    </thead>
    <tbody>
	<?php

	foreach ($messages as $message) : ?>
        <tr>
            <td><?= $message["username"] ?></td>
            <td><?= $message["email"] ?></td>
            <td><?= $message["text"] ?></td>
            <td>
				<?php
				if (!empty($message['filePath'])
					&& file_exists(
						$_SERVER['DOCUMENT_ROOT'] . '/upload/'
						. $message['filePath']
					)
				) : ?>
                    <a href="/upload/<?= htmlspecialchars(
						$message['filePath']
					) ?>" target="_blank">file</a>
				<?php
				endif ?>
            </td>
            <td><?= $message["created"] ?></td>
        </tr>
	<?php
	endforeach ?>
    </tbody>
</table>

<div class="pagination">
	<?php
	if ($currentPage > 1) : ?>
        <a href="?page=<?= ($currentPage - 1) ?>" class="pageLink">Previous</a>
	<?php
	endif ?>

	<?php
	if ($currentPage > 2) : ?>
        <a href="?page=1" class="pageLink">1</a>
		<?php
		if ($currentPage > 3) : ?>
            <span class="pageLink">...</span>
		<?php
		endif ?>
	<?php
	endif ?>
	<?php
	for (
		$i = max(1, $currentPage - 1); $i <= min($totalPages, $currentPage + 1);
		$i++
	) : ?>
		<?php
		if ($i === $currentPage) : ?>
            <a class="pageLink active"><?= $i ?></a>
		<?php
		else : ?>
            <a href="?page=<?= $i ?>" class="pageLink"><?= $i ?></a>
		<?php
		endif ?>
	<?php
	endfor ?>
	<?php
	if ($currentPage < $totalPages - 1) : ?>
		<?php
		if ($currentPage < $totalPages - 2) : ?>
            <span class="pageLink">...</span>
		<?php
		endif ?>
        <a href="?page=<?= $totalPages ?>"
           class="pageLink"><?= $totalPages ?></a>
	<?php
	endif ?>
	<?php
	if ($currentPage < $totalPages) : ?>
        <a href="?page=<?= ($currentPage + 1) ?>" class="pageLink">Next</a>
	<?php
	endif ?>
</div>