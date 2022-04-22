<?php
require '../web.php';
require '../lib/is_login.php';
require '../lib/result.php';
if (!isset($_SESSION['login'])) {
	$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Otentikasi dibutuhkan!', 'msg' => 'Silahkan masuk ke akun Anda.');
	exit(header("Location: ".$config['web']['base_url']."signin/"));
}
$title = "Produk Saya";
?>
<?php

$dataPerHalaman = 15;

$data_targeta = mysqli_query($db, "SELECT * FROM services WHERE author = '".$login['id']."' AND deleted != '2' ORDER BY created_at DESC ");

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

$data_target = mysqli_query($db, "SELECT * FROM services WHERE author = '".$login['id']."' AND deleted != '2' ORDER BY created_at DESC LIMIT $awalData,$dataPerHalaman");
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
                                <h3>Kelola Produk</h3>
                            </div>
                            <div class="filter__items">
                                <div class="filter__option filter--text py-0">
                                    <?php
                                    $jumlah = $model->db_query($db, "*", "services", "author = '".$login['id']."' AND deleted != '2'");
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
                                        while ($data_target_s = mysqli_fetch_assoc($data_target)){
                                            $data_targets = $model->db_query($db, "*", "categories", "id = '".$data_target_s['categories_id']."'");
                                           $user = $model->db_query($db, "*", "user", "id = '".$data_target_s['author']."'"); 
                                           if($data_target_s['status'] == 'active'){
                                               $label = 'success';
                                               $status = 'Aktif';
                                           } elseif($data_target_s['status'] == 'not-active'){
                                               $label = 'warning';
                                               $status = 'Disembunyikan';
                                           } elseif($data_target_s['status'] == 'pending'){
                                               $label = 'warning';
                                               $status = 'Menunggu Persetujuan';
                                           } elseif($data_target_s['status'] == 'revisi'){
                                               $label = 'warning';
                                               $status = 'Revisi';
                                           } elseif($data_target_s['status'] == 'delete' && $data_target_s['deleted'] == '1'){
                                               $expire_tanggal = format_date(substr($data_target_s['deleted_time'], 0, -9)).", ".substr($data_target_s['deleted_time'], -8);
                                               $label = 'danger';
                                               $status = 'Hapus Otomatis Pada '.$expire_tanggal.' UTC+7';
                                           }
                                           
                                    ?>
                                    <div class="col-lg-4 col-md-6">
                                        <div class="product-single latest-single items--edit">
                                            <!--<div class="featured-badge"><span>Status</span></div>-->
                                            <div class="product-thumb">
                                                <div class="s-promotion">Rp <?= number_format($data_target_s['price'],0,',','.') ?></div>
                                                <figure>
                                                    <img src="<?= $config['web']['base_url']; ?>file-photo/<?=$data_target_s['id']?>/<?=$data_target_s['photo']?>" alt="<?=$data_target_s['photo']?>" class="img-fluid">
                                                    <div class="prod_option show">
                                                        <a href="#" id="drop_1" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                                            <span class="icon-settings setting-icon"></span>
                                                        </a>
                                                        <div class="options dropdown-menu" aria-labelledby="drop_1">
                                                            <ul>
                                                                <?
                                                                if($data_target_s['status'] == 'active'){
                                                                ?>
                                                                    <li class="dropdown-item">
                                                                        <a href="<?= $config['web']['base_url']; ?>edit/<?= $data_target_s['id']?>">
                                                                            <span class="icon-pencil"></span>Edit</a>
                                                                    </li>
                                                                    <li class="dropdown-item">
                                                                        <a href="<?= $config['web']['base_url']; ?>status/<?= $data_target_s['id']; ?>/not-active">
                                                                            <span class="icon-eye"></span>Sembunyikan</a>
                                                                    </li>
                                                                    <li class="dropdown-item">
                                                                        <a href="<?= $config['web']['base_url']; ?>deleted/<?= $data_target_s['id']; ?>/1" class="delete">
                                                                            <span class="icon-trash"></span>Delete</a>
                                                                    </li>
                                                                    <?
                                                                    if($data_target_s['premium'] == '0'){
                                                                    ?>
                                                                    <li class="dropdown-item">
                                                                        <a href="<?= $config['web']['base_url']; ?>buy-premium/<?= $data_target_s['id']; ?>/1" class="delete">
                                                                            <span class="icon-star"></span>Premium</a>
                                                                    </li>
                                                                    <?
                                                                    }
                                                                    ?>
                                                                    <?
                                                                    if($data_target_s['featured'] == '0'){
                                                                    ?>
                                                                    <li class="dropdown-item">
                                                                        <a href="<?= $config['web']['base_url']; ?>buy-featured/<?= $data_target_s['id']; ?>/2" class="delete">
                                                                            <span class="icon-star"></span>Featured</a>
                                                                    </li>
                                                                    <?
                                                                    }
                                                                    ?>
                                                                    
                                                                
                                                                <?
                                                                } elseif ($data_target_s['status'] == 'not-active'){
                                                                ?>
                                                                    <li class="dropdown-item">
                                                                        <a href="<?= $config['web']['base_url']; ?>edit/<?= $data_target_s['id']?>">
                                                                            <span class="icon-pencil"></span>Edit</a>
                                                                    </li>
                                                                    <li class="dropdown-item">
                                                                        <a href="<?= $config['web']['base_url']; ?>status/<?= $data_target_s['id']; ?>/active">
                                                                            <span class="icon-eye"></span>Tampilkan</a>
                                                                    </li>  
                                                                    <li class="dropdown-item">
                                                                        <a href="<?= $config['web']['base_url']; ?>deleted/<?= $data_target_s['id']; ?>/1" class="delete">
                                                                            <span class="icon-trash"></span>Delete</a>
                                                                    </li>
                                                                
                                                                <?    
                                                                } elseif ($data_target_s['status'] == 'delete' && $data_target_s['deleted'] == '1'){
                                                                ?>
                                                                    <li class="dropdown-item">
                                                                    <a href="<?= $config['web']['base_url']; ?>deleted/<?= $data_target_s['id']; ?>/0" class="delete">
                                                                        <span class="icon-trash"></span>Undo Delete</a>
                                                                </li>
                                                                <?
                                                                } elseif($data_target_s['status'] == 'pending' OR $data_target_s['status'] == 'revisi'){
                                                                ?>
                                                                    <li class="dropdown-item">
                                                                        <a href="<?= $config['web']['base_url']; ?>edit/<?= $data_target_s['id']?>">
                                                                            <span class="icon-pencil"></span>Edit</a>
                                                                    </li>
                                                                    <li class="dropdown-item">
                                                                        <a href="<?= $config['web']['base_url']; ?>deleted/<?= $data_target_s['id']; ?>/1" class="delete">
                                                                            <span class="icon-trash"></span>Delete</a>
                                                                    </li>
                                                                
                                                                <?
                                                                }
                                                                ?>
                                                            </ul>
                                                        </div>
                                                    </div>
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
                                                <ul class="product-facts clearfix">
                                                    <?
                                                    if($data_target_s['premium'] == '1'){
                                                       ?>
                                                       <li class="product_cat">
                                                        <h5><span class="badge badge-success"> Premium </span></h5>
                                                        </li>
                                                       <?
                                                   }
                                                   if($data_target_s['featured'] == '1'){
                                                       ?>
                                                       <li class="product_cat">
                                                        <h5><span class="badge badge-success"> Featured </span></h5>
                                                        </li>
                                                       <?
                                                   }
                                                    ?>
                                                    
                                                    <li class="product_cat">
                                                    <h5><span class="badge badge-<?= $label ?>"> <?= $status ?> </span></h5> 
                                                    </li>
                                                </ul>
                                                <?
                                                    if($data_target_s['status'] == 'revisi'){
                                                    ?>
                                                    <span>Admin: <?=$data_target_s['ket_revisi']?></span>
                                                    <?
                                                    }
                                                    ?>
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
                                <a class="page-link" href="<?= $config['web']['base_url']; ?>my-product/<?= $pageActive - 1 ?>" aria-label="Previous">
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
                                <li class="page-item active"><a class="page-link" href="<?= $config['web']['base_url']; ?>my-product/<?= $i; ?>"><?= $i ?></a></li>
                                <? } else {?>
                                <li class="page-item"><a class="page-link" href="<?= $config['web']['base_url']; ?>my-product/<?= $i; ?>"><?= $i ?></a></li>
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
                                <a class="page-link" href="<?= $config['web']['base_url']; ?>my-product/<?= $pageActive + 1 ?>" aria-label="Next">
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