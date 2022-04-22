<?php
require '../web.php';

if (!isset($_GET['user'])) {
	exit(header("Location: ".$config['web']['base_url']));
}

$data_targets = $model->db_query($db, "*", "user", "username = '".mysqli_real_escape_string($db, $_GET['user'])."'");
if($data_targets['count'] == 0){
    exit(header("Location: ".$config['web']['base_url']));
} 

$dataPerHalaman = 15;

$data_targeta = mysqli_query($db, "SELECT * FROM services WHERE author = '".$data_targets['rows']['id']."' AND status = 'active' ORDER BY created_at DESC ");

$jumlahData = mysqli_num_rows($data_targeta);
$jumlahHalaman = ceil($jumlahData/$dataPerHalaman);

if (isset($_GET['page'])) {
	$pageActive = $_GET['page'];
} else {
    $pageActive = 1;
}
$awalData = ($dataPerHalaman * $pageActive) - $dataPerHalaman;
$jumlahLink = 2;
if($pageActive > $jumlahLink){
    $start_number = $pageActive - $jumlahLink;
} else {
    $start_number = 1;
}

if($pageActive < ($jumlahHalaman - $jumlahLink)){
    $end_number = $pageActive + $jumlahLink;
} else {
    $end_number = $jumlahHalaman;
}

$data_targetaa = mysqli_query($db, "SELECT * FROM services WHERE author = '".$data_targets['rows']['id']."' AND status = 'active' ORDER BY created_at DESC LIMIT $awalData,$dataPerHalaman");



$banyakRating = mysqli_query($db, "SELECT * FROM review_order WHERE seller_id = '".$data_targets['rows']['id']."' ");
   $hasilBanyakRating = mysqli_num_rows($banyakRating);
   
   $query = mysqli_query($db, "SELECT SUM(rating) AS total FROM `review_order` WHERE seller_id = '".$data_targets['rows']['id']."' ");
   $fetch = mysqli_fetch_array($query);
   $totalRating = $fetch['total'];
   
  $averageRating = $totalRating / $hasilBanyakRating;





$dataPerHalaman_review = 15;

$data_targeta_review = mysqli_query($db, "SELECT * FROM review_order WHERE seller_id = '".$data_targets['rows']['id']."' ORDER BY created_at DESC ");

$jumlahData_review = mysqli_num_rows($data_targeta_review);
$jumlahHalaman_review = ceil($jumlahData_review/$dataPerHalaman_review);

if (isset($_GET['page_review'])) {
	$pageActive_review = $_GET['page_review'];
} else {
    $pageActive_review = 1;
}
$awalData_review = ($dataPerHalaman_review * $pageActive_review) - $dataPerHalaman_review;
$jumlahLink_review = 2;
if($pageActive_review > $jumlahLink_review){
    $start_number_review = $pageActive_review - $jumlahLink_review;
} else {
    $start_number_review = 1;
}

if($pageActive_review < ($jumlahHalaman_review - $jumlahLink_review)){
    $end_number_review = $pageActive_review + $jumlahLink_review;
} else {
    $end_number_review = $jumlahHalaman_review;
}


$data_target_review = mysqli_query($db, "SELECT * FROM review_order WHERE seller_id = '".$data_targets['rows']['id']."' ORDER BY created_at DESC  LIMIT $awalData_review,$dataPerHalaman_review");


$title = ucfirst($data_targets['rows']['username']);
$description = "Beli Kebutuhan Produk Virutal atau jasa anda bersama ".ucfirst($data_targets['rows']['username'])." Menggunakan Metode Pembayaran Lengkap dan Sangat Aman";
$keyword = ucfirst($data_targets['rows']['username']);
?>
<?php
require '../template/header.php';
?>

