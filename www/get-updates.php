<?php
header('Content-type: text/json');
$t=intval($_REQUEST['t']??0);
$now=time();
do {
	$vals=json_decode(file_get_contents('../vals.json'), true);
	if ($vals['image-time']>$t || $now+20<time()) {
		echo json_encode($vals);
		exit;
	}
} while(1);
