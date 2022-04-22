<?php
require '../web.php';
require '../lib/is_login.php';
require '../lib/result.php';
if (!isset($_SESSION['login'])) {
	$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Otentikasi dibutuhkan!', 'msg' => 'Silahkan masuk ke akun Anda.');
	exit(header("Location: ".$config['web']['base_url']."signin/"));
}
$title = "Favorite";
?>
<?php

$dataPerHalaman = 12;

$data_targeta = mysqli_query($db, "SELECT * FROM favorite WHERE user_id = '".$login['id']."' AND status = 'like' ORDER BY id DESC");

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

$data_target = mysqli_query($db, "SELECT * FROM favorite WHERE user_id = '".$login['id']."' AND status = 'like' ORDER BY id DESC LIMIT $awalData,$dataPerHalaman");
require '../template/header.php';
require '../template/header-dashboard.php';
?>
    
    <section class="dashboard-area">
        <div class="dashboard_contents section--padding">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="filter-bar dashboard_title_area clearfix filter-bar2">
                            <div class="dashboard__title">
                                <h3>Produk Disukai</h3>
                            </div> 
                            <div class="filter__items">
                                <div class="filter__option filter--text py-0">
                                    <?php
                                    $jumlah = $model->db_query($db, "*", "favorite", "user_id = '".$login['id']."' AND status = 'like'");
                                    ?>
                                    <p><span><?= number_format($jumlah['count']); ?></span> Produk</p>
                                </div>
                            </div><!-- ends: .pull-right -->
                        </div><!-- ends: .filter-bar -->
                    </div><!-- ends: .col-md-12 -->
                </div><!-- ends: .row -->
                <div class="product-list">
                                <div class="row">
                                    <?php
                                        while ($data_target_sa = mysqli_fetch_assoc($data_target)){
                                            $data_target_st = mysqli_query($db, "SELECT * FROM services WHERE id = '".$data_target_sa['service_id']."'");
                                            $data_target_s = mysqli_fetch_assoc($data_target_st);
                                            // $data_target_s = $model->db_query($db, "*", "services", "id = '".$data_target_sa['service_id']."'");
                                            $data_targets = $model->db_query($db, "*", "categories", "id = '".$data_target_s['categories_id']."'");
                                           $user = $model->db_query($db, "*", "user", "id = '".$data_target_s['author']."'"); 
                                          
                                    ?>
                                    <div class="col-lg-4 col-md-6">
                                        <div class="product-single latest-single items--edit">
                                            <!--<div class="featured-badge"><span>Status</span></div>-->
                                            <div class="product-thumb">
                                                <div class="s-promotion">Rp <?= number_format($data_target_s['price'],0,',','.') ?></div>
                                                <figure>
                                                    <img src="<?= $config['web']['base_url']; ?>file-photo/<?=$data_target_s['id']?>/<?=$data_target_s['photo']?>" alt="<?=$data_target_s['photo']?>" class="img-fluid">
                                                    
                                                </figure>
                                            </div><!-- Ends: .product-thumb -->
                                            <div class="product-excerpt">
                                                <h5>
                                                    <a href="<?= $config['web']['base_url']; ?>product/<?= $data_target_s['id'] ?>/<?= $data_target_s['url']?>"><?= $data_target_s['nama_layanan'] ?></a>
                                                </h5>
                                                <ul class="titlebtm">
                                                    <li>
                                                        <?
                                                        if( $user['rows']['photo'] != null){
                                                        ?>
                                                        <img class="auth-img" src="<?= $config['web']['base_url']; ?>user-photo/<?= $user['rows']['photo'] ?>" alt="<?= $user['rows']['username'] ?>">
                                                        <?
                                                        }else {
                                                        ?>
                                                         <img class="auth-img" src="<?= $config['web']['base_url']; ?>img/avatar.png" alt="<?= $user['rows']['username'] ?>">
                                                        <?
                                                        }
                                                        ?>
                                                       
                                                        <p>
                                                            <a href="#"><?= $user['rows']['username'] ?></a>
                                                        </p>
                                                    </li>
                                                    <li class="product_cat">
                                                        in
                                                        <a href="#"><?= $data_targets['rows']['category']; ?></a>
                                                    </li>
                                                </ul>
                                                
                                            </div><!-- Ends: .product-excerpt -->
                                        </div><!-- Ends: .product-single -->
                                    </div><!-- Ends: .col-md-4 -->
                                    <?
                                        }
                                    ?>
                            </div><!-- Ends: .tab-pane -->
                        <!--<div class="text-center m-top-20">-->
                        <!--    <a href="" class="btn btn--lg btn-primary">All New Products</a>-->
                        <!--</div>-->
                    </div><!-- Ends: .product-list -->
                <div class="row">
                    <div class="col-md-12">
                        <!-- Start Pagination -->
                        <nav class="pagination-default">
                        <ul class="pagination">
                            <? if($pageActive > 1){ 
                            ?>
                            <li class="page-item">
                                <a class="page-link" href="<?= $config['web']['base_url']; ?>favorite/<?= $pageActive - 1 ?>" aria-label="Previous">
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
                                <li class="page-item active"><a class="page-link" href="<?= $config['web']['base_url']; ?>favorite/<?= $i; ?>"><?= $i ?></a></li>
                                <? } else {?>
                                <li class="page-item"><a class="page-link" href="<?= $config['web']['base_url']; ?>favorite/<?= $i; ?>"><?= $i ?></a></li>
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
                                <a class="page-link" href="<?= $config['web']['base_url']; ?>favorite/<?= $pageActive + 1 ?>" aria-label="Next">
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
            </div><!-- ends: .container -->
        </div><!-- ends: .dashboard_menu_area -->
    </section><!-- ends: .dashboard-area -->
<?php
require '../template/footer.php';

?>