<?php
require 'web.php';

if(!isset($_GET['query'])){
    exit(header("Location: ".$config['web']['base_url']));
} else {
$keyword = $db->real_escape_string(trim(htmlspecialchars(htmlentities($_GET['query']))));
$data_target = mysqli_query($db, "SELECT * FROM services WHERE status = 'active' AND nama_layanan LIKE '%$keyword%' ORDER BY created_at DESC");

require 'template/header.php'; 

?>
    
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
                    <div class="row" id ="searchQuery">
                        <?
                        if(mysqli_num_rows($data_target) > 0){
                        while ($data_target_s = mysqli_fetch_assoc($data_target)){
                            $data_targetss = $model->db_query($db, "*", "categories", "id = '".$data_target_s['categories_id']."'"); 
                           $user = $model->db_query($db, "*", "user", "id = '".$data_target_s['author']."'"); 
                           
                           $banyakRating = mysqli_query($db, "SELECT * FROM review_order WHERE seller_id = '".$user['rows']['id']."' ");
                           $hasilBanyakRating = mysqli_num_rows($banyakRating);
                           
                           $query = mysqli_query($db, "SELECT SUM(rating) AS total FROM `review_order` WHERE seller_id = '".$user['rows']['id']."' ");
                           $fetch = mysqli_fetch_array($query);
                           $totalRating = $fetch['total'];
                           
                           $averageRating = $totalRating / $hasilBanyakRating;
                           
                           $check_like = mysqli_query($db, "SELECT * FROM favorite WHERE user_id = '".$_SESSION['login']."' AND status = 'like' AND service_id = '".$data_target_ss['id']."'");
                           
                           $query_penjualan1 = mysqli_query($db, "SELECT SUM(price_for_seller) AS total FROM `orders` WHERE seller_id = '".$data_target_ss['author']."' AND status ='complete' AND kliring = '1'");
                            $fetch_penjualan1 = mysqli_fetch_array($query_penjualan1);
                            if($fetch_penjualan1['total'] < 1){
                                $totalPenjualan1 = '0';
                            } else {
                               $totalPenjualan1 = $fetch_penjualan1['total'];
                            }
                        ?>
                        <div class="col-lg-4 col-md-6 ">
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
                                        if($totalPenjualan1 > 0 && $totalPenjualan1 < 500000){
                                        ?>
                                        <img src="<?= $config['web']['base_url'] ?>img/recommended.png" alt="user recommended" class="svg" width="30px" height="30px" title="Recommended Seller">
                                        <?
                                        } elseif($totalPenjualan1 >= 500000 && $totalPenjualan1 < 1000000){
                                        ?>
                                        <img src="<?= $config['web']['base_url'] ?>img/author_rank_bronze.png" alt="user rank" class="svg" width="30px" height="30px" title="Bronze Seller">
                                        <img src="<?= $config['web']['base_url'] ?>img/recommended.png" alt="user recommended" class="svg" width="30px" height="30px" title="Recommended Seller">
                                        <?    
                                        } elseif($totalPenjualan1 >= 1000000 && $totalPenjualan1 < 5000000){
                                        ?>
                                        <img src="<?= $config['web']['base_url'] ?>img/author_rank_golden.png" alt="user rank" class="svg" width="30px" height="30px" title="Golden Seller">
                                        <img src="<?= $config['web']['base_url'] ?>img/recommended.png" alt="user recommended" class="svg" width="30px" height="30px" title="Recommended Seller">
                                        <?      
                                        } elseif($totalPenjualan1 >= 5000000){
                                         ?>
                                        <img src="<?= $config['web']['base_url'] ?>img/author_rank_diamond.png" alt="user rank" class="svg" width="30px" height="30px" title="Diamond Seller">
                                        <img src="<?= $config['web']['base_url'] ?>img/recommended.png" alt="user recommended" class="svg" width="30px" height="30px" title="Recommended Seller">
                                        <?    
                                        }
                                        ?>
                                    </ul>
                                    <ul class="product-facts clearfix">
                                        <li class="price"><?= $data_target_s['jangka_waktu'] ?> Hari</li>
                                        <li class="sells">
                                            <span class="icon-basket"></span><?= $data_target_s['total_sales']?>
                                        </li>
                                        <li class="product-fav">
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
                                        </li>
                                        <li class="product-rating">
                                            <?= rating($averageRating)?>
                                        </li>
                                    </ul>
                                </div>
                                <!-- Ends: .product-excerpt -->
                            </div><!-- Ends: .product-single -->
                        </div><!-- ends: .col-lg-4 -->
                        
                        <?php    
                        }
                        } else {
                            $website = $model->db_query($db, "*", "website", "id = '1'"); 
                         ?>
                         <section class="four_o_four_area section--padding">
                                <div class="container">
                                    <div class="row">
                                        <div class="col-lg-12 text-center">
                                            <img src="<?= $config['web']['base_url']; ?>file-photo/website/<?=$website['rows']['logo_web']?>" alt="Not Found" width="330px" height ="100px">
                                            <div class="not_found">
                                                <h2>Yaaaah.. Produk Yang Kamu Cari Tidak Tersedia...</h2>
                                                <a href="<?=$config['web']['base_url']?>" class="btn btn--md btn-primary">Kembali</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                         </section><!-- ends: .four_o_four_area -->
                         <?   
                        }
                        ?>
                    </div>
                    
                </div>
                <!-- Ends: .product-list -->
            </div>
        </div>
    </section><!-- ends: .product-grid -->
    
    <?php
require 'template/footer.php';
}
?>