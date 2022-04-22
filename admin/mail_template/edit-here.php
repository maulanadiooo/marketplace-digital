<?php

require '../../web.php';
require '../../lib/check_session_admin.php';

$cara_kerja = $model->db_query($db, "*", "pages", "id = '1'");
$syarat = $model->db_query($db, "*", "pages", "id = '2'");
$tentang = $model->db_query($db, "*", "pages", "id = '3'");
$kebijakan = $model->db_query($db, "*", "pages", "id = '4'");

$title = "Pages";
require '../lib/sidebar.php';
require '../lib/header.php';
?>

<div class="page-wrapper">
			<div class="page-content">
				<!--breadcrumb-->
				<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
					<div class="breadcrumb-title pe-3">Email</div>
					<div class="ps-3">
						<nav aria-label="breadcrumb">
							<ol class="breadcrumb mb-0 p-0">
								<li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
								</li>
								<li class="breadcrumb-item active" aria-current="page">Template</li>
							</ol>
						</nav>
					</div>
				</div>
				<!--end breadcrumb-->
				<form class="row g-3" action="<?= $config['web']['base_url'] ?>administrator/mail_template/action.php" method="post">
				<div class="card">
					<div class="card-body">
					    <?
					    $mail_verifikasi = $model->db_query($db, "*", "email", "id = '1'");
					    ?>
						<h4 class="mb-0 text-uppercase">Email verifikasi</h4>
						<hr/>
						<div class="col-md-12" id="email_verifikasi">
							<textarea id="mail_verif" name="verif_email" class="form-control form-control-lg" rows="10"><?=$mail_verifikasi['rows']['email']?></textarea>
						</div><br>
						<p>Note: Jangan Dirubah Urutan Dan Kode {{full_name}}  {{alternatif_link}}</p>
						<hr/>
					</div>
					<div class="card-body">
					    <?
					    $mail_reset = $model->db_query($db, "*", "email", "id = '2'");
					    ?>
    					<h4 class="mb-0 text-uppercase">Reset Password</h4>
    					<hr/>
						<div class="col-md-12" id="reset_password">
							<textarea id="password_reset" name="reset_password_mail" class="form-control form-control-lg" rows="10"><?=$mail_reset['rows']['email']?></textarea>
						</div><br>
						<p>Note: Jangan Dirubah Urutan Dan Kode {{reset_link}}</p>
						<hr/>
					</div>
					<div class="card-body">
					    <?
					    $mail_order = $model->db_query($db, "*", "email", "id = '3'");
					    ?>
    					<h4 class="mb-0 text-uppercase">Orderan Masuk</h4>
    					<hr/>
						<div class="col-md-12" id="order_mail">
							<textarea id="mail_order" name="order_mail" class="form-control form-control-lg" rows="10"><?=$mail_order['rows']['email']?></textarea>
						</div><br>
						<p>Note: Jangan Dirubah Link yang telah disisipkan pada kata "Disini" atau merubah kode {{link_penjualan}}</p>
						<hr/>
					</div>
					<div class="card-body">
					    <?
					    $mail_order_perbarui = $model->db_query($db, "*", "email", "id = '4'");
					    ?>
    					<h4 class="mb-0 text-uppercase">Pembaruan Orderan</h4>
    					<hr/>
						<div class="col-md-12" id="pembaruan_orderan">
							<textarea id="orderan_pembaruan" name="pembaruan_orderan" class="form-control form-control-lg" rows="10"><?=$mail_order_perbarui['rows']['email']?></textarea>
						</div><br>
						<p>Note: Jangan Dirubah Link yang telah disisipkan pada kata "Disini" atau merubah kode {{link_penjualan}}</p>
						<hr/>
					</div>
					<div class="card-body">
					    <?
					    $mail_pesan = $model->db_query($db, "*", "email", "id = '5'");
					    ?>
    					<h4 class="mb-0 text-uppercase">Pesan Masuk</h4>
    					<hr/>
						<div class="col-md-12" id="pesan_masuk">
							<textarea id="masuk_pesan" name="pesan_masuk" class="form-control form-control-lg" rows="10"><?=$mail_pesan['rows']['email']?></textarea>
						</div><br>
						<p>Note: Jangan Dirubah Urutan Dan Kode {{username}}</p>
						<hr/>
					</div>
					<div class="card-body">
					    <?
					    $mail_validasi = $model->db_query($db, "*", "email", "id = '6'");
					    ?>
    					<h4 class="mb-0 text-uppercase">Validasi Pembayaran</h4>
    					<hr/>
						<div class="col-md-12" id="validasi_pembayaran">
							<textarea id="pembayaran_validasi" name="validasi_pembayaran" class="form-control form-control-lg" rows="10"><?=$mail_validasi['rows']['email']?></textarea>
						</div><br>
						<p>Note: Jangan Dirubah Urutan Dan Kode {{username}} {{jumlah_pembayaran}}</p>
						<hr/>
					</div>
					<div class="card-body">
					    <?
					    $mail_invoice = $model->db_query($db, "*", "email", "id = '7'");
					    ?>
    					<h4 class="mb-0 text-uppercase">Invoice Sukses</h4>
    					<hr/>
						<div class="col-md-12" id="invoice">
							<textarea id="in_voice" name="invoice" class="form-control form-control-lg" rows="10"><?=$mail_invoice['rows']['email']?></textarea>
						</div><br>
						<p>Note: <br>
						- Logo website tidak tampil, namun akan tampil saat pengiriman email <br>
						- Jangan Dirubah Urutan Dan Kode {{id_invoice}} {{amount}} {{layanan}} {{quantity}} {{admin_fee}}</p>
						<hr/>
					</div>
        			<div class="mb-3">
        					<button class="btn btn-primary" type="submit">Save</button>
        			</div>
        			</form>
				</div>
			</div>
		</div>
		<!--end page wrapper -->
		<!--start overlay-->
		<div class="overlay toggle-icon"></div>
		<!--end overlay-->
		<!--Start Back To Top Button--> <a href="javaScript:;" class="back-to-top"><i class='bx bxs-up-arrow-alt'></i></a>
		<!--End Back To Top Button-->
		<footer class="page-footer">
			<p class="mb-0">Copyright © 2021. All right reserved.</p>
		</footer>
	</div>
