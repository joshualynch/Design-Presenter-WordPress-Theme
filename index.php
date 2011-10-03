<?php get_header(); ?>

	<h2><?php bloginfo('name'); ?></h2>

</div>
 <!--end #header-->


<div id="content">

	<h2>This Page Can Not Be Found</h2>
 
 			<h4>We're sorry, but that page doesn't exist or has been moved. Contact your project manager for assistance<?php $help = get_option('help_url');

					if (empty($help)) {

					echo '.';

					} else { 

					echo ' or view the <a href="';
					
					echo $help;
					
					echo '">Help Page</a>.';

					} ?>
            </h4>
            
</div> <!-- end #content -->
<div class="clr"></div>

<?php get_footer(); ?>