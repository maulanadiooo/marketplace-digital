<?
require "../../web.php";
require "../../lib/check_session_admin.php";
require "../../lib/is_login.php";

$title = "User Chats";
require "../lib/sidebar.php";
require "../lib/header.php";

$chat = mysqli_query($db, "SELECT * FROM reply_message ORDER BY date DESC");
?>

<div class="page-wrapper">
			<div class="page-content">
				<!--breadcrumb-->
				<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
					<div class="breadcrumb-title pe-3">User Chats</div>
					<div class="ps-3"> 
						<nav aria-label="breadcrumb">
							<ol class="breadcrumb mb-0 p-0">
								<li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
								</li>
								<li class="breadcrumb-item active" aria-current="page">User Chats</li>
							</ol>
						</nav>
					</div>
				</div>
				<!--end breadcrumb-->
				
				<h6 class="mb-0 text-uppercase">User Chats</h6>
				<hr/>
				<div class="card">
					<div class="card-body">
						<div class="table-responsive">
							<table id="example" class="table table-striped table-bordered">
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

<?
require "../lib/footer.php"
?>