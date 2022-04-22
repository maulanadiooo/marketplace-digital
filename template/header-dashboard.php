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
                        <button class="menu-toggler d-md-none">
                            <span class="icon-menu"></span> Dashboard Menu
                        </button>
                        <ul class="dashboard_menu">
                            <li>
                                <a href="<?= $config['web']['base_url']; ?>setting/"><span class="lnr icon-settings"></span>Pengaturan</a>
                            </li>
                            <li>
                                <a href="<?= $config['web']['base_url']; ?>my-orders/"><span class="lnr icon-basket"></span>Belanjaan</a>
                            </li>
                            <li>
                                <a href="<?= $config['web']['base_url']; ?>add-balance/"><span class="lnr icon-credit-card"></span>Tambah Saldo</a>
                            </li>
                            <li>
                                <a href="<?= $config['web']['base_url']; ?>my-revenue"><span class="lnr icon-chart"></span>Pendapatan</a>
                            </li>
                            <li>
                                <a href="<?= $config['web']['base_url']; ?>create/"><span class="lnr icon-cloud-upload"></span>Upload Produk</a>
                            </li>
                            <li>
                                <a href="<?= $config['web']['base_url']; ?>my-product/"><span class="lnr icon-note"></span>Produk Saya</a>
                            </li>
                            <li>
                                <a href="<?= $config['web']['base_url']; ?>withdraw"><span class="lnr icon-briefcase"></span>Withdrawals</a>
                            </li>
                        </ul><!-- ends: .dashboard_menu -->
                    </div><!-- ends: .col-md-12 -->
                </div><!-- ends: .row -->
            </div><!-- ends: .container -->
        </div><!-- ends: .dashboard_menu_area -->


<?php    

?>
