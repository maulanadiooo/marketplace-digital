<?
require "../../web.php";
require "../../lib/check_session_admin.php";
require "../../lib/is_login.php";

$title = "User Chats";
require "../lib/header.php";
require "../lib/sidebar.php";

$chat = mysqli_query($db, "SELECT * FROM reply_message ORDER BY date DESC");
?>

<div class="main-panel">
	<div class="content">
		<div class="page-inner">
		    <div class="page-header">
				<h4 class="page-title">Chat</h4>
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
						<a href="#">User</a>
					</li>
				</ul>
			</div>
				
				<div class="row">
    			    <div class="col-md-12">
    							<div class="card">
    								<div class="card-header">
    									<h4 class="card-title">User Chats</h4>
    								</div>
    								<div class="card-body">
    									<div class="table-responsive">
    										<table id="basic-datatables" class="display table table-striped table-hover" >
    											<thead>
    												<tr>
                									    <td>ID</td>
                									    <th>Pengirim</th>
                                                        <th>Penerima</th>
                                                        <th>Pesan</th>
                                                        <th>Attachment</th>
                                                        <th>Tanggal</th>
                									</tr>
    											</thead>
    											<tfoot>
    												<tr>
                									    <td>ID</td>
                									    <th>Pengirim</th>
                                                        <th>Penerima</th>
                                                        <th>Pesan</th>
                                                        <th>Attachment</th>
                                                        <th>Tanggal</th>
                									</tr>
    											</tfoot>
    											<tbody>
                								    <?
                								    while ($chat_assoc = mysqli_fetch_assoc($chat)){
                                                    
                                                   
                                                        $pengirim = $model->db_query($db, "*", "user", "id = '".$chat_assoc['pengirim']."' ");
                                                        $penerima = $model->db_query($db, "*", "user", "id = '".$chat_assoc['penerima']."' ");
                                                    ?>  
                								    <tr>
                								        <td><?=$chat_assoc['id']?></td>
                                                        <td><a href="<?= $config['web']['base_url']; ?>user/<?=$pengirim['rows']['username']?>" target="_blank"><?=$pengirim['rows']['username']?></a></td>
                                                        <td><a href="<?= $config['web']['base_url']; ?>user/<?=$penerima['rows']['username']?>" target="_blank"><?=$penerima['rows']['username']?></a></td>
                                                        <td><?=$chat_assoc['message']?></td>
                                                        <?
                                                        if($chat_assoc['attachment'] != null){
                                                        ?>
                                                        <td><a href="<?= $config['web']['base_url']; ?>files-conversation/<?=$chat_assoc['attachment']?>" target="_blank"><i class="fadeIn animated bx bx-cloud-lightning"></i> <span>Attachment</span></a></td>
                                                        <?
                                                        } else {
                                                        ?>
                                                        <td></td>
                                                        <?
                                                        }
                                                        ?>
                                                        <td><?= format_date(substr($chat_assoc['date'], 0, -9)).", ".substr($chat_assoc['date'], -8); ?></td>
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