<?php
if (!isset($_REQUEST['val'])) {
	exit;
}
$val=floatval($_REQUEST['val']);
$obj=json_decode(file_get_contents('../vals.json'), true);
if (!isset($obj['azimuth-requested']) || $obj['azimuth-requested']!=$val) {
	$obj['azimuth-requested']=$val;
	file_put_contents('../vals.json', json_encode($obj));
}