<section class="author-profile-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="author-profile">
                        <div class="row">
                            <div class="col-lg-5 col-md-7">
                                <div class="author-desc">
                                    <?php
                                    if($data_targets['rows']['photo'] == null){
                                    ?>    
                                     <img class="auth-img" src="<?= $config['web']['base_url'] ?>img/avatar.png" alt="<?= $data_targets['rows']['username']; ?>" width="120px" height="120px">
                                    <?  
                                    } else {
                                    ?>
                                    <img class="auth-img" src="<?= $config['web']['base_url'] ?>user-photo/<?= $data_targets['rows']['photo'] ?>" alt="<?= $data_targets['rows']['username']; ?>" width="120px" height="120px">
                                    <?
                                    }
                                    ?>
                                    <div class="infos">
                                        <h4><?= $data_targets['rows']['nama'] ?></h4><spane>(<?= $data_targets['rows']['username']?>)</spane>
                                        <span>Bergabung : <?= format_date(substr($data_targets['rows']['created_at'], 0, -9)) ?></span>
                                        <?
                                        $username = $data_targets['rows']['username'];
                                        $user = $model->db_query($db, "*", "user", "username = '$username'");  
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
                                        <span class="badge badge-<?=$badge?>" ><?= $status_online ?></span>
                                        <ul>
                                            <li>
                                                <a href="<?= $config['web']['base_url'] ?>conversation/<?=$data_targets['rows']['username']?>" class="btn btn-danger btn--xs" >
                                                    <span class="icon-envelope-open"></span>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div><!-- ends: .author-desc -->
                            </div><!-- ends: .col-lg-5 -->
                            <div class="col-lg-4 order-lg-1 col-md-12 order-md-2">
                                <div class="author-social social social--color--filled">
                                    <?
                                    $query = mysqli_query($db, "SELECT SUM(price_for_seller) AS total FROM `orders` WHERE seller_id = '".$data_targets['rows']['id']."' AND status ='complete' AND kliring = '1'");
                                    $fetch = mysqli_fetch_array($query);
                                    if($fetch['total'] < 1){
                                        $totalPenjualan1 = '0';
                                    } else {
                                       $totalPenjualan1 = $fetch['total'];
                                    }
                                    ?>
                                    <ul>
                                    <?
                                    if($totalPenjualan1 > 0 && $totalPenjualan1 < 500000){
                                    ?>
                                    <li>
                                    <img src="<?= $config['web']['base_url'] ?>img/recommended.png" alt="user recommended" class="svg" width="30px" height="30px" title="Recommended Seller"> Recommended Seller
                                    </li>
                                    <?
                                    } elseif($totalPenjualan1 >= 500000 && $totalPenjualan1 < 1000000){
                                    ?>
                                    <li>
                                    <img src="<?= $config['web']['base_url'] ?>img/author_rank_bronze.png" alt="user rank" class="svg" width="30px" height="30px" title="Bronze Seller"> Bronze Seller
                                    </li>
                                    <li>
                                    <img src="<?= $config['web']['base_url'] ?>img/recommended.png" alt="user recommended" class="svg" width="30px" height="30px" title="Recommended Seller"> Recommended Seller
                                    </li>
                                    <?    
                                    } elseif($totalPenjualan1 >= 1000000 && $totalPenjualan1 < 5000000){
                                    ?>
                                    <li>
                                    <img src="<?= $config['web']['base_url'] ?>img/author_rank_golden.png" alt="user rank" class="svg" width="30px" height="30px" title="Golden Seller"> Golden Seller
                                    </li>
                                    <li>
                                    <img src="<?= $config['web']['base_url'] ?>img/top_seller.png" alt="user recommended" class="svg" width="30px" height="30px" title="Top Seller"> Top Seller
                                    </li>
                                    <li>
                                    <img src="<?= $config['web']['base_url'] ?>img/recommended.png" alt="user recommended" class="svg" width="30px" height="30px" title="Recommended Seller"> Recommended Seller
                                    </li>
                                    <?      
                                    } elseif($totalPenjualan1 >= 5000000){
                                     ?>
                                     <li>
                                    <img src="<?= $config['web']['base_url'] ?>img/author_rank_diamond.png" alt="user rank" class="svg" width="30px" height="30px" title="Diamond Seller"> Diamond Seller
                                    </li>
                                    <li>
                                    <img src="<?= $config['web']['base_url'] ?>img/top_seller.png" alt="user recommended" class="svg" width="30px" height="30px" title="Top Seller"> Top Seller
                                    </li>
                                    <li>
                                    <img src="<?= $config['web']['base_url'] ?>img/recommended.png" alt="user recommended" class="svg" width="30px" height="30px" title="Recommended Seller"> Recommended Seller
                                    </li>
                                    <?    
                                    }
                                    ?>
                                    </ul>
                                    
                                </div><!-- ends: .author-social -->
                            </div><!-- ends: .col-lg-3 -->
                            <div class="col-lg-3 order-lg-2 col-md-5 order-md-1">
                                <div class="author-stats">
                                    <ul>
                                        <li class="t_items">
                                            <?php 
                                            $item = $model->db_query($db, "*", "services", "author = '".$data_targets['rows']['id']."' AND status = 'active' ");
                                            ?>
                                            <span><?= $item['count'] ?></span>
                                            <p>Total Produk</p>
                                        </li>
                                        <li class="t_sells">
                                            <?
                                            $query = mysqli_query($db, "SELECT SUM(total_sales) AS total FROM `services` WHERE author = '".$data_targets['rows']['id']."' ");
                                            $fetch = mysqli_fetch_array($query);
                                            if($fetch['total'] < 1){
                                                $penjualan = '0';
                                            } else {
                                                $penjualan = $fetch['total'];
                                            }
                                            ?>
                                            
                                            <span><?= $penjualan ?></span>
                                            <p>Penjualan</p>
                                        </li>
                                        <li class="t_reviews">
                                            <div>
                                                <?= rating($averageRating)?>
                                                <?
                                                if($averageRating > 0){
                                                ?>
                                                <span class="avg_r"><?=number_format($averageRating, 2, ',', ' ')?></span>
                                                <?
                                                }
                                                ?>
                                                <span>(<?=$hasilBanyakRating?> reviews)</span>
                                            </div>
                                        </li>
                                    </ul>
                                </div><!-- ends: .author-stats -->
                            </div><!-- ends: .col-lg-4 -->
                        </div>
                    </div>
                </div><!-- ends: .col-lg-12 -->
                <div class="col-md-12 author-info-tabs">
                    <ul class="nav nav-tabs" id="author-tab" role="tablist">
                        <li>
                            <a class="active" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="true">Profile</a>
                        </li>
                        <li>
                            <a id="items-tab" data-toggle="tab" href="#items" role="tab" aria-controls="items" aria-selected="false">Produk</a>
                        </li>
                        <li>
                            <a id="reviews-tab" data-toggle="tab" href="#reviews" role="tab" aria-controls="reviews" aria-selected="false">Reviews</a>
                        </li>
                    </ul><!-- Ends: .nav-tabs -->
                    <div class="tab-content" id="author-tab-content">
                        <div class="tab-pane fade show active" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                            <div class="author_module about_author">
                                <h3>Hallo, Saya 
                                    <span><?= $data_targets['rows']['nama'] ?></span>
                                </h3>
                                <?
                                if($data_targets['rows']['profesi'] != null){
                                ?>
                                <h5>Profesi : <?= $data_targets['rows']['profesi'] ?></h5> <br><br>
                                <?
                                }
                                ?>
                                
                                <?= $data_targets['rows']['bio'] ?>
                            </div>
                        </div><!-- Ends: .profile-tab -->
                        <div class="tab-pane fade" id="items" role="tabpanel" aria-labelledby="items-tab">
                            <h3>Semua Produk Dari
                                <span><?= $data_targets['rows']['username'] ?></span>
                            </h3>
                            <div class="row">
                                <?php
                                    while ($data_target_s = mysqli_fetch_assoc($data_targetaa)){
                                        $data_targetss = $model->db_query($db, "*", "categories", "id = '".$data_target_s['categories_id']."'"); 
                                       $user = $model->db_query($db, "*", "user", "id = '".$data_target_s['author']."'"); 
                                       
                                       $banyakRating1 = mysqli_query($db, "SELECT * FROM review_order WHERE seller_id = '".$user['rows']['id']."' ");
                                       $hasilBanyakRating1 = mysqli_num_rows($banyakRating1);
                                       
                                       $query1 = mysqli_query($db, "SELECT SUM(rating) AS total FROM `review_order` WHERE seller_id = '".$user['rows']['id']."' ");
                                       $fetch1 = mysqli_fetch_array($query1);
                                       $totalRating1 = $fetch1['total'];
                                       
                                       $averageRating1 = $totalRating1 / $hasilBanyakRating1;
                                       
                                       $check_like = mysqli_query($db, "SELECT * FROM favorite WHERE user_id = '".$_SESSION['login']."' AND status = 'like' AND service_id = '".$data_target_s['id']."'");
                                    ?>
                                    <div class="col-lg-4 col-md-6">
                                        <div class="product-single latest-single">
                                            <div class="product-thumb">
                                                <div class="s-promotion">Rp <?= number_format($data_target_s['price'],0,',','.') ?></div>
                                                <figure>
                                                    <img src="<?= $config['web']['base_url'] ?>file-photo/<?= $data_target_s['id'] ?>/<?= $data_target_s['photo'] ?>" alt="<?= $data_target_s['photo'] ?>" class="img-fluid">
                                                    <figcaption>
                                                        <ul class="list-unstyled">
                                                            <li><a href="<?= $config['web']['base_url']; ?>product/<?= $data_target_s['id'] ?>/<?= $data_target_s['url']?>">View</a></li>
                                                        </ul>
                                                    </figcaption>
                                                </figure>
                                            </div>
                                            <!-- Ends: .product-thumb -->
                                            <div class="product-excerpt">
                                                <h5>
                                                    <a href="<?= $config['web']['base_url']; ?>product/<?= $data_target_s['id'] ?>/<?= $data_target_s['url']?>">Jual <?= $data_target_s['nama_layanan']?></a>
                                                </h5>
                                                <ul class="titlebtm">
                                                    <li>
                                                        <?php
                                                        if($user['rows']['photo'] == null){
                                                        ?>    
                                                         <img class="auth-img" src="<?= $config['web']['base_url'] ?>img/avatar.png" alt="<?= $data_targets['rows']['username']; ?>">
                                                        <?  
                                                        } else {
                                                        ?>
                                                        <img class="auth-img" src="<?= $config['web']['base_url'] ?>user-photo/<?= $data_targets['rows']['photo'] ?>" alt="<?= $data_targets['rows']['username']; ?>">
                                                        <?
                                                        }
                                                        ?>
                                                        <p><a href="<?= $config['web']['base_url']; ?>user/<?= $user['rows']['username']; ?>"><?= $user['rows']['username']; ?></a></p>
                                                    </li>
                                                    <li class="product_cat">
                                                        in
                                                        <a href="#"><?= $data_targetss['rows']['category']; ?></a>
                                                    </li>
                                                </ul>
                                                <ul class="product-facts clearfix">
                                                    <li class="price"><?= $data_target_s['jangka_waktu'] ?> Hari</li>
                                                    <li class="sells">
                                                        <span class="icon-basket"></span><?= $data_target_s['total_sales']?>
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
                                                            <?=rating($averageRating1)?>
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
                            <!-- Start Pagination -->
                            <!-- Start Pagination -->
                            <nav class="pagination-default">
                                <ul class="pagination">
                                    <? if($pageActive > 1){ 
                                    ?>
                                    <li class="page-item">
                                        <a class="page-link" href="<?= $config['web']['base_url']; ?><?= $data_targets['rows']['username']; ?>/product/<?= $pageActive - 1 ?>" aria-label="Previous">
                                            <span aria-hidden="true"><i class="fa fa-long-arrow-left"></i></span>
                                            <span class="sr-only">Previous</span>
                                        </a>
                                    </li>
                                    <?
                                    }?>
                                    <? if($start_number > 1){?>
                                    <li class="page-item disabled"><a class="page-link" href="#">...</a></li>
                                    <?}?>
                                    
                                    <!--navigasi-->
                                    <?php
                                    for($i = $start_number; $i <= $end_number; $i++){
                                    ?>   
                                        
                                        <? if ($i == $pageActive){?>
                                        <li class="page-item active"><a class="page-link" href="<?= $config['web']['base_url']; ?><?= $data_targets['rows']['username']; ?>/product/<?= $i; ?>"><?= $i ?></a></li>
                                        <? } else {?>
                                        <li class="page-item"><a class="page-link" href="<?= $config['web']['base_url']; ?><?= $data_targets['rows']['username']; ?>/product/<?= $i; ?>"><?= $i ?></a></li>
                                        <? } ?>
                                    <?    
                                    }
                                    
                                    ?>
                                    
                                    <? if($end_number < $jumlahHalaman){?>
                                    <li class="page-item disabled"><a class="page-link" href="#">...</a></li>
                                    <?}?>
                                    <? if($pageActive < $jumlahHalaman){ 
                                    ?>
                                    <li class="page-item">
                                        <a class="page-link" href="<?= $config['web']['base_url']; ?><?= $data_targets['rows']['username']; ?>/product/<?= $pageActive + 1 ?>" aria-label="Next">
                                            <span aria-hidden="true"><i class="fa fa-long-arrow-right"></i></span>
                                            <span class="sr-only">Next</span>
                                        </a>
                                    </li>
                                    <?
                                    }?>
                                    
                                </ul>
                            </nav><!-- Ends: .pagination-default -->
                        </div><!-- Ends: .items-tab -->
                        <div class="tab-pane fade" id="reviews" role="tabpanel" aria-labelledby="reviews-tab">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="product-title-area">
                                        <div class="product__title">
                                            <h3><span class="bold"><?=$hasilBanyakRating?></span> Reviews Sebagai Penjual</h3>
                                        </div>
                                    </div><!-- ends: .product-title-area -->
                                    <div class="thread thread_review thread_review2">
                                        <ul class="media-list thread-list">
                                            <?
                                        while ($data_target_reviews = mysqli_fetch_assoc($data_target_review)){
                                            $komentarBuyer = $model->db_query($db, "*", "user", "id = '".$data_target_reviews['user_id']."' "); // user_id itu buyer_id
                                            $service_review = $model->db_query($db, "*", "services", "id = '".$data_target_reviews['service_id']."' "); 
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
                                                        <a>
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
                                                    </div>
                                                    <div class="media-body">
                                                        <div class="d-flex flex-wrap">
                                                            <div class="">
                                                                <div class="media-heading">
                                                                    
                                                                        <h4><?= $usernameBuyer_bintang_3 ?></h4>
                                                                    
                                                                    <!--<a href="<?= $config['web']['base_url']; ?>product/<?=$service_review['rows']['id']?>/<?=$service_review['rows']['url']?>" class="rev_item"><?=$service_review['rows']['nama_layanan']?> </a>-->
                                                                </div>
                                                                <div class="rating product--rating">
                                                                    <ul>
                                                                         <?= rating($data_target_reviews['rating'])?>
                                                                    </ul>
                                                                </div>
                                                                <span class="review_tag"><?=$reviewBasedOn?></span>
                                                            </div>
                                                            <div class="rev_time"><?= format_date(substr($data_target_reviews['created_at'], 0, -9)); ?></div>
                                                        </div>
                                                        <p><?= html_entity_decode($data_target_reviews['comment'], ENT_NOQUOTES)  ?></p>
                                                    </div>
                                                </div>
                                            </li>
                                            <?}?>
                                        </ul><!-- ends: .media-list -->
                                    </div><!-- ends: .comments -->
                                    <!-- Start Pagination -->
                                    <!-- Start Pagination -->
                                    <nav class="pagination-default">
                                        <ul class="pagination">
                                            <? if($pageActive_review > 1){ 
                                                ?>
                                                <li class="page-item">
                                                    <a class="page-link" href="<?= $config['web']['base_url']; ?><?= $data_targets['rows']['username']; ?>/review/<?= $pageActive_review - 1 ?>" aria-label="Previous">
                                                        <span aria-hidden="true"><i class="fa fa-long-arrow-left"></i></span>
                                                        <span class="sr-only">Previous</span>
                                                    </a>
                                                </li>
                                                <?
                                                }?>
                                                <? if($start_number_review > 1){?>
                                                <li class="page-item disabled"><a class="page-link" href="#">...</a></li>
                                                <?}?>
                                                
                                                <!--navigasi-->
                                                <?php
                                                for($i = $start_number_review; $i <= $end_number_review; $i++){
                                                ?>   
                                                    
                                                    <? if ($i == $pageActive_review){?>
                                                    <li class="page-item active"><a class="page-link" href="<?= $config['web']['base_url']; ?><?= $data_targets['rows']['username']; ?>/review/<?= $i; ?>"><?= $i ?></a></li>
                                                    <? } else {?>
                                                    <li class="page-item"><a class="page-link" href="<?= $config['web']['base_url']; ?><?= $data_targets['rows']['username']; ?>/review/<?= $i; ?>"><?= $i ?></a></li>
                                                    <? } ?>
                                                <?    
                                                }
                                                
                                                ?>
                                                
                                                <? if($end_number_review < $jumlahHalaman_review){?>
                                                <li class="page-item disabled"><a class="page-link" href="#">...</a></li>
                                                <?}?>
                                                <? if($pageActive_review < $jumlahHalaman_review){ 
                                                ?>
                                                <li class="page-item">
                                                    <a class="page-link" href="<?= $config['web']['base_url']; ?><?= $data_targets['rows']['username']; ?>/review/<?= $pageActive_review + 1 ?>" aria-label="Next">
                                                        <span aria-hidden="true"><i class="fa fa-long-arrow-right"></i></span>
                                                        <span class="sr-only">Next</span>
                                                    </a>
                                                </li>
                                                <?
                                                }?>
                                        </ul>
                                    </nav><!-- Ends: .pagination-default -->
                                </div><!-- ends: .col-md-12 -->
                            </div><!-- ends: .row -->
                        </div><!-- Ends: reviews-tab -->
                        <!-- Ends: followers-tab -->
                    </div><!-- ends: .tab-content -->
                </div><!-- Ends: .author-info-tabs -->
            </div>
        </div>
    </section><!-- ends: .author-profile-area -->
<?php
require '../template/footer.php';

?>