<?
require "../../web.php";
require "../../lib/check_session_admin.php";
require "../../lib/is_login.php";

$title = "Pending Produk";
require "../lib/header.php";
require "../lib/sidebar.php";

$services_pending = mysqli_query($db, "SELECT * FROM services WHERE status = 'pending' ORDER BY created_at DESC");
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
						<a href="#">Pending</a>
					</li>
				</ul>
			</div>
				
				<div class="row">
    			    <div class="col-md-12">
    							<div class="card">
    								<div class="card-header">
    									<h4 class="card-title">Users</h4>
    								</div>
    								<div class="card-body">
    									<div class="table-responsive">
    										<table id="basic-datatables" class="display table table-striped table-hover" >
    											<thead>
    												<tr>
    													<th>User</th>
                                                        <th>Kategori</th>
                                                        <th>Layanan</th>
                                                        <th>Url</th>
                                                        <th>Buyer Information</th>
                                                        <th>Featured</th>
                                                        <th>Premium</th>
                                                        <th>Aksi</th>
    												</tr>
    											</thead>
    											<tfoot>
    												<tr>
    													<th>User</th>
                                                        <th>Kategori</th>
                                                        <th>Layanan</th>
                                                        <th>Url</th>
                                                        <th>Buyer Information</th>
                                                        <th>Featured</th>
                                                        <th>Premium</th>
                                                        <th>Aksi</th>
    												</tr>
    											</tfoot>
    											<tbody>
            								    <?
            								    while ($services_pending_assoc = mysqli_fetch_assoc($services_pending)){
            								        $user = $model->db_query($db, "*", "user", "id = '".$services_pending_assoc['author']."'"); 
                                                    $category = $model->db_query($db, "*", "categories", "id = '".$services_pending_assoc['categories_id']."'"); 
                                                    if($services_pending_assoc['featured'] == 1){
                                                        $featured = 'Ya';
                                                    } else {
                                                        $featured = 'Tidak';
                                                    }
                                                    if($services_pending_assoc['premium'] == 1){
                                                        $premium = 'Ya';
                                                    } else {
                                                        $premium = 'Tidak';
                                                    }
            								    ?>    
            								    <tr>
            										<td><a href="<?= $config['web']['base_url']; ?>user/<?=$user['rows']['username']?>" target="_blank"><?=$user['rows']['username']?></a></td>
                                                    <td><?=ucfirst($category['rows']['category'])?></td>
                                                    <td><a href="<?= $config['web']['base_url']; ?>product/<?=$services_pending_assoc['id']?>/<?=$services_pending_assoc['url']?>" target="_blank"><?=$services_pending_assoc['nama_layanan']?></a></td>
                                                    <td><?=$services_pending_assoc['url']?></td>
                                                    <td><?=$services_pending_assoc['buyer_information']?></td>
                                                    <td><?= $featured ?></td>
                                                    <td><?= $premium ?></td>
            										
            										<td>
            										    <a href="<?= $config['web']['base_url']; ?>administrator/services-pending/action.php?approve=<?=$services_pending_assoc['id']?>"><button class="btn btn-primary" title="Setujui"><i class="far fa-check-square"></i></button></a>
            										    <button title="Revisi" type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal<?=$services_pending_assoc['id']?>"><i class="far fa-window-close"></i></button>
            										</td>
            									</tr>
            									
            										
            										<div class="modal fade" id="exampleModal<?=$services_pending_assoc['id']?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            											<div class="modal-dialog">
            												<div class="modal-content">
            												    <form action="<?= $config['web']['base_url']; ?>administrator/services-pending/revisi.php" method="post">
            													<div class="modal-header">
            														<h5 class="modal-title" id="exampleModalLabel">Revisi Produk ID#<?=$services_pending_assoc['id']?></h5>
            														<button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            													</div>
            													<div class="modal-body">
            												
                                                              
                                                              <div class="modal-body">
                                                                  <input type="hidden" name="suid" value="<?=$services_pending_assoc['id']?>">
                                                                
                                                                <div class="col-12">
                            										<label for="InputReason" class="form-label">Alasan Revisi</label>
                            										<textarea name="alasan_revisi" class="form-control" id="InputReason" placeholder="Silahkan Perbaiki........" rows="5"></textarea>
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

<?
require "../lib/footer.php"
?>