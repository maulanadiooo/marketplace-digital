<?
require 'web.php';
$website = $model->db_query($db, "*", "website", "id = '1'"); 
$banned = $model->db_query($db, "*", "user", "id = '".$_SESSION['login']."'");
if(!isset($_SESSION['login'])){
    exit(header("Location: ".$config['web']['base_url']));
}
if($banned['rows']['status'] != 'Banned'){
    exit(header("Location: ".$config['web']['base_url']));
}
?>
<head>
    <meta charset="UTF-8">
    <!-- viewport meta -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?
    if($description != null){
    ?>    
    <meta name="description" content="<?=$description?>, <?=$website['rows']['description']?>">
    <?
    } else {
    ?>
    <meta name="description" content="<?=$website['rows']['description']?>">
    <?
    }
    ?>
    <?
    if($keyword != null){
        $strip_key = ",";
    }
    ?>
    <meta name="keywords" content="<?=$keyword?><?=$strip_key?><?=$website['rows']['keyword']?>">
    <?
    if($title != null){
        $strip = "-";
    }
    ?>
    <title><?=$title?> <?=$strip?> <?=$website['rows']['title']?></title>
    <link href="https://fonts.googleapis.com/css?family=Work+Sans:400,500,600" rel="stylesheet">
    <!-- inject:css-->
    <link rel="stylesheet" href="<?= $config['web']['base_url']; ?>vendor_assets/css/bootstrap/bootstrap.css">
    <link rel="stylesheet" href="<?= $config['web']['base_url']; ?>vendor_assets/css/animate.css">
    <link rel="stylesheet" href="<?= $config['web']['base_url']; ?>vendor_assets/css/font-awesome.min.css">
    <link rel="stylesheet" href="<?= $config['web']['base_url']; ?>vendor_assets/css/jquery-ui.css">
    <link rel="stylesheet" href="<?= $config['web']['base_url']; ?>vendor_assets/css/line-awesome.min.css">
    <link rel="stylesheet" href="<?= $config['web']['base_url']; ?>vendor_assets/css/magnific-popup.css">
    <link rel="stylesheet" href="<?= $config['web']['base_url']; ?>vendor_assets/css/owl.carousel.css">
    <link rel="stylesheet" href="<?= $config['web']['base_url']; ?>vendor_assets/css/select2.min.css">
    <link rel="stylesheet" href="<?= $config['web']['base_url']; ?>vendor_assets/css/simple-line-icons.css">
    <link rel="stylesheet" href="<?= $config['web']['base_url']; ?>vendor_assets/css/slick.css">
    <link rel="stylesheet" href="<?= $config['web']['base_url']; ?>vendor_assets/css/trumbowyg.min.css">
    <link rel="stylesheet" href="<?= $config['web']['base_url']; ?>vendor_assets/css/venobox.css">
    <link rel="stylesheet" href="<?= $config['web']['base_url']; ?>style.css">
    
    <!--<script src="https://cdn.jsdelivr.net/npm/sweetalert2@1.2.0/src/sweetalert2.js"></script>-->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@8.0.6/dist/sweetalert2.all.min.js"></script>
    <!-- endinject -->
    <!-- Favicon Icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="<?= $config['web']['base_url']; ?>file-photo/website/<?=$website['rows']['fav_icon']?>">
    <!--css untuk tomblol upload-->
    <style>
        #custom-button {
          padding: 10px;
          color: white;
          background-color: #095dbd;
          border: 1px solid #000;
          border-radius: 5px;
          cursor: pointer;
        }
        
        #custom-button:hover {
          background-color: #42cdff;
        }
        
        #custom-text {
          margin-left: 10px;
          font-family: sans-serif;
          color: #aaa;
        }
    </style>
    <!--google analytics-->
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-Y6XDM386WH"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
    
      gtag('config', 'G-Y6XDM386WH');
    </script>
</head>
<section class="four_o_four_area section--padding">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 offset-lg-3 text-center">
                    <center><img src="<?= $config['web']['base_url']; ?>file-photo/website/<?=$website['rows']['logo_web']?>" alt="Not Found" width="330px" height ="100px">
                    <div class="not_found">
                        <h2>Akunmu Ditangguhkan Secara Permanen</h2>
                        <span>Alasan: <?=$banned['rows']['reason']?></span>
                        <p>Jika Anda Merasa Tidak Melakukan Tindakan Apapun Hubungi Kami Pada Email Dibawah</p>
                        <a href="<?=$config['web']['base_url']?>mailhandler.php" class="btn btn--md btn-primary"><?=$website['rows']['email_notifikasi']?></a>
                    </div>
                    </center>
                </div>
            </div>
        </div>
 </section><!-- ends: .four_o_four_area -->


