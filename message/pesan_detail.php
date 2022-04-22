<?php
require '../web.php';
require '../lib/is_login.php';
require '../lib/csrf_token.php';

if (!isset($_GET['username'])) {
	exit(header("Location: ".$config['web']['base_url']."message/"));
}
$id_query = $db->real_escape_string(trim(htmlspecialchars(htmlentities($_GET['username']))));
if (!isset($_SESSION['login'])) {
    $username = $model->db_query($db, "*", "user", "username = '$id_query'");
    $token = str_rand(50);
    $nows = date("Y-m-d H:i:s");
    $expired_re = date('Y-m-d H:i:s',strtotime('+20 Min',strtotime($nows)));
    $input_post = array(
	    'token' => $token,
		'go' => "conversation/".$id_query,
		'expired_at' => $expired_re, 
	);
	$model->db_insert($db, "redirect", $input_post);
	exit(header("Location: ".$config['web']['base_url']."signin/?redirect=".$input_post['token']));
}


$getID = $db->real_escape_string(trim(htmlspecialchars(htmlentities($_GET['username']))));
$id_SESI = $_SESSION['login'];

$data_user_message =$model->db_query($db, "*", "user", "username = '$getID'");
if ($data_user_message['count'] == 0) {
	$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'User Tidak Ditemukan.');
	exit(header("Location: ".$config['web']['base_url']."message/"));
}
$idUserMessage = $data_user_message['rows']['id'];

if($id_SESI > $idUserMessage ){
    $chatAntara = $idUserMessage."-".$id_SESI;
} else {
    $chatAntara = $id_SESI."-".$idUserMessage;
}
 
$model->db_update($db, "message", array('dibaca_penerima' => 'yes'), "chat_antara = '$chatAntara' AND penerima ='".$login['id']."' ");


$data_target_message = mysqli_query($db, "SELECT * FROM reply_message WHERE chat_antara ='$chatAntara' ORDER by date ASC");
$title = "Percakapan Dengan ".$getID;
?>
<?php
require '../template/header.php';
require '../template/header-dashboard.php';
?>

