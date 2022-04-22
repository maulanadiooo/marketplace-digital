<?php

require '../web.php';
require '../lib/is_login.php';
require '../lib/result.php';
require '../lib/csrf_token.php';
if (!isset($_SESSION['login'])) {
	$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Otentikasi dibutuhkan!', 'msg' => 'Silahkan masuk ke akun Anda.');
	exit(header("Location: ".$config['web']['base_url']."signin/"));
}


    if (!isset($_SESSION['login'])) {
		exit("No direct script access allowed!1");
	}
	if ($model->db_query($db, "*", "user", "id = '".$_SESSION['login']."'")['count'] == 0) {
		exit("No direct script access allowed!2");
	}
	if (!isset($_GET['id'])) {
		exit("No direct script access allowed!3");
	}
	$data_target = $model->db_query($db, "*", "services", "id = '".mysqli_real_escape_string($db, $_GET['id'])."'");
    if ($data_target['count'] == 0) {
        $_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Data tidak ditemukan.');
		exit(header("Location: ".$config['web']['base_url']."my-product/"));
	}
	if ($data_target['rows']['author'] != $login['id']) {
        $_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Data tidak ditemukan.');
		exit(header("Location: ".$config['web']['base_url']."my-product/"));
	}
    $category_active = $model->db_query($db, "*", "categories", "id = '".$data_target['rows']['categories_id']."'");


$title = "Edit Produk";
require '../template/header.php';
require '../template/header-dashboard.php';
?>



