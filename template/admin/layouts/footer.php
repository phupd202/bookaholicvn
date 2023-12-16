</main>
</div>
</div>


<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="<?= asset('public/admin-panel/js/bootstrap.min.js') ?>"></script>
<script src="<?= asset('public/admin-panel/js/mdb.min.js') ?>"></script>
<script src="<?= asset('public/ckeditor/ckeditor.js') ?>"></script>
<script src="<?= asset('public/jalalidatepicker/persian-date.min.js') ?>"></script>
<script src="<?= asset('public/jalalidatepicker/persian-datepicker.min.js') ?>"></script>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

<script>
        $(document).ready(function() {
                $('.btn-danger').on('click', function(e) {
                        e.preventDefault();
                        var url = $(this).attr('href');
                        Swal.fire({
                                title: "Xác nhận xóa bỏ?",
                                text: "Bạn có chắc chắn muốn xóa bỏ?",
                                icon: "warning",
                                showCancelButton: true,
                                confirmButtonColor: "#d33",
                                cancelButtonColor: "#3085d6",
                                confirmButtonText: "Xóa bỏ",
                                cancelButtonText: "Đóng",
                        }).then((result) => {
                                if (result.isConfirmed) {
                                        window.location.href = url;
                                }
                        });
                });
        });
</script>


<script>
        $(document).ready(function() {
                CKEDITOR.replace('summary');
                CKEDITOR.replace('body');

                $("#published_at_view").persianDatepicker({

                        format: 'YYYY-MM-DD HH:mm:ss',
                        toolbox: {
                                calendarSwitch: {
                                        enabled: true
                                }
                        },
                        timePicker: {
                                enabled: true,
                        },
                        observer: true,
                        altField: '#published_at'

                })
        });
</script>

</body>

</html>