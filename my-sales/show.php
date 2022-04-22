<?php
require '../web.php';
require '../lib/is_login.php';
require '../lib/result.php';
require '../lib/csrf_token.php';

if (!isset($_SESSION['login'])) {
	exit(header("Location: ".$config['web']['base_url']."signin/"));
}
if ($model->db_query($db, "*", "user", "id = '".$_SESSION['login']."'")['count'] == 0) {
	exit(header("Location: ".$config['web']['base_url']."signin/"));
}
if (!isset($_GET['query_id'])) {
	exit("No direct script access allowed!11");
}

$model->db_update($db, "notifikasi", array('read_by_user' => '1'), "go = 'show-sales/".mysqli_real_escape_string($db, $_GET['query_id'])."' AND seller_id ='".$login['id']."' ");

$data_target = $model->db_query($db, "*", "orders", "id = '".mysqli_real_escape_string($db, $_GET['query_id'])."' AND status != 'unpaid' ");

if ($login['id'] == $data_target['rows']['seller_id'] || $login['id'] == $data_target['rows']['buyer_id'] ) {
    


$price_utama = $data_target['rows']['price'];
$price_extra = $data_target['rows']['price_extra_product'];
$price_extra1 = $data_target['rows']['price_extra_product1'];
$price_extra2 = $data_target['rows']['price_extra_product2'];
$jumlah_price = $data_target['rows']['total_price'];
$dataSeller = $model->db_query($db, "*", "user", "id = '".$data_target['rows']['seller_id']."'");
$dataBuyer = $model->db_query($db, "*", "user", "id = '".$data_target['rows']['buyer_id']."'");

$title = "Detail Pesanan";
?>
<?php
require '../template/header.php';
require '../template/header-dashboard.php';
?>

        <div class="dashboard_contents section--padding">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 col-sm-12">
                        <form action="<?= $config['web']['base_url'] ?>send_product/<?=$data_target['rows']['id']?>/<?=$login['id']?>" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="csrf_token" value="<?php echo $config['csrf_token'] ?>">
                            <div class="upload_modules">
                                <div class="modules__title">
                                    <?
                                    $data_service = $model->db_query($db, "*", "services", "id = '".$data_target['rows']['service_id']."'");
                                    ?>
                                    <h4>#<?= $data_target['rows']['id'] ?> Pembelian <a href="<?= $config['web']['base_url']; ?>product/<?= $data_service['rows']['id']?>/<?= $data_service['rows']['url']?>"><?= $data_service['rows']['nama_layanan']?></a> Untuk Rp <?= number_format($data_target['rows']['total_price'],0,',','.') ?> ,-</h4><br>
                                    <?
                                    if($login['id'] == $data_target['rows']['buyer_id']){
                                    ?>
                                    <?
                                    
                                    $user = $model->db_query($db, "*", "user", "username = '".$dataSeller['rows']['username']."'");  
                                    $now = date("Y-m-d H:i:s");
                                    $last_login = $user['rows']['last_login'];
                                    $online = date('Y-m-d H:i:s',strtotime('-5 Min',strtotime($now)));
                                    
                                    if(strtotime($online) < strtotime($last_login) ){
                                        $status_online = 'Online';
                                        $badge = 'success';
                                        
                                    } else {
                                        $status_online = 'Last Online: '.format_date(substr($last_login, 0, -9)).", ".substr($last_login, 11, -3).' UTC+7';
                                        $badge = 'warning';
                                    }
                                    ?>
                                    <span>Seller : <a href="<?= $config['web']['base_url']; ?>user/<?=$dataSeller['rows']['username']?>"><?=$dataSeller['rows']['username']?></a></span> <span class="badge badge-<?=$badge?>" ><?= $status_online ?></span>
                                    
                                    <?
                                    } else {
                                    ?>
                                    <?
                                    
                                    $user = $model->db_query($db, "*", "user", "username = '".$dataBuyer['rows']['username']."'");  
                                    $now = date("Y-m-d H:i:s");
                                    $last_login = $user['rows']['last_login'];
                                    $online = date('Y-m-d H:i:s',strtotime('-5 Min',strtotime($now)));
                                    
                                    if(strtotime($online) < strtotime($last_login) ){
                                        $status_online = 'Online';
                                        $badge = 'success';
                                        
                                    } else {
                                        $status_online = 'Last Online: '.format_date(substr($last_login, 0, -9)).", ".substr($last_login, 11, -3).' UTC+7';
                                        $badge = 'warning';
                                    }
                                    ?>
                                    <span>Buyer : <a href="<?= $config['web']['base_url']; ?>user/<?=$dataBuyer['rows']['username']?>"><?=$dataBuyer['rows']['username']?></a></span> <span class="badge badge-<?=$badge?>" ><?= $status_online ?></span>
                                    <?
                                    }
                                    ?>
                                    
                                </div><!-- ends: .module_title -->
                                <div class="modules__content">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <center><label for="product_name">Jumlah Pesanan : <b><?= $data_target['rows']['quantity'] ?></b></label></center>
                                            </div>
                                        </div><br><br><!-- ends: .col-md-6 -->
                                        <?
                                        if($data_target['rows']['instruction'] != null){
                                        ?>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <center><label for="product_name">Respon Pembeli Dari Informasi : <b><?= $data_service['rows']['buyer_information'] ?></b></label>
                                                <b><h3><?= $data_target['rows']['instruction'] ?></h3></b></center>
                                            </div>
                                        </div><br><br><!-- ends: .col-md-6 -->
                                        <?    
                                        }
                                        ?>
                                        
                                        <div class="col-md-12">
                                            <?
                                            if($data_target['rows']['extra_product'] != null || $data_target['rows']['extra_product1'] != null || $data_target['rows']['extra_product2'] != null){
                                            ?>
                                            <center><label for="product_name">Pembeli Menambahkan</label></center>
                                            <?
                                            }
                                            ?>
                                            <?
                                            if($data_target['rows']['extra_product'] != null){
                                            ?>
                                            <center><b><h4>- <?= $data_target['rows']['extra_product'] ?></h4></b></center>
                                            <?    
                                            }
                                            ?>
                                            <?
                                            if($data_target['rows']['extra_product1'] != null){
                                            ?>
                                            <center><b><h4>- <?= $data_target['rows']['extra_product1'] ?></h4></b></center>
                                            <?    
                                            }
                                            ?>
                                            <?
                                            if($data_target['rows']['extra_product2'] != null){
                                            ?>
                                            <center><b><h4>- <?= $data_target['rows']['extra_product2'] ?></h4></b></center>
                                            <?    
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div><!-- ends: .modules__content -->
                            </div><!-- ends: .upload_modules -->
                            <center><span>Terima notifikasi pesan dan orderan via telegram, Silahkan ke <a href="<?=$config['web']['base_url']?>setting/" target="_blank">pengaturan</a></span></center>
                            <?php
                            $message = mysqli_query($db, "SELECT * FROM conversation_order WHERE orders_id = '".$data_target['rows']['id']."' ORDER BY created_at ASC ");
                            $jumlahPesan = mysqli_num_rows($message);
                            
                            if($jumlahPesan > 0){
                             while ($isiConversation = mysqli_fetch_assoc($message)){
                                $user = $model->db_query($db, "*", "user", "id = '".$isiConversation['user_id']."'");
                            ?>
                            <div class="upload_modules">
                                <div class="modules__title">
                                    <?
                                    if($user['rows']['photo'] == null){
                                    ?>    
                                    <h4 ><img src="<?= $config['web']['base_url']; ?>img/avatar.png" width="50px" height = "50px"> <a href="<?= $config['web']['base_url']; ?>user/<?=$user['rows']['username']?>"><?=$user['rows']['username']?></a> </h4>
                                    <?    
                                    } else {
                                    ?>    
                                    <h4 ><img src="<?= $config['web']['base_url']; ?>user-photo/<?=$user['rows']['photo']?>" width="50px" height = "50px"><a href="<?= $config['web']['base_url']; ?>user/<?=$user['rows']['username']?>"><?=$user['rows']['username']?></a> </h4>
                                    <?    
                                    }
                                    ?>
                                     
                                     <span><?= $isiConversation['created_at']?> UTC+7</span>
                                </div>
                                <div class="modules__content">
                                <div class="row">
                                    <div class="col-md-12">
                                        <? if($isiConversation['message_status'] == 'success'){
                                        ?>
                                        <li class="fa fa-check fa-2x" style="color:#02632f"> Pesanan Telah Selesai!</li><br><br>
                                        <?
                                        }?>
                                        <? if($isiConversation['message_status'] == 'cancel'){
                                        ?>
                                        <li class="fa fa-times fa-2x" style="color:#ff0011">Pesanan Ditolak Oleh Pembeli!</li><br><br>
                                        <?
                                        }?>
                                        <? if($isiConversation['message_status'] == 'refund'){
                                        ?>
                                        <li class="fa fa-times fa-2x" style="color:#ff0011">Pengajuan Pembatalan Bersama!</li><br><span>Otomatis Batal Setelah 24 Jam Jika Tidak Ditanggapi Penjual</span><br><br>
                                        <?
                                        }?>
                                    <label><?php echo html_entity_decode($isiConversation['message'], ENT_NOQUOTES) ?></label><br>
                                    <?
                                    if($isiConversation['file'] != null){
                                    ?>
                                    <a href="<?= $config['web']['base_url']; ?>files-produk/<?= $isiConversation['file']; ?>" target="_blank"><span class="icon-cloud-download"></span> Download File</a>
                                    <?
                                    }
                                    ?>
                                    </div>
                                </div>
                                </div>
                            </div>
                            <?
                            }
                            }
                            ?>
                            
                            <?
                            if($data_target['rows']['status'] == 'success' && $data_target['rows']['reviewed'] == '0') {
                            if($login['id'] == $data_target['rows']['buyer_id'] ){
                            ?>
                            <div class="upload_modules">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="shortcode_module_title">
                                            <div class="dashboard__title">
                                                <h3>Review Pesanan</h3>
                                                <span>Dengan mereview pesanan, dana akan diteruskan kepada seller</span>
                                            </div>
                                            <div class="">
                                                <a href="#" class="btn btn--md btn-primary" data-target="#myModal1" data-toggle="modal">Review</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?
                            }
                            ?>
                            <?    
                            } 
                            ?>
                            
                            
                            <?
                            if($data_target['rows']['status'] != 'complete' && $data_target['rows']['status'] != 'refunded'){
                            ?>
                            <div class="upload_modules">
                                <div class="modules__title">
                                    <h4> Kirim Pesan</h4>
                                </div><!-- ends: .module_title -->
                                <div class="modules__content">
                                <div class="row">
                                        <div class="col-md-12">
                                        <?
                                        if($login['id'] == $data_target['rows']['seller_id'] && $data_target['rows']['status'] != 'refund' ){
                                        ?>
                                        <label class="label">Kirim Pesanan ? <span>(Klik tombol disebalah kanan untuk mengirim)</span></label>
                                         <div class="custom_checkbox checkbox-outline">
                                         <span class="check-confirm" data-text-swap="Ya" data-text-original="Tidak">Tandai Sebagai Terkirim ? </span>
                                         <label class="toggle-switch">
                                            <input type="checkbox" name="send_product" value="send">
                                            <span class="slider round"></span>
                                        </label>
                                        </div>
                                        <!--<br>-->
                                        <!--<label class="label">Tolak/Refund Pesanan ? <span>(Klik tombol disebalah kanan untuk Menolak Pesanan)</span></label>-->
                                        <!--<div class="custom_checkbox checkbox-outline">-->
                                        <!-- <span class="check-confirm" data-text-swap="Ya" data-text-original="Tidak">Tolak Pesanan / Refund ? </span>-->
                                        <!-- <label class="toggle-switch">-->
                                        <!--    <input type="checkbox" name="send_product" value="setujurefund">-->
                                        <!--    <span class="slider round"></span>-->
                                        <!--</label>-->
                                        <!--</div>-->
                                        <?
                                        }
                                        ?>
                                        
                                        <?
                                        if($login['id'] == $data_target['rows']['buyer_id'] && $data_target['rows']['status'] == 'success' ){
                                        ?>
                                        <label class="label">Pesanan Tidak Sesuai ? <span>(Klik tombol disebalah kanan untuk meminta seller revisi)</span></label>
                                         <div class="custom_checkbox checkbox-outline">
                                         <span class="check-confirm" data-text-swap="Ya" data-text-original="Tidak">Tandai Sebagai Revisi ? </span>
                                         <label class="toggle-switch">
                                            <input type="checkbox" name="send_product" value="cancel">
                                            <span class="slider round"></span>
                                        </label>
                                        </div>
                                        <?
                                        }
                                        ?>
                                        <?
                                        if($login['id'] == $data_target['rows']['buyer_id'] && $data_target['rows']['status'] == 'active' ||  $login['id'] == $data_target['rows']['buyer_id'] && $data_target['rows']['status'] == 'cancel'){
                                        ?>
                                        <label class="label">Ada Masalah ? Ajukan Pembatalan Bersama <span>(Klik tombol disebalah kanan untuk pembatalan)</span></label>
                                         <div class="custom_checkbox checkbox-outline">
                                         <span class="check-confirm" data-text-swap="Ya" data-text-original="Tidak">Tandai Sebagai Pembatalan  ? </span>
                                         <label class="toggle-switch">
                                            <input type="checkbox" name="send_product" value="refund">
                                            <span class="slider round"></span>
                                        </label>
                                        </div>
                                        <?
                                        }
                                        ?>
                                        </div>
                                        <?
                                        if($login['id'] == $data_target['rows']['seller_id'] && $data_target['rows']['status'] == 'refund' ){
                                        ?>
                                        <div class="col-md-12">
                                             <label class="label">Setuju Pembatalan ? <span>(Klik tombol disebalah kanan untuk mebatalkan)</span></label>
                                             <div class="custom_checkbox checkbox-outline">
                                             <span class="check-confirm" data-text-swap="Ya" data-text-original="Tidak">Tidak </span>
                                             <label class="toggle-switch">
                                                <input type="checkbox" name="send_product" value="setujurefund">
                                                <span class="slider round"></span>
                                            </label>
                                            </div>
                                            <div class="col-md-12" id="description_container">
                                            <div class="m-bottom-20 no-margin">
                                                <label class="label">Komentar Tentang Pembatalan <span>(Klik Clear Format Jika Kata yang digunakan Copy Paste)</span></label>
                                                <textarea id="content" name="deskripsi" class="form-control form-control-lg" rows="20"></textarea>
                                                <span>(Max 1500 Karakter)</span>
                                            </div>
                                            </div>
                                        </div>
                                        <?
                                        } else {
                                        ?>
                                        
                                        <div class="col-md-12" id="description_container">
                                            <div class="m-bottom-20 no-margin">
                                                <label class="label">Deskripsi Produk <span>(Klik Clear Format Jika Kata yang digunakan Copy Paste)</span></label>
                                                <textarea id="content" name="deskripsi" class="form-control form-control-lg" rows="20"></textarea>
                                                <span>(Max 1500 Karakter)</span>
                                            </div>
                                        </div>
                                        
                                        <?
                                        }
                                        ?>
                                </div>
                                </div>
                            </div>
                            
                            
                            <?
                            if($data_target['rows']['status'] != 'refund'){
                            ?>
                            <div class="upload_modules module--upload">
                                <div class="modules__title">
                                    <h4>Upload Files</h4>
                                </div><!-- ends: .module_title -->
                                <div class="modules__content">
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12">
                                            <div class="form-group">
                                                <div class="upload_wrapper">
                                                    <div class="upload-field">
                                                        <input type="file" name="file" id="real-file" hidden="hidden" />
                                                        <button type="button" id="custom-button"><i class="icon-cloud-upload"></i>Upload File</button>
                                                        <p>
                                                            <span id="custom-text">(File yang di izinkan png, jpg, jpeg, rar, zip, txt, xls, xlt, xlsx, doc, docx, max 2MB)</span>
                                                        </p>
                                                    </div>
                                                </div><!-- ends: .upload_wrapper -->
                                            </div><!-- ends: .form-group -->
                                        </div><!-- ends:.col-md-6 -->
                                    </div><!-- ends: .row -->
                                </div><!-- ends .module_content -->
                            </div><!-- ends: .upload_modules -->
                            <?
                            }
                            ?>
                            <?    
                            } 
                            ?>
                            <?
                            if($data_target['rows']['status'] == 'refunded'){
                            ?>
                            <div class="upload_modules">
                                <div class="modules__title">
                                   <center><h4 style="color:#f2022a"><li class="fa fa-times fa-3x" style="color:#f2022a"></li>Pesanan Telah Dibatalkan, Silahkan Hubungi Pembeli/Penjual Melalui Message</h4></center>
                                </div><!-- ends: .module_title -->
                            </div><!-- ends: .upload_modules --> 
                            <?    
                            }
                            ?>
                            
                            
                            
                            <?
                            if ($data_target['rows']['status'] == 'complete'){
                                $review = $model->db_query($db, "*", "review_order", "order_id = '".$data_target['rows']['id']."'");
                                $rating = $review['rows']['rating'];
                            ?>
                            <div class="upload_modules">
                                <div class="modules__title">
                                    <?
                                    if($review['rows']['based_on'] == 'good_product'){
                                        $reviewBasedOn = 'Kualitas Produk';
                                    } elseif($review['rows']['based_on'] == 'good_seller'){
                                        $reviewBasedOn = 'Attitude Seller';
                                    } elseif($review['rows']['based_on'] == 'fast_delivery'){
                                        $reviewBasedOn = 'Pengiriman';
                                    } elseif($review['rows']['based_on'] == 'good_support'){
                                        $reviewBasedOn = 'Pelayanan';
                                    } else {
                                        $reviewBasedOn = 'Sistem';
                                    }
                                    ?>
                                      
                                        <center><h2>Pesanan Anda Telah Direview</h2>
                                        <h4><?= rating($rating); ?><br>
                                         Rate Berdasarkan : <?= $reviewBasedOn ?> </h4></center>
                                </div><!-- ends: .module_title -->
                                <div class="modules__content">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label><?= $review['rows']['comment'] ?></label>
                                            
                                        </div>
                                    </div>
                                </div>
                            </div><!-- ends: .upload_modules --> 
                            
                            <?
                            if($login['id'] == $data_target['rows']['seller_id'] ){
                            ?>
                            <div class="upload_modules">
                                <div class="modules__title">
                                   <h4><li class="fa fa-check fa-3x" style="color:#02632f"></li>Pesanan Ini Sudah Dinyatakan Tuntas, Silahkan Hubungi Pembeli Melalui Message</h4>
                                </div><!-- ends: .module_title -->
                            </div><!-- ends: .upload_modules --> 
                            <?  
                            } else {
                            ?>
                                <div class="upload_modules">
                                    <div class="modules__title">
                                       <h4><li class="fa fa-check fa-3x" style="color:#02632f"></li>Pesanan Ini Sudah Dinyatakan Tuntas, Silahkan Hubungi Seller Melalui Message</h4>
                                    </div><!-- ends: .module_title -->
                                </div><!-- ends: .upload_modules --> 
                            <?
                            }
                            }
                            ?>
                            <?
                            if($data_target['rows']['status'] != 'complete' && $data_target['rows']['status'] != 'refunded'){
                            ?>
                            <div class="btns m-top-40">
                                <button type="submit" class="btn btn-lg btn-primary m-right-15">Submit</button>
                            </div>
                            <?
                            }
                            ?>
                        </form>
                    </div><!-- ends: .col-md-8 -->
                </div><!-- ends: .row -->
            </div><!-- ends: .container -->
        </div><!-- ends: .dashboard_menu_area -->
        
        <div class="modal fade rating_modal" id="myModal1" tabindex="-1" role="dialog" aria-labelledby="rating_modal">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h3 class="modal-title" id="rating_modal">Review Produk</h3>
                    <h4>Jual <?= $data_service['rows']['nama_layanan']?></h4>
                    <p>by
                        <a href="author.html"><?= $dataSeller['rows']['username'] ?></a>
                    </p>
                </div>
                <!-- end /.modal-header -->
                <div class="modal-body">
                    <form action="<?= $config['web']['base_url']; ?>send_review/<?=$data_target['rows']['id']?>" method="post">
                        <input type="hidden" name="csrf_token" value="<?php echo $config['csrf_token'] ?>">
                        <ul>
                            <li>
                                <p>Rating</p>
                                <div class="right_content btn btn--round btn--white btn--md">
                                    <select name="rating" class="give_rating">
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                    </select>
                                </div>
                            </li>
                            <li>
                                <p>Rating Berdasarkan</p>
                                <div class="right_content">
                                    <div class="select-wrap">
                                        <select name="review_reason" id="rev">
                                            <option value="good_seller">Attitude Seller</option>
                                            <option value="fast_delivery">Pengiriman</option>
                                            <option value="good_support">Pelayanan</option>
                                            <option value="good_product">Kualitas Produk</option>
                                        </select>
                                        <span class="icon-arrow-down"></span>
                                    </div>
                                </div>
                            </li>
                        </ul>
                        <div class="rating_field">
                            <label for="rating_field">Komentar</label>
                            <textarea name="rating_field" id="rating_field" class="text_field" placeholder="Silahkan berkomentar tentang produk dari <?= $dataSeller['rows']['username'] ?>"></textarea>
                            <p class="notice">Review dari anda akan di publish ^.^ </p>
                        </div>
                        <button type="submit" class="btn btn-md btn-primary">Submit Rating</button>
                        <button class="btn modal_close" data-dismiss="modal">Close</button>
                    </form>
                    <!-- end /.form -->
                </div>
                <!-- end /.modal-body -->
            </div>
        </div>
    </div>
<script>
    const realFileBtn = document.getElementById("real-file");
    const customBtn = document.getElementById("custom-button");
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

<script src="<?= $config['web']['base_url']; ?>vendor_assets/js/tinymce/tinymce.min.js"></script>    
<script>
    tinymce.init({
        selector: '#content',
        plugins: 'preview fullpage directionality visualblocks visualchars fullscreen image media hr toc advlist lists imagetools',
        toolbar: 'formatselect | bold italic strikethrough | alignleft aligncenter alignright alignjustify  | numlist bullist outdent | removeformat code',
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

<?

require '../template/footer.php';
} else {
    $_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Data tidak ditemukan.');
    exit(header("Location: ".$config['web']['base_url']."my-sales/"));
}
?>