<!-- Breadcrumb Area -->
    
        <div class="dashboard_contents section--padding">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 col-sm-12">
                        <form action="<?= $config['web']['base_url'] ?>product-edit/<?=$data_target['rows']['id']?>" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="csrf_token" value="<?php echo $config['csrf_token'] ?>">
                            <div class="upload_modules">
                                <div class="modules__title">
                                    <h4>Nama Item & Deskripsi</h4>
                                </div><!-- ends: .module_title -->
                                <div class="modules__content">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="product_name">Nama Produk</label> 
                                                <input type="text" name="product_name" id="product_name" class="text_field" value="<?= $data_target['rows']['nama_layanan']; ?>"  readonly>
                                                <span>(Minimal 10 Karakter Max 100)</span>
                                            </div>
                                        </div><!-- ends: .col-md-6 -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="category">Pilih Kategori</label>
                                                <div class="select-wrap select-wrap2">
                                                    <select name="category" id="category" class="text_field" required>
                                                        <option value="<?= $data_target['rows']['categories_id']; ?>"><?= $category_active['rows']['category']?></option>
                                                        <?php
                    									$category_select = $model->db_query($db, "*", "categories");
                    									
                    									if ($category_select['count'] == 1) {
                    										print('<option value="'.$category_select['rows']['id'].'">'.$category_select['rows']['category'].'</option>');
                    									} else {
                    									foreach ($category_select['rows'] as $key) {
                    										print('<option value="'.$key['id'].'">'.$key['category'].'</option>');
                    									}
                    									}
                    									?>
                                                    </select>
                                                    <span class="lnr icon-arrow-down"></span>
                                                </div>
                                            </div>
                                        </div><!-- end: .col-md-6 -->
                                        <div class="col-md-12" id="description_container">
                                            <div class="m-bottom-20 no-margin">
                                                <label class="label">Deksripsi Produk</label>
                                                <textarea id="content" name="deskripsi" class="form-control form-control-lg" rows="20"><?= $data_target['rows']['description']; ?></textarea>
                                                <span>(Minimal 400 Karakter Max 1500)</span>
                                            </div>
                                        </div><!-- ends: .col-md-12 --> <br>
                                        <div class="col-md-12" id="description_container_faq">
                                            <div class="m-bottom-20 no-margin">
                                                <label class="label">FAQ</label>
                                                <textarea id="content_faq" name="faq" class="form-control form-control-lg" rows="20"><?= $data_target['rows']['faq']; ?></textarea>
                                                <span>(Minimal 250 Karakter Max 1500)</span>
                                            </div>
                                        </div><!-- ends: .col-md-12 -->
                                    </div>
                                </div><!-- ends: .modules__content -->
                            </div><!-- ends: .upload_modules -->
                            <div class="upload_modules module--upload">
                                <div class="modules__title">
                                    <h4>Upload Picture</h4>
                                </div><!-- ends: .module_title -->
                                <div class="modules__content">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6">
                                            <div class="form-group">
                                                <div class="upload_wrapper">
                                                    <div class="upload-field">
                                                        <input type="file" name="file" id="real-file" hidden="hidden" />
                                                        <button type="button" class="btn btn-lg btn-primary" id="custom-button3"><i class="icon-cloud-upload"></i>Upload Thumbnail</button>
                                                        <p>
                                                            <span id="custom-text">(Tidak ada file terpilih.. )</span>
                                                        </p>
                                                    </div>
                                                            <span>(Hanya Menerima Format JPEG, JPG, PNG Max 2MB)</span>
                                                </div><!-- ends: .upload_wrapper -->
                                            </div><!-- ends: .form-group -->
                                        
                                        </div><!-- ends: .col-md-6 -->
                                        <div class="col-lg-6 col-md-6">
                                            <div class="form-group">
                                                <label>Thumbnail Saat Ini</label>
                                                <div class="upload_wrapper">
                                                    <img src="<?= $config['web']['base_url']; ?>file-photo/<?= $data_target['rows']['id']; ?>/<?= $data_target['rows']['photo']; ?>" width ="150px" height="150px">
                                                </div><!-- ends: .upload_wrapper -->
                                            </div><!-- ends: .form-group -->
                                        
                                        </div><!-- ends: .col-md-6 -->
                                        
                                        <div class="col-lg-6 col-md-6">
                                            <div class="form-group">
                                                <div class="upload_wrapper">
                                                    <div class="upload-field">
                                                        <input type="file" name="file1" id="real-file1" hidden="hidden" />
                                                        <button type="button" class="btn btn-lg btn-primary" id="custom-button1"><i class="icon-cloud-upload"></i>Upload Other Pict</button>
                                                        <p>
                                                            <span id="custom-text1">(Tidak ada file terpilih.. )</span>
                                                        </p>
                                                    </div>
                                                            <span>(Hanya Menerima Format JPEG, JPG, PNG Max 2MB)</span>
                                                </div><!-- ends: .upload_wrapper -->
                                            </div><!-- ends: .form-group -->
                                        
                                        </div><!-- ends: .col-md-6 -->
                                        <div class="col-lg-6 col-md-6">
                                            <div class="form-group">
                                                <label>Other Pict Saat Ini</label>
                                                <div class="upload_wrapper">
                                                    <?
                                                    if($data_target['rows']['photo_1'] != null ){
                                                    ?>
                                                    <img src="<?= $config['web']['base_url']; ?>file-photo/<?= $data_target['rows']['id']; ?>/<?= $data_target['rows']['photo_1']; ?>" width ="150px" height="150px">
                                                    <?
                                                    }
                                                    ?>
                                                    
                                                </div><!-- ends: .upload_wrapper -->
                                            </div><!-- ends: .form-group -->
                                        
                                        </div><!-- ends: .col-md-6 -->
                                        
                                        <div class="col-lg-6 col-md-6">
                                            <div class="form-group">
                                                <div class="upload_wrapper">
                                                    <div class="upload-field">
                                                        <input type="file" name="file2" id="real-file2" hidden="hidden" />
                                                        <button type="button" class="btn btn-lg btn-primary" id="custom-button2"><i class="icon-cloud-upload"></i>Upload Other Pict</button>
                                                        <p>
                                                            <span id="custom-text2">(Tidak ada file terpilih.. )</span>
                                                        </p>
                                                    </div>
                                                            <span>(Hanya Menerima Format JPEG, JPG, PNG Max 2MB)</span>
                                                </div><!-- ends: .upload_wrapper -->
                                            </div><!-- ends: .form-group -->
                                        
                                        </div><!-- ends: .col-md-6 -->
                                        <div class="col-lg-6 col-md-6">
                                            <div class="form-group">
                                                <label>Other Pict Saat Ini</label>
                                                <div class="upload_wrapper">
                                                    <?
                                                    if($data_target['rows']['photo_2'] != null ){
                                                    ?>
                                                    <img src="<?= $config['web']['base_url']; ?>file-photo/<?= $data_target['rows']['id']; ?>/<?= $data_target['rows']['photo_2']; ?>" width ="150px" height="150px">
                                                    <?
                                                    }
                                                    ?>
                                                </div><!-- ends: .upload_wrapper -->
                                            </div><!-- ends: .form-group -->
                                        
                                        </div><!-- ends: .col-md-6 -->
                                    </div><!-- ends: .row -->
                                </div><!-- ends .module_content -->
                            </div><!-- ends: .upload_modules -->
                            <div class="upload_modules pricing--info">
                                <div class="modules__title">
                                    <h4>Informasi Harga & Waktu Pengerjaan</h4>
                                </div><!-- ends: .module_title -->
                                <div class="modules__content">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="rlicense">Harga Produk Utama (Rp)</label>
                                                <div class="input-group">
                                                    <!--<span class="input-group-addon">Rp</span>-->
                                                    <input type="number" name="price" id="rlicense" class="text_field" value="<?= $data_target['rows']['price']; ?>" required>
                                                </div>
                                            </div>
                                        </div><!-- ends: .col-md-6 -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="rlicense">Waktu Pengerjaan (Hari)</label>
                                                <div class="input-group">
                                                    <!--<span class="input-group-addon">Rp</span>-->
                                                    <input type="number" name="jangka_waktu" id="rlicense" class="text_field" value="<?= $data_target['rows']['jangka_waktu']; ?>" required>
                                                </div>
                                            </div>
                                        </div><!-- ends: .col-md-6 -->
                                    </div><!-- ends: .row -->
                                </div><!-- ends: .modules__content -->
                            </div><!-- ends: .upload_modules -->
                            <div class="upload_modules">
                                <div class="modules__title">
                                    <h4>Informasi Lain</h4>
                                </div><!-- ends: .module_title -->
                                <div class="modules__content">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="m-bottom-15">
                                                <label for="tags">Item Tags
                                                    <span>(Max 10 tags)</span>
                                                </label>
                                                <textarea name="tags" id="tags" class="text_field" required><?= $data_target['rows']['tags']; ?></textarea>
                                            </div>
                                        </div>
                                    </div><!-- ends: .row -->
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="withdraw_amount" id="quantity_amount">
                                                <div class="input-group">
                                                    <!--<span class="input-group-addon">Rp</span>-->
                                                    <span class="circle"></span>Maksimal Pembelian Per Transaksi</label>
                                                    <select name="quantity" class="text_field">
                                                        <option value="<?=$data_target['rows']['max_pembelian']?>"><?=$data_target['rows']['max_pembelian']?></option>
                                                        <?php
                                                        for($i = 1; $i <= 10; $i++){
                                                        ?>
                                                        <option value="<?=$i?>"><?=$i?></option>
                                                        <?    
                                                        }
                                                        ?>
                                                        </select> 
                                                </div>
                                            </div>
                                        </div>
                                    </div><!-- ends: .row -->
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="m-bottom-15">
                                                <label for="tags">Informasi Untuk Pembeli 
                                                    <span>(Optional)</span>
                                                </label>
                                                <textarea name="buyer_information" class="text_field"><?= $data_target['rows']['buyer_information']; ?></textarea>
                                            </div>
                                        </div>
                                    </div><!-- ends: .row -->
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="m-bottom-15">
                                                <label for="tags">Extra Produk
                                                    <span>(Optional)</span>
                                                </label>
                                                <input type="text" name="extra_produk" class="text_field" placeholder="<?= $data_target['rows']['extra_product']; ?>">
                                                
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="m-bottom-15">
                                                <label for="tags">Harga
                                                </label>
                                                <input type="number" name="price_extra_produk" class="text_field" placeholder="<?= $data_target['rows']['price_extra_product']; ?>">
                                            </div>
                                        </div>
                                    </div><!-- ends: .row -->
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="m-bottom-15">
                                                <label for="tags">Extra Produk
                                                    <span>(Optional)</span>
                                                </label>
                                                <input type="text" name="extra_produk1" class="text_field" placeholder="<?= $data_target['rows']['extra_product1']; ?>">
                                                
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="m-bottom-15">
                                                <label for="tags">Harga
                                                </label>
                                                <input type="number" name="price_extra_produk1" class="text_field" placeholder="<?= $data_target['rows']['price_extra_product1']; ?>">
                                            </div>
                                        </div>
                                    </div><!-- ends: .row -->
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="m-bottom-15">
                                                <label for="tags">Extra Produk
                                                    <span>(Optional)</span>
                                                </label>
                                                <input type="text" name="extra_produk2" class="text_field" placeholder="<?= $data_target['rows']['extra_product2']; ?>">
                                                
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="m-bottom-15">
                                                <label for="tags">Harga
                                                </label>
                                                <input type="number" name="price_extra_produk2" class="text_field" placeholder="<?= $data_target['rows']['price_extra_product2']; ?>">
                                            </div>
                                        </div>
                                    </div><!-- ends: .row -->
                                </div><!-- ends: .upload_modules -->
                            </div><!-- ends: .upload_modules -->
                            <div class="btns m-top-40">
                                <button type="submit" class="btn btn-lg btn-primary m-right-15">Submit</button>
                            </div>
                        </form>
                    </div><!-- ends: .col-md-8 -->
                </div><!-- ends: .row -->
            </div><!-- ends: .container -->
        </div><!-- ends: .dashboard_menu_area -->
    </section><!-- ends: .dashboard-area -->
    
