<?php
require_once(BASE_PATH . "/template/admin/layouts/head-tag.php");
?>


<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h5 "><i class="fas fa-tools"></i> Website Setting</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a role="button" href="<?= url('admin/web-setting/set') ?>" class="btn btn-sm btn-success">set web setting</a>
    </div>
</div>
<section class="table-responsive">
    <table class="table table-striped table-sm">
        <caption>Website setting</caption>
        <thead>
            <tr style="text-align: center;">
                <th style="text-align: center;">Name</th>
                <th style="text-align: center;">Value</th>
            </tr>
        </thead>
        <tbody>

            <tr style="text-align: center;">
                <td>Title</td>
                <td><?= $setting['title']; ?></td>
            </tr>
            <tr style="text-align: center;">
                <td>Description</td>
                <td><?= $setting['description']; ?></td>
            </tr>
            <tr style="text-align: center;">
                <td>Key words</td>
                <td><?= $setting['keywords']; ?></td>
            </tr>
            <tr style="text-align: center;">
                <td>Logo</td>
                <td><img src="<?= asset($setting['logo']); ?>" alt="" width="100px" height="100px"></td>
            </tr>
            <tr style="text-align: center;">
                <td>Icon</td>
                <td><img src="<?= asset($setting['icon']); ?>" alt="" width="100px" height="100px" </td>
            </tr>
        </tbody>
    </table>
</section>


<?php
require_once(BASE_PATH . "/template/admin/layouts/footer.php");
?>