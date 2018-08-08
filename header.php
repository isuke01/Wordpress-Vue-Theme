<!DOCTYPE html>
<html>
  <head>
  <title><?php wp_title(); ?></title>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<meta name="application-name" content="<?php echo bloginfo( 'name' ); ?>"/>
	<meta name="apple-mobile-web-app-title" content="<?php echo bloginfo( 'name' ); ?>">
	
	<link rel="manifest" href="<?php echo get_template_directory_uri() ?>/manifest.json">
	<meta name="theme-color" content="#044269">
	<!-- Windows Phone -->
	<meta name="msapplication-navbutton-color" content="#044269">
	<!-- Windows -->
	<meta name="msapplication-TileColor" content="#044269"/>
	<!-- <meta name="msapplication-square150x150logo" content="square.png"/> -->

	<!-- iOS Safari -->
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="#044269">
	
  	<script> 
		var $buoop = {notify:{i:-3,f:-4,o:-4,s:-2,c:-4},insecure:true,unsupported:true,style:"corner",api:5}; 
		function $buo_f(){ 
		var e = document.createElement("script"); 
			e.src = "//browser-update.org/update.min.js?v=1.0"; 
			document.body.appendChild(e);
		};
		try { document.addEventListener("DOMContentLoaded", $buo_f,false) }
		catch(e){window.attachEvent("onload", $buo_f)}
	</script>

	<?php wp_head(); ?>
  </head>
  <body>
 
<?php

wp_head(); ?>