<script>
    const realFileBtn = document.getElementById("real-file");
    const customBtn = document.getElementById("custom-button3");
    const customTxt = document.getElementById("custom-text");
    
    customBtn.addEventListener("click", function() {
      realFileBtn.click();
    });
    
    realFileBtn.addEventListener("change", function() {
      if (realFileBtn.value) {
        customTxt.innerHTML = realFileBtn.value.match(
          /[\/\\]([\w\d\s\.\-\(\)]+)$/
        )[1];
      } else {
        customTxt.innerHTML = "Tidak ada file terpilih.. ";
      }
    });

    
</script>

<script>
    const realFileBtn1 = document.getElementById("real-file1");
    const customBtn1 = document.getElementById("custom-button1");
    const customTxt1 = document.getElementById("custom-text1");
    
    customBtn1.addEventListener("click", function() {
      realFileBtn1.click();
    });
    
    realFileBtn1.addEventListener("change", function() {
      if (realFileBtn1.value) {
        customTxt1.innerHTML = realFileBtn1.value.match(
          /[\/\\]([\w\d\s\.\-\(\)]+)$/
        )[1];
      } else {
        customTxt1.innerHTML = "Tidak ada file terpilih.. ";
      }
    });

    
</script>

<script>
    const realFileBtn2 = document.getElementById("real-file2");
    const customBtn2 = document.getElementById("custom-button2");
    const customTxt2 = document.getElementById("custom-text2");
    
    customBtn2.addEventListener("click", function() {
      realFileBtn2.click();
    });
    
    realFileBtn2.addEventListener("change", function() {
      if (realFileBtn2.value) {
        customTxt2.innerHTML = realFileBtn2.value.match(
          /[\/\\]([\w\d\s\.\-\(\)]+)$/
        )[1];
      } else {
        customTxt2.innerHTML = "Tidak ada file terpilih.. ";
      }
    });

    
