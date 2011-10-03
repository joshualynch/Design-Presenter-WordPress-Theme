<?php get_header(); ?>

	<h2><?php bloginfo('name'); ?></h2>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

</div>
<!--end #header-->
 
	<div id="content">
 
 		<?php the_title('<h2>', '</h2>'); ?>

  			<?php the_content(__('Read On...'));?>
            
        <?php edit_post_link('Edit This Page', '<h6>', '</h6>'); ?>     
  
  	</div>
  	<!--end #content-->
  
	<div class="clr"></div>
  
<?php endwhile; else: ?>
 	
    <h4>Nothing found. Please contact your project manager.</h4>

<?php endif; ?>

<?php get_footer(); ?>