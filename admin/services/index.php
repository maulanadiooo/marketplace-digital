<?
require "../../web.php";
require "../../lib/check_session_admin.php";
exit(header("Location: ".$config['web']['base_url']."administrator"));
?>