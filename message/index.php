<?php
require '../web.php';
require '../lib/is_login.php';
require '../lib/result.php';
if (!isset($_SESSION['login'])) {
	$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Otentikasi dibutuhkan!', 'msg' => 'Silahkan masuk ke akun Anda.');
	exit(header("Location: ".$config['web']['base_url']."signin/"));
}
if ($model->db_query($db, "*", "user", "id = '".$_SESSION['login']."'")['count'] == 0) {
	exit("No direct script access allowed!2");
}


$dataPerHalaman = 20;

$data_targeta = mysqli_query($db, "SELECT * FROM message WHERE pengirim = '".$login['id']."' OR penerima = '".$login['id']."' ORDER by date DESC");

$jumlahData = mysqli_num_rows($data_targeta);
$jumlahHalaman = ceil($jumlahData/$dataPerHalaman);

if (isset($_GET['page'])) {
	$pageActive = $_GET['page'];
} else {
    $pageActive = 1;
}
$awalData = ($dataPerHalaman * $pageActive) - $dataPerHalaman;
$jumlahLink = 2;
if($pageActive > $jumlahLink){
    $start_number = $pageActive - $jumlahLink;
} else {
    $start_number = 1;
}

if($pageActive < ($jumlahHalaman - $jumlahLink)){
    $end_number = $pageActive + $jumlahLink;
} else {
    $end_number = $jumlahHalaman;
}


$data_targetraa = mysqli_query($db, "SELECT * FROM message WHERE pengirim = '".$login['id']."' OR penerima = '".$login['id']."' ORDER by date DESC LIMIT $awalData,$dataPerHalaman");
$data_targetr_notif = mysqli_query($db, "SELECT * FROM message WHERE dibaca_penerima = 'no' AND penerima = '".$_SESSION['login']."' ORDER by date DESC");
$jumlah_notif = mysqli_num_rows($data_targetr_notif); 

