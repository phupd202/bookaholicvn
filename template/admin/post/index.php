<?php

require_once(BASE_PATH . '/template/admin/layouts/head-tag.php')

?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h5"><i class="fas fa-newspaper"></i> Articles</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a role="button" href="<?= url('admin/post/create') ?>" class="btn btn-sm btn-success">create</a>
    </div>
</div>
<div class="table-responsive">
    <table class="table table-striped table-sm">
        <caption>List of posts</caption>
        <thead>
            <tr>
                <th style="text-align: center;">#</th>
                <th style="text-align: center;">Title</th>
                <th style="text-align: center;">Summary</th>
                <th style="text-align: center;">View</th>
                <th style="text-align: center;">Status</th>
                <th style="text-align: center;">User ID</th>
                <th style="text-align: center;">Cat ID</th>
                <th style="text-align: center;">Image</th>
                <th style="text-align: center;">Actions</th>
            </tr>
        </thead>
        <tbody>

            <?php foreach ($posts as $key => $post) { ?>

                <tr>
                    <td style="text-align: center;">
                        <?= $key += 1 ?>
                    </td>
                    <td style="text-align: center;">
                        <?= $post['title'] ?>
                    <td style="text-align: center;">
                        <?= $post['summary'] ?>
                    </td>
                    <td style="text-align: center;">
                        <?= $post['view'] ?>
                    </td>
                    <td style="text-align: center;">
                        <?php if ($post['breaking_news'] == 2) { ?>
                            <span class="badge badge-success">#breaking_news</span>
                        <?php }
                        if ($post['selected'] == 2) { ?>
                            <span class="badge badge-dark">#editor_selected</span>
                        <?php } ?>
                    </td>
                    <td style="text-align: center;">
                        <?= $post['user_id'] ?>
                    </td>
                    <td style="text-align: center;">
                        <?= $post['cat_id'] ?>
                    </td>
                    <td style="text-align: center;">
                        <img style="width: 80px;" src="<?= asset($post['image']) ?>" alt="">
                    </td>
                    <td style="width: 25rem; text-align: center;">
                        <a role="button" class="btn btn-sm btn-warning btn-info text-dark" href="<?= url('admin/post/breaking-news/' . $post['id']) ?>">
                            <?php if ($post['breaking_news'] == 2) { ?>
                                remove breaking news
                            <?php } else { ?>
                                add breaking news
                            <?php } ?>
                        </a>
                        <a role="button" class="btn btn-sm btn-warning btn-warning text-dark" href="<?= url('admin/post/selected/' . $post['id']) ?>">
                            <?php if ($post['selected'] == 2) { ?>
                                remove selected
                            <?php } else { ?>
                                add selected
                            <?php } ?>
                        </a>
                        <hr class="my-1" />
                        <a role="button" class="btn btn-sm btn-primary text-white" href="<?= url('admin/post/edit/' . $post['id']) ?>"><i class="fas fa-pen"></i></a>
                        <a role="button" class="btn btn-sm btn-danger text-white" href="<?= url('admin/post/delete/' . $post['id']) ?>"><i class="fas fa-trash"></i></a>
                    </td>
                </tr>

            <?php } ?>
        </tbody>

    </table>
</div>



<?php

require_once(BASE_PATH . '/template/admin/layouts/footer.php')

?>