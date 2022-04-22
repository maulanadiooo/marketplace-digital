<?php
require '../web.php';
require '../lib/is_login.php';
require '../lib/result.php';
require '../lib/csrf_token.php';
if (!isset($_SESSION['login'])) {
	$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Otentikasi dibutuhkan!', 'msg' => 'Silahkan masuk ke akun Anda.');
	exit(header("Location: ".$config['web']['base_url']."signin/"));
}
if ($model->db_query($db, "*", "user", "id = '".$login['id']."'")['count'] == 0) {
	exit(header("Location: ".$config['web']['base_url']."signin/"));
}
$website = $model->db_query($db, "*", "website", "id = '1'");
$user_info = $model->db_query($db, "*", "user", "id = '".$login['id']."'");
$bank_info = $model->db_query($db, "*", "bank_penarikan", "id = '".$user_info['rows']['id_bank']."'");

$dataPerHalaman = 20;

$wd_infoa = mysqli_query($db, "SELECT * FROM withdraw_request WHERE user_id = '".$login['id']."'  ORDER BY created_at DESC ");

$jumlahData = mysqli_num_rows($wd_infoa);
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

$wd_infot = mysqli_query($db, "SELECT * FROM withdraw_request WHERE user_id = '".$login['id']."' ORDER BY created_at DESC LIMIT $awalData,$dataPerHalaman");


