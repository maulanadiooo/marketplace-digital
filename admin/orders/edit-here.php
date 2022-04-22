<?
require "../../web.php";
require "../../lib/check_session_admin.php";
require "../../lib/is_login.php";

$title = "Orders";
require "../lib/sidebar.php";
require "../lib/header.php";

$orders = mysqli_query($db, "SELECT * FROM orders ORDER BY id DESC");
?>

<div class="page-wrapper">
			<div class="page-content">
				<!--breadcrumb-->
				<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
					<div class="breadcrumb-title pe-3">Orders</div>
					<div class="ps-3">
						<nav aria-label="breadcrumb">
							<ol class="breadcrumb mb-0 p-0">
								<li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
								</li>
								<li class="breadcrumb-item active" aria-current="page">Orders</li> 
							</ol>
						</nav>
					</div>
				</div>
				<!--end breadcrumb-->
				
				<h6 class="mb-0 text-uppercase">Orders</h6>
				<hr/>
				<div class="card">
					<div class="card-body">
						<div class="table-responsive">
							<table id="example" class="table table-striped table-bordered">
								<thead>
									<tr>
									    <th>Invoice</th>
									    <th>ID Orderan</th>
                                        <th>Buyer</th>
                                        <th>Layanan</th>
                                        <th>Jumlah</th>
                                        <th>Extra Product</th>
                                        <th>Total Pembyaran</th>
                                        <th>Pembayaran</th>
                                        <th>Status</th>
                                        <th>Tanggal</th>
                                        <th>Aksi</th>
									</tr>
								</thead>
								<tbody>
								    <?
								    while ($orders_assoc = mysqli_fetch_assoc($orders)){
								        $user = $model->db_query($db, "*", "user", "id = '".$orders_assoc['buyer_id']."'"); 
                                        $service = $model->db_query($db, "*", "services", "id = '".$orders_assoc['service_id']."'"); 
                                        $cart = $model->db_query($db, "*", "cart", "kode_unik = '".$orders_assoc['kode_unik']."'"); 
                                        $orders_detailsss = $model->db_query($db, "*", "orders", "kode_unik = '".$orders_assoc['kode_unik']."'"); 
                                       $bank = $model->db_query($db, "*", "bank_information", "id = '".$cart['rows']['pembayaran_id_bank']."'"); 
                                        
                                        if($orders_detailsss['rows']['status'] == 'unpaid'){
                                            $status = 'Belum Dibayar';
                                            $label = 'warning';
                                        } elseif($orders_detailsss['rows']['status'] == 'active'){
                                            $status = 'Aktif';
                                            $label = 'info';
                                        } elseif($orders_detailsss['rows']['status'] == 'success'){
                                            $status = 'Sukses';
                                            $label = 'success';
                                        } elseif($orders_detailsss['rows']['status'] == 'complete'){
                                            $status = 'Selesai';
                                            $label = 'success';
                                        } elseif($orders_detailsss['rows']['status'] == 'cancel'){
                                            $status = 'Ditolak Pembeli';
                                            $label = 'danger';
                                        } elseif($orders_detailsss['rows']['status'] == 'refund'){
                                            $status = 'Pengajuan Refund Dari Pembeli';
                                            $label = 'warning';
                                        } elseif($orders_detailsss['rows']['status'] == 'refunded'){
                                            $status = 'Sudah Direfund';
                                            $label = 'danger';
                                        } 
								    ?>    
								    <tr>
								        <td><?=$cart['rows']['kode_invoice']?></td>
								        <td><?=$orders_assoc['id']?></td>
										<td><a href="<?= $config['web']['base_url']; ?>user/<?=$user['rows']['username']?>" target="_blank"><?=$user['rows']['username']?></a></td>
										<td><a href="<?= $config['web']['base_url']; ?>product/<?=$service['rows']['id']?>/<?=$service['rows']['url']?>" target="_blank"><?=$service['rows']['nama_layanan']?></a></td>
										<td><?=$cart['rows']['quantity']?></td>
										<td>
                                        <?
                                        if($cart['rows']['extra_product'] != null){
                                        ?>
                                            <?=$cart['rows']['extra_product']?> <br>
                                        <?
                                        }
                                        if($cart['rows']['extra_product1'] != null){
                                        ?>
                                            <?=$cart['rows']['extra_product1']?> <br>
                                        <?
                                        }
                                        if($cart['rows']['extra_product2'] != null){
                                        ?>
                                            <?=$cart['rows']['extra_product2']?> <br>
                                        <?
                                        }
                                        ?>
                                        </td>
                                        
										<?
                                        if($cart['rows']['pembayaran_id_bank'] == 'saldo_tersedia'){
                                        ?>
                                        <td>Rp <?= number_format($cart['rows']['total_price_admin'],0,',','.') ?></td>
                                        <?
                                        } else {
                                        ?>
                                        <td>Rp <?= number_format($cart['rows']['price_kode_unik'],0,',','.') ?></td>
                                        <?
                                        }
                                        ?>
                                        
                                        <?
                                        if($cart['rows']['pembayaran_id_bank'] == 'saldo_tersedia'){
                                        ?>
                                        <td>Saldo Tersedia</td>
                                        <?
                                        } else {
                                        ?>
                                        <td><?=$bank['rows']['bank']?></td>
                                        <?
                                        }
                                        ?>
                                        
                                        <td>
                                            <span class="badge bg-<?=$label?>"><?=$status?></span>
                                        </td>
                                        
                                        <td><?=format_date(substr($cart['rows']['created_at'], 0, -9)).", ".substr($cart['rows']['created_at'], 11, -3)?></td>
                                        <td>
                                        <?
                                        $now = date('Y-m-d H:i:s');
                                        if($cart['rows']['status'] == 'pending' && $now < $cart['rows']['expired_date']){
                                        ?>
                                        
                                            
                                            <button title="Konfirmasi Pembayaran" type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal<?=$cart['rows']['kode_invoice']?>"><i class="fadeIn animated bx bx-check"></i></button>
                                        
                                        <?
                                        }
                                        ?>
                                        <?
                                        if($orders_assoc['status'] == 'complete'){
                                        ?>
                                        <button title="Refund" type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modalSayaOrders<?=$orders_assoc['id']?>"><i class="lni lni-close"></i></button> 
                                        <?
                                        }
                                        ?>
                                            
                                        </td>
									</tr>
									    <div class="modal fade" id="exampleModal<?=$cart['rows']['kode_invoice']?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
											<div class="modal-dialog">
												<div class="modal-content">
												    <form action="<?= $config['web']['base_url'] ?>administrator/orders/action.php?approve=<?=$cart['rows']['id']?>" method="post">
													<div class="modal-header">
														<h5 class="modal-title" id="exampleModalLabel">Terima Pembayaran Invoice: <?=$cart['rows']['kode_invoice']?></h5>
														<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
													</div>
													<div class="modal-body">
												
                                                  
                                                  <div class="modal-body">
                                                        <input type="hidden" name="oid" value="<?=$cart['rows']['id']?>">
                                                        <input type="hidden" name="inid" value="<?=$cart['rows']['kode_invoice']?>">
                                                    <div class="col-12">
                										<textarea name="ket_category" class="form-control" id="InputKet"  rows="5" disabled>Yakin Telah Menerima Pembayaran ?</textarea>
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
										
										<div class="modal fade" id="modalSayaOrders<?=$orders_assoc['id']?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
											<div class="modal-dialog">
												<div class="modal-content">
												    <form action="<?= $config['web']['base_url'] ?>administrator/orders/refund.php" method="post">
													<div class="modal-header">
														<h5 class="modal-title" id="exampleModalLabel">Refund Order ID: <?=$orders_assoc['id']?></h5>
														<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
													</div>
													<div class="modal-body">
												
                                                  
                                                  <div class="modal-body">
                                                      <input type="hidden" name="oid" value="<?=$orders_assoc['id']?>">
                                                    <div class="rating_field">
                                                        <label for="Refund" class="form-label">Jumlah Refund</label>
                                                        <span>Jika Full Refund, Input Sesuai Total Pembayaran</span>
                                                        <input id="Refund" name="refund_amount" class="form-control mb-3" type="number">
                                                    </div><br>
                                                    <div class="col-12">
                										<label for="InputKet" class="form-label">Kembalikan Biaya Admin</label>
                										<span>Jika Full Refund, Isi Dengan "Iya"</span>
                										<select name="admin_fee" class="form-select form-select-sm mb-3" aria-label=".form-select-sm example">
                        									<option value="0">Silahkan Pilih</option>   
                                                        <option value="ya">Iya</option>
                                                        <option value="no">Tidak</option>
                        								</select>
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