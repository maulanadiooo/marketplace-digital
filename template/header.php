<?php

error_reporting(0);
$website = $model->db_query($db, "*", "website", "id = '1'");
$smtp_mail = $model->db_query($db, "*", "smtp", "id = '1'");
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
$model->db_update($db, "user", array('last_login' => date('Y-m-d H:i:s'), 'ip' => get_client_ip()), "id ='".$_SESSION['login']."' ");
$check_banned = $model->db_query($db, "*", "user", "id = '".$_SESSION['login']."'");
if($check_banned['rows']['status'] == 'Banned'){
    exit(header("Location: ".$config['web']['base_url']."banned"));
}

?>
<!doctype HTML>
<html lang="en">

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
    <link rel="icon" href="<?= $config['web']['base_url']; ?>file-photo/website/<?=$website['rows']['fav_icon']?>" type="image/png" />
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
    <script data-ad-client="ca-pub-5150670592657269" async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
    
    <!--awal google ads-->
    <!-- Global site tag (gtag.js) - Google Ads: 380175450 -->
<script async src="https://www.googletagmanager.com/gtag/js?id=AW-380175450"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'AW-380175450');
</script>

<!-- Event snippet for Website sale conversion page -->
<script>
  gtag('event', 'conversion', {
      'send_to': 'AW-380175450/ladrCJLtn8QCENqIpLUB',
      'transaction_id': ''
  });
