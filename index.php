<?php
require 'web.php';
require 'lib/csrf_token.php';

$dataPerHalaman = 12;
 
$data_targeta = mysqli_query($db, "SELECT * FROM services WHERE status = 'active' AND featured = '0' ORDER BY created_at DESC");

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

$data_targetas = mysqli_query($db, "SELECT * FROM services WHERE status = 'active' ORDER BY created_at DESC LIMIT $awalData,$dataPerHalaman");

$premium_check = mysqli_query($db, "SELECT * FROM services WHERE premium = '1' AND status = 'active'");
                                 


require 'template/header.php';
 $website = $model->db_query($db, "*", "website", "id = '1'"); 
?>
    <section class="hero-area4 bgimage">
        <div class="bg_image_holder">
            <img src="img/shape_2.png" alt="background-image">
        </div>
        <div class="hero-content content_above">
            <div class="content-wrapper">
                <div class="container">
                    <div class="row">
                        
                        
                        <!--coursel-->
                        <div class="col-md-6 col-lg-6">
                                <h4 class="text-center p-2">Promotion </h4>
                                    <div id="carouselExampleSlidesOnly" class="carousel slide" data-ride="carousel" style="box-shadow: 0px 0px 4px #949494;">
                                      <div class="carousel-inner">
                                        <div class="carousel-item active">
                                          <a href="<?=$website['rows']['url_promotion']?>" target="_blank"><img class="d-block w-100" src="<?=$config['web']['base_url']?>file-photo/website/<?=$website['rows']['logo_promotion']?>" alt="<?=$website['rows']['url_promotion']?>" width = "100%" height = "288px"></a>
                                          
                                        </div>
                                      </div>
                                    </div>
                        
                        </div>
                        <?
                        if(mysqli_num_rows($premium_check) > 0){
                        ?>
                        
                        <div class="col-md-6 col-lg-6">
                            <h4 class="text-center p-2"> Produk Premium </h4>
                            <div id="carouselExampleIndicators" class="carousel slide" data-interval="3000" data-ride="carousel" style="box-shadow: 0px 0px 4px #949494;">
                              <ol class="carousel-indicators">
                                <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                                <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
                                <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
                              </ol>
                              <div class="carousel-inner">
                                  <?
                                  $data_layanan_premi = mysqli_query($db, "SELECT * FROM services WHERE premium = '1' AND status = 'active' ORDER BY created_at DESC LIMIT 1");
                                  $data_premi = mysqli_fetch_assoc($data_layanan_premi);
                                  if(mysqli_num_rows($data_layanan_premi) > 0){
                                      
                                 ?>
                                 <div class="carousel-item active">
                                     <div class="ism-slider" data-play_type="loop" data-radios="false" id="ultimate-slider" style="box-shadow:0 4px 8px 0 rgba(0,0,0,0.2), 0 6px 20px 0 rgba(0,0,0,0.19)">
                                            <li>
                                            <a href="<?= $config['web']['base_url']; ?>product/<?= $data_premi['id'] ?>/<?= $data_premi['url']?>"><img src="<?= $config['web']['base_url'] ?>file-photo/<?= $data_premi['id'] ?>/<?= $data_premi['photo'] ?>" width = "100%" height = "265px"></a>
                                            <center><a href="<?= $config['web']['base_url']; ?>product/<?= $data_premi['id'] ?>/<?= $data_premi['url']?>"><div class="ism-caption ism-caption-0">Jual <?= $data_premi['nama_layanan'] ?> untuk Rp <?= number_format($data_premi['price'],0,',','.') ?></div></a></center>
                                            </li>
                                    </div>
                                </div>
                                 <?
                                  } 
                                  $data_layanan_premi2 = mysqli_query($db, "SELECT * FROM services WHERE premium = '1' AND status = 'active' AND id != '".$data_premi['id']."' ORDER BY created_at DESC LIMIT 1");
                                  $data_premi2 = mysqli_fetch_assoc($data_layanan_premi2);
                                  if(mysqli_num_rows($data_layanan_premi2) > 0){
                                  ?>
                                  <div class="carousel-item">
                                       <div class="ism-slider" data-play_type="loop" data-radios="false" id="ultimate-slider" style="box-shadow:0 4px 8px 0 rgba(0,0,0,0.2), 0 6px 20px 0 rgba(0,0,0,0.19)">
                                            <li>
                                            <a href="<?= $config['web']['base_url']; ?>product/<?= $data_premi2['id'] ?>/<?= $data_premi2['url']?>"><img src="<?= $config['web']['base_url'] ?>file-photo/<?= $data_premi2['id'] ?>/<?= $data_premi2['photo'] ?>" width = "100%" height = "265px"></a>
                                            <center><a href="<?= $config['web']['base_url']; ?>product/<?= $data_premi2['id'] ?>/<?= $data_premi2['url']?>"><div class="ism-caption ism-caption-0">Jual <?= $data_premi2['nama_layanan'] ?> untuk Rp <?= number_format($data_premi2['price'],0,',','.') ?></div></a></center>
                                            </li>
                                    </div>
                                </div>
                                  <?
                                  }
                                  ?>
                                  <?
                                  $data_layanan_premi3 = mysqli_query($db, "SELECT * FROM services WHERE premium = '1' AND status = 'active' AND id != '".$data_premi2['id']."' AND id != '".$data_premi['id']."' ORDER BY created_at DESC LIMIT 1");
                                  $data_premi3 = mysqli_fetch_assoc($data_layanan_premi3);
                                  if(mysqli_num_rows($data_layanan_premi3) > 0){
                                  ?>
                                  <div class="carousel-item">
                                      <div class="ism-slider" data-play_type="loop" data-radios="false" id="ultimate-slider" style="box-shadow:0 4px 8px 0 rgba(0,0,0,0.2), 0 6px 20px 0 rgba(0,0,0,0.19)">
                                            <li>
                                            <a href="<?= $config['web']['base_url']; ?>product/<?= $data_premi3['id'] ?>/<?= $data_premi3['url']?>"><img src="<?= $config['web']['base_url'] ?>file-photo/<?= $data_premi3['id'] ?>/<?= $data_premi3['photo'] ?>" width = "100%" height = "265px"></a>
                                            <center><a href="<?= $config['web']['base_url']; ?>product/<?= $data_premi3['id'] ?>/<?= $data_premi3['url']?>"><div class="ism-caption ism-caption-0">Jual <?= $data_premi3['nama_layanan'] ?> untuk Rp <?= number_format($data_premi3['price'],0,',','.') ?></div></a></center>
                                            </li>
                                    </div>
                                </div>
                                  
                                  <?
                                  }
                                  ?>
                              </div>
                              <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true" style=" background: rgba(0, 0, 0, 1);"><i class="icon-arrow-left"></i></span>
                                <span class="sr-only">Previous</span>
                              </a>
                              <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true" style=" background: rgba(0, 0, 0, 1);"><i class="icon-arrow-right"></i></span>
                                <span class="sr-only">Next</span>
                              </a>
                            </div>
                        </div>
                        <?
                        } else {
                        ?>    
                        <div class="col-md-6 col-lg-6">
                                <h4 class="text-center p-2">Promotion </h4>
                                    <div id="carouselExampleSlidesOnly" class="carousel slide" data-ride="carousel" style="box-shadow: 0px 0px 4px #949494;">
                                      <div class="carousel-inner">
                                        <div class="carousel-item active">
                                          <a href=""><img class="d-block w-100" src="<?=$config['web']['base_url']?>file-photo/website/<?=$website['rows']['logo_promotion']?>" alt="First slide" width = "100%" height = "280px"></a>
                                          
                                        </div>
                                      </div>
                                    </div>
                        
                        </div>
                        <?    
                        }
                        ?>
                        
                    </div>
                </div>
            </div><!-- end .contact_wrapper -->
        </div><!-- end hero-content -->
    </section><!-- ends: .hero-area -->
    <section class="featured-products2 p-top-80 p-bottom-50">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="section-title2">
                        <ul class="list-unstyled">
                            <li>
                                <h2>Produk Featured</h2> 
                            </li>
                            <!--<li><a href="" class="btn btn-outline-primary">View All</a></li>-->
                        </ul>
                    </div>
                </div>
                <?php
                $data_target = mysqli_query($db, "SELECT * FROM services WHERE featured = '1' AND status = 'active' LIMIT 3");
                while ($data_target_s = mysqli_fetch_assoc($data_target)){
                    $data_targets = $model->db_query($db, "*", "categories", "id = '".$data_target_s['categories_id']."'"); 
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
                <div class="col-lg-4 col-md-6">
                    <div class="product-single latest-single">
                        <div class="featured-badge"><span>Featured</span></div>
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
                                
                                <li class="product-rating">
                                    <ul class="list-unstyled">
                                        <li class="stars">
                                            <?= rating($averageRating)?>
                                        </li>
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
    </section><!-- ends: .fearured-product2 -->
    <section class="latest-product p-top-80 p-bottom-50">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="section-title2">
                        <ul class="list-unstyled">
                            <li>
                                <h2>Produk Terbaru</h2>
                            </li>
                            <!--<li><a href="" class="btn btn-outline-primary">View All</a></li>-->
                        </ul>
                    </div>
                </div><!-- Ends: .col-md-12 -->
                <div class="col-md-12 product-list">
                    <div class="row">
                        <?php
                        
                        while ($data_target_ss = mysqli_fetch_assoc($data_targetas)){
                            $data_targetss = $model->db_query($db, "*", "categories", "id = '".$data_target_ss['categories_id']."'");
                           $user = $model->db_query($db, "*", "user", "id = '".$data_target_ss['author']."'"); 
                           
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
                        <div class="col-lg-4 col-md-6">
                            <div class="product-single latest-single">
                                <div class="product-thumb">
                                    <div class="s-promotion">Rp <?= number_format($data_target_ss['price'],0,',','.') ?></div>
                                    <figure>
                                        <img src="<?= $config['web']['base_url'] ?>file-photo/<?= $data_target_ss['id'] ?>/<?= $data_target_ss['photo'] ?>" alt="<?= $data_target_ss['photo'] ?>" class="img-fluid">
                                        <figcaption>
                                            <ul class="list-unstyled">
                                                <li><a href="<?= $config['web']['base_url']; ?>product/<?= $data_target_ss['id'] ?>/<?= $data_target_ss['url']?>">View</a></li>
                                            </ul>
                                        </figcaption>
                                    </figure>
                                </div>
                                <!-- Ends: .product-thumb -->
                                <div class="product-excerpt">
                                    <h5>
                                        <a href="<?= $config['web']['base_url']; ?>product/<?= $data_target_ss['id'] ?>/<?= $data_target_ss['url']?>">Jual <?= $data_target_ss['nama_layanan']?></a>
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
                                        </li>
                                    </ul>
                                    <ul class="product-facts clearfix">
                                        <li class="price"><?= $data_target_ss['jangka_waktu'] ?> Hari</li>
                                        <li class="sells">
                                            <span class="icon-basket"></span><?= $data_target_ss['total_sales']?>
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
                                                <li class="stars">
                                                    <?= rating($averageRating)?>
                                                </li>
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
                    <nav class="pagination-default">
                        <ul class="pagination">
                            <? if($pageActive > 1){ 
                            ?>
                            <li class="page-item">
                                <a class="page-link" href="<?= $config['web']['base_url']; ?>page/<?= $pageActive - 1 ?>" aria-label="Previous">
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
                                <li class="page-item active"><a class="page-link" href="<?= $config['web']['base_url']; ?>page/<?= $i; ?>"><?= $i ?></a></li>
                                <? } else {?>
                                <li class="page-item"><a class="page-link" href="<?= $config['web']['base_url']; ?>page/<?= $i; ?>"><?= $i ?></a></li>
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
                                <a class="page-link" href="<?= $config['web']['base_url']; ?>page/<?= $pageActive + 1 ?>" aria-label="Next">
                                    <span aria-hidden="true"><i class="fa fa-long-arrow-right"></i></span>
                                    <span class="sr-only">Next</span>
                                </a>
                            </li>
                            <?php
                            }
                            ?>
                            
                        </ul>
                    </nav><!-- Ends: .pagination-default -->
                </div><!-- Ends: .product-list -->
            </div>
        </div>
    </section><!-- ends: .latest-product -->
    <div class="dashboard_contents dashboard_statement_area section--padding">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="dashboard_title_area">
                            <div class="dashboard__title">
                                <h3>Daftar Permintaan</h3>
                            </div>
                            <?
                            if(isset($_SESSION['login'])){
                            ?>
                            <div class="ml-auto add-payment-btn">
                                <a data-toggle="modal" data-target="#modalReq">
                                <button class="btn btn--md btn-primary"><i class="fa fa-plus-square"></i><span>Buat Permintaan</span></button>
                                </a>
                            </div>
                            <?
                            }
                            ?>
                        </div>
                    </div><!-- ends: .col-md-12 -->
                </div><!-- ends: .row -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="statement_table table-responsive">
                            <table class="table">
                               <thead>
                                    <tr>
                                        <th>Pembeli</th>
                                        <th>Permintaan</th>
                                        <th>Estimasi Harga</th>
                                        <th>Lama Pengiriman</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <?php
                                $permintaan = mysqli_query($db, "SELECT * FROM permintaan_pembeli WHERE status = 'active' ORDER by created_at DESC LIMIT 5");
                                while ($orders_info = mysqli_fetch_assoc($permintaan)){
                                    
                                    
                                    $buyer_info = $model->db_query($db, "*", "user", "id = '".$orders_info['user_id']."'");
                                ?>
                                <tbody>
                                    <tr>
                                        <td>
                                            <?
                                            if($buyer_info['rows']['photo'] == null){
                                            ?>
                                            <img src="<?=$config['web']['base_url']?>img/avatar.png" alt="<?=$buyer_info['rows']['username']?>" width="50px" height="50px">
                                            <?
                                            } else {
                                            ?>
                                            <img src="<?=$config['web']['base_url']?>user-photo/<?=$buyer_info['rows']['photo']?>" alt="<?=$buyer_info['rows']['username']?>" width="50px" height="50px">
                                            <?    
                                            }
                                            ?>
                                            <?=$buyer_info['rows']['username']?>
                                            </td>
                                        <td><?=$orders_info['permintaan']?></td>
                                        <td>Rp <?= number_format($orders_info['budget'],0,',','.') ?></td>
                                        <td><?=$orders_info['jangka_waktu']?> Hari</td>
                                        <?
                                        if(!isset($_SESSION['login'])){
                                        ?>
                                        <td><a href="<?=$config['web']['base_url']?>signin/" class="btn btn-primary" aria-hidden="true" title="Masuk Untuk Mengirim Penawaran"><i class="fa fa-sign-in" aria-hidden="true"></i>Masuk</a></td>
                                        <?    
                                        } else {
                                        ?>    
                                        <td>
                                            <a data-toggle="modal" data-target="#modalSaya<?=$orders_info['id']?>">
                                                 <button class="btn btn-primary" aria-hidden="true" title="Kirim Penawaran"><i class="fa fa-paper-plane"></i></button>
                                            </a>
                                        </td>
                                        <div class="modal fade" id="modalSaya<?=$orders_info['id']?>" tabindex="-1" role="dialog" aria-labelledby="modalSayaLabel" aria-hidden="true">
                                              <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                
                                                <form action="<?= $config['web']['base_url']; ?>sendoffer.php" method="post">
                                                <input type="hidden" name="csrf_token" value="<?php echo $config['csrf_token'] ?>">
                                                  <div class="modal-header">
                                                    <h5 class="modal-title" id="modalSayaLabel">Kirim Penawaran</h5>
                                                    <span><?=$orders_info['permintaan']?></span>
                                                  </div>
                                                  <div class="modal-body">
                                                    <div class="rating_field">
                                                        <label for="produk">Pilih Produk</label>
                                                        <div class="select-wrap select-wrap2">
                                                            <select name="produk" class="text_field" required>
                                                                <option value="0">Silahkan Pilih...</option>
                                                                <?php
                                                                
                            									$category_select = $model->db_query($db, "*", "services", "author = '".$_SESSION['login']."' AND status ='active' ");
                            									
                            									if ($category_select['count'] == 1) {
                            										print('<option value="'.$category_select['rows']['id'].'">'.$category_select['rows']['nama_layanan'].'</option>');
                            									} else {
                            									foreach ($category_select['rows'] as $key) {
                            										print('<option value="'.$key['id'].'">'.$key['nama_layanan'].'</option>');
                            									}
                            									}
                            									?>
                                                            </select>
                                                            <span class="lnr icon-arrow-down"></span>
                                                        </div>
                                                    </div><br>
                                                    <input type="hidden" name="puid" value="<?=$orders_info['id']?>">
                                                    <input type="hidden" name="uid" value=" <?=$buyer_info['rows']['id']?>">
                                                    <div class="rating_field">
                                                        <label for="rating_field">Pesan</label>
                                                        <textarea name="message_off"></textarea>
                                                    </div>
                                                  </div>
                                                  <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                                    <button type="submit" class="btn btn-primary">Oke</button>
                                                  </div>
                                                  </form>
                                                </div>
                                              </div>
                                            </div>
                                        <?    
                                        }
                                        ?>
                                        
                                    </tr>
                                </tbody>
                                
                                <?php
                                }  
                                ?>
                            </table>
                            <?
                            $banyak_permintaan = mysqli_num_rows($permintaan);
                            if($banyak_permintaan > 5){
                            ?>
                            <div class="more-item-btn">
                                <a href="<?=$config['web']['base_url']?>request/" class="btn btn--lg btn-secondary">Semua Permintaan</a>
                            </div>
                            <?
                            }
                            ?>
                            
                            
                        </div><!-- ends: .statement_table -->
                    </div>
                </div><!-- ends: .row -->
            </div><!-- ends: .container -->
        </div><!-- ends: .dashboard_menu_area -->
        
    <section class="services bgcolor">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-sm-6">
                    <div class="service-single">
                        <span class="icon-lock"></span>
                        <h4>Aman</h4>
                        <p>Transaksi Aman dan Sangat Mudah </p>
                    </div>
                </div><!-- Ends: .col-sm-6 -->
                <div class="col-lg-3 col-sm-6">
                    <div class="service-single">
                        <span class="icon-like"></span>
                        <h4>Produk Berkualitas</h4>
                        <p>Produk yang tampil adalah produk yang telah kami seleksi</p>
                    </div>
                </div><!-- Ends: .col-sm-6 -->
                <div class="col-lg-3 col-sm-6">
                    <div class="service-single">
                        <span class="icon-wallet"></span>
                        <h4>Jaminan Uang Kembali</h4>
                        <p>Jika transaksi anda gagal, uang kembali 100%</p>
                    </div>
                </div><!-- Ends: .col-sm-6 -->
                <div class="col-lg-3 col-sm-6">
                    <div class="service-single">
                        <span class="icon-people"></span>
                        <h4>24/7 Support</h4>
                        <p>Kami siap membantu anda setiap saat</p>
                    </div>
                </div><!-- Ends: .col-sm-6 -->
            </div>
        </div>
    </section><!-- ends: .services -->

<div class="modal fade" id="modalReq" tabindex="-1" role="dialog" aria-labelledby="modalSayaLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
    
    <form action="<?= $config['web']['base_url']; ?>makerequest.php" method="post">
    <input type="hidden" name="csrf_token" value="<?php echo $config['csrf_token'] ?>">
      <div class="modal-header">
        <h5 class="modal-title" id="modalSayaLabel">Buat Permintaan</h5>
      </div>
      <div class="modal-body">
        <div class="rating_field">
            <label for="rating_field">Permintaan</label>
            <textarea name="message_req" placeholder="Silahkan Buat Permintaan Anda"></textarea>
        </div>
        <label>Harga Untuk Permintaan (Rupiah)</label>
        <input name="price" type="number" placeholder="Harga Untuk Permintaan">
         <label>Lama Pengerjaan (Hari)</label>
        <input name="jangka_waktu" type="number" placeholder="Lama Pengerjaan">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
        <button type="submit" class="btn btn-primary">Oke</button>
      </div>
      </form>
    </div>
  </div>
</div>

<?php
require 'template/footer.php'

?>