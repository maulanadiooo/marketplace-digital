<?
require "../../web.php";
require "../../lib/check_session_admin.php";
require "../../lib/is_login.php";

$title = "Category";
require "../lib/sidebar.php";
require "../lib/header.php";

$category = mysqli_query($db, "SELECT * FROM categories ORDER BY category ASC");
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
								<li class="breadcrumb-item active" aria-current="page">Category</li>
							</ol>
						</nav>
					</div>
					<!-- Button trigger modal -->
		            
					<div class="ms-auto">
						<div class="btn-group">
							<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">Add Category</button>
						</div>
					</div>
				</div>
				<!--end breadcrumb-->
				
				<h6 class="mb-0 text-uppercase">Category</h6>
				<hr/>
				<div class="card">
					<div class="card-body">
						<div class="table-responsive">
							<table id="example" class="table table-striped table-bordered">
								<thead>
									<tr>
										<th>#</th>
                                        <th>Kategori</th>
                                        <th>Keterangan</th>
                                        <th>Aksi</th>
									</tr>
								</thead>
								<tbody>
								    <?
								    while ($category_assoc = mysqli_fetch_assoc($category)){
                                
                                    ?>  
								    <tr>
										<td></td>
                                        <td><?=ucfirst($category_assoc['category'])?></td>
                                        <td><?=$category_assoc['ket']?></a></td>
										<td><button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal<?=$category_assoc['id']?>"><i class="fadeIn animated bx bx-message-edit"></i></button> <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#exampleModalDelete<?=$category_assoc['id']?>"><i class="lni lni-close"></i></button></td>
									</tr>
									
										<!-- Button trigger modal -->
										<!--<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">Basic modal</button>-->
										<!-- Modal -->
										<div class="modal fade" id="exampleModal<?=$category_assoc['id']?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
											<div class="modal-dialog">
												<div class="modal-content">
												    <form action="<?= $config['web']['base_url']; ?>administrator/settings/edit_category.php" method="post">
													<div class="modal-header">
														<h5 class="modal-title" id="exampleModalLabel">Edit Kategori <?=$category_assoc['category']?></h5>
														<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
													</div>
													<div class="modal-body">
												
                                                  
                                                  <div class="modal-body">
                                                      <input type="hidden" name="cuid" value="<?=$category_assoc['id']?>">
                                                    <div class="rating_field">
                                                        <label for="namaKategori" class="form-label">Nama Kategori</label>
                                                        <input id="namaKategori" name="category" class="form-control mb-3" type="text"  aria-label="default input example" value="<?=ucfirst($category_assoc['category'])?>">
                                                    </div><br>
                                                    <div class="col-12">
                										<label for="InputKet" class="form-label">Keterangan</label>
                										<textarea name="ket_category" class="form-control" id="InputKet"  rows="5"><?=ucfirst($category_assoc['ket'])?></textarea>
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
										<div class="modal fade" id="exampleModalDelete<?=$category_assoc['id']?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
											<div class="modal-dialog">
												<div class="modal-content">
												    <form action="<?= $config['web']['base_url']; ?>administrator/settings/delete_category.php" method="post">
													<div class="modal-header">
														<h5 class="modal-title" id="exampleModalLabel">Hapus Kategori: <?=$category_assoc['category']?></h5>
														<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
													</div>
													<div class="modal-body">
												
                                                  
                                                  <div class="modal-body">
                                                        <input type="hidden" name="delid" value="<?=$category_assoc['id']?>">
                                                    <div class="col-12">
                										<textarea name="ket_category" class="form-control" id="InputKet"  rows="5" disabled>Anda Yakin Ingin Menghapus Kategori Ini ? Mengahpus Kategori Berpengaruh Terhadap Produk/Layanan Yang Telah Tampil</textarea>
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
<!--Moda add kategori-->
    <div class="col">
		<!-- Modal -->
		<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
				    <form action="<?= $config['web']['base_url']; ?>administrator/settings/add_category.php" method="post">
					<div class="modal-header">
						<h5 class="modal-title" id="exampleModalLabel">Tambah Kategori</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body">
					    <div class="rating_field">
                            <label for="namaKategori" class="form-label">Nama Kategori</label>
                            <input id="namaKategori" name="category" class="form-control mb-3" type="text">
                        </div>
                        <div class="col-12">
                            <label for="InputReason" class="form-label">Keterangan</label>
						    <textarea name="ket_category" class="form-control" id="InputReason" placeholder="Kategori ini berisi tentang..." rows="5"></textarea>
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
	<!--end modal add kategori-->

<?
require "../lib/footer.php"
?>