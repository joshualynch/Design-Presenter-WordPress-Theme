<?php
/*
Template Name: Client
*/
?>

<?php get_header(); ?>

	<h2><?php bloginfo('name'); ?></h2>
    
    	<div id="nav-container">

		<div id="jnav">
        
        	<h3 class="jnav-head">Design Views &darr;</h3>
        	
				<ul class="jnav">
  	
				<?php wp_list_pages('title_li=&child_of='.$post->ID.'sort_column=menu_order'); ?>
  
  				</ul>
    
        </div>
        <!--end #jnav-->

		</div>
    	<!--end #nav-container-->    


</div>
<!--end #header-->

<div id="container"> 
 
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
 
	<div id="content">
 
 			<?php the_title('<h2>', '</h2>'); ?>

  				<?php the_content(__('Read On...'));?>
                
                <?php edit_post_link('Edit This Page', '<h6>', '</h6>'); ?>	
                
                	
                    <?php $alert = get_option('client_alert');

					if (empty($alert)) {

					echo '';

					} else { 

					echo '<div class="alert">';
					
					echo stripslashes (get_option('client_alert'));
					
					echo '</div>';

					} ?>
  
  						<h4>Design Views:</h4>
  
							<?php
                            $args = array(
                                'orderby' => 'menu_order',
                                'order' => 'ASC',  
                                'post_parent' => $post->ID,
								'numberposts' => '0',
                                'post_type' => 'page',
                                'post_status' => 'publish'
                            ); $postslist = get_posts($args); foreach ($postslist as $post) : setup_postdata($post); ?>
                            
                                <a class="button" href="<?php the_permalink(); ?>">
                            
                                    <?php the_title(); ?>
                                
                                </a>
                            
                            <?php endforeach; ?>
  
	</div>
	<!--end #content-->
  
  	<div class="clr"></div>  
  
  	<div id="author-meta">

		<p>
            
            		Reach 
				
					<?php $title = get_the_author_meta('user_title');

					if (empty($title)) {

					echo '';

					} else { 

					echo 'your ';
					
					echo $title;
					
					echo ', ';

					} ?>

					<strong><?php the_author(); ?></strong><?php $title = get_the_author_meta('user_title');

					if (empty($title)) {

					echo '';

					} else { 

					echo ', ';

					} ?>
                    
                    at <?php the_author_meta('user_email'); ?><?php $phone = get_the_author_meta('user_phone'); if (empty($phone)) {echo '';} else {echo ' or ';echo $phone;} ?>. 
            
            <br/>
            
            <br/>
            
            	<?php $help = get_option('help_url');

					if (empty($help)) {

					echo '';

					} else { 

					echo '<a href="';
					
					echo $help;
					
					echo '">Get Help with This Site</a>';

					} ?>
            
        </p>

	</div> <!--end #author-meta-->

	<div class="clr"></div>
    
</div>
<!--end #container-->    
  
<?php endwhile; else: ?>
 
 	<h4>Nothing found. Please contact your project manager.</h4>
 
<?php endif; ?>

<?php get_footer(); ?>