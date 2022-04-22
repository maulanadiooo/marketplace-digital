<?php
require '../web.php';
require '../lib/is_login.php';
require '../lib/result.php';
require '../lib/csrf_token.php';

if (!isset($_SESSION['login'])) {
	$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Otentikasi dibutuhkan!', 'msg' => 'Silahkan masuk ke akun Anda.');
	exit(header("Location: ".$config['web']['base_url']."signin/"));
}
if ($model->db_query($db, "*", "user", "id = '".$_SESSION['login']."'")['count'] == 0) {
	exit("No direct script access allowed!!");
}
                                    
                                    
$title = "Add Balance";

require '../template/header.php';
require '../template/header-dashboard.php';

?>  

<div class="dashboard_contents section--padding">
            <div class="container">
                <form action="<?=$config['web']['base_url']?>add-balance/action.php" method="post">
                    <input type="hidden" name="csrf_token" value="<?php echo $config['csrf_token'] ?>">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="credit_modules">
                                <div class="modules__title">
                                    <h4>Penambahakan Saldo</h4>
                                </div>
                                <div class="modules__content credit--contents">
                                    
                                    <div>
                                        <p class="subtitle">Jumlah</p>
                                        <div class="custom_amount">
                                            <div class="input-group">
                                                <input type="number" name="add_balance" id="rlicense" class="text_field" placeholder="Minimal Rp <?= number_format($website['rows']['min_depo'],0,',','.') ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- ends: .modules__content -->
                            </div><!-- ends: .credit_modules -->
                        </div><!-- ends: .col-md-12 -->
                        <div class="col-md-12">
                            <div class="payment_module">
                                <div class="information_module payment_options">
                                
                                    <div class="toggle_title">
                                        <h4>Pilih Metode Pembayaran</h4><br>
                                        <p><font color="red">Tidak dapat melakukan deposit lebih dari 1x jika deposit sebelumnya belum berhasil</font></p>
                                    </div>
                                    <ul>
                                        <?
                                        $data_target = mysqli_query($db, "SELECT * FROM bank_information WHERE deposit = '1' ");
                                        while ($data_target_s = mysqli_fetch_assoc($data_target)){
                                        ?>
                                        <li>
                                            <div class="custom-radio">
                                                <input type="radio" id="opt<?=$data_target_s['id']?>" class="" value="<?=$data_target_s['id']?>" name="filter_opt">
                                                <label for="opt<?=$data_target_s['id']?>">
                                                    <span class="circle"></span><?=$data_target_s['bank']?></label>
                                            </div>
                                        </li>
                                        <?
                                        }
                                        ?>
                                    </ul>
                                    <div class="payment_info modules__content">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <button type="submit" class="btn btn--md btn-primary">Submit</button>
                                        </div>
                                    </div>
                                    </div>
                                </div><!-- ends: .information_module-->
                            </div><!-- ends: .payment_module -->
                        </div><!-- ends: .col-md-12 -->
                    </div><!-- ends: .row -->
                </form><!-- ends: .add_credit_form -->
            </div><!-- ends: .container -->
        </div><!-- ends: .dashboard_contents -->
    </section><!-- ends: .dashboard-area -->
    
<?php
require '../template/footer.php';
?>