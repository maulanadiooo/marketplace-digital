<?php
require '../web.php';
$about = $model->db_query($db, "*", "pages", "id = '3'");

$title = "Tentang Kami";
?>
<?php
require '../template/header.php';
?>

<section class="about_mission">
        <div class="content_block1">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-xl-5 col-lg-6">
                        <div class="content_area m-bottom-md">
                            <p><?=$about['rows']['value']?></p>
                        </div>
                    </div><!-- end .col-md-5 -->
                    <div class="col-xl-6 offset-xl-1 col-lg-6">
                        <img src="<?=$config['web']['base_url']?>file-photo/website/3026238.jpg" alt="" class="img-fluid">
                    </div>
                </div><!-- end .row -->
            </div><!-- end .container -->
        </div><!-- ends: .content_block1 -->
        <div class="content_block2">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-xl-5 col-lg-6">
                        <img src="<?=$config['web']['base_url']?>file-photo/website/6671.jpg" alt="" class="img-fluid">
                    </div>
                    <div class="col-xl-6 offset-xl-1 col-lg-6">
                        <div class="content_area m-top-md">
                            <p><?=$about['rows']['value_1']?></p>
                        </div>
                    </div><!-- end .col-md-6 -->
                </div><!-- end .row -->
            </div><!-- end .container -->
        </div><!-- ends: .content_block2 -->
    </section><!-- ends: .about_mission -->
    
   <?php
require '../template/footer.php';

?>