<?php
require '../web.php';


$user_info = $model->db_query($db, "*", "user", "id = '".$login['id']."'");

$dataPerHalaman = 20;
$orders_infoa = mysqli_query($db, "SELECT * FROM permintaan_pembeli WHERE status = 'active' ORDER BY created_at DESC ");

$jumlahData = mysqli_num_rows($orders_infoa);
$jumlahHalaman = ceil($jumlahData/$dataPerHalaman);

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


$permintaan = mysqli_query($db, "SELECT * FROM permintaan_pembeli WHERE status = 'active' ORDER BY created_at DESC LIMIT $awalData,$dataPerHalaman");




$title = "Daftar Permintaan";
?>
<?php
require '../template/header.php';
?>

<div class="dashboard_contents dashboard_statement_area section--padding">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="dashboard_title_area">
                            <div class="dashboard__title">
                                <h3>Daftar Permintaan</h3>
                            </div>
                        </div>
                    </div><!-- ends: .col-md-12 -->
                </div><!-- ends: .row -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="statement_table table-responsive">
                            <table class="table">
                               <thead>
                                    <tr>
                                        <th>Pembeli</th>
                                        <th>Permintaan</th>
                                        <th>Estimasi Harga</th>
                                        <th>Lama Pengiriman</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <?php
                                while ($orders_info = mysqli_fetch_assoc($permintaan)){
                                    
                                    
                                    $buyer_info = $model->db_query($db, "*", "user", "id = '".$orders_info['user_id']."'");
                                ?>
                                <tbody>
                                    <tr>
                                        <td>
                                            <?
                                            if($buyer_info['rows']['photo'] == null){
                                            ?>
                                            <img src="<?=$config['web']['base_url']?>img/avatar.png" alt="<?=$buyer_info['rows']['username']?>" width="50px" height="50px">
                                            <?
                                            } else {
                                            ?>
                                            <img src="<?=$config['web']['base_url']?>user-photo/<?=$buyer_info['rows']['photo']?>" alt="<?=$buyer_info['rows']['username']?>" width="50px" height="50px">
                                            <?    
                                            }
                                            ?>
                                            <?=$buyer_info['rows']['username']?>
                                            </td>
                                        <td><?=$orders_info['permintaan']?></td>
                                        <td>Rp <?= number_format($orders_info['budget'],0,',','.') ?></td>
                                        <td><?=$orders_info['jangka_waktu']?> Hari</td>
                                        <?
                                        if(!isset($_SESSION['login'])){
                                        ?>
                                        <td><a href="<?=$config['web']['base_url']?>signin/" class="btn btn-primary" aria-hidden="true" title="Masuk Untuk Mengirim Penawaran"><i class="fa fa-sign-in" aria-hidden="true"></i>Masuk</a></td>
                                        <?    
                                        } else {
                                        ?>    
                                        <td>
                                            <a data-toggle="modal" data-target="#modalSaya<?=$orders_info['id']?>">
                                                 <button class="btn btn-primary" aria-hidden="true" title="Kirim Penawaran"><i class="fa fa-paper-plane">Kirim Penawaran</i></button>
                                            </a>
                                        </td>
                                        <div class="modal fade" id="modalSaya<?=$orders_info['id']?>" tabindex="-1" role="dialog" aria-labelledby="modalSayaLabel" aria-hidden="true">
                                              <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                
                                                <form action="<?= $config['web']['base_url']; ?>sendoffer.php" method="post">
                                                <input type="hidden" name="csrf_token" value="<?php echo $config['csrf_token'] ?>">
                                                  <div class="modal-header">
                                                    <h5 class="modal-title" id="modalSayaLabel">Kirim Penawaran</h5>
                                                    <span><?=$orders_info['permintaan']?></span>
                                                  </div>
                                                  <div class="modal-body">
                                                    <div class="rating_field">
                                                        <label for="produk">Pilih Produk</label>
                                                        <div class="select-wrap select-wrap2">
                                                            <select name="produk" class="text_field" required>
                                                                <option value="0">Silahkan Pilih...</option>
                                                                <?php
                                                                
                            									$category_select = $model->db_query($db, "*", "services", "author = '".$_SESSION['login']."' AND status ='active' ");
                            									
                            									if ($category_select['count'] == 1) {
                            										print('<option value="'.$category_select['rows']['id'].'">'.$category_select['rows']['nama_layanan'].'</option>');
                            									} else {
                            									foreach ($category_select['rows'] as $key) {
                            										print('<option value="'.$key['id'].'">'.$key['nama_layanan'].'</option>');
                            									}
                            									}
                            									?>
                                                            </select>
                                                            <span class="lnr icon-arrow-down"></span>
                                                        </div>
                                                    </div><br>
                                                    <input type="hidden" name="puid" value="<?=$orders_info['id']?>">
                                                    <input type="hidden" name="uid" value=" <?=$buyer_info['rows']['id']?>">
                                                    <div class="rating_field">
                                                        <label for="rating_field">Pesan</label>
                                                        <textarea name="message_off"></textarea>
                                                    </div>
                                                  </div>
                                                  <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                                    <button type="submit" class="btn btn-primary">Oke</button>
                                                  </div>
                                                  </form>
                                                </div>
                                              </div>
                                            </div>
                                        <?    
                                        }
                                        ?>
                                        
                                    </tr>
                                </tbody>
                                
                                <?php
                                }  
                                ?>
                            </table>
                            <nav class="pagination-default">
                                <ul class="pagination">
                                    <? if($pageActive > 1){ 
                            ?>
                            <li class="page-item">
                                <a class="page-link" href="<?= $config['web']['base_url']; ?>request/<?= $pageActive - 1 ?>" aria-label="Previous">
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
                                <li class="page-item active"><a class="page-link" href="<?= $config['web']['base_url']; ?>request<?= $i; ?>"><?= $i ?></a></li>
                                <? } else {?>
                                <li class="page-item"><a class="page-link" href="<?= $config['web']['base_url']; ?>request/<?= $i; ?>"><?= $i ?></a></li>
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
                                <a class="page-link" href="<?= $config['web']['base_url']; ?>request/<?= $pageActive + 1 ?>" aria-label="Next">
                                    <span aria-hidden="true"><i class="fa fa-long-arrow-right"></i></span>
                                    <span class="sr-only">Next</span>
                                </a>
                            </li>
                            <?
                            }?>
                                </ul>
                            </nav><!-- Ends: .pagination-default -->
                            
                        </div><!-- ends: .statement_table -->
                    </div>
                </div><!-- ends: .row -->
            </div><!-- ends: .container -->
        </div><!-- ends: .dashboard_menu_area -->