<script src="<?= $config['web']['base_url']; ?>vendor_assets/js/tinymce/tinymce.min.js"></script>    
<script>
    tinymce.init({
        selector: '#mail_verif',
        plugins: 'code preview fullpage autolink directionality visualblocks visualchars fullscreen image link media codesample table hr pagebreak nonbreaking toc advlist lists imagetools',
        toolbar: 'formatselect | bold italic strikethrough forecolor backcolor | link | alignleft aligncenter alignright alignjustify  | numlist bullist outdent | removeformat code',
    });

    $('[name="type"]').on('change', (event) => {

        let selectedOption = $(event.currentTarget).find(':selected').attr('value');

        switch(selectedOption) {

            case 'internal':

                $('#url_label').html("Slug");
                $('#url_prepend').show();
                $('input[name="url"]').attr('placeholder', "ex: page-url");
                $('#email_verifikasi').show();

                break;

            case 'external':

                $('#url_label').html("External Url");
                $('#url_prepend').hide();
                $('input[name="url"]').attr('placeholder', "ex: https:\/\/domain.com\/");
                $('#email_verifikasi').hide();

                break;
        }

    });
</script>
<script>
    tinymce.init({
        selector: '#contentcarakerja2',
        plugins: 'code preview fullpage autolink directionality visualblocks visualchars fullscreen image link media codesample table hr pagebreak nonbreaking toc advlist lists imagetools',
        toolbar: 'formatselect | bold italic strikethrough forecolor backcolor | link | alignleft aligncenter alignright alignjustify  | numlist bullist outdent | removeformat code',
    });

    $('[name="type"]').on('change', (event) => {

        let selectedOption = $(event.currentTarget).find(':selected').attr('value');

        switch(selectedOption) {

            case 'internal':

                $('#url_label').html("Slug");
                $('#url_prepend').show();
                $('input[name="url"]').attr('placeholder', "ex: page-url");
                $('#description_container_carakerja2').show();

                break;

            case 'external':

                $('#url_label').html("External Url");
                $('#url_prepend').hide();
                $('input[name="url"]').attr('placeholder', "ex: https:\/\/domain.com\/");
                $('#description_container_carakerja2').hide();

                break;
        }

    });
