<?
require "../../web.php";
require "../../lib/check_session_admin.php";
require "../../lib/is_login.php";

$title = "Semua Request";
require "../lib/header.php";
require "../lib/sidebar.php";

$request = mysqli_query($db, "SELECT * FROM permintaan_pembeli WHERE status = 'active' ORDER BY created_at DESC");
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
						<a href="#">All Request Product</a>
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
    													<th>Username</th>
                										<th>Permintaan</th>
                										<th>Estimasi Harga</th>
                										<th>Lama Pengiriman</th>
                										<th>Aksi</th>
    												</tr>
    											</thead>
    											<tfoot>
    												<tr>
    													<th>Username</th>
                										<th>Permintaan</th>
                										<th>Estimasi Harga</th>
                										<th>Lama Pengiriman</th>
                										<th>Aksi</th>
    												</tr>
    											</tfoot>
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
            										    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#exampleModalDelete<?=$request_assoc['id']?>"><i class="fas fa-trash"></i></button>
            										</td>
            									</tr>
            									
            										<div class="modal fade" id="exampleModalDelete<?=$request_assoc['id']?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            											<div class="modal-dialog">
            												<div class="modal-content">
            												    <form action="<?= $config['web']['base_url']; ?>administrator/services/delete_request.php" method="post">
            													<div class="modal-header">
            														<h5 class="modal-title" id="exampleModalLabel">Delete Request</h5>
            														<button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
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
            														<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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
				</div>
				
			</div>
		</div>
</body>

</html>
<?
require "../lib/footer.php"
?>