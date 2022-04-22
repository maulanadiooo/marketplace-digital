<?php
require '../web.php';
require '../lib/result.php';
require '../lib/csrf_token.php';
$website = $model->db_query($db, "*", "website", "id = '1'");
if (isset($_GET['token']) && isset($_GET['mail'])) {
    if (isset($_SESSION['login'])) {
    	exit(header("Location: ".$config['web']['base_url']));
    }
    $get_token = mysqli_real_escape_string($db, trim(stripslashes(strip_tags(htmlspecialchars($_GET['token'])))));
    $get_mail = encrypt(mysqli_real_escape_string($db, trim(stripslashes(strip_tags(htmlspecialchars($_GET['mail']))))));
    $check_users = mysqli_query($db, "SELECT * FROM user WHERE email = '$get_mail' AND status = 'Verified'");
    
    $data_user = mysqli_fetch_assoc($check_users);

    $cek_kode = password_verify($get_token, $data_user['reset_link']);
    if($cek_kode == false){
        $_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal', 'msg' => 'Link Yang Kamu Masukkan Tidak Sah Atau Sudah Digunakan');
        exit(header("Location: ".$config['web']['base_url'])); 
    }

$title = "New Password";
require '../template/header.php';
?>

<section class="pass_recover_area section--padding">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 offset-lg-3">
                    <form action="<?= $config['web']['base_url']; ?>new_password/action.php" method ="post">
                        <input type="hidden" name="csrf_token" value="<?php echo $config['csrf_token'] ?>">
                        <div class="cardify recover_pass">
                            <div class="login--form">
                                <input type="hidden" name="email" value="<?=$get_mail?>">
                                <input type="hidden" name="token" value="<?=$get_token?>">
                                <div class="form-group">
                                    <label for="email_ad">New Password</label>
                                    <input id="email_ad" name="n_password" type="password" class="text_field" placeholder="Masukan Password Baru">
                                </div>
                                <div class="form-group">
                                    <label for="email_ad">Konfirmasi Password</label>
                                    <input id="email_ad" name="c_password" type="password" class="text_field" placeholder="Masukan Password Baru">
                                </div>
                                <?
                                if($website['rows']['site_key'] != null){
                                ?>
                                <div class="wrap-input100 validate-input">
            					    <center><div class="g-recaptcha" data-sitekey="<?=$website['rows']['site_key']?>"></div></center>
            					</div><br>
                                <?
                                }
                                ?>
                                
                                <button class="btn btn--md register_btn btn-primary" type="submit">Submit</button>
                            </div><!-- end .login--form -->
                        </div><!-- end .cardify -->
                    </form>
                </div><!-- end .col-md-6 -->
            </div><!-- end .row -->
        </div><!-- end .container -->
    </section><!-- ends: .pass_recover_area -->
    <script src='https://www.google.com/recaptcha/api.js'></script>
<?php

require '../template/footer.php';
    
}else {
    exit(header("Location: ".$config['web']['base_url'])); 
}

?>