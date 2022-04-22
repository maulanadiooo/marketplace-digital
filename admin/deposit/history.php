<?
require "../../web.php";
require "../../lib/check_session_admin.php";
require "../../lib/is_login.php";
require '../../lib/csrf_token.php';

$title = "Deposit History";
require "../lib/sidebar.php";
require "../lib/header.php";

$now = date("Y-m-d 23:59:59");
$depoHistory = mysqli_query($db, "SELECT * FROM deposit ORDER BY created_at DESC");
?>

<div class="page-wrapper">
			<div class="page-content">
				<!--breadcrumb-->
				<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
					<div class="breadcrumb-title pe-3">Deposit</div>
					<div class="ps-3">
						<nav aria-label="breadcrumb">
							<ol class="breadcrumb mb-0 p-0">
								<li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
								</li>
								<li class="breadcrumb-item active" aria-current="page">Deposit</li>
							</ol>
						</nav>
					</div>
				</div>
				<!--end breadcrumb-->
				
				<h6 class="mb-0 text-uppercase">Deposit</h6>
				<hr/>
				<div class="card">
					<div class="card-body">
						<div class="table-responsive">
							<table id="example" class="table table-striped table-bordered">
								<thead>
									<tr>
									    <th>ID</th>
										<th>Kode Depo</th>
                                        <th>User</th>
                                        <th>Metode</th>
                                        <th>Jumlah</th>
                                        <th>Tx ID</th>
                                        <th>Status</th>
                                        <th>Tgl</th>
                                        <th>Aksi</th>
									</tr>
								</thead>
								<tbody>
								    <?php
                                        while ($depoHistory_assoc = mysqli_fetch_assoc($depoHistory)){
                                            
                                            $user = $model->db_query($db, "*", "user", "id = '".$depoHistory_assoc['user_id']."'"); 
                                            $service = $model->db_query($db, "*", "services", "id = '".$depoHistory_assoc['service_id']."'"); 
                                           $bank = $model->db_query($db, "*", "bank_information", "id = '".$depoHistory_assoc['id_bank']."'"); 
                                            
                                            if($depoHistory_assoc['status'] == 'success'){
                                                $status = 'Sukses';
                                                $badge = 'success';
                                            } elseif($depoHistory_assoc['status'] == 'pending'){
                                                $status = 'Pending';
                                                $badge = 'warning';
                                            } elseif($depoHistory_assoc['status'] == 'error'){
                                                $status = 'Dibatalkan';
                                                $badge = 'danger';
                                            } 
                                        ?>
								    <tr>
								        <td><?=$depoHistory_assoc['id']?></td>
										<td><?=$depoHistory_assoc['kode_depo']?></td>
                                        <td><a href="<?= $config['web']['base_url']; ?>user/<?=$user['rows']['username']?>" target="_blank"><?=$user['rows']['username']?></a></td>
                                        <td><?=$bank['rows']['bank']?></td>
                                        <td>Rp <?=number_format($depoHistory_assoc['amount'],0,',','.')?></td>
                                        <td><?=$depoHistory_assoc['tx_id']?></td>
                                        <td><span class="badge bg-<?=$badge?>"><?=$status?></span></td>
                                        <td><?=format_date(substr($depoHistory_assoc['created_at'], 0, -9)).", ".substr($depoHistory_assoc['created_at'], 11, -3)?></td>
										
										<?
                                        if($depoHistory_assoc['status'] == 'pending'){
                                        ?>
                                        <td>
                                            <button title="Konfirmasi Deposit" type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal<?=$depoHistory_assoc['id']?>"><i class="fadeIn animated bx bx-check"></i></button> 
                                        </td>
                                        <?
                                        } else {
                                        ?>
                                        <td></td>
                                        <?
                                        }
                                        ?>
									</tr>
									
										<div class="modal fade" id="exampleModal<?=$depoHistory_assoc['id']?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
											<div class="modal-dialog">
												<div class="modal-content">
												    <form action="<?= $config['web']['base_url'] ?>administrator/deposit/action.php?approve=<?=$depoHistory_assoc['id']?>" method="post">
												         <input type="hidden" name="csrf_token" value="<?php echo $config['csrf_token'] ?>">
													<div class="modal-header">
														<h5 class="modal-title" id="exampleModalLabel">Terima Deposit Kode : <?=$depoHistory_assoc['kode_depo']?></h5>
														<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
													</div>
													<div class="modal-body">
												
                                                  
                                                  <div class="modal-body">
                                                        <input type="hidden" name="did" value="<?=$depoHistory_assoc['id']?>">
                                                    <div class="col-12">
                										<textarea name="ket_category" class="form-control" id="InputKet"  rows="5" disabled>Yakin Telah Menerima Pembayaran Deposit ?</textarea>
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
<?
require "../lib/footer.php"
?>