$title = "Message";
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
                        <h3>Messages</h3>
                    </div><!-- ends: .content_title -->
                </div><!-- ends: .col-md-12 -->
            </div><!-- ends: .row -->
            <div class="row">
                <div class="col-lg-12 col-md-12">
                    <div class="cardify messaging_sidebar">
                        <div class="messaging__header">
                            <div class="messaging_menu">
                                <?
                                if($jumlah_notif == 0){
                                ?>  
                                <a aria-haspopup="true" aria-expanded="true">
                                    <span class="icon-drawer"></span>Kotak Masuk
                                </a>
                                <?
                                } else {
                                ?>
                                 <a aria-haspopup="true" aria-expanded="true">
                                    <span class="icon-drawer"></span>Kotak Masuk
                                    <span class="msg"><?=$jumlah_notif?></span>
                                </a>
                                <?    
                                }
                                ?>
                            </div><!-- ends: .messaging_menu -->
                        </div><!-- ends: .messaging__header -->
                        <div class="messaging__contents">
                            
                            <?
                            while ($detailTarget = mysqli_fetch_assoc($data_targetraa)){
                                
                                if($detailTarget['pengirim'] == $login['id'] ){
                                    $id_komentar = $detailTarget['penerima'];
                                } else {
                                    $id_komentar = $detailTarget['pengirim'];
                                }
                                
                                
                                $data_user= $model->db_query($db, "*", "user", "id = '$id_komentar' ");
                            ?>
                            <div class="messages">
                                <?
                                if($detailTarget['penerima'] == $login['id'] && $detailTarget['dibaca_penerima'] == 'no'){
                                ?>
                                <div class="message active">
                                <?   
                                } else {
                                ?>
                                <div class="message">
                                <?    
                                }
                                ?>
                                    <div class="message__actions_avatar">
                                        <?
                                        if($data_user['rows']['photo'] == null){
                                        ?>
                                        <div class="avatar">
                                            <img src="<?= $config['web']['base_url'] ?>img/avatar.png" alt="<?=$data_user['rows']['username']?>">
                                        </div>
                                        <?
                                        } else {
                                        ?>
                                        <div class="avatar">
                                            <img src="<?= $config['web']['base_url'] ?>user-photo/<?=$data_user['rows']['photo']?>" alt="<?=$data_user['rows']['username']?>">
                                        </div>
                                        <?
                                        }
                                        ?>
                                    </div><!-- ends: .message__actions_avatar -->
                                    <div class="message_data">
                                        <div class="name_time">
                                            <div class="name">
                                                <a href="<?= $config['web']['base_url'] ?>conversation/<?= $data_user['rows']['username']?>"><p><?= $data_user['rows']['username'] ?></p></a>
                                                <?
                                                if($detailTarget['penerima'] == $login['id'] && $detailTarget['dibaca_penerima'] == 'no'){
                                                ?>
                                                <a href="<?= $config['web']['base_url'] ?>conversation/<?= $data_user['rows']['username']?>"><span class="icon-envelope"></span></a>
                                                <?    
                                                } else{
                                                ?>    
                                                <a href="<?= $config['web']['base_url'] ?>conversation/<?= $data_user['rows']['username']?>"><span class="icon-envelope-open"></span></a>
                                                <?    
                                                }
                                                ?>
                                            </div>
                                            <a href="<?= $config['web']['base_url'] ?>conversation/<?= $data_user['rows']['username']?>"><span class="time"><?= format_date(substr($detailTarget['date'], 0, -9)).", ".substr($detailTarget['date'], -8)?> UTC+7</span></a>
                                            <?
                                            $isi_pesan = html_entity_decode($detailTarget['message'], ENT_NOQUOTES);
                                             $jumlah_karakter = strlen($isi_pesan);
                                             if($jumlah_karakter > 50){
                                                $singkat = substr($isi_pesan, 0, 50) ;
                                                $hasil_pesan = $singkat." ....";
                                             } else {
                                                 $hasil_pesan = $isi_pesan;
                                             }
                                            ?>
                                            <a href="<?= $config['web']['base_url'] ?>conversation/<?= $data_user['rows']['username']?>"><p><?= $hasil_pesan ?></p></a>
                                        </div>
                                    </div><!-- ends: .message_data -->
                                </div><!-- ends: .message -->
                            </div><!-- ends: .messages -->
                            <?}?>
                        </div><!-- ends: .messaging__contents -->
                    </div><!-- ends: .cardify -->
                    <nav class="pagination-default">
                            <ul class="pagination">
                                <? if($pageActive > 1){ 
                                ?>
                                <li class="page-item">
                                    <a class="page-link" href="<?= $config['web']['base_url']; ?>message/<?= $pageActive - 1 ?>" aria-label="Previous">
                                        <span aria-hidden="true"><i class="fa fa-long-arrow-left"></i></span>
                                        <span class="sr-only">Previous</span>
                                    </a>
                                </li>
                                <?
                                }?>
                                <? if($start_number > 1){?>
                                <li class="page-item disabled"><a class="page-link" href="#">...</a></li>
                                <?}?>
                                
                                <!--navigasi-->
                                <?php
                                for($i = $start_number; $i <= $end_number; $i++){
                                ?>   
                                    
                                    <? if ($i == $pageActive){?>
                                    <li class="page-item active"><a class="page-link" href="<?= $config['web']['base_url']; ?>message/<?= $i; ?>"><?= $i ?></a></li>
                                    <? } else {?>
                                    <li class="page-item"><a class="page-link" href="<?= $config['web']['base_url']; ?>message/<?= $i; ?>"><?= $i ?></a></li>
                                    <? } ?>
                                <?    
                                }
                                
                                ?>
                                
                                <? if($end_number < $jumlahHalaman){?>
                                <li class="page-item disabled"><a class="page-link" href="#">...</a></li>
                                <?}?>
                                <? if($pageActive < $jumlahHalaman){ 
                                ?>
                                <li class="page-item">
                                    <a class="page-link" href="<?= $config['web']['base_url']; ?>message/<?= $pageActive + 1 ?>" aria-label="Next">
                                        <span aria-hidden="true"><i class="fa fa-long-arrow-right"></i></span>
                                        <span class="sr-only">Next</span>
                                    </a>
                                </li>
                                <?
                                }?>
                                
                            </ul>
                        </nav><!-- Ends: .pagination-default -->
                </div><!-- ends: .col-md-5 -->
            </div><!-- ends: .row -->
        </div><!-- ends: .container -->
    </section><!-- ends: .message_area -->
    
    <?php
require '../template/footer.php';

?>