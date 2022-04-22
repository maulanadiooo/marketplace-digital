<?php
require '../web.php';
$about = $model->db_query($db, "*", "pages", "id = '3'");

$title = "Badges Seller";
?>
<?php
require '../template/header.php';
?>
<section class="badges author-rank">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="section-title">
                        <h1>Seller Badge</h1>
                        <p>Berikut Merupakan Penjelasan Tentang Badge Pada Penjual</p>
                    </div>
                    <div class="author-badges">
                        <div class="badge-single">
                            <img src="<?=$config['web']['base_url']?>img/svg/recommended.svg" alt="" class="svg">
                            <h4>Recommended Seller</h4>
                            <p>Penjual yang telah berhasil melakukan transaksi hingga selesai masa kliring minimal 1x Penjualan</p>
                        </div>
                        <div class="badge-single">
                            <img src="<?=$config['web']['base_url']?>img/svg/top_seller.svg" alt="" class="svg">
                            <h4>Top Seller</h4>
                            <p>Penjualan lebih dari Rp 1.000.000 yang akan disandingkan dengan golden badge atau diamond badge</p>
                        </div>
                        <div class="badge-single">
                            <img src="<?=$config['web']['base_url']?>img/svg/author_rank_bronze.svg" alt="" class="svg">
                            <h4>Bronze Seller</h4>
                            <p>Total Penjualan Seller Pada Range Rp 500.000,- s/d Rp 1.000.000,-</p>
                        </div>
                        <div class="badge-single">
                            <img src="<?=$config['web']['base_url']?>img/svg/author_rank_golden.svg" alt="" class="svg">
                            <h4>Golden Seller</h4>
                            <p>Total Penjualan Seller Pada Range Rp 1.000.000,- s/d Rp 5.000.000,-</p>
                        </div>
                        <div class="badge-single">
                            <img src="<?=$config['web']['base_url']?>img/svg/author_rank_diamond.svg" alt="" class="svg">
                            <h4>Diamond Seller</h4>
                            <p>Total Penjualan Seller Melebihi Rp 5.000.000,-</p>
                        </div>
                    </div><!-- ends: .author-rank-badges -->
                </div><!-- ends: .col-md-12 -->
            </div>
        </div>
    </section><!-- ends: .author-rank -->    
    
   <?php
require '../template/footer.php';

?>