</script>
<!--akhir google ads-->
</head>
<?
$user_info = $model->db_query($db, "*", "user", "id = '".$_SESSION['login']."'");
?>
<body class="preload">
    <!-- start menu-area -->
    
    <div class="menu-area">
        <div class="top-menu-area">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="menu-fullwidth">
                            <div class="logo-wrapper">
                                <div class="logo logo-top">
                                    <a href="<?= $config['web']['base_url']; ?>"><img src="<?= $config['web']['base_url']; ?>file-photo/website/<?=$website['rows']['logo_web']?>" alt="<?=$website['rows']['title']?>" class="img-fluid" width="145px" height="44px"></a>
                                </div>
                            </div>
                            <div class="menu-container">
                                <div class="d_menu">
                                    <nav class="navbar navbar-expand-lg mainmenu__menu">
                                        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-controls="bs-example-navbar-collapse-1" aria-expanded="false" aria-label="Toggle navigation">
                                            <span class="navbar-toggler-icon icon-menu"></span>
                                        </button>
                                        <!-- Collect the nav links, forms, and other content for toggling -->
                                        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                                            <ul class="navbar-nav">
                                                
                                                <li>
                                                    <a href="<?= $config['web']['base_url']; ?>">Home</a>
                                                </li>
                                                <li class="has_dropdown">
                                                    <a>Kategori</a>
                                                    <div class="dropdown dropdown--menu">
                                                        <ul>
                                                            <?php
                                                            $category = mysqli_query($db, "SELECT * FROM categories ORDER BY category");
                                                            while ($categories = mysqli_fetch_assoc($category)) {
                                                            ?>
                                                            <li>
                                                                <a href="<?= $config['web']['base_url'] ?>categories/<?= $categories['url'] ?>"><?= ucfirst($categories['category']); ?></a>
                                                            </li>
                                                                
                                                            <?php   
                                                            }
                                                            ?>
                                                        </ul>
                                                    </div>
                                                </li>
                                            <?php
                                            if(isset($_SESSION['login'])){
                                            ?>
                                                <li class="has_dropdown">
                                                   
                                                    <?
                                                    $order_info_notif = $model->db_query($db, "*", "orders", "seller_id = '".$_SESSION['login']."' AND status in ('active','ditolak','cancel')");
                                                    if($order_info_notif['count'] > 0){
                                                    ?>
                                                    <a>Penjual  <span class="badge bg-info text-dark"><?= $order_info_notif['count'] ?></span> </a>
                                                    <?
                                                    } else {
                                                    ?>
                                                    <a>Penjual</a>
                                                    <?
                                                    }
                                                    ?>
                                                    <div class="dropdown dropdown--menu">
                                                        <ul>
                                                            <li>
                                                                <a href="<?= $config['web']['base_url']; ?>create">Tambah Produk</a>
                                                            </li>
                                                            <li>
                                                                <a href="<?= $config['web']['base_url']; ?>my-product/">Produk Saya</a>
                                                            </li>
                                                            <?
                                                            if($order_info_notif['count'] > 0){
                                                            ?>
                                                            <li>
                                                                <a href="<?= $config['web']['base_url']; ?>my-sales/">Kelola Penjualan <span class="badge bg-info text-dark"><?= $order_info_notif['count'] ?></span></a>
                                                            </li>
                                                            <?
                                                            } else {
                                                            ?>
                                                            <li>
                                                                <a href="<?= $config['web']['base_url']; ?>my-sales/">Kelola Penjualan</a>
                                                            </li>
                                                            <?
                                                            }
                                                            ?>
                                                            <li>
                                                                <a href="<?= $config['web']['base_url']; ?>my-revenue/">Pendapatan</a>
                                                            </li>
                                                            <li>
                                                                <a href="<?= $config['web']['base_url']; ?>withdraw/">Withdraw</a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </li>
                                                <li class="has_dropdown">
                                                    <?
                                                    $order_info_notif_buyer = $model->db_query($db, "*", "orders", "buyer_id = '".$_SESSION['login']."' AND status in ('active','ditolak','cancel','success')");
                                                    if($order_info_notif_buyer['count'] > 0){
                                                    ?>
                                                    <a>Pembeli  <span class="badge bg-info text-dark"><?= $order_info_notif_buyer['count'] ?></span> </a>
                                                    <?
                                                    } else {
                                                    ?>
                                                    <a>Pembeli</a>
                                                    <?
                                                    }
                                                    ?>
                                                    <div class="dropdown dropdown--menu">
                                                        <ul>
                                                            <li>
                                                                <a href="<?= $config['web']['base_url']; ?>cart/">Checkout</a>
                                                            </li>
                                                        </ul>
                                                        <ul>
                                                            <?
                                                            if($order_info_notif_buyer['count'] > 0){
                                                            ?>
                                                            <li>
                                                                <a href="<?= $config['web']['base_url']; ?>my-orders">Belanjaan Saya <span class="badge bg-info text-dark"><?= $order_info_notif_buyer['count'] ?></span></a>
                                                            </li>
                                                            <?
                                                            } else {
                                                            ?>
                                                            <li>
                                                                <a href="<?= $config['web']['base_url']; ?>my-orders">Belanjaan Saya</a>
                                                            </li>
                                                            <?
                                                            }
                                                            ?>
                                                            
                                                        </ul>
                                                        <ul>
                                                        <li>
                                                                <a href="<?= $config['web']['base_url']; ?>favorite/">Produk Disukai</a>
                                                        </li>
                                                        </ul>
                                                        <ul>
                                                        <li>
                                                                <a href="<?= $config['web']['base_url']; ?>my-revenue/">Saldo Saya</a>
                                                        </li>
                                                        </ul>
                                                        <ul>
                                                            <li>
                                                                <a href="<?= $config['web']['base_url']; ?>add-balance/">Deposit</a>
                                                            </li>
                                                        </ul>
                                                        <ul>
                                                            <li>
                                                                <a href="<?= $config['web']['base_url']; ?>payment-history/">Riwayat Pembayaran</a>
                                                            </li>
                                                        </ul>
                                                        <ul>
                                                            <li>
                                                                <a href="<?= $config['web']['base_url']; ?>deposit-history/">Riwayat Deposit</a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </li>
                                            <?php
                                            }
                                            ?>
                                            
                                            
                                            <?php
                                            if(!isset($_SESSION['login'])){
                                                $url_sekarang = $_SERVER['REQUEST_URI'];
                                                $prefix = '/';
                                                if (substr($url_sekarang, 0, strlen($prefix)) === $prefix) {
                                                    $url_request = substr($url_sekarang, strlen($prefix));
                                                }
                                                
                                                
                                            ?>
                                                <li>
                                                    <a href="<?= $config['web']['base_url']; ?>how-it-works/">Cara kerja</a>
                                                </li>
                                                
                                            <?php    
                                            }
                                            ?>
                                            <li class="has_dropdown">
                                                <a>Layanan Lainnya</a>
                                                <div class="dropdown dropdown--menu">
                                                    <ul>
                                                        
                                                        <li>
                                                            <a href="https://jasaotp.co.id/" target="_blank">Jasa OTP</a>
                                                        </li>
                                                        <li>
                                                            <a href="https://buffmedia.net/" target="_blank">SMM Panel Indonesia</a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </li>    
                                            </ul>
                                        </div>
                                        <!-- /.navbar-collapse -->
                                    </nav>
                                </div>
                            </div>
                            <?php
                            if(!isset($_SESSION['login'])){
                            ?>
                            <div class="author-menu">
                                    <!-- start .author-area -->
                                    <div class="author-area">
                                        <div class="search-wrapper">
                                            <div class="nav_right_module search_module">
                                                <span class="icon-magnifier search_trigger"></span>
                                                <div class="search_area">
                                                    <form action="<?= $config['web']['base_url'] ?>search" method="get">
                                                        <div class="input-group input-group-light">
                                                            <span class="icon-left" id="basic-addon9">
                                                                <i class="icon-magnifier"></i>
                                                            </span>
                                                            <input type="text" name ="query"  class="form-control search_field" placeholder="Search dan Enter">
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="author__access_area">
                                            <ul class="d-flex">
                                                <form action="<?= $config['web']['base_url'] ?>signin/" method="post">
                                                <a data-toggle="modal" data-target="#modalSayaSignIn">    
                                                <li><button class="btn btn--icon btn-sm btn-light"><i class="fa fa-sign-in" aria-hidden="true"></i>Masuk</button></li></a>
                                                </form>
                                                <form action="<?= $config['web']['base_url'] ?>signup" method="post">
                                                 <li><button class="btn btn--icon btn-sm btn-light"><i class="fa fa-user-plus" aria-hidden="true"></i>Daftar</button></li>
                                                </form>
                                            </ul>
                                        </div>
                                    </div>
                                    <!-- end .author-area -->
                                </div>
                            <?
                            }
                            ?>
                            <?php
                            if(isset($_SESSION['login'])){
                            ?>
                            <div class="author-menu">
                                <!-- start .author-area -->
                                <!--Pencarian-->
                                    <div class="author-area">
                                        <div class="search-wrapper">
                                                <div class="nav_right_module search_module">
                                                    <span class="icon-magnifier search_trigger"></span>
                                                    <div class="search_area">
                                                        <form action="<?= $config['web']['base_url'] ?>search" method="get">
                                                            <div class="input-group input-group-light">
                                                                <span class="icon-left" id="basic-addon9">
                                                                    <i class="icon-magnifier"></i>
                                                                </span>
                                                                <input type="text" name ="query"  class="form-control search_field" placeholder="Search dan Enter">
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                    <div class="author__notification_area">
                                        <ul>
                                            <li class="has_dropdown">
                                                <?
                                                $data_targetr_header_notif = mysqli_query($db, "SELECT * FROM notifikasi WHERE read_by_user = '0' AND seller_id = '".$_SESSION['login']."' ORDER by created_at DESC LIMIT 5");
                                                $data_targetr_header_notif2 = mysqli_query($db, "SELECT * FROM notifikasi WHERE read_by_user = '0' AND seller_id = '".$_SESSION['login']."' ORDER by created_at DESC");
                                                $jumlah_unread_notif = mysqli_num_rows($data_targetr_header_notif2);
                                                ?>
                                                <div class="icon_wrap">
                                                    <span class="icon-bell"></span>
                                                    <?
                                                    if($jumlah_unread_notif != 0){
                                                    ?>
                                                    <span class="notification_count noti"><?=$jumlah_unread_notif?></span>
                                                    <?
                                                    }
                                                    ?>
                                                </div>
                                                <div class="dropdown notification--dropdown">
                                                    <div class="dropdown_module_header">
                                                        <h6>Notifikasi Baru</h6>
                                                    </div>
                                                    <div class="notifications_module">
                                                        <!-- end /.notifications -->
                                                        <?
                                                        while ($detailTarget_header_notif = mysqli_fetch_assoc($data_targetr_header_notif)){
                                                            $buyer_info = $model->db_query($db, "*", "user", "id = '".$detailTarget_header_notif['buyer_id']."'"); 
                                                            $service_info = $model->db_query($db, "*", "services", "id = '".$detailTarget_header_notif['service_id']."'"); 
                                                            if($detailTarget_header_notif['type'] == 'pembelian'){
                                                                  $status = 'Membeli Produk';
                                                              } elseif ($detailTarget_header_notif['type'] == 'pesan'){
                                                                  $status = 'Mengirimkan Pesan Kepada Anda';
                                                              } elseif ($detailTarget_header_notif['type'] == 'favorit'){
                                                                  $status = 'Memfavoritkan Produk';
                                                              } elseif ($detailTarget_header_notif['type'] == 'unfavorit'){
                                                                  $status = 'Membatalkan Favorit Produk';
                                                              } elseif ($detailTarget_header_notif['type'] == 'review'){
                                                                  $status = 'Pesanan Anda Telah Direview';
                                                              } elseif ($detailTarget_header_notif['type'] == 'pesanan-success'){
                                                                  $status = 'Pesanan Anda Dikirim';
                                                              } elseif ($detailTarget_header_notif['type'] == 'pesanan-perbarui'){
                                                                  $status = 'Pesanan Anda Diperbarui';
                                                              } elseif ($detailTarget_header_notif['type'] == 'pesanan-cancel'){
                                                                  $status = 'Pesanan Anda Ditolak';
                                                              }
                                                            
                                                                $format = $detailTarget_header_notif['go'];
                                                                $pisah = explode("/", $format);
                                                                $idOrderan = $pisah[1];
                                                        ?>
                                                        <div class="notification">
                                                            <div class="notification__info">
                                                                <?
                                                                if($buyer_info['rows']['photo'] == null){
                                                                ?>
                                                                <div class="info_avatar">
                                                                    <img src="<?= $config['web']['base_url'] ?>img/avatar.png" alt="<?=$buyer_info['rows']['username']?>">
                                                                </div>
                                                                <?
                                                                } else {
                                                                ?>
                                                                <div class="info_avatar">
                                                                    <img src="<?= $config['web']['base_url'] ?>user-photo/<?=$buyer_info['rows']['username']?>" alt="<?=$buyer_info['rows']['username']?>">
                                                                </div>
                                                                <?    
                                                                }
                                                                ?>
                                                                <div class="info">
                                                                    <p>
                                                                        <span><?=$buyer_info['rows']['username']?></span>
                                                                        <a href="<?= $config['web']['base_url'] ?><?=$detailTarget_header_notif['go']?>">#<?=$idOrderan?> <?=$status?></a>
                                                                        <?
                                                                        if($detailTarget_header_notif['service_id'] != 0){
                                                                        ?>
                                                                        <span><?=$service_info['rows']['nama_layanan']?></span>
                                                                        <?
                                                                        }
                                                                        ?>
                                                                    </p>
                                                                    <p class="time"><?= format_date(substr($detailTarget_header_notif['created_at'], 0, -9)).", ".substr($detailTarget_header_notif['created_at'], 11, -3)?> UTC +7</p>
                                                                </div>
                                                            </div>
                                                            <!-- end /.notifications -->
                                                            <?
                                                            if($detailTarget_header_notif['type'] == 'favorit' || $detailTarget_header_notif['type'] == 'unfavorit'){
                                                            ?>
                                                            <div class="notification__icons ">
                                                                <span class="icon-heart loved noti_icon"></span>
                                                            </div>
                                                            <?
                                                            } elseif ($detailTarget_header_notif['type'] == 'review'){
                                                            ?>
                                                            <div class="notification__icons ">
                                                                <span class="icon-star reviewed noti_icon"></span>
                                                            </div>
                                                            <?
                                                            } else {
                                                            ?>
                                                            <div class="notification__icons ">
                                                                <span class="icon-basket-loaded purchased noti_icon"></span>
                                                            </div>
                                                            <?
                                                            }
                                                            ?>
                                                            <!-- end /.notifications -->
                                                        </div>
                                                        <?
                                                        }
                                                        ?>
                                                        <!-- end /.notifications -->
                                                        <div class="text-center m-top-30 p-left-20 p-right-20"><a href="<?= $config['web']['base_url'] ?>notifikasi/" class="btn btn-primary btn-md btn-block">View
                                                                All</a></div>
                                                        <!-- end /.notifications -->
                                                    </div>
                                                    <!-- end /.dropdown -->
                                                </div>
                                            </li>
                                            <?
                                            $data_targetr_header = mysqli_query($db, "SELECT * FROM message WHERE dibaca_penerima = 'no' AND penerima = '".$_SESSION['login']."' ORDER by date DESC LIMIT 5");
                                            $data_targetr_header2 = mysqli_query($db, "SELECT * FROM message WHERE dibaca_penerima = 'no' AND penerima = '".$_SESSION['login']."' ORDER by date DESC");
                                            $jumlah_unread = mysqli_num_rows($data_targetr_header2);
                                            ?>
                                            <li class="has_dropdown">
                                                <?
                                                if($jumlah_unread == 0){
                                                ?>
                                                <div class="icon_wrap">
                                                    <span class="icon-envelope-open"></span>
                                                </div>
                                                <?
                                                } else {
                                                ?>
                                                <div class="icon_wrap">
                                                    <span class="icon-envelope"></span>
                                                    <span class="notification_count msg"><?=$jumlah_unread?></span>
                                                </div>
                                                <?    
                                                }
                                                ?>
                                                <div class="dropdown messaging--dropdown">
                                                    <div class="dropdown_module_header">
                                                        <h6>Pesan Baru</h6>
                                                    </div>
                                                    <div class="messages">
                                                        <?
                                                        while ($detailTarget_header = mysqli_fetch_assoc($data_targetr_header)){
                                                            if($detailTarget_header['pengirim'] == $login['id'] ){
                                                                $id_komentar_header = $detailTarget_header['penerima'];
                                                            } else {
                                                                $id_komentar_header = $detailTarget_header['pengirim'];
                                                            }
                                                            
                                                            
                                                            $data_user_header = $model->db_query($db, "*", "user", "id = '$id_komentar_header' ");
                                                        ?>
                                                        <a href="<?= $config['web']['base_url'] ?>conversation/<?= $data_user_header['rows']['username']?>" class="message recent">
                                                            <div class="message__actions_avatar">
                                                                <div class="avatar">
                                                                    <?
                                                                    if($data_user_header['rows']['photo'] != null){
                                                                    ?>
                                                                    <img src="<?= $config['web']['base_url'] ?>user-photo/<?=$data_user_header['rows']['photo']?>" alt="<?=$data_user_header['rows']['username']?>">    
                                                                    <?
                                                                    } else {
                                                                    ?>
                                                                    <img src="<?= $config['web']['base_url'] ?>img/avatar.png" alt="<?=$data_user_header['rows']['username']?>"> 
                                                                    <?    
                                                                    }
                                                                    ?>
                                                                </div>
                                                            </div>
                                                            <!-- end /.actions -->
                                                            <div class="message_data">
                                                                <div class="name_time">
                                                                    <div class="name">
                                                                        <p><?=$data_user_header['rows']['username']?></p>
                                                                        <span class="icon-envelope"></span>
                                                                    </div>
                                                                    <span class="time"> <?= format_date(substr($detailTarget_header['date'], 0, -9))?></span>
                                                                    <?
                                                                    $isi_pesan_header = html_entity_decode($detailTarget_header['message'], ENT_NOQUOTES);
                                                                      $jumlah_karakter_header = strlen($isi_pesan_header);
                                                                     if($jumlah_karakter_header > 25){
                                                                        $singkat_header = substr($isi_pesan_header, 0, 25) ;
                                                                        $hasil_pesan_header = $singkat_header." ....";
                                                                     } else {
                                                                         $hasil_pesan_header = $isi_pesan_header;
                                                                     }
                                                                    ?>
                                                                    <p><?=$hasil_pesan_header?></p>
                                                                </div>
                                                            </div>
                                                            <!-- end /.message_data -->
                                                        </a>
                                                        <?
                                                        }
                                                        ?>
                                                        <!-- end /.message -->
                                                    </div>
                                                    <div class="text-center m-top-30 m-bottom-30 p-left-20 p-right-20">
                                                        <a href="<?= $config['web']['base_url']; ?>message/" class="btn btn-primary btn-md btn-block">View All</a>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="has_dropdown">
                                                <?
                                                $data_target_notif = mysqli_query($db, "SELECT * FROM cart WHERE buyer_id = '".$_SESSION['login']."' AND status in ('pending', 'waiting') ORDER by created_at DESC LIMIT 5");
                                                $data_target_notif2 = mysqli_query($db, "SELECT * FROM cart WHERE buyer_id = '".$_SESSION['login']."' AND status in ('pending', 'waiting') ORDER by created_at DESC");
                                                 $jumlah_cart = mysqli_num_rows($data_target_notif2);
                                                if($jumlah_cart == 0){
                                                ?>    
                                                
                                                <div class="icon_wrap">
                                                    <span class="icon-basket-loaded"></span>
                                                </div>
                                                <?    
                                                } else {
                                                ?>    
                                                <div class="icon_wrap">
                                                    <span class="icon-basket-loaded"></span>
                                                    <span class="notification_count purch"><?=$jumlah_cart?></span>
                                                </div>
                                                <?    
                                                }
                                                ?>
                                                <div class="dropdown dropdown--cart">
                                                    <div class="cart_area">
                                                        <div class="cart_list">
                                                            <?
                                                            while ($data_target_s_notif = mysqli_fetch_assoc($data_target_notif)){
                                                            $data_service_notif = $model->db_query($db, "*", "services", "id = '".$data_target_s_notif['service_id']."'");
                                                            ?>
                                                            <div class="cart_product">
                                                                <div class="product__info">
                                                                    <div class="thumbn">
                                                                        <img src="<?= $config['web']['base_url'] ?>file-photo/<?=$data_service_notif['rows']['id']?>/<?=$data_service_notif['rows']['photo']?>" alt="<?=$data_service_notif['rows']['nama_layanan']?>" width="70px" height="70px">
                                                                    </div>
                                                                    <div class="info">
                                                                        <a class="title" href="<?= $config['web']['base_url'] ?>product/<?=$data_service_notif['rows']['id']?>/<?=$data_service_notif['rows']['url']?>"><?=$data_service_notif['rows']['nama_layanan']?></a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <?
                                                            }
                                                            ?>
                                                        </div>
                                                        
                                                        <div class="cart_action">
                                                            <a class="btn btn-secondary" href="<?= $config['web']['base_url']; ?>cart/">Check Cart</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                    <!--start .author-author__info-->
                                    <div class="author-author__info has_dropdown">
                                        <div class="author__avatar online">
                                            <?
                                            if($user_info['rows']['photo'] == null){
                                            ?>
                                            <img src="<?= $config['web']['base_url']; ?>img/avatar.png" alt="<?=$user_info['rows']['photo']?>" class="rounded-circle" width="44px" height="44px" >
                                            <?
                                            } else {
                                            ?>
                                            <img src="<?= $config['web']['base_url']; ?>user-photo/<?=$user_info['rows']['photo']?>" alt="<?=$user_info['rows']['photo']?>" class="rounded-circle" width="44px" height="44px">
                                            <?
                                            }
                                            ?>
                                        </div>
                                        <div class="dropdown dropdown--author">
                                            <div class="author-credits d-flex">
                                                <div class="author__avatar">
                                                    <?
                                                    if($user_info['rows']['photo'] == null){
                                                    ?>
                                                    <img src="<?= $config['web']['base_url']; ?>img/avatar.png" alt="<?=$user_info['rows']['photo']?>" class="rounded-circle" width="44px" height="44px">
                                                    <?
                                                    } else {
                                                    ?>
                                                    <img src="<?= $config['web']['base_url']; ?>user-photo/<?=$user_info['rows']['photo']?>" alt="<?=$user_info['rows']['photo']?>" class="rounded-circle" width="44px" height="44px">
                                                    <?
                                                    }
                                                    ?>
                                                </div>
                                                <div class="autor__info">
                                                    <p class="name">
                                                        <?= $user_info['rows']['nama']?>
                                                    </p>
                                                </div>
                                            </div>
                                            <ul>
                                                <li>
                                                    <?
                                                    $id = $_SESSION['login'];
                                                    $data_user = $model->db_query($db, "*", "user", "id = '$id'");
                                                    ?>
                                                    <a href="<?= $config['web']['base_url']; ?>user/<?= $data_user['rows']['username']?>">
                                                        <span class="icon-user"></span>Profile</a>
                                                </li>
                                                <li>
                                                    <a href="<?= $config['web']['base_url']; ?>setting/">
                                                        <span class="icon-settings"></span> Pengaturan</a>
                                                </li>
                                                <li>
                                                    <a href="<?= $config['web']['base_url']; ?>my-orders/">
                                                        <span class="icon-basket"></span>Belanjaan</a>
                                                </li>
                                                <li>
                                                    <a href="<?= $config['web']['base_url']; ?>add-balance/">
                                                        <span class="icon-credit-card"></span>Tambah Saldo</a>
                                                </li>
                                                <li>
                                                    <a href="<?= $config['web']['base_url']; ?>my-revenue/">
                                                        <span class="icon-chart"></span>Pendapatan</a>
                                                </li>
                                                <li>
                                                    <a href="<?= $config['web']['base_url']; ?>create/">
                                                        <span class="icon-cloud-upload"></span>Upload Produk</a>
                                                </li>
                                                <li>
                                                    <a href="<?= $config['web']['base_url']; ?>my-product/">
                                                        <span class="icon-notebook"></span>Produk Saya</a>
                                                </li>
                                                <li>
                                                    <a href="<?= $config['web']['base_url']; ?>favorite/">
                                                        <span class="icon-heart"></span>Produk Disukai</a>
                                                </li>
                                                <li>
                                                    <a href="<?= $config['web']['base_url']; ?>withdraw/">
                                                        <span class="icon-briefcase"></span>Withdrawals</a>
                                                </li>
                                                <li>
                                                    <a href="<?= $config['web']['base_url']; ?>signout/">
                                                        <span class="icon-logout"></span>Logout</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <!--end /.author-author__info-->
                                </div>
                                <?php    
                                }
                                ?>
                                
                                <?php
                                if(isset($_SESSION['login'])){
                                ?>
                                <!-- end .author-area -->
                                <!-- author area restructured for mobile -->
                                <div class="mobile_content ">
                                    <span class="icon-user menu_icon"></span>
                                    <!-- offcanvas menu -->
                                    <div class="offcanvas-menu closed">
                                        <span class="icon-close close_menu"></span>
                                        <div class="author-author__info">
                                            <?
                                            if($user_info['rows']['photo'] == null){
                                            ?>
                                            <div class="author__avatar v_middle">
                                                <img src="<?= $config['web']['base_url']; ?>img/avatar.png" alt="<?=$user_info['rows']['username']?>" width="44px" height="44px">
                                            </div>
                                            <?
                                            } else {
                                            ?>
                                            <div class="author__avatar v_middle">
                                                <img src="<?= $config['web']['base_url']; ?>user-photo/<?=$user_info['rows']['photo']?>" alt="<?=$user_info['rows']['username']?>" width="44px" height="44px">
                                            </div>
                                            <?    
                                            }
                                            ?>
                                            
                                        </div>
                                        <ul class="search_area">
                                            <form action="<?= $config['web']['base_url'] ?>search" method="get">
                                                <div class="input-group input-group-light">
                                                    <input type="text" name ="query" class="form-control search_field" placeholder="Search... " >
                                                </span>
                                                </div>
                                            </form>
                                        </ul>
                                        <!--end /.author-author__info-->
                                        <div class="author__notification_area">
                                            <ul>
                                                <li>
                                                    <a href="<?= $config['web']['base_url']; ?>notifikasi/">
                                                        <div class="icon_wrap">
                                                            <span class="icon-bell"></span>
                                                            <?
                                                            if($jumlah_unread_notif > 0){
                                                            ?>
                                                            <span class="notification_count noti"><?=$jumlah_unread_notif?></span>
                                                            <?    
                                                            }
                                                            ?>
                                                        </div>
                                                    </a>
                                                </li>
                                                <li>
                                                    
                                                    <a href="<?= $config['web']['base_url']; ?>message/">
                                                        <div class="icon_wrap">
                                                            <?
                                                            if($jumlah_unread == 0){
                                                            ?>
                                                            <span class="icon-envelope-open"></span>
                                                            <?
                                                            }
                                                            ?>
                                                            <?
                                                            if($jumlah_unread > 0){
                                                            ?>
                                                            <span class="icon-envelope"></span>
                                                            <span class="notification_count msg"><?=$jumlah_unread?></span>
                                                            <?
                                                            }
                                                            ?>
                                                        </div>
                                                    </a>
                                                    
                                                </li>
                                                <li>
                                                    <a href="<?= $config['web']['base_url']; ?>cart/">
                                                        <div class="icon_wrap">
                                                            <span class="icon-basket"></span>
                                                            <?
                                                            if($jumlah_cart > 0){
                                                            ?>
                                                            <span class="notification_count purch"><?=$jumlah_cart?></span>
                                                            <?
                                                            }
                                                            ?>
                                                        </div>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                        <!--start .author__notification_area -->
                                        <div class="dropdown dropdown--author">
                                            <ul>
                                                <li>
                                                    <?
                                                    $id = $_SESSION['login'];
                                                    $data_user = $model->db_query($db, "*", "user", "id = '$id'");
                                                    ?>
                                                    <a href="<?= $config['web']['base_url']; ?>user/<?= $data_user['rows']['username']?>">
                                                        <span class="icon-user"></span>Profile</a>
                                                </li>
                                                <li>
                                                    <a href="<?= $config['web']['base_url']; ?>setting/">
                                                        <span class="icon-settings"></span> Pengaturan</a>
                                                </li>
                                                <li>
                                                    <a href="<?= $config['web']['base_url']; ?>my-orders/">
                                                        <span class="icon-basket"></span>Belanjaan</a>
                                                </li>
                                                <li>
                                                    <a href="<?= $config['web']['base_url']; ?>add-balance/">
                                                        <span class="icon-credit-card"></span>Tambah Saldo</a>
                                                </li>
                                                <li>
                                                    <a href="<?= $config['web']['base_url']; ?>my-revenue/">
                                                        <span class="icon-chart"></span>Pendapatan</a>
                                                </li>
                                                <li>
                                                    <a href="<?= $config['web']['base_url']; ?>create/">
                                                        <span class="icon-cloud-upload"></span>Upload Produk</a>
                                                </li>
                                                <li>
                                                    <a href="<?= $config['web']['base_url']; ?>my-product/">
                                                        <span class="icon-notebook"></span>Produk Saya</a>
                                                </li>
                                                <li>
                                                    <a href="<?= $config['web']['base_url']; ?>favorite/">
                                                        <span class="icon-heart"></span>Produk Disukai</a>
                                                </li>
                                                <li>
                                                    <a href="<?= $config['web']['base_url']; ?>withdraw/">
                                                        <span class="icon-briefcase"></span>Withdrawals</a>
                                                </li>
                                                <li>
                                                    <a href="<?= $config['web']['base_url']; ?>signout/">
                                                        <span class="icon-logout"></span>Logout</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <!-- end /.mobile_content -->
                            </div>  
                              
                            <?php    
                            }
                            ?>
                            <?php
                            if(!isset($_SESSION['login'])){
                            ?>
                            <!-- end .author-area -->
                            <!-- author area restructured for mobile -->
                            <div class="mobile_content ">
                                <span class="icon-user menu_icon"></span>
                                <!-- offcanvas menu -->
                                <div class="offcanvas-menu closed">
                                    <span class="icon-close close_menu"></span>
                                    <div class="author-author__info">
                                        
                                        <div class="author__avatar v_middle">
                                            <img src="<?= $config['web']['base_url']; ?>file-photo/website/<?=$website['rows']['logo_web']?>" alt="<?=$website['rows']['title']?>" width="135px" height="44px">
                                        </div>
                                        
                                        
                                    </div>
                                    <!--end /.author-author__info-->
                                    
                                    <!--start .author__notification_area -->
                                    <div class="dropdown dropdown--author">
                                        <ul>    
                                            <li class="search_area">
                                                <form action="<?= $config['web']['base_url'] ?>search" method="get">
                                                    <div class="input-group input-group-light">
                                                        <input type="text" name ="query" class="form-control search_field" placeholder="Search... " >
                                                    </span>
                                                    </div>
                                                </form>
                                            </li>
                                            
                                            <form action="<?= $config['web']['base_url'] ?>signin/" method="post">
                                            <a data-toggle="modal" data-target="#modalSayaSignIn">    
                                            <li><button class="btn btn-lg btn-light"><i class="fa fa-sign-in" aria-hidden="true"></i>Masuk</button></li></a>
                                            </form>
                                            <form action="<?= $config['web']['base_url'] ?>signup" method="post">
                                             <li><button class="btn btn-lg btn-light"><i class="fa fa-user-plus" aria-hidden="true"></i>Daftar</button></li>
                                            </form>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <!-- end /.mobile_content -->
                        </div>  
                          
                        <?php    
                        }
                        ?>
                            
                            
                            
                        </div>
                    </div>
                </div>
                <!-- end /.row -->
            </div>
            <!-- end /.container -->
        </div>                              
        <!-- end  -->
    </div>
    <!-- end /.menu-area -->
    
<?php
if (isset($_SESSION['result'])) {
?>
					<!--<div class="alert alert-<?php echo $_SESSION['result']['alert'] ?> alert-dismissable">-->
					<!--	<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>-->
					<!--	<b><?php echo $_SESSION['result']['title'] ?></b> <?php echo $_SESSION['result']['msg'] ?>-->
					<!--</div>-->
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