</script>
<script>
    tinymce.init({
        selector: '#contentcarakerja3',
        plugins: 'code preview fullpage autolink directionality visualblocks visualchars fullscreen image link media codesample table hr pagebreak nonbreaking toc advlist lists imagetools',
        toolbar: 'formatselect | bold italic strikethrough forecolor backcolor | link | alignleft aligncenter alignright alignjustify  | numlist bullist outdent | removeformat code',
    });

    $('[name="type"]').on('change', (event) => {

        let selectedOption = $(event.currentTarget).find(':selected').attr('value');

        switch(selectedOption) {

            case 'internal':

                $('#url_label').html("Slug");
                $('#url_prepend').show();
                $('input[name="url"]').attr('placeholder', "ex: page-url");
                $('#description_container_carakerja3').show();

                break;

            case 'external':

                $('#url_label').html("External Url");
                $('#url_prepend').hide();
                $('input[name="url"]').attr('placeholder', "ex: https:\/\/domain.com\/");
                $('#description_container_carakerja3').hide();

                break;
        }

    });
</script>
<script>
    tinymce.init({
        selector: '#mail_order',
        plugins: 'code preview fullpage autolink directionality visualblocks visualchars fullscreen image link media codesample table hr pagebreak nonbreaking toc advlist lists imagetools',
        toolbar: 'formatselect | bold italic strikethrough forecolor backcolor | link | alignleft aligncenter alignright alignjustify  | numlist bullist outdent | removeformat code',
    });

    $('[name="type"]').on('change', (event) => {

        let selectedOption = $(event.currentTarget).find(':selected').attr('value');

        switch(selectedOption) {

            case 'internal':

                $('#url_label').html("Slug");
                $('#url_prepend').show();
                $('input[name="url"]').attr('placeholder', "ex: page-url");
                $('#order_mail').show();

                break;

            case 'external':

                $('#url_label').html("External Url");
                $('#url_prepend').hide();
                $('input[name="url"]').attr('placeholder', "ex: https:\/\/domain.com\/");
                $('#order_mail').hide();

                break;
        }

    });
</script>
<script>
    tinymce.init({
        selector: '#contenttentang',
        plugins: 'code preview fullpage autolink directionality visualblocks visualchars fullscreen image link media codesample table hr pagebreak nonbreaking toc advlist lists imagetools',
        toolbar: 'formatselect | bold italic strikethrough forecolor backcolor | link | alignleft aligncenter alignright alignjustify  | numlist bullist outdent | removeformat code',
    });

    $('[name="type"]').on('change', (event) => {

        let selectedOption = $(event.currentTarget).find(':selected').attr('value');

        switch(selectedOption) {

            case 'internal':

                $('#url_label').html("Slug");
                $('#url_prepend').show();
                $('input[name="url"]').attr('placeholder', "ex: page-url");
                $('#description_container_tentang').show();

                break;

            case 'external':

                $('#url_label').html("External Url");
                $('#url_prepend').hide();
                $('input[name="url"]').attr('placeholder', "ex: https:\/\/domain.com\/");
                $('#description_container_tentang').hide();

                break;
        }

    });
</script>
<script>
    tinymce.init({
        selector: '#password_reset',
        plugins: 'code preview fullpage autolink directionality visualblocks visualchars fullscreen image link media codesample table hr pagebreak nonbreaking toc advlist lists imagetools',
        toolbar: 'formatselect | bold italic strikethrough forecolor backcolor | link | alignleft aligncenter alignright alignjustify  | numlist bullist outdent | removeformat code',
    });

    $('[name="type"]').on('change', (event) => {

        let selectedOption = $(event.currentTarget).find(':selected').attr('value');

        switch(selectedOption) {

            case 'internal':

                $('#url_label').html("Slug");
                $('#url_prepend').show();
                $('input[name="url"]').attr('placeholder', "ex: page-url");
                $('#reset_password').show();

                break;

            case 'external':

                $('#url_label').html("External Url");
                $('#url_prepend').hide();
                $('input[name="url"]').attr('placeholder', "ex: https:\/\/domain.com\/");
                $('#reset_password').hide();

                break;
        }

    });
