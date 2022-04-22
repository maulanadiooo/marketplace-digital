<?
require "../../web.php";
require "../../lib/check_session_admin.php";
require "../../lib/is_login.php";
require '../../lib/csrf_token.php';

$title = "Log Pembayaran";
require "../lib/header.php";
require "../lib/sidebar.php";

$now = date("Y-m-d 23:59:59");
$logPembayran = mysqli_query($db, "SELECT * FROM history_pembayaran ORDER BY created_at DESC");
?>

<div class="main-panel">
	<div class="content">
		<div class="page-inner">
				<div class="page-header">
				<h4 class="page-title">Logs</h4>
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
						<a href="#">Pembayaran</a>
					</li>
				</ul>
			</div>
				<div class="row">
        			    <div class="col-md-12">
        							<div class="card">
        								<div class="card-header">
        									<h4 class="card-title">Logs Pembayaran</h4>
        								</div>
        								<div class="card-body">
        									<div class="table-responsive">
        										<table id="basic-datatables" class="display table table-striped table-hover" >
        											<thead>
        												<tr>
                    									    <th>ID</th>
                    										<th>Username</th>
                                                            <th>Tindakan</th>
                                                            <th>Jumlah</th>
                                                            <th>Waktu</th>
                    									</tr>
        											</thead>
        											<tfoot>
        												<tr>
                    									    <th>ID</th>
                    										<th>Username</th>
                                                            <th>Tindakan</th>
                                                            <th>Jumlah</th>
                                                            <th>Waktu</th>
                    									</tr>
        											</tfoot>
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
				</div>
				
			</div>
		</div>
<?
require "../lib/footer.php"
?>