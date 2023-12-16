<?php
require_once(BASE_PATH . '/template/admin/layouts/head-tag.php');
?>


<?php

use database\DataBase;

$db = new DataBase();
$uniquePostIds = $db->select("SELECT DISTINCT post_id FROM comments")->fetchAll(PDO::FETCH_COLUMN);

$postIdFilter = isset($_GET['postIdFilter']) ? $_GET['postIdFilter'] : '';

$commentsQuery = "SELECT comments.*, posts.title AS post_title, users.email AS email FROM comments
                  LEFT JOIN posts ON comments.post_id = posts.id
                  LEFT JOIN users ON comments.user_id = users.id";
if (!empty($postIdFilter)) {
    $commentsQuery .= " WHERE comments.post_id = $postIdFilter";
}
$commentsQuery .= " ORDER BY `id` DESC";

$comments = $db->select($commentsQuery);
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h5"><i class="fas fa-comments"></i> Comments</h1>
    <div class="btn-toolbar mb-2 mb-md-0 d-none">
        <a role="button" href="#" class="btn btn-sm btn-success disabled">create</a>
    </div>
</div>

<div class="mb-3">
    <form id="filterForm" class="form-inline" method="GET" action="<?= url('admin/comment/') ?>">
        <label for="postIdFilter" class="mr-2">Filter by Post ID:</label>
        <select name="postIdFilter" id="postIdFilter" class="form-control mr-2">
            <option value="">All</option>
            <?php foreach ($uniquePostIds as $postId) { ?>
                <option value="<?= $postId ?>" <?= ($postIdFilter == $postId) ? 'selected' : '' ?>><?= $postId ?></option>
            <?php } ?>
        </select>
        <button type="submit" class="btn btn-primary">Filter</button>
    </form>
</div>

<section class="table-responsive">
    <table class="table table-striped table-sm">
        <caption>List of comments</caption>
        <thead>
            <tr>
                <th style="text-align: center;">#</th>
                <th style="text-align: center;">User Email</th>
                <th style="text-align: center;">Post Title</th>
                <th style="text-align: center;">Post ID</th>
                <th style="text-align: center;">Comment</th>
                <th style="text-align: center;">Status</th>
                <th style="text-align: center;">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($comments as $key => $comment) { ?>
                <tr>
                    <td style="text-align: center;">
                        <a href=""><?= $key += 1 ?></a>
                    </td>
                    <td style="text-align: center;">
                        <?= $comment['email'] ?>
                    </td>
                    <td style="text-align: center;">
                        <?= $comment['post_title'] ?>
                    </td>
                    <td style="text-align: center;">
                        <?= $comment['post_id'] ?>
                    </td>
                    <td style="text-align: center;">
                        <?= $comment['comment'] ?>
                    </td>
                    <td style="text-align: center;">
                        <?= $comment['status'] ?>
                    </td>
                    <td style="text-align: center;">
                        <?php if ($comment['status'] == 'seen') { ?>
                            <a role="button" class="btn btn-sm btn-success text-white" href="<?= url('admin/comment/change-status/' . $comment['id']) ?>">click to approved</a>
                        <?php } else { ?>
                            <a role="button" class="btn btn-sm btn-warning text-white" href="<?= url('admin/comment/change-status/' . $comment['id']) ?>">click not to approved</a>
                        <?php } ?>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</section>




<?php

require_once(BASE_PATH . '/template/admin/layouts/footer.php');


?>