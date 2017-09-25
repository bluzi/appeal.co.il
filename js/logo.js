$(document).ready(function() {
	$("#site-name").hover(function() {
		$("#logo").attr('src', 'images/logo-highlight.png');
	},
	function() {
		$("#logo").attr('src', 'images/logo.png');
	});
});