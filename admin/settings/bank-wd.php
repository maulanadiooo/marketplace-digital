<?
require "../../web.php";
require "../../lib/check_session_admin.php";
require "../../lib/is_login.php";

$title = "Bank Withdraw";
require "../lib/sidebar.php";
require "../lib/header.php";

$bankWd = mysqli_query($db, "SELECT * FROM bank_penarikan ORDER BY bank ASC");
?>

<div class="page-wrapper">
			<div class="page-content">
				<!--breadcrumb-->
				<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
					<div class="breadcrumb-title pe-3">Settings</div>
					<div class="ps-3">
						<nav aria-label="breadcrumb">
							<ol class="breadcrumb mb-0 p-0">
								<li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
								</li>
								<li class="breadcrumb-item active" aria-current="page">Bank Withdraw</li>
							</ol>
						</nav>
					</div>
					<div class="ms-auto">
						<div class="btn-group">
							<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">Add Bank</button>
						</div>
					</div>
				</div>
				<!--end breadcrumb-->
				
				<h6 class="mb-0 text-uppercase">Bank Withdraw</h6>
				<hr/>
				<div class="card">
					<div class="card-body">
						<div class="table-responsive">
							<table id="example" class="table table-striped table-bordered">
								<thead>
									<tr>
										<th></th>
                                        <th>Bank</th>
                                        <th>Aksi</th>
									</tr>
								</thead>
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
										<td><button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#exampleModalDelete<?=$bankWd_assoc['id']?>"><i class="lni lni-close"></i></button></td>
									</tr>
									
									<div class="modal fade" id="exampleModalDelete<?=$bankWd_assoc['id']?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
										<div class="modal-dialog">
											<div class="modal-content">
											    <form action="<?= $config['web']['base_url']; ?>administrator/settings/bank-wd-delete.php" method="post">
												<div class="modal-header">
													<h5 class="modal-title" id="exampleModalLabel">Hapus Bank: <?=$bankWd_assoc['bank']?></h5>
													<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
    <!--Moda add bank-->
    <div class="col">
		<!-- Modal -->
		<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
				    <form action="<?= $config['web']['base_url']; ?>administrator/settings/bank-wd-add.php" method="post">
					<div class="modal-header">
						<h5 class="modal-title" id="exampleModalLabel">Tambah Bank</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body">
					    <div class="rating_field">
                            <label for="namaKategori" class="form-label">Nama Kategori</label>
                            <input id="namaKategori" name="bank" Placeholder="Masukkan Nama Bank" class="form-control mb-3" type="text">
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
	</div>
	<!--end modal add bank-->
<?
require "../lib/footer.php"
?>