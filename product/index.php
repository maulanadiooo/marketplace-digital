<?php
require '../web.php';
require '../lib/result.php';
require '../lib/csrf_token.php';

if (!isset($_GET['service']) && !isset($_GET['product'])) {
	exit(header("Location: ".$config['web']['base_url']));
}
$check_user = $model->db_query($db, "*", "user", "id = '".$_SESSION['login']."'"); 
if($check_user['rows']['role'] == '2'){
    $data_targets = $model->db_query($db, "*", "services", "url = '".mysqli_real_escape_string($db, $_GET['product'])."' AND id = '".mysqli_real_escape_string($db, $_GET['service'])."'");
} else {
    $data_targets = $model->db_query($db, "*", "services", "url = '".mysqli_real_escape_string($db, $_GET['product'])."' AND id = '".mysqli_real_escape_string($db, $_GET['service'])."' AND status = 'active'");
}
if($data_targets['count'] == 0){
    include_once '../no-product.php';
    exit();
}
$user_service = $data_targets['rows']['author']; 
$check_author = $model->db_query($db, "*", "user", "id = '$user_service'");
if($check_author['rows']['status'] == 'Banned'){
    $_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Penyedia Layanan Ini Dalam Masa Penangguhan, Silahkan Cari Produk Lain yang Serupa.');
    exit(header("Location: ".$config['web']['base_url']));
}

$model->db_update($db, "notifikasi", array('read_by_user' => '1'), "go = 'product/".mysqli_real_escape_string($db, mysqli_real_escape_string($db, $_GET['service']))."/".mysqli_real_escape_string($db, $_GET['product'])."' AND seller_id ='".$_SESSION['login']."' ");

$banyakRating = mysqli_query($db, "SELECT * FROM review_order WHERE service_id = '".$data_targets['rows']['id']."' ");
$hasilBanyakRating = mysqli_num_rows($banyakRating);

$query = mysqli_query($db, "SELECT SUM(rating) AS total FROM `review_order` WHERE service_id = '".$data_targets['rows']['id']."' ");
$fetch = mysqli_fetch_array($query);
$totalRating = $fetch['total'];

$averageRating = $totalRating / $hasilBanyakRating;


$title = $data_targets['rows']['nama_layanan'];

$user_information = $model->db_query($db, "*", "user", "id = '".$data_targets['rows']['author']."' ");
$data_target_review = mysqli_query($db, "SELECT * FROM review_order WHERE seller_id = '".$user_information['rows']['id']."' AND service_id = '".$data_targets['rows']['id']."' ORDER by created_at DESC");


$share = $config['web']['base_url']."product/".$data_targets['rows']['id']."/".$data_targets['rows']['url'];
$judul = "Jual ".$data_targets['rows']['nama_layanan']." Rp ".number_format($data_targets['rows']['price'],0,',','.');

$update_views = $db->query("UPDATE services set views = views+1 WHERE id = '".$data_targets['rows']['id']."'");

$description = $data_targets['rows']['nama_layanan']." ".substr(strip_tags(html_entity_decode($data_targets['rows']['description'], ENT_NOQUOTES)), 0, 400); 
$keyword = "Jual ".$data_targets['rows']['nama_layanan'];

$images1 = $data_targets['rows']['photo'];
if($data_targets['rows']['photo_1'] != null){
    $images2 = $data_targets['rows']['photo_1'];
} else {
    $images2 = $images1;
}
if($data_targets['rows']['photo_2'] != null){
    $images3 = $data_targets['rows']['photo_2'];
} else {
    $images3 = $images1;
}

require '../template/header.php';
?>

