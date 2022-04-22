<?php
require '../web.php';

if (!isset($_GET['category'])) {
	exit(header("Location: ".$config['web']['base_url']));
}

$data_targets = $model->db_query($db, "*", "categories", "url = '".mysqli_real_escape_string($db, $_GET['category'])."'");
if($data_targets['count'] == 0){
    exit(header("Location: ".$config['web']['base_url']));
}

$id_categories = $data_targets['rows']['id'];

$dataPerHalaman = 16;

$data_targeta = mysqli_query($db, "SELECT * FROM services WHERE categories_id = '$id_categories' AND status = 'active' ORDER BY created_at DESC ");

$jumlahData = mysqli_num_rows($data_targeta);
$jumlahHalaman = ceil($jumlahData/$dataPerHalaman);
$category = mysqli_real_escape_string($db, $_GET['category']);

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

$data_target = mysqli_query($db, "SELECT * FROM services WHERE categories_id = '$id_categories' AND status = 'active' ORDER BY created_at DESC LIMIT $awalData,$dataPerHalaman");

$title = ucfirst($data_targets['rows']['category']);
$description = ucfirst($data_targets['rows']['category']);
$keyword = ucfirst($data_targets['rows']['category']);
require '../template/header.php';

?>
    <section class="hero-area2 hero-area3 bgimage">
        <div class="bg_image_holder">
            <img src="<?= $config['web']['base_url'] ?>file-photo/kategori.jpg" alt="background-image">
        </div>
        <div class="hero-content content_above">
            <div class="content-wrapper">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="hero__content__title">
                                <h1><?=ucfirst($data_targets['rows']['category']);?></h1>
                            </div><!-- end .hero__btn-area-->
                            <div class="hero__content__title">
                                   <center><h4><font color="white"><?=ucfirst($data_targets['rows']['ket']);?></font></h4></center>
                            </div>
                            <!--start .search-area -->
                        </div><!-- end .col-md-12 -->
                    </div>
                </div>
            </div><!-- end .contact_wrapper -->
        </div><!-- end hero-content -->
    </section><!-- ends: .hero-area -->
    <div class="filter-area product-filter-area">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                </div><!-- end .col-md-12 -->
            </div>
        </div>
    </div><!-- end .filter-area -->
    <section class="product-grid p-bottom-100">
        <div class="container">
            <div class="row">
                <!-- Start .product-list -->
                <div class="col-md-12 product-list">
                    <div class="row" id="searchQuery">
                        <?php
                        while ($data_target_s = mysqli_fetch_assoc($data_target)){
                            $data_targetss = $model->db_query($db, "*", "categories", "id = '".$data_target_s['categories_id']."'"); 
                           $user = $model->db_query($db, "*", "user", "id = '".$data_target_s['author']."'"); 
                           
                           $banyakRating = mysqli_query($db, "SELECT * FROM review_order WHERE seller_id = '".$user['rows']['id']."' ");
                           $hasilBanyakRating = mysqli_num_rows($banyakRating);
                           
                           $query = mysqli_query($db, "SELECT SUM(rating) AS total FROM `review_order` WHERE seller_id = '".$user['rows']['id']."' ");
                           $fetch = mysqli_fetch_array($query);
                           $totalRating = $fetch['total'];
                           
                           $averageRating = $totalRating / $hasilBanyakRating;
                           
                           $check_like = mysqli_query($db, "SELECT * FROM favorite WHERE user_id = '".$_SESSION['login']."' AND status = 'like' AND service_id = '".$data_target_s['id']."'");
                           
                           $query_penjualan = mysqli_query($db, "SELECT SUM(price_for_seller) AS total FROM `orders` WHERE seller_id = '".$data_target_s['author']."' AND status ='complete' AND kliring = '1'");
                            $fetch_penjualan = mysqli_fetch_array($query_penjualan);
                            if($fetch_penjualan['total'] < 1){
                                $totalPenjualan = '0';
                            } else {
                               $totalPenjualan = $fetch_penjualan['total'];
                            }
                        ?>
                        <div class="col-lg-3 col-md-6 ">
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
                                    <div class="product-rating2">
                                        <ul class="list-unstyled">
                                            <li class="stars">
                                                <?= rating($averageRating)?>
                                            </li>
                                        </ul>
                                    </div>
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
                                             <img class="auth-img" src="<?= $config['web']['base_url'] ?>img/avatar.png" alt="<?= $user['rows']['username']; ?>">
                                            <?  
                                            } else {
                                            ?>
                                            <img class="auth-img" src="<?= $config['web']['base_url'] ?>user-photo/<?= $user['rows']['photo'] ?>" alt="<?= $user['rows']['username']; ?>">
                                            <?
                                            }
                                            ?>
                                            <p><a href="<?= $config['web']['base_url']; ?>user/<?= $user['rows']['username']; ?>"><?= $user['rows']['username']; ?></a></p>
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
                    <nav class="pagination-default">
                        <ul class="pagination">
                            <? if($pageActive > 1){ 
                            ?>
                            <li class="page-item">
                                <a class="page-link" href="<?= $config['web']['base_url']; ?>categories/<?= $data_targets['rows']['category']; ?>/<?= $pageActive - 1 ?>" aria-label="Previous">
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
                                <li class="page-item active"><a class="page-link" href="<?= $config['web']['base_url']; ?>categories/<?= $data_targets['rows']['category']; ?>/<?= $i; ?>"><?= $i ?></a></li>
                                <? } else {?>
                                <li class="page-item"><a class="page-link" href="<?= $config['web']['base_url']; ?>categories/<?= $data_targets['rows']['category']; ?>/<?= $i; ?>"><?= $i ?></a></li>
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
                                <a class="page-link" href="<?= $config['web']['base_url']; ?>categories/<?= $data_targets['rows']['category']; ?>/<?= $pageActive + 1 ?>" aria-label="Next">
                                    <span aria-hidden="true"><i class="fa fa-long-arrow-right"></i></span>
                                    <span class="sr-only">Next</span>
                                </a>
                            </li>
                            <?
                            }?>
                            
                        </ul>
                    </nav><!-- Ends: .pagination-default -->
                </div>
                <!-- Ends: .product-list -->
            </div>
        </div>
    </section><!-- ends: .product-grid -->
    
    <?php
require '../template/footer.php';

?>