<section class="message_area">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="content_title">
                        <h3>Messages</h3> <br>
                        <p><font color="red">Dilarang Untuk Mengarahkan Penjual/Pembeli Bertransaksi diluar Dari GubukDigital.net, Melanggar akan menyebabkan akun dibanned permanen dan saldo hangus</font></p>
                        <span>Terima notifikasi pesan dan orderan via telegram, Silahkan ke <a href="<?=$config['web']['base_url']?>setting/" target="_blank">pengaturan</a></span>
                    </div><!-- ends: .content_title -->
                    
                </div><!-- ends: .col-md-12 -->
            </div><!-- ends: .row -->
            <div class="row">
                <div class="col-lg-12 col-md-12">
                    <div class="chat_area cardify">
                        <div class="chat_area--title">
                            <h3>Pesan Bersama <span class="name"><a href="<?=$config['web']['base_url']?>user/<?=$getID ?>"><?=$getID ?></a></span></h3>
                            <?
                            
                            $user = $model->db_query($db, "*", "user", "username = '$getID'");  
                            $now = date("Y-m-d H:i:s");
                            $last_login = $user['rows']['last_login'];
                            $online = date('Y-m-d H:i:s',strtotime('-5 Min',strtotime($now)));
                            
                            if(strtotime($online) < strtotime($last_login) ){
                                $status_online = 'Online';
                                $badge = 'success';
                                
                            } else {
                                $status_online = 'Last Online: <br>'.format_date(substr($last_login, 0, -9)).", ".substr($last_login, 11, -3).' UTC+7';
                                $badge = 'warning';
                            }
                            ?>
                            <br><span class="badge badge-<?=$badge?>" ><?= $status_online ?></span><br>
                            
                        </div><!-- ends: .chat_area--title -->
                        <div class="chat_area--conversation">
                            <?
                            while ($detailTargetMessage = mysqli_fetch_assoc($data_target_message)){
                              
                                $data_user_message =$model->db_query($db, "*", "user", "id = '".$detailTargetMessage['pengirim']."'");
                              
                            ?>
                            <?
                            if($detailTargetMessage['pengirim'] == $login['id']){
                            ?>
                            <div class="conversation">
                            <?    
                            } else {
                            ?>
                            <div class="conversation" >
                            <?    
                            }
                            ?>
                            
                                <div class="head">
                                    <?
                                    if($data_user_message['rows']['photo'] == null){
                                    ?>
                                    <div class="chat_avatar">
                                        <img src="<?= $config['web']['base_url']?>img/avatar.png" alt="<?= $data_user_message['rows']['username']?>"> 
                                    </div>
                                    <?
                                    } else {
                                    ?>
                                    <div class="chat_avatar">
                                        <img src="<?= $config['web']['base_url']?>user-photo/<?= $data_user_message['rows']['photo']?>" alt="<?= $data_user_message['rows']['username']?>">
                                    </div>
                                    <?
                                    }
                                    ?>
                                    
                                    <div class="name_time">
                                        <div>
                                            
                                            <a href="<?= $config['web']['base_url']?>user/<?= $data_user_message['rows']['username'] ?>"><span><?= $data_user_message['rows']['username'] ?></span></a>
                                            
                                            <p>
                                                
                                                    
                                                
                                            
                                            <?= format_date(substr($detailTargetMessage['date'], 0, -9)).", ".substr($detailTargetMessage['date'], -8)?> UTC+7 
                                            <?
                                            if($detailTargetMessage['report'] == '0' && $detailTargetMessage['penerima'] == $login['id']){
                                                $report = 'message';
                                            ?>
                                            <br> 
                                            <a type="submit" href="<?=$config['web']['base_url']?>report/report?report=<?=$report?>&query=<?=$detailTargetMessage['id']?>" title="Laporkan Pesan"><i class="fa fa-exclamation-triangle" aria-hidden="true" style="color:#ffa621"></i></a> 
                                            <?
                                            } 
                                            ?>
                                            
                                            <?
                                            if($detailTargetMessage['report'] == '1' && $detailTargetMessage['penerima'] == $login['id']){
                                                $report = 'message';
                                            ?>
                                            <br> 
                                            <span><font color="red">Laporan Telah Kami Terima</font></span> 
                                            <?
                                            } 
                                            ?>
                                            
                                            
                                        </div>
                                    </div><!-- ends: .name_time -->
                                </div><!-- ends: .head -->
                                <div class="body" >
                                    <?
                                    if($detailTargetMessage['terkait'] != '0'){
                                        $services = $model->db_query($db, "*", "services", "id = '".$detailTargetMessage['terkait']."' ");
                                    ?>
                                    <p>Pesan Terkait Produk : <a href="<?= $config['web']['base_url']; ?>product/<?=$services['rows']['id']?>/<?=$services['rows']['url']?>"><?=$services['rows']['nama_layanan']?></a></p> <br><br>
                                    <?
                                    }
                                    ?>
                                    <?
                                    if($detailTargetMessage['permintaan'] != '0'){
                                        $permintaan = $model->db_query($db, "*", "permintaan_pembeli", "id = '".$detailTargetMessage['permintaan']."' ");
                                        $tawaran = $model->db_query($db, "*", "services", "id = '".$detailTargetMessage['service_id']."' ");
                                    ?>
                                    <p>Pesan Terkait Permintaan : <?=$permintaan['rows']['permintaan']?></p> <br>
                                    <p>Tawaran : <a href="<?=$config['web']['base_url']?>product/<?=$tawaran['rows']['id']?>/<?=$tawaran['rows']['url']?>"><?=$tawaran['rows']['nama_layanan']?></a></p> <br><br>
                                    <?
                                    }
                                    ?>
                                    <p><?= nl2br($detailTargetMessage['message'])?></p>
                                    <?
                                    if($detailTargetMessage['attachment'] != null){
                                    $format = $detailTargetMessage['attachment'];
                                    $pisah = explode("-", $format);
                                    $namaFile = $pisah[1].$pisah[2].$pisah[3].$pisah[4].$pisah[5].$pisah[6].$pisah[7].$pisah[8].$pisah[9].$pisah[10].$pisah[11].$pisah[12].$pisah[13].$pisah[14].$pisah[15].$pisah[16].$pisah[17].$pisah[18].$pisah[19].$pisah[20];
                                    ?>
                                    <div class="attachments">
                                        <div class="attachment_head">
                                            <p><a href="<?= $config['web']['base_url']; ?>files-conversation/<?=$detailTargetMessage['attachment']?>" target="_blank"><span class="icon-cloud-download"></span> <span>Attachment</span></a></p>
                                            
                                            
                                        </div>
                                    </div>
                                    <?
                                    }
                                    ?>
                                </div><!-- ends: .body -->
                            </div><!-- ends: .conversation -->
                            <?}?>
                        </div><!-- ends: .chat_area--conversation -->
                        <form method="post" action="<?= $config['web']['base_url']; ?>message/send.php" enctype="multipart/form-data">
                        <input type="hidden" name="csrf_token" value="<?php echo $config['csrf_token'] ?>">
                        <input type="hidden" name="msgatr" value="<?=$chatAntara?>">
                        <div class="message_composer">
                            <div class="col-md-12">
                                <div class="m-bottom-20 no-margin">
                                    <label class="label">Kirim Pesan <span></span></label>
                                    <textarea  name="message" class="form-control form-control-lg" rows="20"></textarea>
                                </div>
                            </div>
                            <div class="attached"></div>
                            <div class="btns">
                                <button type="submit" class="btn send btn--sm btn-primary">Submit</button>
                                <label for="att">
                                    <input type="file" name="file" class="attachment_field" id="att" multiple>
                                    <span class="icon-paper-clip"></span> Attachment
                                </label>
                                <span id="custom-text">(png, jpg, jpeg, rar, zip, txt, xls, xlt, xlsx, doc, docx, max 2MB)</span>
                            </div><!-- ends: .message_composer -->
                        </div><!-- ends: .message_composer -->
                        </form>
                    </div><!-- ends: .chat_area -->
                </div><!-- ends: .col-md-7 -->
            </div><!-- ends: .row -->
        </div><!-- ends: .container -->
    </section><!-- ends: .message_area -->

