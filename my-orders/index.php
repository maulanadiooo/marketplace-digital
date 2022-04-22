<?php
require '../web.php';
require '../lib/is_login.php';
require '../lib/result.php';
if (!isset($_SESSION['login'])) {
	$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Otentikasi dibutuhkan!', 'msg' => 'Silahkan masuk ke akun Anda.');
	exit(header("Location: ".$config['web']['base_url']."signin/"));
}
if ($model->db_query($db, "*", "user", "id = '".$_SESSION['login']."'")['count'] == 0) {
	exit("No direct script access allowed!2");
}
if (isset($_GET['query_status'])) {
    $query_status = mysqli_real_escape_string($db, $_GET['query_status']);
}
if(!$query_status || $query_status == 'active'){
    $dataPerHalaman = 12;

    $data_targeta = mysqli_query($db, "SELECT * FROM orders WHERE status in ('active', 'ditolak', 'refund', 'cancel') AND buyer_id = '".$login['id']."' ORDER BY created_at DESC");
    
    $jumlahData = mysqli_num_rows($data_targeta);
    $jumlahHalaman = ceil($jumlahData/$dataPerHalaman);
    $category = mysqli_real_escape_string($db, $_GET['category']);
    
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
    
    $data_targetraa = mysqli_query($db, "SELECT * FROM orders WHERE status in ('active', 'ditolak', 'refund', 'cancel') AND buyer_id = '".$login['id']."' ORDER BY created_at DESC LIMIT $awalData,$dataPerHalaman");
    
}
if($query_status == 'success'){
    $data_targeta = mysqli_query($db, "SELECT * FROM orders WHERE status = 'success' AND buyer_id = '".$login['id']."' ORDER BY created_at DESC");
    $dataPerHalaman = 12;
    
    $jumlahData = mysqli_num_rows($data_targeta);
    $jumlahHalaman = ceil($jumlahData/$dataPerHalaman);
    $category = mysqli_real_escape_string($db, $_GET['category']);
    
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
    
    $data_targetraaa = mysqli_query($db, "SELECT * FROM orders WHERE status = 'success' AND buyer_id = '".$login['id']."' ORDER BY created_at DESC LIMIT $awalData,$dataPerHalaman");
    
}
if($query_status == 'complete'){
    $data_targeta = mysqli_query($db, "SELECT * FROM orders WHERE status = 'complete' AND buyer_id = '".$login['id']."' ORDER BY created_at DESC");
    $dataPerHalaman = 12;
    
    $jumlahData = mysqli_num_rows($data_targeta);
    $jumlahHalaman = ceil($jumlahData/$dataPerHalaman);
    $category = mysqli_real_escape_string($db, $_GET['category']);
    
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
    
    $data_targetraaaaa = mysqli_query($db, "SELECT * FROM orders WHERE status = 'complete' AND buyer_id = '".$login['id']."' ORDER BY created_at DESC LIMIT $awalData,$dataPerHalaman");
}

if($query_status == 'cancel'){
    $data_targeta = mysqli_query($db, "SELECT * FROM orders WHERE status in ('refunded') AND buyer_id = '".$login['id']."' ORDER BY created_at DESC");
    $dataPerHalaman = 12;
    
    $jumlahData = mysqli_num_rows($data_targeta);
    $jumlahHalaman = ceil($jumlahData/$dataPerHalaman);
    $category = mysqli_real_escape_string($db, $_GET['category']);
    
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
    
    $data_targetraaaa = mysqli_query($db, "SELECT * FROM orders WHERE status in ('refunded') AND buyer_id = '".$login['id']."' ORDER BY created_at DESC LIMIT $awalData,$dataPerHalaman");
}

