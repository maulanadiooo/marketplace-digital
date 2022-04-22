<?php
require '../web.php';
require '../lib/is_login.php';
require '../lib/result.php';
if (!isset($_SESSION['login'])) {
	$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Otentikasi dibutuhkan!', 'msg' => 'Silahkan masuk ke akun Anda.');
	exit(header("Location: ".$config['web']['base_url']."signin/"));
}
if ($model->db_query($db, "*", "user", "id = '".$login['id']."'")['count'] == 0) {
	exit(header("Location: ".$config['web']['base_url']."signin/"));
}


$user_info = $model->db_query($db, "*", "user", "id = '".$login['id']."'");

$dataPerHalaman = 20;

$orders_infoa = mysqli_query($db, "SELECT * FROM orders WHERE seller_id = '".$user_info['rows']['id']."' AND status != 'unpaid'  ORDER BY created_at DESC ");

$jumlahData = mysqli_num_rows($orders_infoa);
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

$orders_infot = mysqli_query($db, "SELECT * FROM orders WHERE seller_id = '".$user_info['rows']['id']."' AND status != 'unpaid' ORDER BY created_at DESC LIMIT $awalData,$dataPerHalaman");





$title = "Pendapatan";
?>
<?php
require '../template/header.php';
require '../template/header-dashboard.php';
?>


        <div class="dashboard_contents dashboard_statement_area section--padding">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="dashboard_title_area">
                            <div class="dashboard__title">
                                <h3>Riwayat Pendapatan</h3>
                            </div>
                        </div>
                    </div><!-- ends: .col-md-12 -->
                </div><!-- ends: .row -->
                <div class="row">
                    <div class="col-lg-6 col-sm-12">
                        <div class="statement_info_card">
                            <div class="info_wrap">
                                <span>
                                    <span class="icon-handbag icon primarybg transparent-bg primary"></span>
                                </span>
                                <div class="info">
                                    <?
                                    $query = mysqli_query($db, "SELECT SUM(price_for_seller) AS total FROM `orders` WHERE seller_id = '".$login['id']."' AND status ='complete' AND kliring = '1'");
                                    $fetch = mysqli_fetch_array($query);
                                    if($fetch['total'] < 1){
                                        $totalPenjualan = '0';
                                    } else {
                                       $totalPenjualan = $fetch['total'];
                                    }
                                    ?>
                                    
                                    <center><p class="primary">Rp <?= number_format($totalPenjualan,0,',','.') ?></p>
                                    <span>Total Penjualan</span> <br> <h15 class="primary">Sudah melewati masa kliring</h15></center>
                                </div>
                            </div><!-- end .info_wrap -->
                        </div><!-- end .statement_info_card -->
                    </div><!-- end .col-lg-3 -->
                    <div class="col-lg-6 col-sm-12">
                        <div class="statement_info_card">
                            <div class="info_wrap">
                                <span>
                                    <span class="icon-basket-loaded icon secondarybg transparent-bg secondary"></span>
                                </span>
                                <div class="info">
                                    <center><p class="secondary">Rp <?= number_format($user_info['rows']['saldo_tersedia'],0,',','.') ?></p>
                                    <span>Saldo Tersedia</span><br><h15 class="secondary">Saldo yang dapat Ditarik/Dipakai</h15></center>
                                </div>
                            </div>
                            <!-- end .info_wrap -->
                        </div>
                        <!-- end .statement_info_card -->
                    </div>
                </div>
                <div class="row">
                    <!-- end .col-lg-3 -->
                    <div class="col-lg-6 col-sm-12">
                        <div class="statement_info_card">
                            <div class="info_wrap">
                                <span>
                                    <span class="icon-wallet icon mcolorbg3 transparent-bg info"></span>
                                </span>
                                <div class="info">
                                    <?
                                    $query_kliring = mysqli_query($db, "SELECT SUM(price_for_seller) AS total FROM `orders` WHERE seller_id = '".$login['id']."' AND status = 'complete' AND kliring = '0'");
                                    $fetch_kliring = mysqli_fetch_array($query_kliring);
                                    if($fetch_kliring['total'] < 1){
                                        $totalKliring = '0';
                                    } else {
                                       $totalKliring = $fetch_kliring['total'];
                                    }
                                    ?>
                                    <center><p class="info">Rp <?= number_format($totalKliring,0,',','.') ?></p>
                                    <span>Menunggu Kliring</span><br><h15 class="info">Proses Kliring 3 Hari</h15></center>
                                </div>
                            </div><!-- end .info_wrap -->
                        </div><!-- end .statement_info_card -->
                    </div><!-- end .col-lg-3 -->
                    <div class="col-lg-6 col-sm-12">
                        <div class="statement_info_card">
                            <div class="info_wrap">
                                <span>
                                    <span class="icon-briefcase icon mcolorbg4 transparent-bg danger"></span>
                                </span>
                                <div class="info">
                                    <center><p class="danger">Rp <?= number_format($user_info['rows']['withdraw'],0,',','.') ?></p>
                                    <span>Telah Ditarik</span><br><h15 class="danger">Saldo yang telah masuk ke rekening anda</h15></center>
                                </div>
                            </div><!-- end .info_wrap -->
                        </div><!-- end .statement_info_card -->
                    </div><!-- end .col-lg-3 -->
                </div><!-- end .row -->
                <div class="row">
                    <div class="col-lg-12 col-sm-12">
                        <div class="statement_info_card">
                            <div class="info_wrap">
                                <span>
                                    <span class="icon-wallet icon mcolorbg3 transparent-bg info"></span>
                                </span>
                                <div class="info">
                                    <?
                                    $query_kliring_1 = mysqli_query($db, "SELECT SUM(price_for_seller) AS total FROM `orders` WHERE seller_id = '".$login['id']."' AND status in ('success','active','ditolak','cancel') AND kliring = '0'");
                                    $fetch_kliring_1 = mysqli_fetch_array($query_kliring_1);
                                    if($fetch_kliring_1['total'] < 1){
                                        $totalKliring_1 = '0';
                                    } else {
                                       $totalKliring_1 = $fetch_kliring_1['total'];
                                    }
                                    ?>
                                    <p class="info">Rp <?= number_format($totalKliring_1,0,',','.') ?></p>
                                    <span>Pembayaran Mendatang</span>
                                </div>
                            </div><!-- end .info_wrap -->
                        </div><!-- end .statement_info_card -->
                    </div><!-- end .col-lg-3 -->
                </div>
                    <div class="col-md-12">
                        <div class="statement_table table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Pembeli</th>
                                        <th>Produk</th>
                                        <th>Status</th>
                                        <th>Pendapatan Bersih</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <?php
                                while ($orders_info = mysqli_fetch_assoc($orders_infot)){
                                    if($orders_info['status'] == 'active'){
                                        $statusOrderan = "Aktif";
                                        $label = "purchase";
                                    } elseif($orders_info['status'] == 'success'){
                                        $statusOrderan = "Terkirim";
                                        $label = "credited";
                                    } elseif($orders_info['status'] == 'cancel'){
                                        $statusOrderan = "Dibatalkan";
                                        $label = "withdrawal";
                                    } elseif($orders_info['status'] == 'refunded'){
                                        $statusOrderan = "Refund";
                                        $label = "withdrawal";
                                    } elseif($orders_info['status'] == 'ditolak'){
                                        $statusOrderan = "Revisi";
                                        $label = "withdrawal";
                                    } elseif($orders_info['status'] == 'complete'){
                                        $statusOrderan = "Selesai";
                                        $label = "sale";
                                    } elseif($orders_info['status'] == 'selesai'){
                                        $statusOrderan = "Selesai";
                                        $label = "sale";
                                    }
                                    
                                    $buyer_info = $model->db_query($db, "*", "user", "id = '".$orders_info['buyer_id']."'");
                                    $layananInfo = $model->db_query($db, "*", "services", "id = '".$orders_info['service_id']."'");
                                ?>
                                <tbody>
                                    <tr>
                                        <td><?=$orders_info['id']?></td>
                                        <td class="author"><?=$buyer_info['rows']['username']?></td>
                                        <td class="detail">
                                            <a href="<?= $config['web']['base_url']; ?>show-sales/<?=$orders_info['id']?>"><?=$layananInfo['rows']['nama_layanan']?></a>
                                        </td>
                                        <td class="type">
                                            <span class="<?=$label?>"><?=$statusOrderan?></span>
                                        </td>
                                        
                                        <td class="earning">Rp <?=number_format($orders_info['price_for_seller'],0,',','.')?> ,-</td>
                                        
                                        <td><?= format_date(substr($orders_info['created_at'], 0, -9))?></td>
                                    </tr>
                                </tbody>
                                
                                <?php
                                }
                                ?>
                            </table>
                            <!-- Start Pagination -->
                            <!-- Start Pagination -->
                            <nav class="pagination-default">
                                <ul class="pagination">
                                    <? if($pageActive > 1){ 
                            ?>
                            <li class="page-item">
                                <a class="page-link" href="<?= $config['web']['base_url']; ?>my-revenue/page/<?= $pageActive - 1 ?>" aria-label="Previous">
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
                                <li class="page-item active"><a class="page-link" href="<?= $config['web']['base_url']; ?>my-revenue/page/<?= $i; ?>"><?= $i ?></a></li>
                                <? } else {?>
                                <li class="page-item"><a class="page-link" href="<?= $config['web']['base_url']; ?>my-revenue/page/<?= $i; ?>"><?= $i ?></a></li>
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
                                <a class="page-link" href="<?= $config['web']['base_url']; ?>my-revenue/page/<?= $pageActive + 1 ?>" aria-label="Next">
                                    <span aria-hidden="true"><i class="fa fa-long-arrow-right"></i></span>
                                    <span class="sr-only">Next</span>
                                </a>
                            </li>
                            <?
                            }?>
                                </ul>
                            </nav><!-- Ends: .pagination-default -->
                        </div><!-- ends: .statement_table -->
                    </div>
                </div><!-- ends: .row -->
            </div><!-- ends: .container -->
        </div><!-- ends: .dashboard_menu_area -->
        
<?php
require '../template/footer.php';
?>