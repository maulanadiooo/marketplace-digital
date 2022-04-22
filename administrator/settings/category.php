<?
require "../../web.php";
require "../../lib/check_session_admin.php";
require "../../lib/is_login.php";

$title = "Category";
require "../lib/header.php";
require "../lib/sidebar.php";

$category = mysqli_query($db, "SELECT * FROM categories ORDER BY category ASC");
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
    						<a href="#">Category</a>
    					</li>
    				</ul>
    			</div>
    			<div class="ms-auto">
						<div class="btn-group">
							<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">Add Category</button>
						</div>
					</div>
				<!--breadcrumb-->
				<hr/>
				<div class="row">
			    <div class="col-md-12">
							<div class="card">
								<div class="card-header">
									<h4 class="card-title">Category</h4>
								</div>
								<div class="card-body">
									<div class="table-responsive">
										<table id="basic-datatables" class="display table table-striped table-hover" >
											<thead>
            									<tr>
            										<th>#</th>
                                                    <th>Kategori</th>
                                                    <th>Keterangan</th>
                                                    <th>Aksi</th>
            									</tr>
            								</thead>
											<tfoot>
												<tr>
            										<th>#</th>
                                                    <th>Kategori</th>
                                                    <th>Keterangan</th>
                                                    <th>Aksi</th>
            									</tr>
											</tfoot>
											<tbody>
        								    <?
        								    while ($category_assoc = mysqli_fetch_assoc($category)){
                                        
                                            ?>  
        								    <tr>
        										<td></td>
                                                <td><?=ucfirst($category_assoc['category'])?></td>
                                                <td><?=$category_assoc['ket']?></a></td>
        										<td><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal<?=$category_assoc['id']?>"><i class="far fa-edit"></i></button> <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#exampleModalDelete<?=$category_assoc['id']?>"><i class="fas fa-trash"></i></button></td>
        									</tr>
        									
        										
        										<div class="modal fade" id="exampleModal<?=$category_assoc['id']?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        											<div class="modal-dialog">
        												<div class="modal-content">
        												    <form action="<?= $config['web']['base_url']; ?>administrator/settings/edit_category.php" method="post">
        													<div class="modal-header">
        														<h5 class="modal-title" id="exampleModalLabel">Edit Kategori <?=$category_assoc['category']?></h5>
        														<button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
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
        														<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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
        														<button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
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
<!--Moda add kategori-->
    <div class="col">
		<!-- Modal -->
		<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
				    <form action="<?= $config['web']['base_url']; ?>administrator/settings/add_category.php" method="post">
					<div class="modal-header">
						<h5 class="modal-title" id="exampleModalLabel">Tambah Kategori</h5>
						<button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
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
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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