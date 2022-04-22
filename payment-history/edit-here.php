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

$orders_infoa = mysqli_query($db, "SELECT * FROM history_pembayaran WHERE user_id = '".$login['id']."'  ORDER BY created_at DESC ");

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

$orders_infot = mysqli_query($db, "SELECT * FROM history_pembayaran WHERE user_id = '".$login['id']."' ORDER BY created_at DESC LIMIT $awalData,$dataPerHalaman");





$title = "Pembayaran";
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
                                <h3>Riwayat Pembayaran</h3>
                            </div>
                        </div>
                    </div><!-- ends: .col-md-12 -->
                </div><!-- ends: .row -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="statement_table table-responsive">
                            <table class="table">
                                <?
                                if(mysqli_num_rows($orders_infot) == 0){
                                ?>
                                <center><h4>Kamu Belum Memiliki Riawayat Pembayaran Apapun</h4></center>
                                <?    
                                } else {
                                ?>    
                                
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Jumlah</th>
                                        <th>Ket</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <?php
                                while ($orders_info = mysqli_fetch_assoc($orders_infot)){
                                    
                                    
                                    $buyer_info = $model->db_query($db, "*", "user", "id = '".$orders_info['buyer_id']."'");
                                    $layananInfo = $model->db_query($db, "*", "services", "id = '".$orders_info['service_id']."'");
                                ?>
                                <tbody>
                                    <tr>
                                        <td><?=$orders_info['id']?></td>
                                        <td>Rp <?= number_format($orders_info['amount'],0,',','.') ?></td>
                                        <td><?=$orders_info['message']?></td>
                                        <td><?= format_date(substr($orders_info['created_at'], 0, -9))?>
                                    </tr>
                                </tbody>
                                
                                <?php
                                }  
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
                                <a class="page-link" href="<?= $config['web']['base_url']; ?>payment-history/<?= $pageActive - 1 ?>" aria-label="Previous">
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
                                <li class="page-item active"><a class="page-link" href="<?= $config['web']['base_url']; ?>payment-history/<?= $i; ?>"><?= $i ?></a></li>
                                <? } else {?>
                                <li class="page-item"><a class="page-link" href="<?= $config['web']['base_url']; ?>payment-history/<?= $i; ?>"><?= $i ?></a></li>
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
                                <a class="page-link" href="<?= $config['web']['base_url']; ?>payment-history/<?= $pageActive + 1 ?>" aria-label="Next">
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