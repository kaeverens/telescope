<?php
$target_dir = "../images/";
if (isset($_FILES['image'])) {
	$check = getimagesize($_FILES["image"]["tmp_name"]);
	if ($check) {
		$t=time();
		if ($_FILES["image"]["name"]=='esp32-cam.raw') {
			$im = new Imagick( $_FILES["image"]["tmp_name"] );
			$im->setImageFormat( 'jpg' );
			$im->writeImage($target_dir.$t.'.jpg');
			$im->clear();
			$im->destroy();
		}
		else {
			move_uploaded_file($_FILES["image"]["tmp_name"], $target_dir.$t.'.jpg');
		}
		$obj=json_decode(file_get_contents('../vals.json'), true);
		$obj['image-time']=$t;
		unset($obj['platform'], $obj['elevation']);
		$azi=floatval($obj['altitude']??0);
		if (isset($obj['altitude-requested'])) {
			if ($obj['altitude-requested']!=$azi) {
				$degs=$obj['altitude-requested']-$azi;
				$steps_per_deg=109;
				$obj['elevation']=$steps_per_deg*$degs;
				$obj['altitude']=$obj['altitude-requested'];
			}
			unset($obj['altitude-requested']);
		}
		$azi=floatval($obj['azimuth']??0);
		if (isset($obj['azimuth-requested'])) {
			if ($obj['azimuth-requested']!=$azi) {
				$degs=$obj['azimuth-requested']-$azi;
				$steps_per_deg=75;
				$obj['platform']=$steps_per_deg*$degs;
				$obj['azimuth']=$obj['azimuth-requested'];
			}
			unset($obj['azimuth-requested']);
		}
		file_put_contents('../vals.json', json_encode($obj));
		echo 'RESPONSE:'.json_encode($obj);
	}
}