$title = "Belanjaan Saya";
?>
<?php
require '../template/header.php';
require '../template/header-dashboard.php';
?>


                <section class="dashboard-area">
                  <div class="container">
                    <div class="product-list">
                        <ul class="nav nav__product-list" id="lp-tab" role="tablist">
                            <?php
                            if($query_status == 'cancel'){
                             ?> 
                            <?
                            $order_info_notif_buyers = $model->db_query($db, "*", "orders", "buyer_id = '".$_SESSION['login']."' AND status in ('active','ditolak','cancel')");
                            if($order_info_notif_buyers['count'] > 0){
                            ?>
                            <li class="nav-item">
                                <a class="nav-link" href="<?= $config['web']['base_url']; ?>my-orders/active">Aktif <span class="badge bg-info text-dark"><?= $order_info_notif_buyers['count'] ?></span></a>
                            </li>
                            <?
                            } else {
                            ?>
                            <li class="nav-item">
                                <a class="nav-link" href="<?= $config['web']['base_url']; ?>my-orders/active">Aktif</a>
                            </li>
                            <?
                            }
                            ?>
                            <?
                            $order_info_notif_buyerss = $model->db_query($db, "*", "orders", "buyer_id = '".$_SESSION['login']."' AND status in ('success')");
                            if($order_info_notif_buyerss['count'] > 0){
                            ?>
                            <li class="nav-item">
                                <a class="nav-link"href="<?= $config['web']['base_url']; ?>my-orders/success">Dikirim <span class="badge bg-info text-dark"><?= $order_info_notif_buyerss['count'] ?></span></a>
                            </li>
                            <?
                            } else {
                            ?>
                            <li class="nav-item">
                                <a class="nav-link"href="<?= $config['web']['base_url']; ?>my-orders/success">Dikirim</a>
                            </li>
                            <?
                            }
                            ?>
                            <li class="nav-item">
                                <a class="nav-link" href="<?= $config['web']['base_url']; ?>my-orders/complete" >Selesai</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link active" href="<?= $config['web']['base_url']; ?>my-orders/cancel" role="tab">Dibatalkan</a>
                            </li>
                            <?
                            } elseif ($query_status == 'refund'){
                            ?>    
                            <?
                            $order_info_notif_buyers = $model->db_query($db, "*", "orders", "buyer_id = '".$_SESSION['login']."' AND status in ('active','ditolak','cancel')");
                            if($order_info_notif_buyers['count'] > 0){
                            ?>
                            <li class="nav-item">
                                <a class="nav-link" href="<?= $config['web']['base_url']; ?>my-orders/active">Aktif <span class="badge bg-info text-dark"><?= $order_info_notif_buyers['count'] ?></span></a>
                            </li>
                            <?
                            } else {
                            ?>
                            <li class="nav-item">
                                <a class="nav-link" href="<?= $config['web']['base_url']; ?>my-orders/active">Aktif</a>
                            </li>
                            <?
                            }
                            ?>
                            <?
                            $order_info_notif_buyerss = $model->db_query($db, "*", "orders", "buyer_id = '".$_SESSION['login']."' AND status in ('success')");
                            if($order_info_notif_buyerss['count'] > 0){
                            ?>
                            <li class="nav-item">
                                <a class="nav-link"href="<?= $config['web']['base_url']; ?>my-orders/success">Dikirim <span class="badge bg-info text-dark"><?= $order_info_notif_buyerss['count'] ?></span></a>
                            </li>
                            <?
                            } else {
                            ?>
                            <li class="nav-item">
                                <a class="nav-link"href="<?= $config['web']['base_url']; ?>my-orders/success">Dikirim</a>
                            </li>
                            <?
                            }
                            ?>
                            <li class="nav-item">
                                <a class="nav-link" href="<?= $config['web']['base_url']; ?>my-orders/complete" >Selesai</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link active" href="<?= $config['web']['base_url']; ?>my-orders/cancel" role="tab">Dibatalkan</a>
                            </li>
                            <?    
                            } elseif($query_status == 'success') {
                            ?>    
                            <?
                            $order_info_notif_buyers = $model->db_query($db, "*", "orders", "buyer_id = '".$_SESSION['login']."' AND status in ('active','ditolak','cancel')");
                            if($order_info_notif_buyers['count'] > 0){
                            ?>
                            <li class="nav-item">
                                <a class="nav-link" href="<?= $config['web']['base_url']; ?>my-orders/active">Aktif <span class="badge bg-info text-dark"><?= $order_info_notif_buyers['count'] ?></span></a>
                            </li>
                            <?
                            } else {
                            ?>
                            <li class="nav-item">
                                <a class="nav-link" href="<?= $config['web']['base_url']; ?>my-orders/active">Aktif</a>
                            </li>
                            <?
                            }
                            ?>
                            <?
                            $order_info_notif_buyerss = $model->db_query($db, "*", "orders", "buyer_id = '".$_SESSION['login']."' AND status in ('success')");
                            if($order_info_notif_buyerss['count'] > 0){
                            ?>
                            <li class="nav-item">
                                <a class="nav-link active"href="<?= $config['web']['base_url']; ?>my-orders/success">Dikirim <span class="badge bg-info text-dark"><?= $order_info_notif_buyerss['count'] ?></span></a>
                            </li>
                            <?
                            } else {
                            ?>
                            <li class="nav-item">
                                <a class="nav-link active"href="<?= $config['web']['base_url']; ?>my-orders/success">Dikirim</a>
                            </li>
                            <?
                            }
                            ?>
                            <li class="nav-item">
                                <a class="nav-link" href="<?= $config['web']['base_url']; ?>my-orders/complete" >Selesai</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="<?= $config['web']['base_url']; ?>my-orders/cancel" role="tab">Dibatalkan</a>
                            </li>
                            <?    
                            } elseif($query_status == 'complete') {
                            ?>    
                            <?
                            $order_info_notif_buyers = $model->db_query($db, "*", "orders", "buyer_id = '".$_SESSION['login']."' AND status in ('active','ditolak','cancel')");
                            if($order_info_notif_buyers['count'] > 0){
                            ?>
                            <li class="nav-item">
                                <a class="nav-link" href="<?= $config['web']['base_url']; ?>my-orders/active">Aktif <span class="badge bg-info text-dark"><?= $order_info_notif_buyers['count'] ?></span></a>
                            </li>
                            <?
                            } else {
                            ?>
                            <li class="nav-item">
                                <a class="nav-link" href="<?= $config['web']['base_url']; ?>my-orders/active">Aktif</a>
                            </li>
                            <?
                            }
                            ?>
                            <?
                            $order_info_notif_buyerss = $model->db_query($db, "*", "orders", "buyer_id = '".$_SESSION['login']."' AND status in ('success')");
                            if($order_info_notif_buyerss['count'] > 0){
                            ?>
                            <li class="nav-item">
                                <a class="nav-link"href="<?= $config['web']['base_url']; ?>my-orders/success">Dikirim <span class="badge bg-info text-dark"><?= $order_info_notif_buyerss['count'] ?></span></a>
                            </li>
                            <?
                            } else {
                            ?>
                            <li class="nav-item">
                                <a class="nav-link"href="<?= $config['web']['base_url']; ?>my-orders/success">Dikirim</a>
                            </li>
                            <?
                            }
                            ?>
                            <li class="nav-item">
                                <a class="nav-link active" href="<?= $config['web']['base_url']; ?>my-orders/complete" >Selesai</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link " href="<?= $config['web']['base_url']; ?>my-orders/cancel" role="tab">Dibatalkan</a>
                            </li>
                            <?    
                            } elseif($query_status == 'active') {
                             ?>
                             <?
                            $order_info_notif_buyers = $model->db_query($db, "*", "orders", "buyer_id = '".$_SESSION['login']."' AND status in ('active','ditolak','cancel')");
                            if($order_info_notif_buyers['count'] > 0){
                            ?>
                            <li class="nav-item">
                                <a class="nav-link active" href="<?= $config['web']['base_url']; ?>my-orders/active">Aktif <span class="badge bg-info text-dark"><?= $order_info_notif_buyers['count'] ?></span></a>
                            </li>
                            <?
                            } else {
                            ?>
                            <li class="nav-item">
                                <a class="nav-link active" href="<?= $config['web']['base_url']; ?>my-orders/active">Aktif</a>
                            </li>
                            <?
                            }
                            ?>
                            <?
                            $order_info_notif_buyerss = $model->db_query($db, "*", "orders", "buyer_id = '".$_SESSION['login']."' AND status in ('success')");
                            if($order_info_notif_buyerss['count'] > 0){
                            ?>
                            <li class="nav-item">
                                <a class="nav-link"href="<?= $config['web']['base_url']; ?>my-orders/success">Dikirim <span class="badge bg-info text-dark"><?= $order_info_notif_buyerss['count'] ?></span></a>
                            </li>
                            <?
                            } else {
                            ?>
                            <li class="nav-item">
                                <a class="nav-link"href="<?= $config['web']['base_url']; ?>my-orders/success">Dikirim</a>
                            </li>
                            <?
                            }
                            ?>
                            <li class="nav-item">
                                <a class="nav-link " href="<?= $config['web']['base_url']; ?>my-orders/complete" >Selesai</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link " href="<?= $config['web']['base_url']; ?>my-orders/cancel" role="tab">Dibatalkan</a>
                            </li>
                             <?
                            } else {
                            ?>
                            <?
                            $order_info_notif_buyers = $model->db_query($db, "*", "orders", "buyer_id = '".$_SESSION['login']."' AND status in ('active','ditolak','cancel')");
                            if($order_info_notif_buyers['count'] > 0){
                            ?>
                            <li class="nav-item">
                                <a class="nav-link active" href="<?= $config['web']['base_url']; ?>my-orders/active">Aktif <span class="badge bg-info text-dark"><?= $order_info_notif_buyers['count'] ?></span></a>
                            </li>
                            <?
                            } else {
                            ?>
                            <li class="nav-item">
                                <a class="nav-link active" href="<?= $config['web']['base_url']; ?>my-orders/active">Aktif</a>
                            </li>
                            <?
                            }
                            ?>
                            <?
                            $order_info_notif_buyerss = $model->db_query($db, "*", "orders", "buyer_id = '".$_SESSION['login']."' AND status in ('success')");
                            if($order_info_notif_buyerss['count'] > 0){
                            ?>
                            <li class="nav-item">
                                <a class="nav-link"href="<?= $config['web']['base_url']; ?>my-orders/success">Dikirim <span class="badge bg-info text-dark"><?= $order_info_notif_buyerss['count'] ?></span></a>
                            </li>
                            <?
                            } else {
                            ?>
                            <li class="nav-item">
                                <a class="nav-link"href="<?= $config['web']['base_url']; ?>my-orders/success">Dikirim</a>
                            </li>
                            <?
                            }
                            ?>
                            <li class="nav-item">
                                <a class="nav-link " href="<?= $config['web']['base_url']; ?>my-orders/complete" >Selesai</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link " href="<?= $config['web']['base_url']; ?>my-orders/cancel" role="tab">Dibatalkan</a>
                            </li>
                            <?
                            }
                            ?>
                        </ul>
                                
                                <div class="row">
                                    <?php
                                        if($query_status == 'success'){
                                            
                                            while ($data_target_so = mysqli_fetch_assoc($data_targetraaa)){
                                              $data_target_serviceo = $model->db_query($db, "*", "services", "id = '".$data_target_so['service_id']."'");
                                              $data_sellero = $model->db_query($db, "*", "user", "id = '".$data_target_so['seller_id']."'");
                                         
                                        ?>
                                    <div class="col-lg-4 col-md-6">
                                        <div class="product-single latest-single">
                                            <div class="product-thumb">
                                                <figure>
                                                    <img src="<?= $config['web']['base_url']; ?>file-photo/<?=$data_target_serviceo['rows']['id'] ?>/<?=$data_target_serviceo['rows']['photo'] ?>" alt="<?=$data_target_serviceo['rows']['photo'] ?>" class="img-fluid">
                                                    <figcaption>
                                                        <ul class="list-unstyled">
                                                            <li><a href="<?= $config['web']['base_url'] ?>show-sales/<?= $data_target_so['id']?>">Check</a></li>
                                                        </ul>
                                                    </figcaption>
                                                </figure>
                                            </div>
                                            <!-- Ends: .product-thumb -->
                                            <div class="product-excerpt">
                                                <h5>
                                                    <a href="<?= $config['web']['base_url'] ?>show-sales/<?= $data_target_so['id']?>"><?=$data_target_serviceo['rows']['nama_layanan'] ?></a>
                                                </h5>
                                                <ul class="titlebtm">
                                                    <li>
                                                        <a href="<?= $config['web']['base_url']; ?>user/<?=$data_sellero['rows']['username']?>"><?=$data_sellero['rows']['username']?></a>
                                                    </li>
                                                </ul>
                                                <ul class="titlebtm">
                                                    <li>
                                                        <a href="<?= $config['web']['base_url']; ?>show-sales/<?=$data_target_so['id']?>"><span class="btn btn-sm btn-info">#<?=$data_target_so['id']?></span> </a>
                                                    </li>
                                                </ul>
                                                <ul class="titlebtm">
                                                    <li>
                                                        <p><?= format_date(substr($data_target_so['created_at'], 0, -9)).", ".substr($data_target_so['created_at'], -8)?></p>
                                                    </li>
                                                </ul>
                                                <ul class="titlebtm">
                                                    <li>
                                                        <p>Dikirim : <?= format_date(substr($data_target_so['delivery_time'], 0, -9)).", ".substr($data_target_so['delivery_time'], -8)?></p>
                                                    </li>
                                                </ul>
                                            </div>
                                            <!-- Ends: .product-excerpt -->
                                        </div><!-- Ends: .product-single -->
                                    </div><!-- ends: .col-md-6 -->
                                    <?php
                                    }
                                    }
                                    ?>
                                </div>
                                <div class="row">
                                    <? if ($query_status == 'complete'){
                                      
                                        while ($data_target_sooo = mysqli_fetch_assoc($data_targetraaaaa)){
                                          $data_target_serviceooo = $model->db_query($db, "*", "services", "id = '".$data_target_sooo['service_id']."'");
                                          $data_sellerooo = $model->db_query($db, "*", "user", "id = '".$data_target_sooo['seller_id']."'");
                                     
                                    ?>
                                    <div class="col-lg-4 col-md-6">
                                        <div class="product-single latest-single">
                                            <div class="product-thumb">
                                                <figure>
                                                    <img src="<?= $config['web']['base_url']; ?>file-photo/<?=$data_target_serviceooo['rows']['id'] ?>/<?=$data_target_serviceooo['rows']['photo'] ?>" alt="<?=$data_target_serviceooo['rows']['photo'] ?>" class="img-fluid">
                                                    <figcaption>
                                                        <ul class="list-unstyled">
                                                            <li><a href="<?= $config['web']['base_url'] ?>show-sales/<?= $data_target_sooo['id']?>">Check</a></li>
                                                        </ul>
                                                    </figcaption>
                                                </figure>
                                            </div>
                                            <!-- Ends: .product-thumb -->
                                            <div class="product-excerpt">
                                                <h5>
                                                    <a href="<?= $config['web']['base_url'] ?>show-sales/<?= $data_target_sooo['id']?>"><?=$data_target_serviceooo['rows']['nama_layanan'] ?></a>
                                                </h5>
                                                <ul class="titlebtm">
                                                    <li>
                                                        <a href="<?= $config['web']['base_url']; ?>user/<?=$data_sellerooo['rows']['username']?>"><?=$data_sellerooo['rows']['username']?></a>
                                                    </li>
                                                </ul>
                                                <ul class="titlebtm">
                                                    <li>
                                                        <a href="<?= $config['web']['base_url'] ?>show-sales/<?= $data_target_sooo['id']?>"><span class="btn btn-sm btn-info">#<?=$data_target_sooo['id']?></span></a>
                                                    </li>
                                                </ul>
                                                <ul class="titlebtm">
                                                    <li>
                                                        <p><?= format_date(substr($data_target_sooo['created_at'], 0, -9)).", ".substr($data_target_sooo['created_at'], -8)?></p>
                                                    </li>
                                                </ul>
                                                <ul class="titlebtm">
                                                    <li>
                                                        <p>Dikirim : <?= format_date(substr($data_target_sooo['delivery_time'], 0, -9)).", ".substr($data_target_sooo['delivery_time'], -8)?></p>
                                                    </li>
                                                </ul>
                                            </div>
                                            <!-- Ends: .product-excerpt -->
                                        </div><!-- Ends: .product-single -->
                                    </div><!-- ends: .col-md-6 -->
                                    <?php
                                    }
                                    }
                                    ?>
                                </div>
                                <div class="row">
                                    <? if ($query_status == 'cancel'){
                                      
                                        while ($data_target_soo = mysqli_fetch_assoc($data_targetraaaa)){
                                          $data_target_serviceoo = $model->db_query($db, "*", "services", "id = '".$data_target_soo['service_id']."'");
                                          $data_sellerroo = $model->db_query($db, "*", "user", "id = '".$data_target_soo['seller_id']."'");
                                     
                                    ?>
                                    <div class="col-lg-4 col-md-6">
                                        <div class="product-single latest-single">
                                            <div class="product-thumb">
                                                <figure>
                                                    <img src="<?= $config['web']['base_url']; ?>file-photo/<?=$data_target_serviceoo['rows']['id'] ?>/<?=$data_target_serviceoo['rows']['photo'] ?>" alt="<?=$data_target_serviceoo['rows']['photo'] ?>" class="img-fluid">
                                                    <figcaption>
                                                        <ul class="list-unstyled">
                                                            <li><a href="<?= $config['web']['base_url'] ?>show-sales/<?= $data_target_soo['id']?>">Check</a></li>
                                                        </ul>
                                                    </figcaption>
                                                </figure>
                                            </div>
                                            <!-- Ends: .product-thumb -->
                                            <div class="product-excerpt">
                                                <h5>
                                                    <a href="<?= $config['web']['base_url'] ?>show-sales/<?= $data_target_soo['id']?>"><?=$data_target_serviceoo['rows']['nama_layanan'] ?></a>
                                                </h5>
                                                <ul class="titlebtm">
                                                    <li>
                                                        <a href="<?= $config['web']['base_url']; ?>user/<?=$data_sellerroo['rows']['username']?>"><?=$data_sellerroo['rows']['username']?></a>
                                                    </li>
                                                </ul>
                                                <ul class="titlebtm">
                                                    <li>
                                                        <a href="<?= $config['web']['base_url'] ?>show-sales/<?= $data_target_soo['id']?>"><span class="btn btn-sm btn-info">#<?=$data_target_soo['id']?></span></a> 
                                                    </li>
                                                </ul>
                                                <ul class="titlebtm">
                                                    <li>
                                                        <p><?= format_date(substr($data_target_soo['created_at'], 0, -9)).", ".substr($data_target_soo['created_at'], -8)?></p>
                                                    </li>
                                                </ul>
                                                <ul class="titlebtm">
                                                    <li>
                                                        <p>Dibatalkan/Refund</p>
                                                    </li>
                                                </ul>
                                            </div>
                                            <!-- Ends: .product-excerpt -->
                                        </div><!-- Ends: .product-single -->
                                    </div><!-- ends: .col-md-6 -->
                                    <?php
                                    }
                                    }
                                    ?>
                                </div>
                                <div class="row">
                                      
                                <? if($query_status == 'active') {
                                  while ($data_target_sy = mysqli_fetch_assoc($data_targetraa)){
                                      $data_target_servicey = $model->db_query($db, "*", "services", "id = '".$data_target_sy['service_id']."'");
                                      $data_seller = $model->db_query($db, "*", "user", "id = '".$data_target_sy['seller_id']."'");
                                  ?>
                                    <div class="col-lg-4 col-md-6">
                                        <div class="product-single latest-single">
                                            <div class="product-thumb">
                                                <figure>
                                                    <img src="<?= $config['web']['base_url']; ?>file-photo/<?=$data_target_servicey['rows']['id'] ?>/<?=$data_target_servicey['rows']['photo'] ?>" alt="<?=$data_target_servicey['rows']['photo'] ?>" class="img-fluid">
                                                    <figcaption>
                                                        <ul class="list-unstyled">
                                                            <li><a href="<?= $config['web']['base_url'] ?>show-sales/<?= $data_target_sy['id']; ?>">Check</a></li>
                                                        </ul>
                                                    </figcaption>
                                                </figure>
                                            </div>
                                            <!-- Ends: .product-thumb -->
                                            <div class="product-excerpt">
                                                <h5>
                                                    <a href="<?= $config['web']['base_url'] ?>show-sales/<?= $data_target_sy['id']; ?>"><?=$data_target_servicey['rows']['nama_layanan'] ?></a>
                                                </h5>
                                                <ul class="titlebtm">
                                                    <li>
                                                        <a href="<?= $config['web']['base_url']; ?>user/<?=$data_seller['rows']['username']?>"><?=$data_seller['rows']['username']?></a>
                                                    </li>
                                                </ul>
                                                <ul class="titlebtm">
                                                    <li>
                                                        <a href="<?= $config['web']['base_url'] ?>show-sales/<?= $data_target_sy['id']?>"><span class="btn btn-sm btn-info">#<?=$data_target_sy['id']?></span> </a>
                                                    </li>
                                                </ul>
                                                <ul class="titlebtm">
                                                    <li>
                                                        <p><?= format_date(substr($data_target_sy['created_at'], 0, -9)).", ".substr($data_target_sy['created_at'], -8)?></p>
                                                    </li>
                                                </ul>
                                                <ul class="titlebtm">
                                                <?php if($data_target_sy['status'] == 'ditolak'){
                                                ?>
                                                <li>
                                                    <p><font color="red">Kiriman Kamu Ditolak Pembeli, Segara Revisi</font></p>
                                                </li>
                                                <?
                                                }?>
                                                </ul>
                                                <ul class="titlebtm">
                                                    <li>
                                                        <p>Est Dikirim : <?= format_date(substr($data_target_sy['send_before'], 0, -9)).", ".substr($data_target_sy['send_before'], -8)?></p>
                                                    </li>
                                                </ul>
                                            </div>
                                            <!-- Ends: .product-excerpt -->
                                        </div><!-- Ends: .product-single -->
                                    </div><!-- ends: .col-md-6 -->
                                    <?      
                                    }    
                                    }
                                    ?>
                                    </div>
                                <div class="row">
                                      
                                <? if(!$query_status) {
                                  while ($data_target_s = mysqli_fetch_assoc($data_targetraa)){
                                      $data_target_service = $model->db_query($db, "*", "services", "id = '".$data_target_s['service_id']."'");
                                      $data_sellera = $model->db_query($db, "*", "user", "id = '".$data_target_s['seller_id']."'");
                                  ?>
                                    <div class="col-lg-4 col-md-6">
                                        <div class="product-single latest-single">
                                            <div class="product-thumb">
                                                <figure>
                                                    <img src="<?= $config['web']['base_url']; ?>file-photo/<?=$data_target_service['rows']['id'] ?>/<?=$data_target_service['rows']['photo'] ?>" alt="<?=$data_target_service['rows']['photo'] ?>" class="img-fluid">
                                                    <figcaption>
                                                        <ul class="list-unstyled">
                                                            <li><a href="<?= $config['web']['base_url'] ?>show-sales/<?= $data_target_s['id']?>">Check</a></li>
                                                        </ul>
                                                    </figcaption>
                                                </figure>
                                            </div>
                                            <!-- Ends: .product-thumb -->
                                            <div class="product-excerpt">
                                                <h5>
                                                    <a href="<?= $config['web']['base_url'] ?>show-sales/<?= $data_target_s['id']?>"><?=$data_target_service['rows']['nama_layanan'] ?></a>
                                                </h5>
                                                <ul class="titlebtm">
                                                    <li>
                                                        <a href="<?= $config['web']['base_url']; ?>user/<?=$data_sellera['rows']['username']?>"><?=$data_sellera['rows']['username']?></a>
                                                    </li>
                                                </ul>
                                                <ul class="titlebtm">
                                                    <li>
                                                        <a href="<?= $config['web']['base_url'] ?>show-sales/<?= $data_target_s['id']?>"><span class="btn btn-sm btn-info">#<?=$data_target_s['id']?></span> </a>
                                                    </li>
                                                </ul>
                                                <ul class="titlebtm">
                                                    <li>
                                                        <p><?= format_date(substr($data_target_s['created_at'], 0, -9)).", ".substr($data_target_s['created_at'], -8)?></p>
                                                    </li>
                                                </ul>
                                                <ul class="titlebtm">
                                                <?php if($data_target_s['status'] == 'ditolak'){
                                                ?>
                                                <li>
                                                    <p><font color="red">Kiriman Kamu Ditolak Pembeli, Segara Revisi</font></p>
                                                </li>
                                                <?
                                                }?>
                                                </ul>
                                                <ul class="titlebtm">
                                                    <li>
                                                        <p>Est Dikirim : <?= format_date(substr($data_target_s['send_before'], 0, -9)).", ".substr($data_target_s['send_before'], -8)?></p>
                                                    </li>
                                                </ul>
                                            </div>
                                            <!-- Ends: .product-excerpt -->
                                        </div><!-- Ends: .product-single -->
                                    </div><!-- ends: .col-md-6 -->
                                    <?      
                                    }    
                                    }
                                    ?>
                                </div>
                                  
                                
                        <!--<div class="text-center m-top-20">-->
                        <!--    <a href="" class="btn btn--lg btn-primary">All New Products</a>-->
                        <!--</div>-->
                                    <?
                                    if(!$query_status || $query_status == 'active'){
                                    ?>
                                    <nav class="pagination-default">
                                        <ul class="pagination">
                                            <? if($pageActive > 1){ 
                                            ?>
                                            <li class="page-item">
                                                <a class="page-link" href="<?= $config['web']['base_url']; ?>my-orders/active/<?= $pageActive - 1 ?>" aria-label="Previous">
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
                                                <li class="page-item active"><a class="page-link" href="<?= $config['web']['base_url']; ?>my-orders/active/<?= $i; ?>"><?= $i ?></a></li>
                                                <? } else {?>
                                                <li class="page-item"><a class="page-link" href="<?= $config['web']['base_url']; ?>my-orders/active/<?= $i; ?>"><?= $i ?></a></li>
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
                                                <a class="page-link" href="<?= $config['web']['base_url']; ?>my-orders/active/<?= $pageActive + 1 ?>" aria-label="Next">
                                                    <span aria-hidden="true"><i class="fa fa-long-arrow-right"></i></span>
                                                    <span class="sr-only">Next</span>
                                                </a>
                                            </li>
                                            <?
                                            }?>
                                            
                                        </ul>
                                    </nav><!-- Ends: .pagination-default --><br>
                                    <?
                                    }
                                    ?>
                                    
                                    <?
                                    if($query_status == 'success'){
                                    ?>
                                    <nav class="pagination-default">
                                        <ul class="pagination">
                                            <? if($pageActive > 1){ 
                                            ?>
                                            <li class="page-item">
                                                <a class="page-link" href="<?= $config['web']['base_url']; ?>my-orders/success/<?= $pageActive - 1 ?>" aria-label="Previous">
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
                                                <li class="page-item active"><a class="page-link" href="<?= $config['web']['base_url']; ?>my-orders/success/<?= $i; ?>"><?= $i ?></a></li>
                                                <? } else {?>
                                                <li class="page-item"><a class="page-link" href="<?= $config['web']['base_url']; ?>my-orders/success/<?= $i; ?>"><?= $i ?></a></li>
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
                                                <a class="page-link" href="<?= $config['web']['base_url']; ?>my-orders/success/<?= $pageActive + 1 ?>" aria-label="Next">
                                                    <span aria-hidden="true"><i class="fa fa-long-arrow-right"></i></span>
                                                    <span class="sr-only">Next</span>
                                                </a>
                                            </li>
                                            <?
                                            }?>
                                            
                                        </ul>
                                    </nav><!-- Ends: .pagination-default --><br>
                                    <?
                                    }
                                    ?>
                                    
                                    <?
                                    if($query_status == 'complete'){
                                    ?>
                                    <nav class="pagination-default">
                                        <ul class="pagination">
                                            <? if($pageActive > 1){ 
                                            ?>
                                            <li class="page-item">
                                                <a class="page-link" href="<?= $config['web']['base_url']; ?>my-orders/complete/<?= $pageActive - 1 ?>" aria-label="Previous">
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
                                                <li class="page-item active"><a class="page-link" href="<?= $config['web']['base_url']; ?>my-orders/complete/<?= $i; ?>"><?= $i ?></a></li>
                                                <? } else {?>
                                                <li class="page-item"><a class="page-link" href="<?= $config['web']['base_url']; ?>my-orders/complete/<?= $i; ?>"><?= $i ?></a></li>
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
                                                <a class="page-link" href="<?= $config['web']['base_url']; ?>my-orders/complete/<?= $pageActive + 1 ?>" aria-label="Next">
                                                    <span aria-hidden="true"><i class="fa fa-long-arrow-right"></i></span>
                                                    <span class="sr-only">Next</span>
                                                </a>
                                            </li>
                                            <?
                                            }?>
                                            
                                        </ul>
                                    </nav><!-- Ends: .pagination-default --><br>
                                    <?
                                    }
                                    ?>
                                    
                                    <?
                                    if($query_status == 'cancel'){
                                    ?>
                                    <nav class="pagination-default">
                                        <ul class="pagination">
                                            <? if($pageActive > 1){ 
                                            ?>
                                            <li class="page-item">
                                                <a class="page-link" href="<?= $config['web']['base_url']; ?>my-orders/cancel/<?= $pageActive - 1 ?>" aria-label="Previous">
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
                                                <li class="page-item active"><a class="page-link" href="<?= $config['web']['base_url']; ?>my-orders/cancel/<?= $i; ?>"><?= $i ?></a></li>
                                                <? } else {?>
                                                <li class="page-item"><a class="page-link" href="<?= $config['web']['base_url']; ?>my-orders/cancel/<?= $i; ?>"><?= $i ?></a></li>
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
                                                <a class="page-link" href="<?= $config['web']['base_url']; ?>my-orders/cancel/<?= $pageActive + 1 ?>" aria-label="Next">
                                                    <span aria-hidden="true"><i class="fa fa-long-arrow-right"></i></span>
                                                    <span class="sr-only">Next</span>
                                                </a>
                                            </li>
                                            <?
                                            }?>
                                            
                                        </ul>
                                    </nav><!-- Ends: .pagination-default --><br>
                                    <?
                                    }
                                    ?>
                                    
                    </div><!-- Ends: .product-list -->
                  </div> <!-- Ends: .container -->
            </section>





<?php
require '../template/footer.php';

?>