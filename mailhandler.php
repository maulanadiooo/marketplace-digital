<?
require 'web.php';

$website = $model->db_query($db, "*", "website", "id = '1'");
exit(header("location: mailto:".$website['rows']['email_notifikasi']));
?>