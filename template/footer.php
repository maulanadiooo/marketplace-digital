<?php

?>
<!--Modal Sigin-->
    <div class="modal fade" id="modalSayaSignIn" tabindex="-1" role="dialog" aria-labelledby="modalSayaLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
        
        <form action="<?= $config['web']['base_url']; ?>signin/action_modal.php" method="post">
        <input type="hidden" name="redirect_modal" value="<?php echo $url_request ?>">
          <div class="cardify login">
            <div class="login--header">
                <h3>Selamat Datang Kembali</h3>
                <p>Gunakan Email Untuk Masuk</p>
            </div><!-- end .login_header -->
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
                
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
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
          <script src='https://www.google.com/recaptcha/api.js'></script>
        </div>
      </div>
    </div>
<footer class="footer-area footer--light">
        <div class="footer-big">
            <!-- start .container -->
            <div class="container">
                <div class="row">
                    <div class="col-lg-3 col-sm-6">
                        <div class="footer-widget">
                            <div class="widget-about">
                                <img src="<?= $config['web']['base_url']; ?>file-photo/website/<?=$website['rows']['logo_web']?>" alt="<?=$website['rows']['title']?>" class="img-fluid" width="162px" height="50px">
                                <p><?=$website['rows']['description']?></p>
                                
                            </div>
                        </div>
                        <!-- Ends: .footer-widget -->
                    </div>
                    <!-- end /.col-md-4 -->
                    <!-- end /.col-md-3 -->
                    <div class="col-lg-3 col-sm-6">
                        <div class="footer-widget">
                            <div class="footer-menu">
                                <h5 class="footer-widget-title">Our Company</h5>
                                <ul>
                                    <li>
                                        <a href="<?=$config['web']['base_url']?>about">About Us</a>
                                    </li>
                                    <li>
                                        <a href="<?=$config['web']['base_url']?>term-condition">Syarat & Ketentuan</a>
                                    </li>
                                    <li>
                                        <a href="<?=$config['web']['base_url']?>privacy">Kebijakan Privasi</a>
                                    </li>
                                </ul>
                            </div>
                            <!-- end /.footer-menu -->
                        </div>
                        <!-- Ends: .footer-widget -->
                    </div>
                    <!-- end /.col-lg-3 -->
                    <div class="col-lg-2 col-sm-6">
                        <div class="footer-widget">
                            <div class="footer-menu no-padding">
                                <h5 class="footer-widget-title">Bantuan</h5>
                                <ul>
                                    <li>
                                        <a href="<?=$config['web']['base_url']?>how-it-works">Cara Kerja</a>
                                    </li>
                                    <li>
                                        <a href="<?=$config['web']['base_url']?>badges/">Badge</a>
                                    </li>
                                    
                                </ul>
                            </div>
                            <!-- end /.footer-menu -->
                        </div>
                        <!-- Ends: .footer-widget -->
                    </div>
                    
                    <div class="col-lg-4 col-sm-6">
                        <div class="footer-widget">
                            <div class="footer-menu footer-menu--1">
                                <h5 class="footer-widget-title">Hubungi Kami</h5>
                                <ul class="contact-details">
                                    <?
                                    if($website['rows']['no_hp'] != 0){
                                    ?>
                                    <li>
                                        <span class="fa fa-whatsapp"></span>
                                        <a href="https://wa.me/<?=$website['rows']['no_hp']?>">+<?=$website['rows']['no_hp']?></a>
                                    </li>   
                                    <?
                                    }
                                    ?>
                                    <li>
                                        <span class="icon-envelope-open"></span>
                                        <a href="<?=$config['web']['base_url']?>mailhandler.php"><?=$website['rows']['email_notifikasi']?></a>
                                    </li>
                                </ul>
                            </div>
                            <!-- end /.footer-menu -->
                        </div>
                        <!-- Ends: .footer-widget -->
                    </div>
                    <!-- Ends: .col-lg-3 -->
                </div>
                <style>
				.footer-payment-logo {
					background:#e7e8ed;border-radius:4px;padding:7px 10px;display:inline-block;min-width:55px;text-align:center;margin:5px 0px;
				}
				.footer-payment-logo img {
					height:100%;max-height:40px !important;
					width:100%;max-width:100px !important;
				}
			    </style>
                <div class="row">
                    
                 <center>
                <div class="footer-payment-logo">
                  
                      <img src="<?=$config['web']['base_url']?>img/bca.png" class="d-block w-70"  alt="Bca">
                </div>
                <div class="footer-payment-logo">
                  
                      <img src="<?=$config['web']['base_url']?>img/bni.png" class="d-block w-70" alt="BNI">
                </div>
                <div class="footer-payment-logo">
                  
                      <img src="<?=$config['web']['base_url']?>img/bri.png" class="d-block w-70"  alt="BRI">
                </div>
                <div class="footer-payment-logo">
                  
                      <img src="<?=$config['web']['base_url']?>img/mandiri.png" class="d-block w-70" alt="Mandiri">
                </div>
                <div class="footer-payment-logo">
                  
                      <img src="<?=$config['web']['base_url']?>img/qris.png" class="d-block w-70" alt="qris">
                </div>
                <div class="footer-payment-logo">
                  
                      <img src="<?=$config['web']['base_url']?>img/alfamart.png" class="d-block w-70" alt="alfamart">
                </div>
                <div class="footer-payment-logo">
                  
                      <img src="<?=$config['web']['base_url']?>img/indomaret.png" class="d-block w-70" alt="indomaret">
                </div>
                <div class="footer-payment-logo">
                  
                      <img src="<?=$config['web']['base_url']?>img/btc.png" class="d-block w-70"  alt="bitcoin">
                </div>
                <div class="footer-payment-logo">
                  
                      <img src="<?=$config['web']['base_url']?>img/eth.png" class="d-block w-70" alt="Ethereum">
                </div>
                <div class="footer-payment-logo">
                  
                      <img src="<?=$config['web']['base_url']?>img/paypal.png" class="d-block w-70"   alt="paypal">
                </div>
                <div class="footer-payment-logo">
                  
                      <img src="<?=$config['web']['base_url']?>img/pm.png" class="d-block w-70" alt="perfect money">
                </div>
                </center>
                </div>
                <!-- end /.row -->
            </div>
            <!-- end /.container -->
        </div>
        <!-- end /.footer-big -->
        <div class="mini-footer">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="copyright-text">
                            <p>&copy; <?=date('Y')?>
                                <a href="<?=$config['web']['base_url']?>"><?=$website['rows']['title']?></a>. All rights reserved.
                            </p>
                        </div>
                        <div class="go_top">
                            <span class="icon-arrow-up"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!--<script src="http://maps.googleapis.com/maps/api/js?key=AIzaSyDxflHHc5FlDVI-J71pO7hM1QJNW1dRp4U"></script>-->
    <!-- inject:js-->
    <script src="<?= $config['web']['base_url']; ?>vendor_assets/js/jquery/jquery-1.12.4.min.js"></script>
    <script src="<?= $config['web']['base_url']; ?>vendor_assets/js/jquery/uikit.min.js"></script>
    <script src="<?= $config['web']['base_url']; ?>vendor_assets/js/bootstrap/popper.js"></script>
    <script src="<?= $config['web']['base_url']; ?>vendor_assets/js/bootstrap/bootstrap.min.js"></script>
    <script src="<?= $config['web']['base_url']; ?>vendor_assets/js/chart.bundle.min.js"></script>
    <script src="<?= $config['web']['base_url']; ?>vendor_assets/js/grid.min.js"></script>
    <script src="<?= $config['web']['base_url']; ?>vendor_assets/js/jquery-ui.min.js"></script>
    <script src="<?= $config['web']['base_url']; ?>vendor_assets/js/jquery.barrating.min.js"></script>
    <script src="<?= $config['web']['base_url']; ?>vendor_assets/js/jquery.countdown.min.js"></script>
    <script src="<?= $config['web']['base_url']; ?>vendor_assets/js/jquery.counterup.min.js"></script>
    <script src="<?= $config['web']['base_url']; ?>vendor_assets/js/jquery.easing1.3.js"></script>
    <script src="<?= $config['web']['base_url']; ?>vendor_assets/js/jquery.magnific-popup.min.js"></script>
    <script src="<?= $config['web']['base_url']; ?>vendor_assets/js/owl.carousel.min.js"></script>
    <script src="<?= $config['web']['base_url']; ?>vendor_assets/js/select2.full.min.js"></script>
    <script src="<?= $config['web']['base_url']; ?>vendor_assets/js/slick.min.js"></script>
    <script src="<?= $config['web']['base_url']; ?>vendor_assets/js/tether.min.js"></script>
    <script src="<?= $config['web']['base_url']; ?>vendor_assets/js/trumbowyg.min.js"></script>
    <script src="<?= $config['web']['base_url']; ?>vendor_assets/js/venobox.min.js"></script>
    <script src="<?= $config['web']['base_url']; ?>vendor_assets/js/waypoints.min.js"></script>
    <script src="<?= $config['web']['base_url']; ?>theme_assets/js/dashboard.js"></script>
    <script src="<?= $config['web']['base_url']; ?>theme_assets/js/main.js"></script>
    <!--<script src="<?= $config['web']['base_url']; ?>theme_assets/js/map.js"></script>-->
    <!-- endinject-->
</body>

</html>

<?php

?>