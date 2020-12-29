<?php
if (!isset($_REQUEST['val'])) {
	exit;
}
$val=floatval($_REQUEST['val']);
$obj=json_decode(file_get_contents('../vals.json'), true);
if (!isset($obj['altitude-requested']) || $obj['altitude-requested']!=$val) {
	$obj['altitude-requested']=$val;
	file_put_contents('../vals.json', json_encode($obj));
}
