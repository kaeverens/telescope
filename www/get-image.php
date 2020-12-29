<?php
$t=intval($_REQUEST['t']??0);
if ($t) {
	header('Content-type: image/jpeg');
	echo file_get_contents('../images/'.$t.'.jpg');
}
