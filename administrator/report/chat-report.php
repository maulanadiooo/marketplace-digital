<?
require "../../web.php";
require "../../lib/check_session_admin.php";
require "../../lib/is_login.php";

$title = "Chat Report";
require "../lib/header.php";
require "../lib/sidebar.php";

$reportChat = mysqli_query($db, "SELECT * FROM report WHERE report = 'message' ORDER BY id DESC");
?>

<div class="main-panel">
	<div class="content">
		<div class="page-inner">
		    <div class="page-header">
				<h4 class="page-title">Report</h4>
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
						<a href="#">Chat</a>
					</li>
				</ul>
			</div>
				
    			<div class="row">
    			    <div class="col-md-12">
    							<div class="card">
    								<div class="card-header">
    									<h4 class="card-title">Chat Report</h4>
    								</div>
    								<div class="card-body">
    									<div class="table-responsive">
    										<table id="basic-datatables" class="display table table-striped table-hover" >
    											<thead>
    												<tr>
                									    <th>ID</th>
                									     <th>Pelapor</th>
                                                        <th>Tanggal</th>
                                                        <th>Detail</th>
                									</tr>
    											</thead>
    											<tfoot>
    												<tr>
                									    <th>ID</th>
                									     <th>Pelapor</th>
                                                        <th>Tanggal</th>
                                                        <th>Detail</th>
                									</tr>
    											</tfoot>
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
                										<td><button title="Isi Pesan" type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal<?=$reportChat_assoc['id']?>"><i class="fas fa-archive"></i></button></td>
                									</tr>
                									    <div class="modal fade" id="exampleModal<?=$reportChat_assoc['id']?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                											<div class="modal-dialog">
                												<div class="modal-content">
                													<div class="modal-header">
                														<h5 class="modal-title" id="exampleModalLabel">Chat Bersama: <?=$user_terlapor['rows']['username']?></h5>
                														<button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
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
                														<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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
				</div>
				
			</div>
		</div>

<?
require "../lib/footer.php"
?>