<section class="breadcrumb-area">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="breadcrumb-contents">
                        <h2 class="page-title">Jual <?= $data_targets['rows']['nama_layanan'] ?></h2>
                        <div class="breadcrumb">
                            
                        </div>
                    </div>
                </div><!-- end .col-md-12 -->
            </div><!-- end .row -->
        </div><!-- end .container -->
    </section><!-- ends: .breadcrumb-area -->
    <section class="single-product-desc">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-md-12">
                    <div class="item-preview">
                        <div class="item-prev-area">
                            <div class="preview-img-wrapper">
                                <?
                                if($data_targets['rows']['photo_2'] == null && $data_targets['rows']['photo_1'] == null){
                                ?>
                                <div class="item__preview-img">
                                    <div class="item__preview-slider">
                                        
                                        <div class="prev-slide">
                                            <img src="<?= $config['web']['base_url']; ?>file-photo/<?=$data_targets['rows']['id']?>/<?=$images1?>" alt="Preview Image" width="750px" height="430">
                                        </div>
                                    </div><!-- ends: .item--preview-slider -->
                                </div>
                                <?
                                } else {
                                ?>    
                                <div class="item__preview-img">
                                    <div class="item__preview-slider">
                                        
                                        <div class="prev-slide">
                                            <img src="<?= $config['web']['base_url']; ?>file-photo/<?=$data_targets['rows']['id']?>/<?=$images1?>" alt="Preview Image" width="750px" height="430">
                                        </div>
                                         <div class="prev-slide">
                                            <img src="<?= $config['web']['base_url']; ?>file-photo/<?=$data_targets['rows']['id']?>/<?=$images2?>" alt="Preview Image" width="750px" height="430">
                                        </div>
                                        
                                        <div class="prev-slide">
                                            <img src="<?= $config['web']['base_url']; ?>file-photo/<?=$data_targets['rows']['id']?>/<?=$images3?>" alt="Preview Image" width="750px" height="430">
                                        </div>
                                        <div class="prev-slide">
                                            <img src="<?= $config['web']['base_url']; ?>file-photo/<?=$data_targets['rows']['id']?>/<?= $images1?>" alt="Preview Image" width="750px" height="430">
                                        </div>
                                        <div class="prev-slide">
                                            <img src="<?= $config['web']['base_url']; ?>file-photo/<?=$data_targets['rows']['id']?>/<?=$images2?>" alt="Preview Image" width="750px" height="430">
                                        </div>
                                        <div class="prev-slide">
                                            <img src="<?= $config['web']['base_url']; ?>file-photo/<?=$data_targets['rows']['id']?>/<?=$images3?>" alt="Preview Image" width="750px" height="430">
                                        </div>
                                    </div><!-- ends: .item--preview-slider -->
                                    <div class="prev-nav thumb-nav">
                                        <span class="lnr nav-left icon-arrow-left"></span>
                                        <span class="lnr nav-right icon-arrow-right"></span>
                                    </div><!-- ends: .prev-nav -->
                                </div>
                                <div class="item__preview-thumb">
                                    <div class="prev-thumb">
                                        <div class="thumb-slider">
                                            <div class="item-thumb">
                                                <img src="<?= $config['web']['base_url']; ?>file-photo/<?=$data_targets['rows']['id']?>/<?=$images1?>" alt="Thumbnail Image">
                                            </div>
                                            <div class="item-thumb">
                                                <img src="<?= $config['web']['base_url']; ?>file-photo/<?=$data_targets['rows']['id']?>/<?=$images2?>" alt="Thumbnail Image">
                                            </div>
                                            <div class="item-thumb">
                                                <img src="<?= $config['web']['base_url']; ?>file-photo/<?=$data_targets['rows']['id']?>/<?=$images3?>" alt="Thumbnail Image">
                                            </div>
                                            <div class="item-thumb">
                                                <img src="<?= $config['web']['base_url']; ?>file-photo/<?=$data_targets['rows']['id']?>/<?=$images1?>" alt="Thumbnail Image">
                                            </div>
                                            <div class="item-thumb">
                                                <img src="<?= $config['web']['base_url']; ?>file-photo/<?=$data_targets['rows']['id']?>/<?=$images2?>" alt="Thumbnail Image">
                                            </div>
                                            <div class="item-thumb">
                                                <img src="<?= $config['web']['base_url']; ?>file-photo/<?=$data_targets['rows']['id']?>/<?=$images3?>" alt="Thumbnail Image">
                                            </div>
                                            <div class="item-thumb">
                                                <img src="<?= $config['web']['base_url']; ?>file-photo/<?=$data_targets['rows']['id']?>/<?=$images1?>" alt="Thumbnail Image">
                                            </div>
                                            <div class="item-thumb">
                                                <img src="<?= $config['web']['base_url']; ?>file-photo/<?=$data_targets['rows']['id']?>/<?=$images2?>" alt="Thumbnail Image">
                                            </div>
                                        </div><!-- end .thumb-slider -->
                                    </div>
                                </div><!-- ends: .item__preview-thumb -->
                                <?    
                                }
                                ?>
                                
                            </div><!-- ends: .preview-img-wrapper -->
                        </div><!-- Ends: .item-prev-area -->
                        <div class="item-preview--excerpt">
                            <?
                            $check_like = mysqli_query($db, "SELECT * FROM favorite WHERE user_id = '".$_SESSION['login']."' AND status = 'like' AND service_id = '".$data_targets['rows']['id']."'");
                            if(mysqli_num_rows($check_like) == 0){
                            ?>  
                            <div class="item-preview--action">
                                <div class="action-btns">
                                    <a href="<?= $config['web']['base_url']; ?>like-product/<?=$data_targets['rows']['id']?>/like" class="btn btn--lg btn--icon btn-outline-primary">
                                        <i class="fa fa-heart-o"></i>Tambahkan Ke Favorit</a>
                                </div>
                            </div><!-- ends: .item-preview--action -->  
                            <?    
                            } else {
                            ?>
                            <div class="item-preview--action">
                                <div class="action-btns">
                                    <a href="<?= $config['web']['base_url']; ?>like-product/<?=$data_targets['rows']['id']?>/unlike" class="btn btn--lg btn--icon btn-outline-primary">
                                        <i class="fa fa-heart" style="color:#fc0505"></i>Batalkan Favorite</a>
                                </div>
                            </div><!-- ends: .item-preview--action -->  
                            <?
                            }
                            ?>
                            <div class="item-preview--activity">
                                <div class="activity-single">
                                    <p>
                                        <span class="icon-basket"></span> Penjualan
                                    </p>
                                    <p><?= number_format($data_targets['rows']['total_sales'],0,',','.') ?></p>
                                </div>
                                <div class="activity-single">
                                    <p>
                                        <span class="icon-star"></span> Reviews
                                    </p>
                                    <ul class="list-unstyled">
                                        <?= rating($averageRating) ?>
                                    </ul>
                                </div>
                                <div class="activity-single">
                                    <p>
                                        <span class="icon-heart"></span>Difavoritkan
                                    </p>
                                    <p><?= number_format($data_targets['rows']['like_fav'],0,',','.') ?></p>
                                </div>
                            </div><!-- Ends: .item-preview--activity -->
                        </div>
                    </div><!-- ends: .item-preview-->
                    <div class="item-info">
                        <div class="item-navigation">
                            <ul class="nav nav-tabs" role="tablist">
                                <li>
                                    <a href="#product-details" class="active" id="tab1" aria-controls="product-details" role="tab" data-toggle="tab" aria-selected="true">
                                        <span class="icon icon-docs"></span> Deskripsi</a>
                                </li>
                                <li>
                                    <a href="#product-faq" id="tab5" aria-controls="product-faq" role="tab" data-toggle="tab">
                                        <span class="icon icon-question"></span>FAQ</a>
                                </li>
                                <li>
                                    <?$hasilBanyakRating = mysqli_num_rows($data_target_review);?>
                                    <a href="#product-review" id="tab3" aria-controls="product-review" role="tab" data-toggle="tab">
                                        <span class="icon icon-star"></span> Reviews
                                        <span>(<?=$hasilBanyakRating?>)</span>
                                    </a>
                                </li>
                                <?
                                if($data_targets['rows']['author'] != $_SESSION['login']){
                                    ?>
                                    <li>
                                    <a href="#product-support" id="tab4" aria-controls="product-support" role="tab" data-toggle="tab">
                                        <span class="icon icon-envelope-open"></span> Kirim Pesan</a>
                                </li>
                                    <?
                                }
                                ?>
                                
                            </ul>
                        </div><!-- ends: .item-navigation -->
                        <div class="tab-content">
                            <div class="fade show tab-pane product-tab active" id="product-details" role="tabpanel" aria-labelledby="tab1">
                                <div class="tab-content-wrapper">
                                     <?php echo html_entity_decode($data_targets['rows']['description'], ENT_NOQUOTES) ?>
                                </div>
                            </div><!-- ends: .tab-content -->
                            
                            <div class="fade tab-pane product-tab" id="product-review" role="tabpanel" aria-labelledby="tab3">
                                <div class="thread thread_review">
                                    <ul class="media-list thread-list">
                                        <?
                                        while ($data_target_reviews = mysqli_fetch_assoc($data_target_review)){
                                            $komentarBuyer = $model->db_query($db, "*", "user", "id = '".$data_target_reviews['user_id']."' "); // user_id itu buyer_id
                                             $username_buyer = $komentarBuyer['rows']['username'];
                                             $usernameBuyer_ilang_3 = substr($username_buyer, 0, -3); 
                                             $usernameBuyer_bintang_3 = $usernameBuyer_ilang_3."***";
                                             
                                             
                                            if($data_target_reviews['based_on'] == 'good_product'){
                                                $reviewBasedOn = 'Kualitas Produk';
                                            } elseif($data_target_reviews['based_on'] == 'good_seller'){
                                                $reviewBasedOn = 'Attitude Seller';
                                            } elseif($data_target_reviews['based_on'] == 'fast_delivery'){
                                                $reviewBasedOn = 'Pengiriman';
                                            } elseif($data_target_reviews['based_on'] == 'good_support'){
                                                $reviewBasedOn = 'Pelayanan';
                                            } else {
                                                $reviewBasedOn = 'Sistem';
                                            }
                                               
                                        ?>
                                        <li class="single-thread">
                                            <div class="media">
                                                <div class="media-left">
                                                    <a disabled>
                                                        <?
                                                        if($komentarBuyer['rows']['photo'] == null){
                                                        ?>
                                                        <img class="media-object" src="<?= $config['web']['base_url']; ?>img/avatar.png" alt="<?= $komentarBuyer['rows']['username']?>" height="70px" width="70px">
                                                        <?    
                                                        } else {
                                                        ?>
                                                        <img class="media-object" src="<?= $config['web']['base_url']; ?>user-photo/<?= $komentarBuyer['rows']['photo']?>" alt="<?= $komentarBuyer['rows']['username']?>" height="70px" width="70px">
                                                        <?
                                                        }
                                                        ?>
                                                        
                                                    </a>
                                                </div><!-- ends: .media-left -->
                                                <div class="media-body">
                                                    <div class="clearfix">
                                                        <div class="pull-left">
                                                            <div class="media-heading">
                                                                <a disabled>
                                                                    <h4><?= $usernameBuyer_bintang_3 ?></h4>
                                                                </a>
                                                                <span><?= format_date(substr($data_target_reviews['created_at'], 0, -9)); ?></span>
                                                            </div>
                                                            <div class="rating product--rating">
                                                                <ul>
                                                                    <?= rating($data_target_reviews['rating'])?>
                                                                </ul>
                                                            </div>
                                                            <span class="review_tag"><?= $reviewBasedOn ?></span>
                                                        </div>
                                                    </div>
                                                    <p><?= html_entity_decode($data_target_reviews['comment'], ENT_NOQUOTES)  ?></p>
                                                </div><!-- ends: .media-body -->
                                            </div><!-- ends: .media -->
                                        </li><!-- end single comment thread -->
                                        <?
                                        }
                                        ?>
                                    </ul><!-- ends: .media-list -->
                                    <!-- Start Pagination -->
                                </div><!-- ends: .comments -->
                            </div><!-- ends: .product-comment -->
                            <div class="fade tab-pane product-tab" id="product-support" role="tabpanel" aria-labelledby="tab4">
                                <div class="support">
                                    <div class="support__title">
                                        <h3>Kirim Pesan Ke Penjual</h3>
                                    </div>
                                    <div class="support__form">
                                        <div class="usr-msg">
                                            
                                           
                                                <?
                                                if(!isset($_SESSION['login'])){
                                                    $nows = date("Y-m-d H:i:s");
                                                    $expired_re = date('Y-m-d H:i:s',strtotime('+20 Min',strtotime($nows)));
                                                    $token = str_rand(50);
                                                    $input_post = array(
                                        			    'token' => $token,
                                        				'go' => "product/".$data_targets['rows']['id']."/".$data_targets['rows']['url'],
                                        				'expired_at' => $expired_re,
                                        			);
                                        			$redirect = $model->db_insert($db, "redirect", $input_post);
                                                ?>
                                                <p>Silahkan <a href="<?= $config['web']['base_url']; ?>signin/?redirect=<?=$input_post['token']?>">Masuk</a> Untuk menghubungi Seller</p>
                                                <?
                                                } elseif($data_targets['rows']['author'] == $_SESSION['login']){
                                                ?>
                                                    <p>Anda Adalah Penjual</p>
                                                <?    
                                                }else {
                                                ?>
                                                 <form action="<?= $config['web']['base_url']; ?>product/send_message.php" class="support_form" method="post">
                                                  <input type="hidden" name="csrf_token" value="<?php echo $config['csrf_token'] ?>">
                                                 <div class="form-group">
                                                    <label for="supmsg">Masukkan Pesan : </label>
                                                    <input type="hidden" name="puid" value="<?=$data_targets['rows']['id']?>">
                                                    <input type="hidden" name="uid" value="<?=$data_targets['rows']['author']?>">
                                                    <textarea class="text_field" id="supmsg" name="supmsg" placeholder="Tinggalkan Pesan.. "></textarea>
                                                </div>
                                                
                                                
                                                <button type="submit" class="btn btn--lg btn-primary">Submit</button>
                                                 </form>
                                                <?
                                                }
                                                ?>
                                                
                                                
                                           
                                        </div>
                                    </div>
                                </div>
                            </div><!-- ends: .product-support -->
                            <div class="fade tab-pane product-tab" id="product-faq" role="tabpanel" aria-labelledby="tab5">
                                <div class="tab-content-wrapper">
                                    <div class="faq-accordion">
                                        <div class="panel-group accordion" role="tablist" id="accordion">
                                            <?php echo html_entity_decode($data_targets['rows']['faq'], ENT_NOQUOTES) ?>
                                        </div><!-- end .accordion -->
                                    </div><!-- ends: .faq-accordion -->
                                </div>
                            </div><!-- ends: .product-faq -->
                        </div><!-- ends: .tab-content -->
                    </div><!-- ends: .item-info -->
                </div><!-- ends: .col-md-8 -->
                <div class="col-lg-4 col-md-12">
                    <aside class="sidebar sidebar--single-product">
                        <form action ="<?= $config['web']['base_url']; ?>buy/<?=$data_targets['rows']['id']?>" method="post">
                            <input type="hidden" name="csrf_token" value="<?php echo $config['csrf_token'] ?>">
                        <div class="sidebar-card card-pricing">
                            <div class="price">
                                <h1>
                                    <sup>Rp </sup><?= number_format($data_targets['rows']['price'],0,',','.') ?>
                                </h1>
                            </div>
                            <div>
                            <center><p>Jumlah : <select name="quantity" class="text_field" required>
                            <?
                            $data_service = $model->db_query($db, "*", "services", "id = '".mysqli_real_escape_string($db, $_GET['service'])."'");
                            if($data_service['rows']['allow_multisale'] == 'yes'){
                                        $end_number = $data_service['rows']['max_pembelian'];
                                    } else {
                                        $end_number = 1;
                                    }
                            ?>
                            <?php
                            for($i = 1; $i <= $end_number; $i++){
                            ?>
                            <option value="<?=$i?>"><?=$i?></option>
                            <?    
                            }
                            ?>
                            </select> </p></center>
                            </div>
                            <ul class="pricing-options">
                                <?php
                                if($data_targets['rows']['extra_product'] != null){
                                ?>    
                                
                                <li>
                                    <div class="custom-radio">
                                        <input type="checkbox" id="opt1" class="" name="product1" value="<?= $data_targets['rows']['extra_product']; ?>">
                                        <label for="opt1">
                                            <span class="circle"></span><?= $data_targets['rows']['extra_product']; ?>
                                            <span class="pricing__opt">Rp <?= number_format($data_targets['rows']['price_extra_product'],0,',','.') ?></span>
                                        </label>
                                    </div>
                                </li> 
                                 
                                 
                                <?    
                                }
                                ?>
                                <?php
                                if($data_targets['rows']['extra_product1'] != null){
                                ?>    
                                <li>
                                    <div class="custom-radio">
                                        <input type="checkbox" id="opt2" class="" name="product2" value="<?= $data_targets['rows']['extra_product1']; ?>">
                                        <label for="opt2">
                                            <span class="circle"></span><?= $data_targets['rows']['extra_product1']; ?>
                                            <span class="pricing__opt">Rp <?= number_format($data_targets['rows']['price_extra_product1'],0,',','.') ?></span>
                                        </label>
                                    </div>
                                </li>
                                <?    
                                }
                                ?>
                                <?php
                                if($data_targets['rows']['extra_product2'] != null){
                                ?>
                                <li>
                                    <div class="custom-radio">
                                        <input type="checkbox" id="opt3" class="" name="product3" value="<?= $data_targets['rows']['extra_product2']; ?>">
                                        <label for="opt3">
                                            <span class="circle"></span><?= $data_targets['rows']['extra_product2']; ?>
                                            <span class="pricing__opt">Rp <?= number_format($data_targets['rows']['price_extra_product2'],0,',','.') ?></span>
                                        </label>
                                    </div>
                                </li>
                                <?    
                                }
                                ?>
                                
                            </ul><!-- end .pricing-options -->
                            <input type="hidden" name="SID" value="<?=encrypt($data_targets['rows']['id'])?>">
                            <?
                            if($_SESSION['login'] == $data_targets['rows']['author']){
                            ?>
                            <div class="purchase-button">
                                <a href="<?= $config['web']['base_url']; ?>edit/<?=$data_targets['rows']['id']?>" class="btn btn--lg btn-success">Edit</a>
                                
                            </div><!-- end .purchase-button -->    
                            <?    
                            }else {
                            ?>
                            <div class="purchase-button">
                                <button class="btn btn--lg btn-primary">Beli Sekarang</button>
                                
                            </div><!-- end .purchase-button -->
                            <?
                            }
                            ?>
                            
                        </div><!-- end .sidebar--card -->
                        </form>
                        <div class="sidebar-card card--product-infos">
                            <div class="card-title">
                                <h4>Informasi Produk</h4>
                            </div>
                            <ul class="infos">
                                <li>
                                    <p class="data-label">Dibuat</p>
                                    
                                    <p class="info"><?= format_date(substr($data_targets['rows']['created_at'], 0, -9)).", ".substr($data_targets['rows']['created_at'], -8); ?> WIB</p>
                                </li>
                                <?php
                                if($data_targets['rows']['updated_at'] == null){
                                ?>
                                <li>
                                    <p class="data-label">Diupdate</p>
                                    <p class="info"><?= format_date(substr($data_targets['rows']['created_at'], 0, -9)).", ".substr($data_targets['rows']['created_at'], -8); ?> WIB</p>
                                </li>
                                <?php
                                } else {
                                ?>
                                <li>
                                    <p class="data-label">Diupdate</p>
                                    <p class="info"><?= format_date(substr($data_targets['rows']['updated_at'], 0, -9)).", ".substr($data_targets['rows']['updated_at'], -8); ?> WIB</p>
                                </li>
                                <?php
                                }
                                ?>
                                
                                <?php
                                $data_category = $model->db_query($db, "*", "categories", "id = '".$data_targets['rows']['categories_id']."' ");
                                ?>
                                <li>
                                    <p class="data-label">Kategori</p>
                                    <p class="info"><?= $data_category['rows']['category']; ?></p>
                                </li>
                                <li>
                                    <p class="data-label">Estimasi Pengerjaan</p>
                                    <p class="info"><?= $data_targets['rows']['jangka_waktu']; ?> Hari</p>
                                </li>
                                <li>
                                    <p class="data-label">Tags</p>
                                    <p class="info">
                                        <?php
                                        $format = $data_targets['rows']['tags'];
                                        $pisah = explode(" ", $format);
                                        foreach ($pisah as $pisahs ){
                                        ?>
                                        <span class="badge badge-primary"><a href="<?=$config['web']['base_url']?>tag/<?= $pisahs ?>"><font color="white"><?= $pisahs ?></font></a></span>
                                        
                                        
                                        <?php
                                        }
                                        ?>
                                    </p>
                                </li>
                                <li>
                                    <p class="data-label">Dilihat</p>
                                    <p class="info">
                                        <?=$data_targets['rows']['views']?>x
                                    </p>
                                </li>
                            </ul><!-- ends: .infos -->
                        </div><!-- ends: .card--product-infos -->
                       <div class="sidebar-card social-share-card">
                           
                            <p>Bagikan :</p>
                            <ul class="list-unstyled">
                                <li>
                                    
                                    <a href="https://www.facebook.com/sharer/sharer.php?quote=<?=$judul?>&u=<?=$share?>" target="_blank">
                                        <i class="fa fa-facebook"></i>
                                    </a>
                                </li>
                                <li>
                                    <a  href="https://twitter.com/share?text=<?=$judul?>&amp;url=<?=$share?>" target="_blank">
                                        <i class="fa fa-twitter"></i>
                                    </a>
                                </li>
                                <li>
                                <a href="whatsapp://send?text=<?=$judul?> <?=$share?>" target="_blank">
                                    <i class="fa fa-whatsapp"></i>
                                </a>
                                </li>
                            </ul>
                        </div><!-- Ends: .social-share-card -->
                        <div class="author-card sidebar-card">
                            <div class="card-title">
                                <h4>Informasi Penjual</h4>
                            </div>
                            <div class="author-infos">
                                <div class="author-top">
                                    <?php
                                    $user_information = $model->db_query($db, "*", "user", "id = '".$data_targets['rows']['author']."' ");
                                    if($user_information['rows']['photo'] == null ){
                                    ?>
                                    <div class="author_avatar">
                                        <img src="<?= $config['web']['base_url']; ?>img/avatar.png" alt="<?=$user_information['rows']['username']?>" width="100px" height="100px">
                                    </div>
                                    <?
                                    } else {
                                    ?>    
                                    <div class="author_avatar">
                                        <img src="<?= $config['web']['base_url']; ?>user-photo/<?=$user_information['rows']['photo']?>" alt="<?=$user_information['rows']['username']?>" width="100px" height="100px">
                                    </div>
                                    <?    
                                    }
                                    ?>
                                    
                                    <div class="author">
                                        <h5><?= $user_information['rows']['username']; ?></h5>
                                        <?
                                        $username = $user_information['rows']['username'];
                                        $user = $model->db_query($db, "*", "user", "username = '$username'");  
                                        $now = date("Y-m-d H:i:s");
                                        $last_login = $user['rows']['last_login'];
                                        $online = date('Y-m-d H:i:s',strtotime('-5 Min',strtotime($now)));
                                        
                                        if(strtotime($online) < strtotime($last_login) ){
                                            $status_online = 'Online';
                                            $badge = 'success';
                                            
                                        } else {
                                            $status_online = 'Last Online: <br>'.format_date(substr($last_login, 0, -9)).", ".substr($last_login, 11, -3).' UTC+7';
                                            $badge = 'warning';
                                        }
                                        ?>
                                        
                                        <!--<p>Bergabung: <?= format_date(substr($user_information['rows']['created_at'], 0, -9)); ?></p>-->
                                        
                                        <?
                                        $query_penjualan = mysqli_query($db, "SELECT SUM(price_for_seller) AS total FROM `orders` WHERE seller_id = '".$data_targets['rows']['author']."' AND status ='complete' AND kliring = '1'");
                                        $fetch_penjualan = mysqli_fetch_array($query_penjualan);
                                        if($fetch_penjualan['total'] < 1){
                                            $totalPenjualan = '0';
                                        } else {
                                           $totalPenjualan = $fetch_penjualan['total'];
                                        }
                                        ?>
                                        <?
                                        if($totalPenjualan > 0 && $totalPenjualan < 500000){
                                        ?>
                                        <img src="<?= $config['web']['base_url'] ?>img/recommended.png" alt="user recommended" class="svg" width="20px" height="20px" title="Recommended Seller">
                                        <?
                                        } elseif($totalPenjualan >= 500000 && $totalPenjualan < 1000000){
                                        ?>
                                        <img src="<?= $config['web']['base_url'] ?>img/author_rank_bronze.png" alt="user rank" class="svg" width="20px" height="20px" title="Bronze Seller">
                                        <img src="<?= $config['web']['base_url'] ?>img/recommended.png" alt="user recommended" class="svg" width="20px" height="20px" title="Recommended Seller">
                                        <?    
                                        } elseif($totalPenjualan >= 1000000 && $totalPenjualan < 5000000){
                                        ?>
                                        <img src="<?= $config['web']['base_url'] ?>img/author_rank_golden.png" alt="user rank" class="svg" width="20px" height="20px" title="Golden Seller">
                                        <img src="<?= $config['web']['base_url'] ?>img/recommended.png" alt="user recommended" class="svg" width="20px" height="20px" title="Recommended Seller">
                                        <?      
                                        } elseif($totalPenjualan >= 5000000){
                                         ?>
                                        <img src="<?= $config['web']['base_url'] ?>img/author_rank_diamond.png" alt="user rank" class="svg" width="20px" height="20px" title="Diamond Seller">
                                        <img src="<?= $config['web']['base_url'] ?>img/recommended.png" alt="user recommended" class="svg" width="20px" height="20px" title="Recommended Seller">
                                        <?    
                                        }
                                        ?>
                                        <br><span class="badge badge-<?=$badge?>" ><?= $status_online ?></span><br>
                                    </div>
                                </div><!-- ends: .author-top -->
                                <br>
                                <div class="author-btn">
                                    <a href="<?= $config['web']['base_url']; ?>user/<?= $user_information['rows']['username']; ?>" class="btn btn--sm btn-primary">View Profile</a>
                                    <?
                                    if($_SESSION['login'] != $data_targets['rows']['author']){
                                    ?>
                                    <a href="<?= $config['web']['base_url']; ?>conversation/<?= $user_information['rows']['username']; ?>" class="btn btn--sm btn-secondary">Kirim Pesan</a>
                                    <?
                                    }
                                    ?>
                                </div><!-- ends: .author-btn -->
                            </div><!-- ends: .author-infos -->
                        </div><!-- ends: .author-card -->
                    </aside><!-- ends: .sidebar -->
                </div><!-- ends: .col-md-4 -->
            </div><!-- ends: .row -->
        </div><!-- ends: .container -->
    </section><!-- ends: .single-product-desc -->
    <section class="more_product_area p-top-105 p-bottom-75">
        <div class="container">
            <div class="row">
                <!-- start col-md-12 -->
                <div class="col-md-12">
                    <div class="section-title">
                        <h2>More Items by <span><?= $user_information['rows']['username']; ?></span>
                        </h2>
                    </div>
                </div><!-- ends: .col-md-12 -->
                <?php
                $data_target_p = mysqli_query($db, "SELECT * FROM services WHERE author = '".$data_targets['rows']['author']."' AND id != '".$data_targets['rows']['id']."' AND status = 'active' ORDER by created_at DESC LIMIT 6");
                
                while ($data_target_a = mysqli_fetch_assoc($data_target_p)){
                $user_p = $model->db_query($db, "*", "user", "id = '".$data_target_a['author']."'");
                $data_targets_p = $model->db_query($db, "*", "categories", "id = '".$data_target_a['categories_id']."'");
                
                $banyakRating = mysqli_query($db, "SELECT * FROM review_order WHERE seller_id = '".$user_p['rows']['id']."' ");
               $hasilBanyakRating = mysqli_num_rows($banyakRating);
               
               $query = mysqli_query($db, "SELECT SUM(rating) AS total FROM `review_order` WHERE seller_id = '".$user_p['rows']['id']."' ");
               $fetch = mysqli_fetch_array($query);
               $totalRating = $fetch['total'];
               
               $averageRating = $totalRating / $hasilBanyakRating;
               
               $check_like = mysqli_query($db, "SELECT * FROM favorite WHERE user_id = '".$_SESSION['login']."' AND status = 'like' AND service_id = '".$data_target_a['id']."'");
               
               $query_penjualan = mysqli_query($db, "SELECT SUM(price_for_seller) AS total FROM `orders` WHERE seller_id = '".$data_target_a['author']."' AND status ='complete' AND kliring = '1'");
                $fetch_penjualan = mysqli_fetch_array($query_penjualan);
                if($fetch_penjualan['total'] < 1){
                    $totalPenjualan = '0';
                } else {
                   $totalPenjualan = $fetch_penjualan['total'];
                }
                
                ?>
                <div class="col-lg-4 col-md-6">
                    <div class="product-single latest-single">
                        <div class="product-thumb">
                            <div class="s-promotion">Rp <?= number_format($data_target_a['price'],0,',','.') ?></div>
                            <figure>
                                <img src="<?= $config['web']['base_url'] ?>file-photo/<?= $data_target_a['id']?>/<?=$data_target_a['photo']?>" alt="<?=$data_target_a['photo']?>" class="img-fluid">
                                <figcaption>
                                    <ul class="list-unstyled">
                                        <li><a href="<?= $config['web']['base_url']; ?>product/<?= $data_target_a['id'] ?>/<?= $data_target_a['url']?>">View</a></li>
                                    </ul>
                                </figcaption>
                            </figure>
                        </div>
                        <!-- Ends: .product-thumb -->
                        <div class="product-excerpt">
                            <h5>
                                <a href="<?= $config['web']['base_url']; ?>product/<?= $data_target_a['id'] ?>/<?= $data_target_a['url']?>">Jual <?= $data_target_a['nama_layanan']?></a>
                            </h5>
                            <ul class="titlebtm">
                                <li>
                                    <?php
                                    if($user_p['rows']['photo'] == null){
                                    ?>    
                                     <img class="auth-img" src="<?= $config['web']['base_url'] ?>img/avatar.png" alt="<?= $user_p['rows']['username']; ?>">
                                    <?  
                                    } else {
                                    ?>
                                    <img class="auth-img" src="<?= $config['web']['base_url'] ?>user-photo/<?= $user_p['rows']['photo'] ?>" alt="<?= $user_p['rows']['username']; ?>">
                                    <?
                                    }
                                    ?>
                                    <p><a href="<?= $config['web']['base_url']; ?>user/<?= $user_p['rows']['username']; ?>"><?= $user_p['rows']['username']; ?></a></p>
                                </li>
                                <li>
                                    <?
                                    if($totalPenjualan > 0 && $totalPenjualan < 500000){
                                    ?>
                                    <img src="<?= $config['web']['base_url'] ?>img/recommended.png" alt="user recommended" class="svg" width="30px" height="30px" title="Recommended Seller">
                                    <?
                                    } elseif($totalPenjualan >= 500000 && $totalPenjualan < 1000000){
                                    ?>
                                    <img src="<?= $config['web']['base_url'] ?>img/author_rank_bronze.png" alt="user rank" class="svg" width="30px" height="30px" title="Bronze Seller">
                                    <img src="<?= $config['web']['base_url'] ?>img/recommended.png" alt="user recommended" class="svg" width="30px" height="30px" title="Recommended Seller">
                                    <?    
                                    } elseif($totalPenjualan >= 1000000 && $totalPenjualan < 5000000){
                                    ?>
                                    <img src="<?= $config['web']['base_url'] ?>img/author_rank_golden.png" alt="user rank" class="svg" width="30px" height="30px" title="Golden Seller">
                                    <img src="<?= $config['web']['base_url'] ?>img/recommended.png" alt="user recommended" class="svg" width="30px" height="30px" title="Recommended Seller">
                                    <?      
                                    } elseif($totalPenjualan >= 5000000){
                                     ?>
                                    <img src="<?= $config['web']['base_url'] ?>img/author_rank_diamond.png" alt="user rank" class="svg" width="30px" height="30px" title="Diamond Seller">
                                    <img src="<?= $config['web']['base_url'] ?>img/recommended.png" alt="user recommended" class="svg" width="30px" height="30px" title="Recommended Seller">
                                    <?    
                                    }
                                    ?>
                                </li>
                            </ul>
                            <ul class="product-facts clearfix">
                                <li class="price"><?= $data_target_a['jangka_waktu'] ?> Hari</li>
                                <li class="sells">
                                    <span class="icon-basket"></span><?= $data_target_a['total_sales'] ?>
                                </li>
                                <?
                                if(mysqli_num_rows($check_like) > 0){
                                ?>
                                    <a><span class="fa fa-heart" style="color: #f71616" title="Difavoritkan" data-toggle="tooltip"></span></a>
                                <?
                                } else {
                                ?>
                                    <a><span class="fa fa-heart-o" title="Buka Produk Untuk Memfavoritkan" data-toggle="tooltip"></span></a>
                                <?    
                                }
                                ?>
                                <li class="product-rating">
                                    <ul class="list-unstyled">
                                        <?=rating($averageRating)?>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                        <!-- Ends: .product-excerpt -->
                    </div><!-- Ends: .product-single -->
                </div><!-- ends: .col-lg-4 -->
                <?php
                }
                ?>
            </div>
        </div>
    </section><!-- ends: .more_product_area -->
    
<?php
require '../template/footer.php';

?>