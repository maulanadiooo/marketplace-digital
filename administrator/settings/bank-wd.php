<?
require "../../web.php";
require "../../lib/check_session_admin.php";
require "../../lib/is_login.php";

$title = "Bank Withdraw";
require "../lib/header.php";
require "../lib/sidebar.php";

$bankWd = mysqli_query($db, "SELECT * FROM bank_penarikan ORDER BY bank ASC");
?>

<div class="main-panel">
	<div class="content">
		<div class="page-inner">
        	    <div class="page-header">
        			<h4 class="page-title">Settings</h4>
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
        					<a href="#">Bank Withdraws</a>
        				</li>
        			</ul>
        		</div>
        		<div class="ms-auto">
    				<div class="btn-group">
    					<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">Add Bank</button>
    				</div>
    			</div>
    			<hr/>
				
				<div class="row">
			    <div class="col-md-12">
							<div class="card">
								<div class="card-header">
									<h4 class="card-title">Bank Withdraws</h4>
								</div>
								<div class="card-body">
									<div class="table-responsive">
										<table id="basic-datatables" class="display table table-striped table-hover" >
											<thead>
												<tr>
            										<th></th>
                                                    <th>Bank</th>
                                                    <th>Aksi</th>
            									</tr>
											</thead>
											<tfoot>
												<tr>
            										<th></th>
                                                    <th>Bank</th>
                                                    <th>Aksi</th>
            									</tr>
											</tfoot>
											<tbody>
        								    <?php
                                            $no = 0;
                                            while ($bankWd_assoc = mysqli_fetch_assoc($bankWd)){
                                            if($bankWd_assoc['status']=='active'){
                                                $status = "Aktif";
                                            } else {
                                                $status = "Tidak Aktif";
                                            }
                                            $no++;
                                            ?>  
        								    <tr>
        										<td><?=$no?></td>
                                                <td><?=ucfirst($bankWd_assoc['bank'])?></td>
        										<td><button type="button" class="btn btn-danger" data-toggle="modal" data-target="#exampleModalDelete<?=$bankWd_assoc['id']?>"><i class="fas fa-trash"></i></button></td>
        									</tr>
        									
        									<div class="modal fade" id="exampleModalDelete<?=$bankWd_assoc['id']?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        										<div class="modal-dialog">
        											<div class="modal-content">
        											    <form action="<?= $config['web']['base_url']; ?>administrator/settings/bank-wd-delete.php" method="post">
        												<div class="modal-header">
        													<h5 class="modal-title" id="exampleModalLabel">Hapus Bank: <?=$bankWd_assoc['bank']?></h5>
        													<button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
        												</div>
        												<div class="modal-body">
        											
                                                      
                                                      <div class="modal-body">
                                                            <input type="hidden" name="delid" value="<?=$bankWd_assoc['id']?>">
                                                        <div class="col-12">
                    										<textarea class="form-control" id="InputKet"  rows="5" disabled>Anda Yakin Ingin Menghapus Bank Ini ?</textarea>
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
    <!--Moda add bank-->
    <div class="col">
		<!-- Modal -->
		<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
				    <form action="<?= $config['web']['base_url']; ?>administrator/settings/bank-wd-add.php" method="post">
					<div class="modal-header">
						<h5 class="modal-title" id="exampleModalLabel">Tambah Bank</h5>
						<button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body">
					    <div class="rating_field">
                            <label for="namaKategori" class="form-label">Nama Kategori</label>
                            <input id="namaKategori" name="bank" Placeholder="Masukkan Nama Bank" class="form-control mb-3" type="text">
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
	</div>
	<!--end modal add bank-->
<?
require "../lib/footer.php"
?>