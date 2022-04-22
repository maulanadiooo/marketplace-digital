<?php
require '../web.php';

$tc = $model->db_query($db, "*", "pages", "id = '4'");
$title = "Term & Condition";
?>
<?php
require '../template/header.php';
?>

<div class="term-condition-area bgcolor">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="content-block">
                        <?=$tc['rows']['value']?>
                    </div><!-- Ends: .content-block -->
                </div><!-- Ends: .col-lg-12 -->
            </div>
        </div>
    </div><!-- ends: .term-condition-area -->
    

<?php
require '../template/footer.php';

?>