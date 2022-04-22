<?php

?>
<section class="breadcrumb-area">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="breadcrumb-contents">
                        <h2 class="page-title"><?= $title ?></h2>
                        <div class="breadcrumb">
                            <ul>
                                <li>
                                    <a href="#">Home</a>
                                </li>
                                <li class="active">
                                    <a href="#"><?= $title ?></a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div><!-- end .col-md-12 -->
            </div><!-- end .row -->
        </div><!-- end .container -->
    </section><!-- ends: .breadcrumb-area -->
    <section class="dashboard-area">
        <div class="dashboard_menu_area">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <?
                        $now = date("Y-m-d 23:59:59");
                        $service_pending = $model->db_query($db, "*", "services", "status = 'pending' ");
                        $req_pending = $model->db_query($db, "*", "permintaan_pembeli", "status = 'pending' ");
                        $wd_pending = $model->db_query($db, "*", "withdraw_request", "status = 'pending' AND estimasi_wd <= '$now' ");
                        $depo_pending = $model->db_query($db, "*", "deposit", "status = 'pending' ");
                        ?>
                        <button class="menu-toggler d-md-none">
                            <span class="icon-menu"></span> Dashboard Menu
                        </button>
                        <ul class="dashboard_menu">
                            <li>
                                <a href="<?= $config['web']['base_url']; ?>admin-ganteng/"><span class="lnr icon-home"></span>Home</a>
                            </li>
                            <li>
                                <a href="<?= $config['web']['base_url']; ?>admin/pages/"><span class="fa fa-file-o"></span>Halaman</a>
                            </li>
                            <li>
                                <a href="<?= $config['web']['base_url']; ?>admin/setting-web"><span class="lnr icon-settings"></span>Pengaturan</a>
                            </li>
                            <li>
                                <a href="<?= $config['web']['base_url']; ?>admin/users/"><span class="lnr icon-user"></span>Users</a>
                            </li>
                            <li>
                                <a href="<?= $config['web']['base_url']; ?>admin/services/"><span class="lnr icon-basket"></span>Semua Produk</a>
                            </li>
                            <?
                            if($service_pending['count'] > 0){
                            ?> 
                            <li>
                                <a href="<?= $config['web']['base_url']; ?>admin/services-pending/"><span class="lnr icon-basket"></span>Produk Pending <span class="badge badge-light"><?=$service_pending['count']?></span></a>
                            </li>
                            <?
                            } else {
                            ?>    
                            <li>
                                <a href="<?= $config['web']['base_url']; ?>admin/services-pending/"><span class="lnr icon-basket"></span>Produk Pending</a>
                            </li>
                            <?    
                            }
                            if($wd_pending['count'] > 0){
                            ?> 
                            <li>
                                <a href="<?= $config['web']['base_url']; ?>admin/withdraw-request/"><span class="lnr icon-briefcase"></span>Permintaan Penarikan <span class="badge badge-light"><?=$wd_pending['count']?></span></a>
                            </li>
                            <?
                            } else {
                            ?>  
                            <li>
                            <a href="<?= $config['web']['base_url']; ?>admin/withdraw-request/"><span class="lnr icon-briefcase"></span>Permintaan Penarikan</a>
                            </li>
                            <?    
                            }
                            ?>
                            <li>
                                <a href="<?= $config['web']['base_url']; ?>admin/category/"><span class="lnr icon-list"></span>Kategori</a>
                            </li>
                            <li>
                                <a href="<?= $config['web']['base_url']; ?>admin/bank/"><span class="fa fa-university"></span>Bank Admin</a>
                            </li>
                            <li>
                                <a href="<?= $config['web']['base_url']; ?>admin/bank-wd/"><span class="fa fa-university"></span>Bank Untuk Withdraw</a>
                            </li>
                            <?
                            if($req_pending['count'] > 0){
                            ?> 
                            <li>
                                <a href="<?= $config['web']['base_url']; ?>admin/request-pending/"><span class="lnr icon-basket"></span>Request Permintaan Produk  <span class="badge badge-light"><?=$req_pending['count']?></span></a>
                            </li>
                            <?
                            } else {
                            ?>
                            <li>
                                <a href="<?= $config['web']['base_url']; ?>admin/request-pending/"><span class="lnr icon-basket"></span>Request Permintaan Produk </a>
                            </li>
                            <?
                            }
                            ?>
                            <li>
                                <a href="<?= $config['web']['base_url']; ?>admin/orders/"><span class="lnr icon-basket"></span>Orderan </a>
                            </li>
                            <?
                            if($depo_pending['count'] == 0){
                            ?>
                            <li>
                                <a href="<?= $config['web']['base_url']; ?>admin/deposit/"><span class="lnr icon-basket"></span>Deposit</a>
                            </li>
                            <?    
                            } else {
                            ?>    
                            <li>
                                <a href="<?= $config['web']['base_url']; ?>admin/deposit/"><span class="lnr icon-credit-card"></span>Deposit  <span class="badge badge-light"><?=$depo_pending['count']?></span></a>
                            </li>
                            <?    
                            }
                            ?>
                            <li>
                                <a href="<?= $config['web']['base_url']; ?>admin/report/"><span class="fa fa-exclamation-triangle" aria-hidden="true"></span> Laporan</a>
                            </li>
                            <li>
                                <a href="<?= $config['web']['base_url']; ?>admin/chat/"><span class="fa fa-exclamation-triangle" aria-hidden="true"></span> Chat</a>
                            </li>
                        </ul><!-- ends: .dashboard_menu -->
                    </div><!-- ends: .col-md-12 -->
                </div><!-- ends: .row -->
            </div><!-- ends: .container -->
        </div><!-- ends: .dashboard_menu_area -->


<?php    

?>
