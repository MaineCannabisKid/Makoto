<?php

?>
<!-- Latest compiled and minified Bootstrap CSS -->
<!-- Using Darkly Bootstrap Theme from Bootswatch -->
<link rel="stylesheet" type="text/css" href="http://bootswatch.com/darkly/bootstrap.css">
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
<!-- Latest compiled and minified Bootstrap JavaScript
	 MUST BE LOADED AFTER jQuery -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<!-- Font Awesome -->
<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
<!-- Now Doing Some Cleaning Up -->
<!--
Fix Bootstrap's Grid Issues
Apply "flex" class to div with class = "row"
Example: <div class="row flex"><div class="col-lg-12"></div></div>
-->
<style type="text/css">
	.flex {
		display: flex;
		flex-wrap: wrap;
	}
</style>
<!-- Ensuring Proper Rendering & Touch Zooming on Mobile Devices -->
<meta name="viewport" content="width=device-width, initial-scale=1">
<!-- Improving Cross-Browser Rendering -->
<link rel="stylesheet" type="text/css" href="https://raw.githubusercontent.com/necolas/normalize.css/master/normalize.css">
<!-- Favicon  -->
<link rel="icon" type="image/x-icon" href="<?php echo Config::get('links/app_root') . '/assets/imgs/favicon.ico'?>" />