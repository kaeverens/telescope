$(()=>{
	var t=0;
	function getUpdate() {
		$.post('get-updates.php', {
			t:t
		}, ret=>{
			$('#azimuth-val').text((+ret.azimuth)+'°');
			$('#altitude-val').text((+ret.altitude)+'°');
			if (ret['image-time']!=t) {
				t=ret['image-time'];
				var $img=$('<img src="get-image.php?t='+t+'"/>');
				$img.on('load', ()=>{
					$('#image img').attr('src', $img.attr('src'));
					$('#image label').text(new Date(t*1000));
				});
			}
			Object.keys(ret).forEach(k=>{
				var $inp=$('#settings input[name='+k+']');
				if ($inp && $inp.val()=='') {
					$inp.val(ret[k]);
				}
			});
			setTimeout(getUpdate, 100);
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
	$('#settings')
		.on('change', 'input', function() {
			var $this=$(this), name=$this.prop('name');
			console.log(name, $this);
			if (!name) {
				return;
			}
			var val=$this.val();
			$.post('set.php', {
				k:name,
				v:val
			});
		});
});