</script>
<script>
    tinymce.init({
        selector: '#orderan_pembaruan',
        plugins: 'code preview fullpage autolink directionality visualblocks visualchars fullscreen image link media codesample table hr pagebreak nonbreaking toc advlist lists imagetools',
        toolbar: 'formatselect | bold italic strikethrough forecolor backcolor | link | alignleft aligncenter alignright alignjustify  | numlist bullist outdent | removeformat code',
    });

    $('[name="type"]').on('change', (event) => {

        let selectedOption = $(event.currentTarget).find(':selected').attr('value');

        switch(selectedOption) {

            case 'internal':

                $('#url_label').html("Slug");
                $('#url_prepend').show();
                $('input[name="url"]').attr('placeholder', "ex: page-url");
                $('#pembaruan_orderan').show();

                break;

            case 'external':

                $('#url_label').html("External Url");
                $('#url_prepend').hide();
                $('input[name="url"]').attr('placeholder', "ex: https:\/\/domain.com\/");
                $('#pembaruan_orderan').hide();

                break;
        }

    });
</script>
<script>
    tinymce.init({
        selector: '#pembayaran_validasi',
        plugins: 'code preview fullpage autolink directionality visualblocks visualchars fullscreen image link media codesample table hr pagebreak nonbreaking toc advlist lists imagetools',
        toolbar: 'formatselect | bold italic strikethrough forecolor backcolor | link | alignleft aligncenter alignright alignjustify  | numlist bullist outdent | removeformat code',
    });

    $('[name="type"]').on('change', (event) => {

        let selectedOption = $(event.currentTarget).find(':selected').attr('value');

        switch(selectedOption) {

            case 'internal':

                $('#url_label').html("Slug");
                $('#url_prepend').show();
                $('input[name="url"]').attr('placeholder', "ex: page-url");
                $('#validasi_pembayaran').show();

                break;

            case 'external':

                $('#url_label').html("External Url");
                $('#url_prepend').hide();
                $('input[name="url"]').attr('placeholder', "ex: https:\/\/domain.com\/");
                $('#validasi_pembayaran').hide();

                break;
        }

    });
</script>
<script>
    tinymce.init({
        selector: '#masuk_pesan',
        plugins: 'code preview fullpage autolink directionality visualblocks visualchars fullscreen image link media codesample table hr pagebreak nonbreaking toc advlist lists imagetools',
        toolbar: 'formatselect | bold italic strikethrough forecolor backcolor | link | alignleft aligncenter alignright alignjustify  | numlist bullist outdent | removeformat code',
    });

    $('[name="type"]').on('change', (event) => {

        let selectedOption = $(event.currentTarget).find(':selected').attr('value');

        switch(selectedOption) {

            case 'internal':

                $('#url_label').html("Slug");
                $('#url_prepend').show();
                $('input[name="url"]').attr('placeholder', "ex: page-url");
                $('#pesan_masuk').show();

                break;

            case 'external':

                $('#url_label').html("External Url");
                $('#url_prepend').hide();
                $('input[name="url"]').attr('placeholder', "ex: https:\/\/domain.com\/");
                $('#pesan_masuk').hide();

                break;
        }

    });
</script>
<script>
    tinymce.init({
        selector: '#in_voice',
        plugins: 'code preview fullpage autolink directionality visualblocks visualchars fullscreen image link media codesample table hr pagebreak nonbreaking toc advlist lists imagetools',
        toolbar: 'formatselect | bold italic strikethrough forecolor backcolor | link | alignleft aligncenter alignright alignjustify  | numlist bullist outdent | removeformat code',
    });

    $('[name="type"]').on('change', (event) => {

        let selectedOption = $(event.currentTarget).find(':selected').attr('value');

        switch(selectedOption) {

            case 'internal':

                $('#url_label').html("Slug");
                $('#url_prepend').show();
                $('input[name="url"]').attr('placeholder', "ex: page-url");
                $('#invoice').show();

                break;

            case 'external':

                $('#url_label').html("External Url");
                $('#url_prepend').hide();
                $('input[name="url"]').attr('placeholder', "ex: https:\/\/domain.com\/");
                $('#invoice').hide();

                break;
        }

    });
</script>
<?
require '../lib/footer.php';
?>