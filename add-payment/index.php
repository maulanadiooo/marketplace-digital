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

$title = "Tambah Rekening";
?>
<?php
require '../template/header.php';
require '../template/header-dashboard.php';
?>    

        <div class="dashboard_contents section--padding">
            <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="payment_module">
                                <div class="modules__title">
                                    <h4>Tambah/Edit Metode Penarikan Anda</h4>
                                </div> 
                                <div class="payment_tabs p-bottom-sm">
                                    <div class="tab-content"> 
                                        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                                            <div class="modules__content">
                                                
                                                <div class="payment_info modules__content">
                                                    <form action ="<?= $config['web']['base_url']; ?>add-payment/request.php" method="post">
                                                    <input type="hidden" name="csrf_token" value="<?php echo $config['csrf_token'] ?>">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="form-group select-wrap">
                                                                <label for="card_name">Metode Penarikan</label>
                                                                <select name="bank" class="text_field">
                                                                    <option value="0">Pilih Salah Satu...</option>
                                                                    <?php
                                									$category_select = $model->db_query($db, "*", "bank_penarikan");
                                									
                                									if ($category_select['count'] == 1) {
                                										print('<option value="'.$category_select['rows']['id'].'">'.$category_select['rows']['bank'].'</option>');
                                									} else {
                                									foreach ($category_select['rows'] as $key) {
                                										print('<option value="'.$key['id'].'">'.$key['bank'].'</option>');
                                									}
                                									}
                                									?>
                                                                </select>
                                                                <span class="lnr icon-arrow-down"></span>
                                                            </div>
                                                        </div><!-- ends: .col-md-12 -->
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="card_name">Nama Pemilik</label>
                                                                <input id="card_name" type="text" name="nama_pemilik" class="text_field" placeholder="Nama Pemilik Bank">
                                                            </div><!-- ends: .col-md-6 -->
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="card_number">No Rekening/Email Paypal</label>
                                                                <input id="card_number" type="text" name="no_rek" class="text_field" placeholder="No Rekening">
                                                            </div>
                                                        </div><!-- ends: .col-md-6 -->
                                                        <div class="col-md-12">
                                                            <a href="<?=$config['web']['base_url']?>withdraw/" class="btn btn--md btn-success">Kembali</a>
                                                            <button type="submit" class="btn btn--md btn-primary">Submit</button>
                                                        </div>
                                                    </div>
                                                    </form>
                                                </div><!-- ends: .payment_info -->
                                            </div><!-- ends: .modules__content -->
                                        </div>
                                    </div>
                                </div><!-- ends: .payment_tabs -->
                            </div><!-- ends: .payment_module -->
                        </div><!-- ends: .col-md-12 -->
                    </div><!-- ends: .row -->
            </div><!-- ends: .container -->
        </div><!-- ends: .dashboard_contents -->

<?php
require '../template/footer.php';
?>