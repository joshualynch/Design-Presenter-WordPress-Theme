<!DOCTYPE html>

<html <?php language_attributes(); ?>>

<head>

	<meta charset="<?php bloginfo( 'charset' ); ?>" />

	<title><?php wp_title(); ?> : <?php bloginfo('name'); ?> : <?php bloginfo('description'); ?></title>

	<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />

		<!--[if IE]>

			<link rel="stylesheet" type="text/css" href="<?php echo home_url(); ?>/wp-content/themes/presenter/ieisspecial.css" />

		<![endif]-->

	<!-- Include fonts from Google -->
    <link href='http://fonts.googleapis.com/css?family=Droid+Sans' rel='stylesheet' type='text/css'>

	<link href='http://fonts.googleapis.com/css?family=Droid+Serif' rel='stylesheet' type='text/css'>

	<?php wp_head(); ?>	

	<!-- Include local script -->
	<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/slider.js"></script>
   
	<!-- Start loop here to get custom fields meta per page -->
	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

		<style type="text/css">
		
			<?php  $logo = get_option('header_logo');
			
					$width = get_option('logo_width');
					
					$height = get_option('logo_height');
			
					// Checks for logo, displays theme logo if needed

					if (empty($logo)) {

					echo '#header h1 a { background: url(images/logo.png) no-repeat; width: 180px; height: 48px; }';

					} else { 

					echo '#header h1 a { background: url( ';
					
					echo $logo['url'];
					
					echo ' ) no-repeat; width:';
					
					echo $width;
					
					echo '; height: ';
					
					echo $height;
					
					echo '; }';

			} ?>
			
			<?php  $body = get_post_meta($post->ID, 'pcf_background_color', true);
			
					// Checks for body solid color background option

					if (empty($body)) {

					echo '';

					} else { 

					echo 'body { background: ';
					
					echo $body;
					
					echo '; }';

			} ?>
						
			<?php  $slide = get_post_meta($post->ID, 'pcf_jquery_color', true);
			
					// Checks for jQuery button color option

					if (empty($slide)) {

					echo '';

					} else { 

					echo 'a#header-slidetoggle, a#return-blogpage { color: ';
					
					echo $slide;
					
					echo ' !important; }';

			} ?>
			
			<?php  $repeat = get_post_meta($post->ID, 'pcf_bg_repeat', true);
			
					$type = get_post_meta($post->ID, 'pcf_repeat_type', true);

					// Checks for repeating background image option
					
					if (empty($repeat)) {

					echo '';

					} else { 

					echo '#bg-repeat { background: url( ';
					
					echo $repeat;
					
					echo ' ) ';
					
					echo $type;
					
					echo '; }';

			} ?>
			
			<?php  $left = get_post_meta($post->ID, 'pcf_buttons_left', true);
			
			// Checks for button left or right
			
					if ( $left === 'left' )
			
					echo 'a#header-slidetoggle { left: 25px;} a#return-blogpage { left: 130px;}';
			
						else
			
					echo 'a#header-slidetoggle { right: 25px;} a#return-blogpage { right: 130px;}';
			
			?>

		</style>

	<?php endwhile; else: ?>

	<?php endif; ?>

</head>

<body>

	<div id="header">

		<h1><a href="<?php echo get_option('logo_link'); ?>"><?php echo get_option('logo_anchor'); ?></a></h1>
        
        <!-- Leave #header open. It will be closed in page templates.-->

