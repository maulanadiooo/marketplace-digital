<?
require "../../web.php";
require "../../lib/check_session_admin.php";
require "../../lib/is_login.php";

$title = "Withdraw Request";
require "../lib/header.php";
require "../lib/sidebar.php";

$now = date("Y-m-d 23:59:59");
$Wd_req = mysqli_query($db, "SELECT * FROM withdraw_request WHERE status = 'pending' AND estimasi_wd <= '$now'");
?>

<div class="main-panel">
	<div class="content">
		<div class="page-inner">
				<div class="page-header">
				<h4 class="page-title">Withdraws</h4>
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
						<a href="#">Request</a>
					</li>
				</ul>
        			</div>
        				<div class="row">
        			    <div class="col-md-12">
        							<div class="card">
        								<div class="card-header">
        									<h4 class="card-title">Request</h4>
        								</div>
        								<div class="card-body">
        									<div class="table-responsive">
        										<table id="basic-datatables" class="display table table-striped table-hover" >
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
        											<tfoot>
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
        											</tfoot>
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
                    										    <a href="<?= $config['web']['base_url']; ?>administrator/withdraw-request/action.php?approve=<?=$Wd_req_assoc['id']?>"><button class="btn btn-primary" title="Berhasil"><i class="far fa-check-square"></i></button></a>
                    										    <button title="Gagal Transfer" type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal<?=$Wd_req_assoc['id']?>"><i class="far fa-window-close"></i></button>
                    										</td>
                    									</tr>
                    										<div class="modal fade" id="exampleModal<?=$Wd_req_assoc['id']?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    											<div class="modal-dialog">
                    												<div class="modal-content">
                    												    <form action="<?= $config['web']['base_url']; ?>administrator/withdraw-request/gagal-transfer.php" method="post">
                    													<div class="modal-header">
                    														<h5 class="modal-title" id="exampleModalLabel">Gagal WD ID#<?=$Wd_req_assoc['id']?></h5>
                    														<button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
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