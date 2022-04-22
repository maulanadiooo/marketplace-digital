<?php
require '../web.php';
require '../lib/is_login.php';
require '../lib/result.php';
if (!isset($_SESSION['login'])) {
	$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Otentikasi dibutuhkan!', 'msg' => 'Silahkan masuk ke akun Anda.');
	exit(header("Location: ".$config['web']['base_url']."signin/"));
}
$title = "Notifikasi";
?>
<?php


$dataPerHalaman = 15;

$data_targeta = mysqli_query($db, "SELECT * FROM notifikasi WHERE seller_id = '".$login['id']."' ORDER BY created_at DESC ");

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


$data_target = mysqli_query($db, "SELECT * FROM notifikasi WHERE seller_id = '".$login['id']."' ORDER BY created_at DESC LIMIT $awalData,$dataPerHalaman");
require '../template/header.php';
require '../template/header-dashboard.php';
?>


<section class="dashboard-area section--padding">
        <div class="dashboard_contents">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="dashboard_title_area">
                            <div class="dashboard__title">
                                <h3>Semua Notifikasi</h3>
                            </div>
                        </div>
                    </div><!-- ends: .col-md-12 -->
                </div><!-- ends: .row -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="cardify notifications_module">
                            <?
                            if(mysqli_num_rows($data_target) == 0){
                                echo "<center><h4>Kamu Belum meiliki Notifikasi Apapun</h4></center>";
                            } else {
                            ?>    
                            
                            <?php
                              while ($data_target_s = mysqli_fetch_assoc($data_target)){
                                  $buyer_info = $model->db_query($db, "*", "user", "id = '".$data_target_s['buyer_id']."'"); 
                                  $service_info = $model->db_query($db, "*", "services", "id = '".$data_target_s['service_id']."'"); 
                                  if($data_target_s['type'] == 'pembelian'){
                                      $status = 'Membeli Produk';
                                  } elseif ($data_target_s['type'] == 'pesan'){
                                      $status = 'Mengirimkan Pesan Kepada Anda';
                                  } elseif ($data_target_s['type'] == 'favorit'){
                                      $status = 'Memfavoritkan Produk';
                                  } elseif ($data_target_s['type'] == 'unfavorit'){
                                      $status = 'Membatalkan Favorit Produk';
                                  } elseif ($data_target_s['type'] == 'review'){
                                      $status = 'Pesanan Anda Telah Direview';
                                  } elseif ($data_target_s['type'] == 'pesanan-success'){
                                      $status = 'Pesanan Anda Dikirim';
                                  } elseif ($data_target_s['type'] == 'pesanan-perbarui'){
                                      $status = 'Pesanan Anda Diperbarui';
                                  } elseif ($data_target_s['type'] == 'pesanan-cancel'){
                                      $status = 'Pesanan Anda Ditolak';
                                  }
                                  
                                  
                                  $format = $data_target_s['go'];
                                $pisah = explode("/", $format);
                                $idOrderan = $pisah[1];
                            ?>
                            <?
                            if($data_target_s['read_by_user'] == 0){
                            ?>
                            <div class="notification notification__unread">
                            <?
                            } else {
                            ?>
                            <div class="notification">
                            <?
                            }
                            ?>
                                <div class="notification__info">
                                    <div class="info_avatar">
                                        <img src="img/notification_head3.png" alt="">
                                    </div>
                                    <div class="info">
                                        <p>
                                            <span><?=$buyer_info['rows']['username']?></span>
                                            <a href="<?= $config['web']['base_url'] ?><?=$data_target_s['go']?>">#<?=$idOrderan?> <?=$status?></a>
                                            <?
                                            if($data_target_s['service_id'] != 0){
                                            echo $service_info['rows']['nama_layanan'];    
                                            }
                                            ?>
                                        </p>
                                        <p class="time"><?= format_date(substr($data_target_s['created_at'], 0, -9)).", ".substr($data_target_s['created_at'], 11, -3)?> UTC +7</p>
                                    </div>
                                </div><!-- ends: .notifications -->
                            </div><!-- ends: .notifications -->
                            <?
                            }
                            ?>
                            
                            <?    
                            }
                            ?>
                            
                            <!-- Start Pagination -->
                            <!-- Start Pagination -->
                            <nav class="pagination-default">
                                    <ul class="pagination">
                                        <? if($pageActive > 1){ 
                                        ?>
                                        <li class="page-item">
                                            <a class="page-link" href="<?= $config['web']['base_url']; ?>notifikasi/<?= $pageActive - 1 ?>" aria-label="Previous">
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
                                        for($i = 1; $i <= $jumlahHalaman; $i++){
                                        ?>   
                                            
                                            <? if ($i == $pageActive){?>
                                            <li class="page-item active"><a class="page-link" href="<?= $config['web']['base_url']; ?>notifikasi/<?= $i; ?>"><?= $i ?></a></li>
                                            <? } else {?>
                                            <li class="page-item"><a class="page-link" href="<?= $config['web']['base_url']; ?>notifikasi/<?= $i; ?>"><?= $i ?></a></li>
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
                                            <a class="page-link" href="<?= $config['web']['base_url']; ?>notifikasi/<?= $pageActive + 1 ?>" aria-label="Next">
                                                <span aria-hidden="true"><i class="fa fa-long-arrow-right"></i></span>
                                                <span class="sr-only">Next</span>
                                            </a>
                                        </li>
                                        <?
                                        }?>
                                        
                                    </ul>
                                </nav><!-- Ends: .pagination-default -->
                        </div><!-- ends: .notifications_modules -->
                    </div><!-- ends: .col-md-12 -->
                </div><!-- ends: .row -->
            </div><!-- ends: .container -->
        </div><!-- ends: .dashboard_menu_area -->
    </section><!-- ends: .dashboard-area -->
    
<?php
require '../template/footer.php';

?>