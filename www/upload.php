<?php
$target_dir = "../images/";
if (isset($_FILES['image'])) {
	$check = getimagesize($_FILES["image"]["tmp_name"]);
	if ($check) {
		$t=time();
		move_uploaded_file($_FILES["image"]["tmp_name"], $target_dir.$t.'.jpg');
		$obj=json_decode(file_get_contents('../vals.json'), true);
		$obj['image-time']=$t;
		$json=[ // movement orders for the telescope
			'platform'=>0,
			'elevation'=>0
		];
		$azi=floatval($obj['altitude']??0);
		if (isset($obj['altitude-requested'])) {
			if ($obj['altitude-requested']!=$azi) {
				$degs=$obj['altitude-requested']-$azi;
				$steps_per_deg=109;
				$json['elevation']=$steps_per_deg*$degs;
				$obj['altitude']=$obj['altitude-requested'];
			}
			unset($obj['altitude-requested']);
		}
		$azi=floatval($obj['azimuth']??0);
		if (isset($obj['azimuth-requested'])) {
			if ($obj['azimuth-requested']!=$azi) {
				$degs=$obj['azimuth-requested']-$azi;
				$steps_per_deg=75;
				$json['platform']=$steps_per_deg*$degs;
				$obj['azimuth']=$obj['azimuth-requested'];
			}
			unset($obj['azimuth-requested']);
		}
		file_put_contents('../vals.json', json_encode($obj));
		echo 'RESPONSE:'.json_encode($json);
	}
}
