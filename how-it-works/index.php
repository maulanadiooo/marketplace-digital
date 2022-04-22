<?php
require '../web.php';

$hiw = $model->db_query($db, "*", "pages", "id = '1'");
$web = $model->db_query($db, "*", "website", "id = '1'");
$title = "Cara Kerja";
?>
<?php
require '../template/header.php';
?>

<section class="breadcrumb-area breadcrumb--center">
        <div class="container">
            <div class="row">
                <div class="col-md-6 offset-md-3">
                    <div class="page_title">
                        <h1>Jadilah Penjual &amp; Pembeli di <?=$web['rows']['title']?></h1>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="breadcrumb text-center">
                        <ul>
                            <li>
                                <a href="index.html">Home</a>
                            </li>
                            <li class="active">
                                <a>Cara Kerja</a>
                            </li>
                        </ul>
                    </div>
                </div><!-- ends: .col-md-12 -->
            </div><!-- ends: .row -->
        </div><!-- ends: .container -->
    </section><!-- ends: .breadcrumb-area -->
    <section class="how_it_works">
        <div class="how_it_works_module border-bottom">
            <div class="content_block3">
                <div class="container">
                    <div class="row">
                        <div class="col-xl-5 col-lg-6 col-md-12 v_middle">
                            <div class="area_image m-bottom-md">
                                <img src="../img/svg/1.svg" alt="area images" class="svg">
                            </div>
                        </div><!-- end .col-md-12 -->
                        <div class="col-xl-5 offset-xl-2 col-lg-6 col-md-12 v_middle">
                            <div class="area_content">
                                
                                <?=$hiw['rows']['value']?>
                                <a href="<?=$config['web']['base_url']?>signup/" class="btn btn--md btn-primary">Register Now</a>
                            </div>
                        </div><!-- end .col-md-12 -->
                    </div>
                </div>
            </div><!-- ends: .content_block3 -->
        </div><!-- ends: .how_it_works_module -->
        <div class="how_it_works_module border-bottom">
            <div class="content_block4 ">
                <div class="container">
                    <div class="row">
                        <div class="col-xl-5 col-lg-6 col-md-12 v_middle">
                            <div class="area_content m-bottom-md">
                                <?=$hiw['rows']['value_1']?>
                                <a href="<?=$config['web']['base_url']?>signup/" class="btn btn--md btn-primary">Register Now</a>
                            </div>
                        </div><!-- end .col-md-12 -->
                        <div class="col-xl-5 offset-xl-2 col-lg-6 col-md-12 v_middle">
                            <div class="area_image">
                                <img src="../img/svg/2.svg" alt="area images" class="svg">
                            </div>
                        </div><!-- end .col-md-12 -->
                    </div>
                </div>
            </div><!-- ends: .content_block4 -->
        </div><!-- ends: .how_it_works_module -->
        <div class="how_it_works_module">
            <div class="content_block6">
                <div class="container">
                    <div class="row">
                        <div class="col-xl-5 col-lg-7 col-md-6 v_middle">
                            <div class="area_image">
                                <img src="../img/svg/3.svg" alt="area images" class="svg">
                            </div>
                        </div><!-- ends: .col-md-6 -->
                        <div class="col-xl-5 offset-xl-2 col-lg-5 col-md-6 v_middle">
                            <div class="area_content">
                                <?=$hiw['rows']['value_2']?>
                                <a href="<?=$config['web']['base_url']?>signup/" class="btn btn--md btn-primary">Register Now</a>
                            </div>
                        </div><!-- ends: .col-md-6 -->
                    </div>
                </div>
            </div><!-- ends: .content_block6 -->
        </div><!-- ends: .how_it_works_module -->
    </section><!-- ends: .how_it_works -->
    
<?php
require '../template/footer.php';

?>