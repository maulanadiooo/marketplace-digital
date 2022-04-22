<?
require "../../web.php";
require "../../lib/check_session_admin.php";
require "../../lib/is_login.php";

$title = "Semua Request";
require "../lib/sidebar.php";
require "../lib/header.php";

$request = mysqli_query($db, "SELECT * FROM permintaan_pembeli WHERE status = 'active' ORDER BY created_at DESC");
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
								<li class="breadcrumb-item active" aria-current="page">All Request</li>
							</ol>
						</nav>
					</div>
				</div>
				<!--end breadcrumb-->
				
				<h6 class="mb-0 text-uppercase">All Request</h6>
				<hr/>
				<div class="card">
					<div class="card-body">
						<div class="table-responsive">
							<table id="example" class="table table-striped table-bordered">
								<thead>
									<tr>
										<th>Username</th>
										<th>Permintaan</th>
										<th>Estimasi Harga</th>
										<th>Lama Pengiriman</th>
										<th>Aksi</th>
									</tr>
								</thead>
								<tbody>
								    <?
								    while ($request_assoc = mysqli_fetch_assoc($request)){
								        $user = $model->db_query($db, "*", "user", "id = '".$request_assoc['user_id']."'"); 
                                        
								    ?>    
								    <tr>
										<td><a href="<?= $config['web']['base_url']; ?>user/<?=$user['rows']['username']?>" target="_blank"><?=$user['rows']['username']?></td>
										<td><?=ucfirst($request_assoc['permintaan'])?></td>
										<td>Rp <?= number_format($request_assoc['budget'],0,',','.') ?></td>
										<td><?= $request_assoc['jangka_waktu'] ?> Hari</td>
										<td>
										    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#exampleModalDelete<?=$request_assoc['id']?>"><i class="lni lni-close"></i></button>
										</td>
									</tr>
									
										<div class="modal fade" id="exampleModalDelete<?=$request_assoc['id']?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
											<div class="modal-dialog">
												<div class="modal-content">
												    <form action="<?= $config['web']['base_url']; ?>administrator/services/delete_request.php" method="post">
													<div class="modal-header">
														<h5 class="modal-title" id="exampleModalLabel">Delete Request</h5>
														<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
													</div>
													<div class="modal-body">
												
                                                  
                                                  <div class="modal-body">
                                                        <input type="hidden" name="delid" value="<?=$request_assoc['id']?>">
                                                    <div class="col-12">
                										<textarea name="ket_category" class="form-control" id="InputKet"  rows="3" disabled>Anda Yakin Ingin Menghapus Permintaan "<?=$request_assoc['permintaan']?>" Ini ? </textarea>
                									</div>
                                                  </div>
													</div>
													<div class="modal-footer">
														<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
														<button type="submit" class="btn btn-primary">Ya</button>
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