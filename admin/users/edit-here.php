<?
require "../../web.php";
require "../../lib/check_session_admin.php";
require "../../lib/is_login.php";

$title = "Pengguna";
require "../lib/sidebar.php";
require "../lib/header.php";

$users = mysqli_query($db, "SELECT * FROM user ORDER BY created_at DESC");
?>

<div class="page-wrapper">
			<div class="page-content">
				<!--breadcrumb-->
				<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
					<div class="breadcrumb-title pe-3">Users</div>
					<div class="ps-3">
						<nav aria-label="breadcrumb">
							<ol class="breadcrumb mb-0 p-0">
								<li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
								</li>
								<li class="breadcrumb-item active" aria-current="page">Users</li>
							</ol>
						</nav>
					</div>
				</div>
				<!--end breadcrumb-->
				
				<h6 class="mb-0 text-uppercase">Users</h6>
				<hr/>
				<div class="card">
					<div class="card-body">
						<div class="table-responsive">
							<table id="example" class="table table-striped table-bordered">
								<thead>
									<tr>
										<th>Nama</th>
										<th>Username</th>
										<th>Email</th>
										<th>No Hp</th>
										<th>Penjualan</th>
										<th>Pembelian</th>
										<th>Saldo</th>
										<th>Withdraw</th>
										<th>Status</th>
										<th>Join Date</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
								    <?
								    while ($users_assoc = mysqli_fetch_assoc($users)){
								        $total_penjualan = mysqli_query($db, "SELECT SUM(total_sales) AS total FROM `services` WHERE  author = '".$users_assoc['id']."'");
                                        $fetch_total_penjualan = mysqli_fetch_array($total_penjualan);
                                        $total_penjualan_fix = $fetch_total_penjualan['total'];
                                        if($total_penjualan_fix > 0 ){
                                            $penjualan = $total_penjualan_fix;
                                        } else {
                                            $penjualan = '0';
                                        }
                                        
                                        $pembelian = mysqli_query($db, "SELECT * FROM orders WHERE buyer_id = '".$users_assoc['id']."' AND status in ('active','success','cancel','complete') ");
                                        $total_pembelian = mysqli_num_rows($pembelian);
                                        if($users_assoc['status'] == 'Verified'){
                                            $status_banned = 'Aktif';
                                            $badge = "success";
                                        } elseif($users_assoc['status'] == 'Not Verified'){
                                            $status_banned = 'Belum Verifikasi';
                                            $badge = "warning text-dark";
                                        } elseif($users_assoc['status'] == 'Banned'){
                                            $status_banned = 'Banned';
                                            $badge = "danger";
                                        }
								    ?>    
								    <tr>
										<td><?=$users_assoc['nama']?></td>
										<td><a href="<?= $config['web']['base_url']; ?>user/<?=$users_assoc['username']?>" target="_blank"><?=$users_assoc['username']?></a></td>
										<td><?=decrypt($users_assoc['email'])?></td>
										<td>+<?=decrypt($users_assoc['no_hp'])?></td>
										<td><?=$penjualan?></td>
                                        <td><?=$total_pembelian?></td>
                                        <td>Rp <?= number_format($users_assoc['saldo_tersedia'],0,',','.') ?></td>
										<td>Rp <?= number_format($users_assoc['withdraw'],0,',','.') ?></td>
                                        <td><span class="badge bg-<?=$badge?>"><?=$status_banned?></span></td>
										<td><?= (substr($users_assoc['created_at'], 0, -9)); ?></td>
										<td><button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal<?=$users_assoc['id']?>"><i class="fadeIn animated bx bx-message-edit"></i></button></td>
									</tr>
									
										<!-- Button trigger modal -->
										<!--<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">Basic modal</button>-->
										<!-- Modal -->
										<div class="modal fade" id="exampleModal<?=$users_assoc['id']?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
											<div class="modal-dialog">
												<div class="modal-content">
												    <form action="<?= $config['web']['base_url']; ?>administrator/users/banned.php" method="post">
													<div class="modal-header">
														<h5 class="modal-title" id="exampleModalLabel">Banned Akun <?=$users_assoc['username']?></h5>
														<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
													</div>
													<div class="modal-body">
												
                                                  
                                                  <div class="modal-body">
                                                      <input type="hidden" name="userid" value="<?=$users_assoc['id']?>">
                                                    <div class="rating_field">
                                                        <label>Status Akun</label>
                                                        <select name="status" class="form-select form-select-sm mb-3" aria-label=".form-select-sm example">
                        									<option value="<?=$users_assoc['status']?>"><?= $status_banned?> <span>( Saat Ini )</span></option>
                                                            <option value='Banned'>Banned</option>
                                                            <option value='Verified'>Aktifkan</option>
                        								</select>
                                                    </div><br>
                                                    <div class="col-12">
                										<label for="InputReason" class="form-label">Alasan Banned</label>
                										<textarea name="banned" class="form-control" id="InputReason" placeholder="Akun Anda Dibanned Karena....." rows="5"></textarea>
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