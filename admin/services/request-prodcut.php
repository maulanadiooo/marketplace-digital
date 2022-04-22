<?
require "../../web.php";
require "../../lib/check_session_admin.php";
require "../../lib/is_login.php";

$title = "Request Pending";
require "../lib/sidebar.php";
require "../lib/header.php";

$req_Buyer = mysqli_query($db, "SELECT * FROM permintaan_pembeli WHERE status = 'pending' ORDER BY created_at DESC");
?>

<div class="page-wrapper">
			<div class="page-content">
				<!--breadcrumb-->
				<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
					<div class="breadcrumb-title pe-3">Product</div>
					<div class="ps-3">
						<nav aria-label="breadcrumb">
							<ol class="breadcrumb mb-0 p-0">
								<li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
								</li>
								<li class="breadcrumb-item active" aria-current="page">Request Pending</li>
							</ol>
						</nav>
					</div>
				</div>
				<!--end breadcrumb-->
				
				<h6 class="mb-0 text-uppercase">Request Pending</h6>
				<hr/>
				<div class="card">
					<div class="card-body">
						<div class="table-responsive">
							<table id="example" class="table table-striped table-bordered">
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

<?
require "../lib/footer.php"
?>