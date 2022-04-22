<?
require "../../web.php";
require "../../lib/check_session_admin.php";
require "../../lib/is_login.php";
require '../../lib/csrf_token.php';

$title = "Log Pembayaran";
require "../lib/sidebar.php";
require "../lib/header.php";

$now = date("Y-m-d 23:59:59");
$logPembayran = mysqli_query($db, "SELECT * FROM history_pembayaran ORDER BY created_at DESC");
?>

<div class="page-wrapper">
			<div class="page-content">
				<!--breadcrumb-->
				<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
					<div class="breadcrumb-title pe-3">Logs</div>
					<div class="ps-3">
						<nav aria-label="breadcrumb">
							<ol class="breadcrumb mb-0 p-0">
								<li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
								</li>
								<li class="breadcrumb-item active" aria-current="page">Pembayaran</li>
							</ol>
						</nav>
					</div>
				</div>
				<!--end breadcrumb-->
				
				<h6 class="mb-0 text-uppercase">Log Pembayaran</h6>
				<hr/>
				<div class="card">
					<div class="card-body">
						<div class="table-responsive">
							<table id="example" class="table table-striped table-bordered">
								<thead>
									<tr>
									    <th>ID</th>
										<th>Username</th>
                                        <th>Tindakan</th>
                                        <th>Jumlah</th>
                                        <th>Waktu</th>
									</tr>
								</thead>
								<tbody>
								    <?php
                                        while ($logPembayran_assoc = mysqli_fetch_assoc($logPembayran)){
                                            
                                            $user = $model->db_query($db, "*", "user", "id = '".$logPembayran_assoc['user_id']."'"); 
                                            
                                        ?>
								    <tr>
								        <td><?=$logPembayran_assoc['id']?></td>
										<td><a href="<?= $config['web']['base_url']; ?>user/<?=$user['rows']['username']?>" target="_blank"><?=$user['rows']['username']?></a></td>
                                        <td><?=$logPembayran_assoc['message']?></td>
                                        <td>Rp <?=number_format($logPembayran_assoc['amount'],0,',','.')?></td>
                                        <td><?=format_date(substr($logPembayran_assoc['created_at'], 0, -9)).", ".substr($logPembayran_assoc['created_at'], 11, -3)?></td>
										
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