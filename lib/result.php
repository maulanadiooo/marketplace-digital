<?php

if (isset($result_msg)) {
?>
<div class="alert alert-<?php echo $result_msg['alert'] ?> alert-dismissable">
	<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
	<b><?php echo $result_msg['title'] ?></b> <?php echo $result_msg['msg'] ?>
</div>
                    <?php
                    if ($result_msg['alert'] == "danger") {
                    ?>
                    <script>
                        Swal.fire({
                            type: "error",
                            title: "<?php echo $result_msg['title'] ?>",
                            html: "<?php echo $result_msg['msg'] ?>",
                            confirmButtonClass: "btn btn-confirm mt-2"
                        });
                    </script>
                    <?php
                    } else {
                    ?>
                    <script>
                        Swal.fire({
                            type: "success",
                            title: "<?php echo $result_msg['title'] ?>",
                            html: "<?php echo $result_msg['msg'] ?>",
                            confirmButtonClass: "btn btn-confirm mt-2"
                        });
                    </script>
                    <?php
                    }
                    ?>
<?php
}