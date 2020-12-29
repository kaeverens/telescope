$(()=>{
	var t=0;
	function getUpdate() {
		$.post('get-updates.php', ret=>{
			$('#azimuth-val').text((+ret.azimuth)+'°');
			$('#altitude-val').text((+ret.altitude)+'°');
			if (ret['image-time']!=t) {
				t=ret['image-time'];
				var $img=$('<img src="get-image.php?t='+t+'"/>');
				$img.on('load', ()=>{
					$('#image img').attr('src', $img.attr('src'));
					$('#image label').text(new Date(t*1000));
				});
				//$img.appendTo('body');
			}
			setTimeout(getUpdate, 5000);
		});
	}
	getUpdate();
	$('#azimuth').change(function() {
		$.post('set-azimuth.php', {
			val:$(this).val()
		});
	});
	$('#altitude').change(function() {
		$.post('set-altitude.php', {
			val:$(this).val()
		});
	});
});
