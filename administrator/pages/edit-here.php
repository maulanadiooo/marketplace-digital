<?php

require '../../web.php';
require '../../lib/check_session_admin.php';

$cara_kerja = $model->db_query($db, "*", "pages", "id = '1'");
$syarat = $model->db_query($db, "*", "pages", "id = '2'");
$tentang = $model->db_query($db, "*", "pages", "id = '3'");
$kebijakan = $model->db_query($db, "*", "pages", "id = '4'");

$title = "Pages";
require '../lib/header.php';
require '../lib/sidebar.php';
?>

<div class="main-panel">
	<div class="content">
	    <div class="page-inner">
				<div class="page-header">
				<h4 class="page-title">Settings</h4>
				<ul class="breadcrumbs">
					<li class="nav-home">
						<a href="#">
							<i class="flaticon-home"></i>
						</a>
					</li>
					<li class="separator">
						<i class="flaticon-right-arrow"></i>
					</li>
					<li class="nav-item">
						<a href="#">Pages</a>
					</li>
					<li class="separator">
						<i class="flaticon-right-arrow"></i>
					</li>
				</ul>
			</div>
				<!--end breadcrumb-->
				<form class="row g-3" action="<?= $config['web']['base_url'] ?>administrator/pages/action.php" method="post">
				<div class="card">
					<div class="card-body">
						<h1 class="mb-0 text-uppercase">Cara Kerja</h1>
						<hr/>
						<h6 class="mb-4">Paragraf 1</h6>
						<div class="col-md-12" id="description_container_carakerja1">
							<textarea id="contentcarakerja1" name="carakerja1" class="form-control form-control-lg" rows="10"><?=$cara_kerja['rows']['value']?></textarea>
						</div>
						<hr/>
						<h6 class="mb-4">Paragraf 2</h6>
						<div class="col-md-12" id="description_container_carakerja2">
							<textarea id="contentcarakerja2" name="carakerja2" class="form-control form-control-lg" rows="10"><?=$cara_kerja['rows']['value_1']?></textarea>
						</div>
						<hr/>
						<h6 class="mb-4">Paragraf 3</h6>
						<div class="col-md-12" id="description_container_carakerja3">
							<textarea id="contentcarakerja3" name="carakerja3" class="form-control form-control-lg" rows="10"><?=$cara_kerja['rows']['value_2']?></textarea>
						</div>
						<hr/>
					</div>
					<div class="card-body">
    					<h1 class="mb-0 text-uppercase">About Us</h1>
    					<hr/>
    					<h6 class="mb-4">Paragraf 1</h6>
						<div class="col-md-12" id="description_container_tentang">
							<textarea id="contenttentang" name="tentang1" class="form-control form-control-lg" rows="10"><?=$tentang['rows']['value']?></textarea>
						</div>
						<hr/>
    					<h6 class="mb-4">Paragraf 2</h6>
						<div class="col-md-12" id="description_container_tentang1">
							<textarea id="contenttentang1" name="tentang2" class="form-control form-control-lg" rows="10"><?=$tentang['rows']['value_1']?></textarea>
						</div>
						<hr/>
					</div>
					<div class="card-body">
    					<h1 class="mb-0 text-uppercase">Syarat dan Ketentuan</h1>
    					<hr/>
    					<h6 class="mb-4">Paragraf 1</h6>
						<div class="col-md-12" id="description_container_syarat">
							<textarea id="contentsyarat" name="syarat" class="form-control form-control-lg" rows="10"><?=$syarat['rows']['value']?></textarea>
						</div>
						<hr/>
					</div>
					<div class="card-body">
    					<h1 class="mb-0 text-uppercase">Kebijakan dan Privasi</h1>
    					<hr/>
    					<h6 class="mb-4">Paragraf 1</h6>
						<div class="col-md-12" id="description_container_kebijakan">
							<textarea id="contentkebijakan" name="kebijakan" class="form-control form-control-lg" rows="10"><?=$kebijakan['rows']['value']?></textarea>
						</div>
						<hr/>
					</div>
        			<div class="mb-3">
        					<button class="btn btn-primary" type="submit">Save</button>
        			</div>
        			</form>
				</div>
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
        selector: '#contentcarakerja1',
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
                $('#description_container_carakerja1').show();

                break;

            case 'external':

                $('#url_label').html("External Url");
                $('#url_prepend').hide();
                $('input[name="url"]').attr('placeholder', "ex: https:\/\/domain.com\/");
                $('#description_container_carakerja1').hide();

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
        selector: '#contentsyarat',
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
                $('#description_container_syarat').show();

                break;

            case 'external':

                $('#url_label').html("External Url");
                $('#url_prepend').hide();
                $('input[name="url"]').attr('placeholder', "ex: https:\/\/domain.com\/");
                $('#description_container_syarat').hide();

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
        selector: '#contenttentang1',
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
                $('#description_container_tentang1').show();

                break;

            case 'external':

                $('#url_label').html("External Url");
                $('#url_prepend').hide();
                $('input[name="url"]').attr('placeholder', "ex: https:\/\/domain.com\/");
                $('#description_container_tentang1').hide();

                break;
        }

    });
</script>
<script>
    tinymce.init({
        selector: '#contentkebijakan',
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
                $('#description_container_kebijakan').show();

                break;

            case 'external':

                $('#url_label').html("External Url");
                $('#url_prepend').hide();
                $('input[name="url"]').attr('placeholder', "ex: https:\/\/domain.com\/");
                $('#description_container_kebijakan').hide();

                break;
        }

    });
</script>
<?
require '../lib/footer.php';
?>