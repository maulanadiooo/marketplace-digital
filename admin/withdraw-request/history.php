<?
require "../../web.php";
require "../../lib/check_session_admin.php";
require "../../lib/is_login.php";

$title = "Withdraw History";
require "../lib/sidebar.php";
require "../lib/header.php";

$history = mysqli_query($db, "SELECT * FROM withdraw_request ORDER BY created_at DESC");
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
								<li class="breadcrumb-item active" aria-current="page">History</li>
							</ol>
						</nav>
					</div>
				</div>
				<!--end breadcrumb-->
				
				<h6 class="mb-0 text-uppercase">History</h6>
				<hr/>
				<div class="card">
					<div class="card-body">
						<div class="table-responsive">
							<table id="example" class="table table-striped table-bordered">
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

<?
require "../lib/footer.php"
?>