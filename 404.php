
<?
require 'web.php';
require 'template/header.php';
$website = $model->db_query($db, "*", "website", "id = '1'"); 
?>

<section class="four_o_four_area section--padding">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 offset-lg-3 text-center">
                    <img src="<?= $config['web']['base_url']; ?>file-photo/website/<?=$website['rows']['logo_web']?>" alt="Not Found" width="330px" height ="100px">
                    <div class="not_found">
                        <h2>Yaaaah.. Kamu Kesasar....</h2>
                        <a href="<?=$config['web']['base_url']?>" class="btn btn--md btn-primary">Putar Arah</a>
                    </div>
                </div>
            </div>
        </div>
 </section><!-- ends: .four_o_four_area -->
 

<?
require 'template/footer.php';
?>