$title = "Withdraw";
?>
<?php
require '../template/header.php';
require '../template/header-dashboard.php';
?>        
        
        <div class="dashboard_contents p-top-100 p-bottom-70">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="dashboard_title_area">
                            <div class="dashboard__title">
                                <h3>Penarikan</h3>
                            </div>
                            <div class="ml-auto add-payment-btn">
                                <a href="<?= $config['web']['base_url']; ?>add-payment/" class="btn btn--md btn-primary"><i class="fa fa-plus-square"></i><span>Add/Edit Payment</span></a>
                            </div>
                        </div><!-- ends: .dashboard_title_area -->
                    </div><!-- ends: .col-md-12 -->
                </div><!-- ends: .row -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="withdraw_module">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="bg-white payment-method-module">
                                        
                                    </div><!-- ends: .payment-method-module -->
                                </div><!-- ends: .col-md-6 -->
                                <div class="col-md-12">
                                    <div class="withdraw_module--amount bg-white m-top-30 p-bottom-30">
                                        <div class="modules__title">
                                            <h4>Jumlah Ditarik</h4>
                                        </div><!-- ends: .modules__title -->
                                        <form action="<?= $config['web']['base_url']; ?>withdraw/request.php" method="post">
                                         <input type="hidden" name="csrf_token" value="<?php echo $config['csrf_token'] ?>">
                                        <div class="modules__content">
                                            <p class="subtitle">Bank Saya</p>
                                            <div class="withdraw_amount">
                                                <div class="input-group">
                                                    <!--<span class="input-group-addon">Rp</span>-->
                                                    <input type="hidden" name="withdraw_bank" class="text_field" value="<?=$user_info['rows']['id_bank'] ?>">
                                                    <input type="text" class="text_field" value="<?=$bank_info['rows']['bank'] ?>" read_only>
                                                </div>
                                            </div>
                                            <div class="withdraw_amount">
                                                <div class="input-group">
                                                    <!--<span class="input-group-addon">Rp</span>-->
                                                    <input type="text" name="withdraw_nama" class="text_field" value="<?=$user_info['rows']['nama_pemilik_bank']?>" read_only>
                                                </div>
                                            </div>
                                            <div class="withdraw_amount">
                                                <div class="input-group">
                                                    <!--<span class="input-group-addon">Rp</span>-->
                                                    <input type="text" name="withdraw_norek" class="text_field" value="<?= decrypt($user_info['rows']['no_rek'])?>" read_only>
                                                </div>
                                            </div>
                                            <p class="subtitle">Permintaan Penarikan, Min : Rp <?= number_format($website['rows']['min_wd'],0,',','.') ?></p>
                                            <div class="options">
                                                <div class="custom-radio">
                                                    <input type="radio" id="opt4" class="" name="filter_opt" value="<?=$user_info['rows']['saldo_tersedia']?>">
                                                    <label for="opt4">
                                                        <span class="circle"></span>Seluruh Saldo:
                                                        <span class="bold color-primary">Rp <?= number_format($user_info['rows']['saldo_tersedia'],0,',','.') ?></span>
                                                    </label>
                                                </div>
                                                <div class="custom-radio">
                                                    <input type="radio" id="opt5" class="" name="filter_opt">
                                                    <label for="opt5">
                                                        <span class="circle"></span>Sebagian ..</label>
                                                </div>
                                                <div class="withdraw_amount" id="partial_amount">
                                                    <div class="input-group">
                                                        <!--<span class="input-group-addon">Rp</span>-->
                                                        <input type="number" name="amount" id="rlicense" class="text_field" placeholder="<?= number_format($user_info['rows']['saldo_tersedia'],0,',','.') ?>">
                                                    </div>
                                                </div>
                                                <span class="fee">Note : Pastikan Nama dan No Rekening Sesuai.</span><br>
                                                <span>Penarikan Hanya Dilakukan Pada hari Senin - Jumat</span>
                                            </div>
                                            <div class="button_wrapper">
                                                <button type="submit" class="btn btn-md btn-primary">Submit
                                                </button>
                                            </div>
                                        </div>
                                        </form>
                                    </div><!-- ends: .withdraw_module--amount -->
                                </div><!-- ends: .col-md-6 -->
                            </div><!-- ends: .row -->
                        </div><!-- ends: .withdraw_module -->
                    </div><!-- ends: .col-md-12 -->
                </div><!-- ends: .row -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="withdraw_module withdraw_history bg-white">
                            <div class="withdraw_table_header">
                                <h4>Riwayat Penarikan</h4>
                            </div>
                            <div class="table-responsive">
                                <table class="table withdraw__table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Tanggal Permintaan</th>
                                            <th>Metode Penarikan</th>
                                            <th>Jumlah</th>
                                            <th>Estimasi Penarikan</th>
                                            <th>Status</th>
                                            <th>Keterangan</th>
                                        </tr>
                                    </thead>
                                    <?
                                    while ($wd_info = mysqli_fetch_assoc($wd_infot)){
                                        $nama_bank = $model->db_query($db, "*", "bank_penarikan", "id = '".$wd_info['bank']."'");
                                        if($wd_info['status'] == 'pending'){
                                            $status = "Proses";
                                            $label = 'warning';
                                        } elseif ($wd_info['status'] == 'success'){
                                            $status = "Berhasil";
                                            $label = 'success';
                                        } elseif($wd_info['status'] == 'error'){
                                            $status = "Cancel/Error";
                                            $label = 'danger';
                                        }
                                        
                                    ?>
                                    <tbody>
                                        <tr>
                                            <td><?=$wd_info['id']?></td>
                                            <td><?= format_date(substr($wd_info['created_at'], 0, -9))?></td>
                                            <td><?=$nama_bank['rows']['bank']?> | <?=$wd_info['nama_pemilik']?> | <?=decrypt($wd_info['no_rek'])?></td>
                                            <td class="bold">Rp <?=number_format($wd_info['amount'],0,',','.')?> ,-</td>
                                            <td><?= format_date(substr($wd_info['estimasi_wd'], 0, -9))?></td>
                                            <td>
                                                <span class="badge bg-<?=$label?>"><?=$status?></span>
                                            </td>
                                            <?
                                            if($wd_info['status'] ==  'error'){
                                            ?>
                                            <td><?=$wd_info['ket_error']?></td>
                                            <?
                                            } else {
                                            ?>
                                            <td></td>
                                            <?
                                            }
                                            ?>
                                        </tr>
                                    </tbody>
                                    <?
                                    }
                                    ?>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
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
            </div><!-- ends: .container -->
        </div><!-- ends: .dashboard_menu_area -->
        
        

<?php
require '../template/footer.php';
?>