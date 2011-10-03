<?php
/*
Template Name: Design View Pages w/ Dropdown
*/
?>

<?php get_header(); ?>

	<h2><?php bloginfo('name'); ?></h2>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

	<div id="nav-container">

		<div id="jnav">
        
        	<h3 class="jnav-head">Design Views &darr;</h3>
        	
    
			 <?php
              if($post->post_parent)
			  
              $children = wp_list_pages("title_li=&child_of=".$post->post_parent."&echo=0");
              
			  else
              
			  $children = wp_list_pages("title_li=&child_of=".$post->ID."&echo=0");
              
			  if ($children) { ?>
              
              	<ul class="jnav">
              
			  			<?php echo $children; ?>
                        
                        <?php if($post->post_parent) { $parent_link = get_permalink($post->post_parent); ?>

							<li><a href="<?php echo $parent_link; ?>">Design Description</a></li>
	
						<?php } ?>
              
              	</ul>
              
			  <?php } ?>
    
        </div>
        <!--end #jnav-->

	</div>
    <!--end #nav-container-->    

</div>
 <!--end #header-->

	<a id="header-slidetoggle" href="#">Hide Navigation</a>
  
<div class="clr"></div>  
  
	<div id="bg-repeat">  
    
		<div id="container">  

  			<?php the_content(__('Read On...'));?>
  
		</div>
        <!--end #container-->

<div class="clr"></div>    
  
  		<div id="author-meta">

			<p>
            
            	<a href="#header" class="scroll">&uarr;&nbsp;</a>Your design ends here. Reach 
				
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
                    
            	<?php edit_post_link('Edit This Page', ' | ', ''); ?>  
                
            </p>    

		</div>
        <!--end #author-meta--> 

	</div> <!--end #bg-repeat-->

<div class="clr"></div>
  
<?php endwhile; else: ?>
 
 	<h4>Nothing found. Please contact your project manager.</h4>
 
<?php endif; ?>

<?php get_footer(); ?>