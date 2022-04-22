<?
require "../../web.php";
require "../../lib/check_session_admin.php";
require "../../lib/is_login.php";

$title = "Withdraw Request";
require "../lib/sidebar.php";
require "../lib/header.php";

$now = date("Y-m-d 23:59:59");
$Wd_req = mysqli_query($db, "SELECT * FROM withdraw_request WHERE status = 'pending' AND estimasi_wd <= '$now'");
?>

<div class="page-wrapper">
			<div class="page-content">
				<!--breadcrumb-->
				<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
					<div class="breadcrumb-title pe-3">Withdraw</div>
					<div class="ps-3">
						<nav aria-label="breadcrumb">
							<ol class="breadcrumb mb-0 p-0">
								<li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
								</li>
								<li class="breadcrumb-item active" aria-current="page">Request</li>
							</ol>
						</nav>
					</div>
				</div>
				<!--end breadcrumb-->
				
				<h6 class="mb-0 text-uppercase">Request</h6>
				<hr/>
				<div class="card">
					<div class="card-body">
						<div class="table-responsive">
							<table id="example" class="table table-striped table-bordered">
								<thead>
									<tr>
										<th>User</th>
                                        <th>Bank</th>
                                        <th>Nama Pemilik Bank</th>
                                        <th>No Rekening</th>
                                        <th>Jumlah</th>
                                        <th>Tanggal Permintaan</th>
                                        <th>Tanggal Terima</th>
                                        <th>Aksi</th>
									</tr>
								</thead>
								<tbody>
								    <?
								    while ($Wd_req_assoc = mysqli_fetch_assoc($Wd_req)){
								        $user = $model->db_query($db, "*", "user", "id = '".$Wd_req_assoc['user_id']."'"); 
                                        $bank = $model->db_query($db, "*", "bank_penarikan", "id = '".$Wd_req_assoc['bank']."'"); 
                                        $category = $model->db_query($db, "*", "categories", "id = '".$Wd_req_assoc['categories_id']."'"); 
                                        if($Wd_req_assoc['featured'] == 1){
                                            $featured = 'Ya';
                                        } else {
                                            $featured = 'Tidak';
                                        }
                                        if($Wd_req_assoc['premium'] == 1){
                                            $premium = 'Ya';
                                        } else {
                                            $premium = 'Tidak';
                                        }
								    ?>    
								    <tr>
										<td><a href="<?= $config['web']['base_url']; ?>user/<?=$user['rows']['username']?>" target="_blank"><?=$user['rows']['username']?></a></td>
                                        <td><?=ucfirst($bank['rows']['bank'])?></td>
                                        <td><?=$Wd_req_assoc['nama_pemilik']?></td></a>
                                        <td><?=decrypt($Wd_req_assoc['no_rek'])?></td>
                                        <td>Rp <?= number_format($Wd_req_assoc['amount'],0,',','.') ?></td>
                                        <td><?= format_date(substr($Wd_req_assoc['created_at'], 0, -9))?></td>
                                        <td><?= format_date(substr($Wd_req_assoc['estimasi_wd'], 0, -9))?></td>
										
										<td>
										    <a href="<?= $config['web']['base_url']; ?>administrator/withdraw-request/action.php?approve=<?=$Wd_req_assoc['id']?>"><button class="btn btn-primary" title="Berhasil"><i class="lni lni-checkmark"></i></button></a>
										    <button title="Gagal Transfer" type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal<?=$Wd_req_assoc['id']?>"><i class="lni lni-close"></i></button>
										</td>
									</tr>
									
										<!-- Button trigger modal -->
										<!--<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">Basic modal</button>-->
										<!-- Modal -->
										<div class="modal fade" id="exampleModal<?=$Wd_req_assoc['id']?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
											<div class="modal-dialog">
												<div class="modal-content">
												    <form action="<?= $config['web']['base_url']; ?>administrator/withdraw-request/gagal-transfer.php" method="post">
													<div class="modal-header">
														<h5 class="modal-title" id="exampleModalLabel">Gagal WD ID#<?=$Wd_req_assoc['id']?></h5>
														<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
													</div>
													<div class="modal-body">
												
                                                  
                                                  <div class="modal-body">
                                                      <input type="hidden" name="wuid" value="<?=$Wd_req_assoc['id']?>">
                                                    
                                                    <div class="col-12">
                										<label for="InputReason" class="form-label">Alasan Gagal Transfer</label>
                										<textarea name="alasan_gagal" class="form-control" id="InputReason" placeholder="Mohon Maaf..." rows="5"></textarea>
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
<?
require "../lib/footer.php"
?>