<?
require "../../web.php";
require "../../lib/check_session_admin.php";
require "../../lib/is_login.php";

$title = "Semua Produk";
require "../lib/header.php";
require "../lib/sidebar.php";

$services = mysqli_query($db, "SELECT * FROM services ORDER BY created_at DESC");
?>

<div class="main-panel">
	<div class="content">
		<div class="page-inner">
    		    <div class="page-header">
    				<h4 class="page-title">Product</h4> 
    				<ul class="breadcrumbs">
    					<li class="nav-home">
    						<a href="#">
    							<i class="flaticon-home"></i>
    						</a>
    					</li>
    					<li class="separator">
    						<i class="flaticon-right-arrow"></i>
    					</li>
    					<li class="nav-item">
    						<a href="#">All Product</a>
    					</li>
    				</ul>
    			</div>
				
    				<div class="row">
    			    <div class="col-md-12">
    							<div class="card">
    								<div class="card-header">
    									<h4 class="card-title">All Product</h4>
    								</div>
    								<div class="card-body">
    									<div class="table-responsive">
    										<table id="basic-datatables" class="display table table-striped table-hover" >
    											<thead>
    												<tr>
    												    <th>ID</th>
                										<th>Username</th>
                										<th>Kategori</th>
                										<th>Layanan</th>
                										<th>Status</th>
                										<th>Featured</th>
                										<th>Premium</th>
                										<th>Tanggal</th>
                										<th>Aksi</th>
                									</tr>
    											</thead>
    											<tfoot>
    												<tr>
    												    <th>ID</th>
                										<th>Username</th>
                										<th>Kategori</th>
                										<th>Layanan</th>
                										<th>Status</th>
                										<th>Featured</th>
                										<th>Premium</th>
                										<th>Tanggal</th>
                										<th>Aksi</th>
                									</tr>
    											</tfoot>
    											<tbody>
                								    <?
                								    while ($services_assoc = mysqli_fetch_assoc($services)){
                								        $user = $model->db_query($db, "*", "user", "id = '".$services_assoc['author']."'"); 
                                                        $category = $model->db_query($db, "*", "categories", "id = '".$services_assoc['categories_id']."'"); 
                                                        if($services_assoc['featured'] == 1){
                                                            $featured = 'Ya';
                                                            $tgl = ' s/d '.format_date(substr($services_assoc['expired_featured'], 0, -9)).", ".substr($services_assoc['expired_featured'], -8).' WIB';
                                                        } else {
                                                            $featured = 'Tidak';
                                                            $tgl = "";
                                                        }
                                                        if($services_assoc['premium'] == 1){
                                                            $premium = 'Ya';
                                                            $tgl_premi = ' s/d '.format_date(substr($services_assoc['expired_premium'], 0, -9)).", ".substr($services_assoc['expired_premium'], -8).' WIB';
                                                        } else {
                                                            $premium = 'Tidak';
                                                            $tgl_premi = "";
                                                        }
                                                        
                                                        if($services_assoc['status'] == 'active'){
                                                            $status = 'Aktif';
                                                            $badge = 'success';
                                                        } elseif($services_assoc['status'] == 'not-active'){
                                                            $status = 'Tidak Aktif';
                                                            $badge = 'info';
                                                        } elseif($services_assoc['status'] == 'pending'){
                                                            $status = 'Menunggu Persetujuan';
                                                            $badge = 'warning';
                                                        } elseif($services_assoc['status'] == 'delete' && $services_assoc['deleted'] == '1'){
                                                            $status = 'Akan Di Delete';
                                                            $badge = 'danger';
                                                        } elseif($services_assoc['status'] == 'revisi'){
                                                            $status = 'Perlu Revisi';
                                                            $badge = 'warning';
                                                        } 
                								    ?>    
                								    <tr>
                								        <td><?=$services_assoc['id']?></td>
                										<td><a href="<?= $config['web']['base_url']; ?>user/<?=$user['rows']['username']?>" target="_blank"><?=$user['rows']['username']?></td>
                										<td><?=ucfirst($category['rows']['category'])?></td>
                										<td><a href="<?= $config['web']['base_url']; ?>product/<?=$services_assoc['id']?>/<?=$services_assoc['url']?>" target="_blank"><?=$services_assoc['nama_layanan']?></td>
                										<td><span class="badge bg-<?=$badge?>"><?=$status?></span></td>
                										<td><?= $featured ?><?=$tgl?> </td>
                										<td><?= $premium ?><?=$tgl_premi?></td>
                										<td><?= (substr($services_assoc['created_at'], 0, -9)); ?></td>
                										<td><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal<?=$services_assoc['id']?>"><i class="far fa-edit"></i></button></td>
                									</tr>
                									
                										<!-- Button trigger modal -->
                										<!--<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">Basic modal</button>-->
                										<!-- Modal -->
                										<div class="modal fade" id="exampleModal<?=$services_assoc['id']?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                											<div class="modal-dialog">
                												<div class="modal-content">
                												    <form action="<?= $config['web']['base_url']; ?>administrator/services/edit.php" method="post">
                													<div class="modal-header">
                														<h5 class="modal-title" id="exampleModalLabel">Edit Produk ID#<?=$services_assoc['id']?></h5>
                														<button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                													</div>
                													<div class="modal-body">
                												
                                                                  
                                                                  <div class="modal-body">
                                                                      <input type="hidden" name="cuid" value="<?=$services_assoc['id']?>">
                                                                    <div class="form-group">
                                										<label for="squareSelect">Status Akun</label>
                                										<select name="status" class="form-control input-square" id="squareSelect">
                                											<option value="<?=$services_assoc['status']?>"><?= $status?> <span>( Saat Ini )</span></option>
                                                                            <option value='active'>Aktif</option>
                                                                            <option value='revisi'>Tangguhkan</option>
                                										</select>
                                									</div>
                                									<div class="form-group">
                                										<label for="squareSelect">Aktifkan Featured</label>
                                										<select name="featured" class="form-control input-square" id="squareSelect">
                                											<option value="<?=$services_assoc['featured']?>"><?= $featured?> <span>( Saat Ini )</span></option>
                                                                            <option value='0'>Tidak</option>
                                                                            <option value='1'>Ya</option>
                                										</select>
                                									</div>
                                									<div class="form-group">
                                										<label for="squareSelect">Durasi Featured (hari)</label>
                                										<select name="durasi_featured" class="form-control input-square" id="squareSelect">
                                											<?php
                                                                                for($i = 1; $i <= 14; $i++){
                                                                                ?>
                                                                                <option value="<?=$i?>"><?=$i?></option>
                                                                                <?    
                                                                                }
                                                                                ?>
                                										</select>
                                									</div>
                                                                    <div class="form-group">
                                										<label for="squareSelect">Aktifkan Premium</label>
                                										<select name="premium" class="form-control input-square" id="squareSelect">
                                											<option value="<?=$services_assoc['premium']?>"><?= $premium?> <span>( Saat Ini )</span></option>
                                                                            <option value='0'>Tidak</option>
                                                                            <option value='1'>Ya</option>
                                										</select>
                                									</div>
                                									<div class="form-group">
                                										<label for="squareSelect">Durasi Premium (hari)</label>
                                										<select name="durasi_premium" class="form-control input-square" id="squareSelect">
                                											<?php
                                                                                for($i = 1; $i <= 14; $i++){
                                                                                ?>
                                                                                <option value="<?=$i?>"><?=$i?></option>
                                                                                <?    
                                                                                }
                                                                                ?>
                                										</select>
                                									</div>
                                                                    <br>
                                                                    <div class="col-12">
                                										<label for="InputReason" class="form-label">Alasan Penangguhan</label>
                                										<textarea name="tangguh" class="form-control" id="InputReason" placeholder="Layanan Anda Ditangguhkan Karena....." rows="5"></textarea>
                                									</div>
                                                                  </div>
                													</div>
                													<div class="modal-footer">
                														<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                														<button type="submit" class="btn btn-primary">Save changes</button>
                													</div>
                													
                                                                  </form>
                												</div>
                											</div>
                										</div>
                									<?
                								    }
                								    ?>
                								</tbody>
    										</table>
    									</div>
    								</div>
    							</div>
    						</div>
    			</div>
			 </div>	
			</div>
		</div>
</body>

</html>
<?
require "../lib/footer.php"
?>