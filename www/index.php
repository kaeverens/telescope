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
		<script src="telescope.js"></script>
	</body>
</html>
