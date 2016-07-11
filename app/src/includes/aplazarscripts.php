<script type="text/javascript">
	function downloadJSAtOnload() {
		var element = document.createElement("script");
		element.src = url + "scripts/defer.js";
		document.body.appendChild(element);
	}
	if (window.addEventListener)
		window.addEventListener("load", downloadJSAtOnload, false);
	else if (window.attachEvent)
		window.attachEvent("onload", downloadJSAtOnload);
	else window.onload = downloadJSAtOnload;
</script>