</script>
    
<script src="<?= $config['web']['base_url']; ?>vendor_assets/js/tinymce/tinymce.min.js"></script>    
<script>
    tinymce.init({
        selector: '#content',
        plugins: 'preview',
        toolbar: 'formatselect | bold italic strikethrough | removeformat code',
        encoding: 'xml',
        entity_encoding : 'raw',
        entities : '160,nbsp,162,cent,8364,euro,163,pound',
    });

    $('[name="type"]').on('change', (event) => {

        let selectedOption = $(event.currentTarget).find(':selected').attr('value');

        switch(selectedOption) {

            case 'internal':

                $('#url_label').html("Slug");
                $('#url_prepend').show();
                $('input[name="url"]').attr('placeholder', "ex: page-url");
                $('#description_container').show();

                break;

            case 'external':

                $('#url_label').html("External Url");
                $('#url_prepend').hide();
                $('input[name="url"]').attr('placeholder', "ex: https:\/\/domain.com\/");
                $('#description_container').hide();

                break;
        }

    });
</script>
<script>
    tinymce.init({
        selector: '#content_faq',
        plugins: 'preview',
        toolbar: 'formatselect | bold italic strikethrough | removeformat code',
        encoding: 'xml',
        entity_encoding : 'raw',
        entities : '160,nbsp,162,cent,8364,euro,163,pound',
    });

    $('[name="type"]').on('change', (event) => {

        let selectedOption = $(event.currentTarget).find(':selected').attr('value');

        switch(selectedOption) {

            case 'internal':

                $('#url_label').html("Slug");
                $('#url_prepend').show();
                $('input[name="url"]').attr('placeholder', "ex: page-url");
                $('#description_container_faq').show();

                break;

            case 'external':

                $('#url_label').html("External Url");
                $('#url_prepend').hide();
                $('input[name="url"]').attr('placeholder', "ex: https:\/\/domain.com\/");
                $('#description_container_faq').hide();

                break;
        }

    });
</script>
<?php
require '../template/footer.php';

?>