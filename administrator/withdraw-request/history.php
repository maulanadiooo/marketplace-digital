<?
require "../../web.php";
require "../../lib/check_session_admin.php";
require "../../lib/is_login.php";

$title = "Withdraw History";
require "../lib/header.php";
require "../lib/sidebar.php";

$history = mysqli_query($db, "SELECT * FROM withdraw_request ORDER BY created_at DESC");
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
						<a href="#">History</a>
					</li>
				</ul>
        			</div>
        				
        				<div class="row">
        			    <div class="col-md-12">
        							<div class="card">
        								<div class="card-header">
        									<h4 class="card-title">Withdraws History</h4>
        								</div>
        								<div class="card-body">
        									<div class="table-responsive">
        										<table id="basic-datatables" class="display table table-striped table-hover" >
        											<thead>
        												<tr>
                    									    <th>ID</th>
                    										<th>User</th>
                    										<th>Tgl Req</th>
                    										<th>Metode</th>
                    										<th>Jumlah</th>
                    										<th>Estimasi</th>
                    										<th>Status</th>
                    										<th>Keterangan</th>
                    									</tr>
        											</thead>
        											<tfoot>
        												<tr>
                    									    <th>ID</th>
                    										<th>User</th>
                    										<th>Tgl Req</th>
                    										<th>Metode</th>
                    										<th>Jumlah</th>
                    										<th>Estimasi</th>
                    										<th>Status</th>
                    										<th>Keterangan</th>
                    									</tr>
        											</tfoot>
        											<tbody>
                    								    <?
                    								    while ($history_assoc = mysqli_fetch_assoc($history)){
                    								        $user = $model->db_query($db, "*", "user", "id = '".$history_assoc['user_id']."'");
                    								        
                    								        $nama_bank = $model->db_query($db, "*", "bank_penarikan", "id = '".$history_assoc['bank']."'");
                                                            if($history_assoc['status'] == 'pending'){
                                                                $status = "Proses";
                                                                $label = 'warning';
                                                            } elseif ($history_assoc['status'] == 'success'){
                                                                $status = "Berhasil";
                                                                $label = 'success';
                                                            } elseif($history_assoc['status'] == 'error'){
                                                                $status = "Cancel/Error";
                                                                $label = 'danger';
                                                            }
                    								    ?>    
                    								    <tr>
                    								        <td><?=$history_assoc['id']?></td>
                    										<td><a href="<?= $config['web']['base_url']; ?>user/<?=$user['rows']['username']?>" target="_blank"><?=$user['rows']['username']?></a></td>
                    										<td><?= (substr($history_assoc['created_at'], 0, -9)); ?></td>
                    										<td><?=$nama_bank['rows']['bank']?> | <?=$history_assoc['nama_pemilik']?> | <?=decrypt($history_assoc['no_rek'])?></td>
                    										<td class="bold">Rp <?=number_format($history_assoc['amount'],0,',','.')?> ,-</td>
                    										<td><?= format_date(substr($history_assoc['estimasi_wd'], 0, -9))?></td>
                                                            <td><span class="badge bg-<?=$label?>"><?=$status?></span></td>
                                                            <?
                                                            if($history_assoc['status'] ==  'error'){
                                                            ?>
                                                            <td><?=$history_assoc['ket_error']?></td>
                                                            <?
                                                            } else {
                                                            ?>
                                                            <td></td>
                                                            <?
                                                            }
                                                            ?>
                    									</tr>
                    									
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