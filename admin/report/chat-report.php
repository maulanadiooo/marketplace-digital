<?
require "../../web.php";
require "../../lib/check_session_admin.php";
require "../../lib/is_login.php";

$title = "Chat Report";
require "../lib/sidebar.php";
require "../lib/header.php";

$reportChat = mysqli_query($db, "SELECT * FROM report WHERE report = 'message' ORDER BY id DESC");
?>

<div class="page-wrapper">
			<div class="page-content">
				<!--breadcrumb-->
				<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
					<div class="breadcrumb-title pe-3">Report</div>
					<div class="ps-3"> 
						<nav aria-label="breadcrumb">
							<ol class="breadcrumb mb-0 p-0">
								<li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
								</li>
								<li class="breadcrumb-item active" aria-current="page">Chat</li>
							</ol>
						</nav>
					</div>
				</div>
				<!--end breadcrumb-->
				
				<h6 class="mb-0 text-uppercase">Report Chat</h6>
				<hr/>
				<div class="card">
					<div class="card-body">
						<div class="table-responsive">
							<table id="example" class="table table-striped table-bordered">
								<thead>
									<tr>
									    <th>ID</th>
									     <th>Pelapor</th>
                                        <th>Tanggal</th>
                                        <th>Detail</th>
									</tr>
								</thead>
								<tbody>
								    <?
								    while ($reportChat_assoc = mysqli_fetch_assoc($reportChat)){
                                    
                                   
                                        $pelapor = $model->db_query($db, "*", "user", "id = '".$reportChat_assoc['pelapor']."' ");
                                        $message_detail = $model->db_query($db, "*", "reply_message", "id = '".$reportChat_assoc['id_report']."' ");
                                        $user_terlapor = $model->db_query($db, "*", "user", "id = '".$message_detail['rows']['pengirim']."' ");
                                    ?>  
								    <tr>
								        <td><?=$reportChat_assoc['id']?></td>
                                        <td><?=$pelapor['rows']['username']?></td>
                                        <td><?= format_date(substr($reportChat_assoc['created_at'], 0, -9)).", ".substr($reportChat_assoc['created_at'], -8); ?></td>
										<td><button title="Isi Pesan" type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal<?=$reportChat_assoc['id']?>"><i class="fadeIn animated bx bx-message-edit"></i></button></td>
									</tr>
									    <div class="modal fade" id="exampleModal<?=$reportChat_assoc['id']?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
											<div class="modal-dialog">
												<div class="modal-content">
													<div class="modal-header">
														<h5 class="modal-title" id="exampleModalLabel">Chat Bersama: <?=$user_terlapor['rows']['username']?></h5>
														<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
													</div>
													<div class="modal-body">
												
                                                  
                                                  <div class="modal-body">
                                                    <div class="col-12">
                                                        <label>Isi Pesan</label>
                										<textarea  class="form-control" id="InputKet"  rows="10" disabled><?=$message_detail['rows']['message']?></textarea>
                									</div>
                                                  </div>
													</div>
													<div class="modal-footer">
														<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
													</div>
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