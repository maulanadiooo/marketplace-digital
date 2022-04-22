<?php
require '../web.php';
require '../lib/is_login.php';
require '../lib/csrf_token.php';
$website = $model->db_query($db, "*", "website", "id = '1'");
if(isset($_COOKIE['token_login'])){
    
    $cookie_token = $_COOKIE['token_login'];
    $lgid = $_COOKIE['lgid'];
    $check_user = $model->db_query($db, "*", "user", "token_login = '$cookie_token'");
    if ($check_user['count'] == 1) {
        
        $_SESSION['login'] = $check_user['rows']['id'];

    } else {
		unset($_COOKIE['token_login']);
	    setcookie('token_login', NULL, -1);
	}
     
}

if (isset($_SESSION['login'])) {
	exit(header("Location: ".$config['web']['base_url']));
}

if(isset($_GET['redirect'])){
    $redirect = mysqli_real_escape_string($db, $_GET['redirect']);
}

$title = "Sign In";
require '../template/header.php';

?>


<section class="login_area section--padding">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 offset-lg-3 col-md-8 offset-md-2">
                    <form action="<?= $config['web']['base_url']; ?>signin/action.php" method ="post">
                        <input type="hidden" name="csrf_token" value="<?php echo $config['csrf_token'] ?>">
                        <div class="cardify login">
                            <div class="login--header">
                                <h3>Selamat Datang Kembali</h3>
                                <p>Gunakan Email Untuk Masuk</p>
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
                                    <label for="user_name">Email</label>
                                    <input id="user_name" name="email" type="email" class="text_field" placeholder="Email Address">
                                </div>
                                <div class="form-group">
                                    <label for="pass">Password</label>
                                    <input id="pass" name="password" type="password" class="text_field" placeholder="Masukkan Password...">
                                </div>
                                <div class="form-group">
                                    <div class="custom_checkbox">
                                        <input type="checkbox" id="ch2" name="rememberme">
                                        <label for="ch2">
                                            <span class="shadow_checkbox"></span>
                                            <span class="label_text">Remember me</span>
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
                                
                                <button class="btn btn--md btn-primary" type="submit">Masuk</button>
                                <div class="login_assist">
                                    <p class="recover">Lupa
                                        <a href="<?=$config['web']['base_url']?>reset_password/">password</a> ?</p>
                                    <p class="signup">Tidak Punya 
                                        <a href="<?= $config['web']['base_url']."signup/"; ?>">Akun</a> ?</p>
                                </div>
                                
                                <input type="hidden" name="redirect" value="<?=$redirect?>">
                            </div><!-- end .login--form -->
                        </div><!-- end .cardify -->
                    </form>
                </div><!-- end .col-md-6 -->
            </div><!-- end .row -->
        </div><!-- end .container -->
    </section><!-- ends: .login_area -->
    <script src='https://www.google.com/recaptcha/api.js'></script>
<?php
require '../template/footer.php';

?>