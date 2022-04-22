<?php
require '../web.php';

include_once '../lib/csrf_token.php';
if (isset($_SESSION['login'])) {
	exit(header("Location: ".$config['web']['base_url']));

}
$website = $model->db_query($db, "*", "website", "id = '1'");
$title = "Daftar";
?>
<?php
require '../template/header.php';

?>


<section class="signup_area section--padding">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 offset-lg-3">
                    <form action="<?= $config['web']['base_url']; ?>signup/action.php" method="post">
                        <div class="cardify signup_form">
                            <div class="login--header">
                                <h3>Buat Akun Baru</h3>
                                <p>Harap Mengisi Semua Form Dengan Benar
                                </p>
                            </div><!-- end .login_header -->
                            <?php
                                if (isset($_SESSION['result'])) {
                                ?>
                                					<div class="alert alert-<?php echo $_SESSION['result']['alert'] ?> alert-dismissable">
                                						<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                						<b><?php echo $_SESSION['result']['title'] ?></b> <?php echo $_SESSION['result']['msg'] ?>
                                					</div>
                                                    <?php
                                                    if ($_SESSION['result']['alert'] == "danger") {
                                                    ?>
                                                    <script>
                                                        Swal.fire({
                                                            type: "error",
                                                            title: "<?php echo $_SESSION['result']['title'] ?>",
                                                            html: "<?php echo $_SESSION['result']['msg'] ?>",
                                                            confirmButtonClass: "btn btn-confirm mt-2"
                                                        })
                                                    </script>
                                                    <?php
                                                    } else {
                                                    ?>
                                                    <script>
                                                        Swal.fire({
                                                            type: "success",
                                                            title: "<?php echo $_SESSION['result']['title'] ?>",
                                                            html: "<?php echo $_SESSION['result']['msg'] ?>",
                                                            confirmButtonClass: "btn btn-confirm mt-2"
                                                        })
                                                    </script>
                                                    <?php
                                                    }
                                                    ?>
                                <?php
                                unset($_SESSION['result']);
                                }
                                ?>
                            <div class="login--form">
                                <div class="form-group">
                                    <label for="urname">Nama <sup>*</sup></label> 
                                    <input name="full_name" id="urname" type="text" class="text_field" placeholder="Enter your Name" value="<?= $_SESSION['full_name'] ?>">
                                </div>
                                <div class="form-group">
                                    <label for="email_ad">Email Address <sup>*</sup></label>
                                    <input name="email" id="email_ad" type="email" class="text_field" placeholder="Enter your email address" value="<?= $_SESSION['email'] ?>">
                                </div>
                                <div class="form-group">
                                    <label for="email_ad">Whatsapp Number <sup>*</sup></label>
                                    <input name="no_hp"  type="number" class="text_field" placeholder="Nomor Whatsapp Aktif" value="<?= $_SESSION['no_hp'] ?>">
                                </div>
                                <div class="form-group">
                                    <label for="user_name">Username <sup>*</sup></label>
                                    <input name="username" id="user_name" type="text" class="text_field" minlength="5" placeholder="Enter your username..." required oninvalid="this.setCustomValidity('Username Minimal 5 Karakter')" oninput="setCustomValidity('')" value="<?= $_SESSION['username'] ?>">
                                </div>
                                <div class="form-group">
                                    <label for="password">Password <sup>*</sup></label>
                                    <input name="password" id="password" type="password" class="text_field" minlength="5" placeholder="Enter your password..." required oninvalid="this.setCustomValidity('Password Minimal 5 Karakter')" oninput="setCustomValidity('')">
                                </div>
                                <div class="form-group">
                                    <label for="con_pass">Konfirmasi Password <sup>*</sup></label>
                                    <input name="co_password" id="con_pass" type="password" class="text_field" placeholder="Confirm password">
                                </div>
                                <div class="form-group">
                                    
                                    <label>Setuju <a href="<?=$config['web']['base_url']?>term-condition">Syarat & Ketentuan <sup>*</sup></a></label>
                                    <div class="custom_checkbox checkbox-outline">
                                        <span class="check-confirm" data-text-swap="Ya" data-text-original="Tidak">Tidak</span>
                                        <label class="toggle-switch"> <sup>*</sup>
                                            <input type="checkbox" name="accept_terms" >
                                            <span class="slider round"></span>
                                        </label>
                                    </div>
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
                                
                                <br><br>
                                <button class="btn btn--md register_btn btn-primary" type="submit">Daftar Sekarang</button>
                                
                                <div class="login_assist">
                                    <p>Sudah Punya Akun ?
                                        <a href="<?= $config['web']['base_url']; ?>signin/">Masuk</a>
                                    </p>
                                </div>
                            </div><!-- end .login--form -->
                        </div><!-- end .cardify -->
                    </form>
                </div><!-- end .col-md-6 -->
            </div><!-- end .row -->
        </div><!-- end .container -->
    </section><!-- ends: .signup_area -->
    <script src='https://www.google.com/recaptcha/api.js'></script>
    
<?php
require '../template/footer.php';

?>