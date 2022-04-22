<?php
require '../web.php';
require '../lib/result.php';
require '../lib/csrf_token.php';
if (isset($_SESSION['login'])) {
	exit(header("Location: ".$config['web']['base_url']));
}
$website = $model->db_query($db, "*", "website", "id = '1'");
$title = "Reset Password";
require '../template/header.php';
?>

<section class="pass_recover_area section--padding">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 offset-lg-3">
                    <form action="<?= $config['web']['base_url']; ?>reset_password/action.php" method ="post">
                        <input type="hidden" name="csrf_token" value="<?php echo $config['csrf_token'] ?>">
                        <div class="cardify recover_pass">
                            <div class="login--header">
                                <p>Silahkan Masukan Alamat Email Terdaftar Untuk Melakukan Reset Password</p>
                            </div><!-- end .login_header -->
                            <div class="login--form">
                                <div class="form-group">
                                    <label for="email_ad">Email Address</label>
                                    <input id="email_ad" name="email" type="email" class="text_field" placeholder="Masukkan Alamat Email">
                                </div>
                                <?
                                if($website['rows']['site_key'] != null){
                                ?>
                                <div class="wrap-input100 validate-input">
            					    <center><div class="g-recaptcha" data-sitekey="<?=$website['rows']['site_key']?>"></div></center>
            					</div>
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

?>