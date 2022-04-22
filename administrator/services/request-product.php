<?
require "../../web.php";
require "../../lib/check_session_admin.php";
require "../../lib/is_login.php";

$title = "Request Pending";
require "../lib/header.php";
require "../lib/sidebar.php";

$req_Buyer = mysqli_query($db, "SELECT * FROM permintaan_pembeli WHERE status = 'pending' ORDER BY created_at DESC");
?>

<div class="main-panel">
	<div class="content">
		<div class="page-inner">
				<div class="page-header">
				<h4 class="page-title">Product</h4>
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
						<a href="#">Request Pending</a>
					</li>
				</ul>
    			</div>
    				
    				<div class="row">
    			    <div class="col-md-12">
    							<div class="card">
    								<div class="card-header">
    									<h4 class="card-title">Request Pending</h4>
    								</div>
    								<div class="card-body">
    									<div class="table-responsive">
    										<table id="basic-datatables" class="display table table-striped table-hover" >
    											<thead>
    												<tr>
                										<th>User</th>
                                                        <th>Permintaan</th>
                                                        <th>Budget</th>
                                                        <th>Jangka Waktu(Hari)</th>
                                                        <th>Dibuat</th>
                                                        <th>Aksi</th>
                									</tr>
    											</thead>
    											<tfoot>
    												<tr>
                										<th>User</th>
                                                        <th>Permintaan</th>
                                                        <th>Budget</th>
                                                        <th>Jangka Waktu(Hari)</th>
                                                        <th>Dibuat</th>
                                                        <th>Aksi</th>
                									</tr>
    											</tfoot>
    											<tbody>
                								    <?php
                                                    while ($req_Buyer_assoc = mysqli_fetch_assoc($req_Buyer)){
                                                        
                                                        $user = $model->db_query($db, "*", "user", "id = '".$req_Buyer_assoc['user_id']."'"); 
                                                        
                                                    ?>    
                								    <tr>
                										<td><a href="<?= $config['web']['base_url']; ?>user/<?=$user['rows']['username']?>"><?=$user['rows']['username']?></a></td>
                                                        <td><?=$req_Buyer_assoc['permintaan']?></td>
                                                        <td>Rp <?= number_format($req_Buyer_assoc['budget'],0,',','.') ?></td>
                                                        <td><?=$req_Buyer_assoc['jangka_waktu']?></td>
                                                        <td><?=$req_Buyer_assoc['created_at']?></td>
                                                        
                                                        <td>
                                                            <a href="<?= $config['web']['base_url']; ?>administrator/services/request-pending.php?approve=<?=$req_Buyer_assoc['id']?>"><button title="Setujui" type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal<?=$req_Buyer_assoc['id']?>"><i class="fadeIn animated bx bx-check"></i></button></a>
                                                        </td>
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