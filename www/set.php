<?php
$key=$_REQUEST['k']??'';
if (!isset($_REQUEST['v'])) {
	exit;
}
$val=floatval($_REQUEST['v']);
if (!in_array($key, [ 'brightness', 'contrast', 'saturation', 'whitebal', 'awb_gain', 'wb_mode', 'exposure_ctrl', 'aec2', 'ae_level', 'aec_value', 'gain_ctrl', 'agc_gain', 'gainceiling', 'bpc', 'wpc', 'raw_gma', 'lenc', 'hmirror', 'vflip', 'dcw', 'colorbar', ])) {
	exit;
}
$obj=json_decode(file_get_contents('../vals.json'), true);
if (!isset($obj[$key]) || $obj[$key]!=$val) {
	$obj[$key]=$val;
	error_log(json_encode([$key, $val]));
	file_put_contents('../vals.json', json_encode($obj));
}
