<?
require "../../web.php";
require "../../lib/check_session_admin.php";
require "../../lib/is_login.php";
require '../../lib/csrf_token.php';

$title = "Deposit History";
require "../lib/header.php";
require "../lib/sidebar.php";

$now = date("Y-m-d 23:59:59");
$depoHistory = mysqli_query($db, "SELECT * FROM deposit ORDER BY created_at DESC");
?>

<div class="main-panel">
	<div class="content">
		<div class="page-inner">
				<div class="page-header">
				<h4 class="page-title">Deposit</h4>
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
						<a href="#">History</a>
					</li>
				</ul>
			</div>
				
				<div class="row">
    			    <div class="col-md-12">
    							<div class="card">
    								<div class="card-header">
    									<h4 class="card-title">Deposti History</h4>
    								</div>
    								<div class="card-body">
    									<div class="table-responsive">
    										<table id="basic-datatables" class="display table table-striped table-hover" >
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
    											<tfoot>
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
    											</tfoot>
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
                                                            <button title="Konfirmasi Deposit" type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal<?=$depoHistory_assoc['id']?>"><i class="far fa-check-square"></i></button> 
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
                														<button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
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
<?
require "../lib/footer.php"
?>