<script>
    const realFileBtn = document.getElementById("real-file");
    const customBtn = document.getElementById("custom-button");
    const customTxt = document.getElementById("custom-text");
    
    customBtn.addEventListener("click", function() {
      realFileBtn.click();
    });
    
    realFileBtn.addEventListener("change", function() {
      if (realFileBtn.value) {
        customTxt.innerHTML = realFileBtn.value.match(
          /[\/\\]([\w\d\s\.\-\(\)]+)$/
        )[1];
      } else {
        customTxt.innerHTML = "Tidak ada file terpilih.. ";
      }
    });

    
</script>
    
<script src="<?= $config['web']['base_url']; ?>vendor_assets/js/tinymce/tinymce.min.js"></script>    
<script>
    tinymce.init({
        selector: '#content',
        plugins: 'preview fullpage directionality visualblocks visualchars fullscreen image media hr toc advlist lists imagetools',
        toolbar: 'formatselect | bold italic strikethrough | alignleft aligncenter alignright alignjustify  | numlist bullist outdent | removeformat code',
    });

    $('[name="type"]').on('change', (event) => {

        let selectedOption = $(event.currentTarget).find(':selected').attr('value');

        switch(selectedOption) {

            case 'internal':

                $('#url_label').html("Slug");
                $('#url_prepend').show();
                $('input[name="url"]').attr('placeholder', "ex: page-url");
                $('#description_container').show();

                break;

            case 'external':

                $('#url_label').html("External Url");
                $('#url_prepend').hide();
                $('input[name="url"]').attr('placeholder', "ex: https:\/\/domain.com\/");
                $('#description_container').hide();

                break;
        }

    });
</script>    
    <?php

require '../template/footer.php';

?>