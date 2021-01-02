<!doctype html>
<html>
	<head>
		<script src="//code.jquery.com/jquery-3.5.1.min.js"></script>
		<script src="//code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
	</head>
	<body>
		<div id="image" style="position:relative;display:inline-block">
			<img src="" style="max-width:100%"/>
			<label style="position:absolute;right:0;bottom:0;background:#000;color:#fff">please wait...</label>
		</div>
		<table>
			<tr><th>axis</th><td>current value</td><td>new value</td></tr>
			<tr><th>azimuth</th><td id="azimuth-val"></td><td><input id="azimuth" min="0" max="360" style="width:40px"/></td></tr>
			<tr><th>altitude</th><td id="altitude-val"></td><td><input id="altitude" min="22.5" max="157.5" style="width:40px"/></td></tr>
		</table>
		<div id="settings">
			<label>brightness<input type="number" min="-2" max="2" name="brightness"/></label>
			<label>contrast<input type="number" min="-2" max="2" name="contrast"/></label>
			<label>saturation<input type="number" min="-2" max="2" name="saturation"/></label>
			<label>whitebal<input type="number" min="0" max="1" name="whitebal"/></label>
			<label>awb_gain<input type="number" min="0" max="1" name="awb_gain"/></label>
			<label>wb_mode<input type="number" min="0" max="4" name="wb_mode"/></label>
			<label>exposure_ctrl<input type="number" min="0" max="1" name="exposure_ctrl"/></label>
			<label>aec2<input type="number" min="0" max="1" name="aec2"/></label>
			<label>ae_level<input type="number" min="-2" max="2" name="ae_level"/></label>
			<label>aec_value<input type="number" min="0" max="1200" name="aec_value"/></label>
			<label>gain_ctrl<input type="number" min="0" max="1" name="gain_ctrl"/></label>
			<label>agc_gain<input type="number" min="0" max="30" name="agc_gain"/></label>
			<label>gainceiling<input type="number" min="0" max="6" name="gainceiling"/></label>
			<label>bpc<input type="number" min="0" max="1" name="bpc"/></label>
			<label>wpc<input type="number" min="0" max="1" name="wpc"/></label>
			<label>raw_gma<input type="number" min="0" max="1" name="raw_gma"/></label>
			<label>lenc<input type="number" min="0" max="1" name="lenc"/></label>
			<label>hmirror<input type="number" min="0" max="1" name="hmirror"/></label>
			<label>vflip<input type="number" min="0" max="1" name="vflip"/></label>
			<label>dcw<input type="number" min="0" max="1" name="dcw"/></label>
			<label>colorbar<input type="number" min="0" max="1" name="colorbar"/></label>
		</div>
		<script src="telescope.js"></script>
	</body>
</html>
