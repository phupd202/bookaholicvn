<?php

require_once(BASE_PATH . '/template/admin/layouts/head-tag.php');


?>


<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h5"><i class="fas fa-image"></i> Banner</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a role="button" href="<?= url('admin/banner/create') ?>" class="btn btn-sm btn-success">create</a>
    </div>
</div>
<div class="table-responsive">
    <table class="table table-striped table-sm">
        <caption>List of banners</caption>
        <thead>
            <tr>
                <th style="text-align: center;">#</th>
                <!-- <th style="text-align: center;">URL</th> -->
                <th style="text-align: center;">Image</th>
                <th style="text-align: center;">Actions</th>
            </tr>
        </thead>
        <tbody>

            <?php foreach ($banners as $key => $banner) { ?>
                <tr>
                    <td style="text-align: center;"><?= $key += 1 ?></td>
                    <!-- <td style="text-align: center;"><?= $banner['url'] ?></td> -->
                    <td style="text-align: center;"><img style="width: 80px;" src="<?= asset($banner['image']) ?>" alt=""></td>
                    <td style="text-align: center;">
                        <a role="button" class="btn btn-sm btn-primary text-white" href="<?= asset('admin/banner/edit/' . $banner['id']) ?>"><i class="fas fa-pen"></i></a>
                        <a role="button" class="btn btn-sm btn-danger text-white" href="<?= asset('admin/banner/delete/' . $banner['id']) ?>"><i class="fas fa-trash"></i></a>
                    </td>
                </tr>

            <?php } ?>

        </tbody>

    </table>
</div>




<?php

require_once(BASE_PATH . '/template/admin/layouts/footer.php');


?>