<?php

require_once(BASE_PATH . '/template/admin/layouts/head-tag.php');


?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h5"><i class="fas fa-bars"></i> Menus</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a role="button" href="<?= url('admin/menu/create') ?>" class="btn btn-sm btn-success">create</a>
    </div>
</div>
<section class="table-responsive">
    <table class="table table-striped table-sm">
        <caption>List of menus</caption>
        <thead>
            <tr>
                <th style="text-align: center;">#</th>
                <th style="text-align: center;">Name</th>
                <th style="text-align: center;">URL</th>
                <!-- <th style="text-align: center;">Parent ID</th> -->
                <th style="text-align: center;">Actions</th>
            </tr>
        </thead>
        <tbody>

            <?php foreach ($menus as $key => $menu) { ?>
                <tr>
                    <td style="text-align: center;">
                        <?= $key += 1 ?>
                    </td>
                    <td style="text-align: center;">
                        <?= $menu['name'] ?>
                    </td>
                    <td style="text-align: center;">
                        <?= $menu['url'] ?>
                    </td>
                    <!-- <td style="text-align: center;">
                        <?= $menu['parent_id'] == null ? 'Main menu' : $menu['parent_name'] ?>
                    </td> -->
                    <td style="text-align: center;">
                        <a role="button" class="btn btn-sm btn-primary text-white" href="<?= url('admin/menu/edit/' . $menu['id']) ?>"><i class="fas fa-pen"></i></a>
                        <a role="button" class="btn btn-sm btn-danger text-white" href="<?= url('admin/menu/delete/' . $menu['id']) ?>"><i class="fas fa-trash"></i></a>
                    </td>
                </tr>

            <?php } ?>

        </tbody>
    </table>
</section>

<?php

require_once(BASE_PATH . '/template/admin/layouts/footer.php');


?>