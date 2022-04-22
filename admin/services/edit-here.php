<?
require "../../web.php";
require "../../lib/check_session_admin.php";
require "../../lib/is_login.php";

$title = "Semua Produk";
require "../lib/sidebar.php";
require "../lib/header.php";

$services = mysqli_query($db, "SELECT * FROM services ORDER BY created_at DESC");
?>

<div class="page-wrapper">
			<div class="page-content">
				<!--breadcrumb-->
				<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
					<div class="breadcrumb-title pe-3">Product</div>
					<div class="ps-3">
						<nav aria-label="breadcrumb">
							<ol class="breadcrumb mb-0 p-0">
								<li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
								</li>
								<li class="breadcrumb-item active" aria-current="page">All Product</li>
							</ol>
						</nav>
					</div>
				</div>
				<!--end breadcrumb-->
				
				<h6 class="mb-0 text-uppercase">All Product</h6>
				<hr/>
				<div class="card">
					<div class="card-body">
						<div class="table-responsive">
							<table id="example" class="table table-striped table-bordered">
								<thead>
									<tr>
										<th>Username</th>
										<th>Kategori</th>
										<th>Layanan</th>
										<th>Status</th>
										<th>Featured</th>
										<th>Tanggal</th>
										<th>Aksi</th>
									</tr>
								</thead>
								<tbody>
								    <?
								    while ($services_assoc = mysqli_fetch_assoc($services)){
								        $user = $model->db_query($db, "*", "user", "id = '".$services_assoc['author']."'"); 
                                        $category = $model->db_query($db, "*", "categories", "id = '".$services_assoc['categories_id']."'"); 
                                        if($services_assoc['featured'] == 1){
                                            $featured = 'Ya';
                                        } else {
                                            $featured = 'Tidak';
                                        }
                                        if($services_assoc['premium'] == 1){
                                            $premium = 'Ya';
                                        } else {
                                            $premium = 'Tidak';
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
										<td><a href="<?= $config['web']['base_url']; ?>user/<?=$user['rows']['username']?>" target="_blank"><?=$user['rows']['username']?></td>
										<td><?=ucfirst($category['rows']['category'])?></td>
										<td><a href="<?= $config['web']['base_url']; ?>product/<?=$services_assoc['id']?>/<?=$services_assoc['url']?>" target="_blank"><?=$services_assoc['nama_layanan']?></td>
										<td><span class="badge bg-<?=$badge?>"><?=$status?></span></td>
										<td><?= $featured ?></td>
										<td><?= (substr($services_assoc['created_at'], 0, -9)); ?></td>
										<td><button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal<?=$services_assoc['id']?>"><i class="fadeIn animated bx bx-message-edit"></i></button></td>
									</tr>
									
										<!-- Button trigger modal -->
										<!--<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">Basic modal</button>-->
										<!-- Modal -->
										<div class="modal fade" id="exampleModal<?=$services_assoc['id']?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
											<div class="modal-dialog">
												<div class="modal-content">
												    <form action="<?= $config['web']['base_url']; ?>administrator/services/edit.php" method="post">
													<div class="modal-header">
														<h5 class="modal-title" id="exampleModalLabel">Edit Produk ID#<?=$services_assoc['id']?></h5>
														<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
													</div>
													<div class="modal-body">
												
                                                  
                                                  <div class="modal-body">
                                                      <input type="hidden" name="cuid" value="<?=$services_assoc['id']?>">
                                                    <div class="rating_field">
                                                        <label>Status Akun</label>
                                                        <select name="status" class="form-select form-select-sm mb-3" aria-label=".form-select-sm example">
                        									<option value="<?=$services_assoc['status']?>"><?= $status?> <span>( Saat Ini )</span></option>
                                                            <option value='active'>Aktif</option>
                                                            <option value='revisi'>Tangguhkan</option>
                        								</select>
                                                    </div><br>
                                                    <div class="rating_field">
                                                        <label>Aktifkan Featured</label>
                                                        <select name="featured" class="form-select form-select-sm mb-3" aria-label=".form-select-sm example">
                                                             <option value="<?=$services_assoc['featured']?>"><?= $featured?> <span>( Saat Ini )</span></option>
                                                            <option value='0'>Tidak</option>
                                                            <option value='1'>Ya</option>
                                                        </select>
                                                    </div><br>
                                                    <div class="col-12">
                										<label for="InputReason" class="form-label">Alasan Penangguhan</label>
                										<textarea name="tangguh" class="form-control" id="InputReason" placeholder="Layanan Anda Ditangguhkan Karena....." rows="5"></textarea>
                									</div>
                                                  </div>
													</div>
													<div class="modal-footer">
														<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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
</body>

</html>
<?
require "../lib/footer.php"
?>