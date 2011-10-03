<?php

// Use jquery from Google
function my_init_method() {
    if (!is_admin()) {
        wp_deregister_script( 'jquery' );
        wp_register_script( 'jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js');
        wp_enqueue_script( 'jquery' );
    }
}    
 
add_action('init', 'my_init_method');

// Add custom thumbnail creation
add_theme_support( 'post-thumbnails' );
add_image_size( 'featured-thumbnail', 280, 210, true ); // Permalink thumbnail size

// Remove 'Protected' from password-protected posts & pages
add_filter('protected_title_format', 'blank');
function blank($title) {
       return '%s';
}

// Change password-protected posts & pages text
add_filter( 'the_password_form', 'custom_password_form' );
function custom_password_form() {
	global $post;
	$label = 'pwbox-'.( empty( $post->ID ) ? rand() : $post->ID );
	$o = '<form class="protected-post-form" action="' . get_option('siteurl') . '/wp-pass.php" method="post">
	' . __( "This page is password protected. Please enter your password below." ) . '
	<label for="' . $label . '">' . __( "" ) . ' </label><input name="post_password" id="' . $label . '" type="password" size="20" /><input type="submit" class="submit" name="Submit" value="' . esc_attr__( "Log In" ) . '" />
	</form>
	';
	return $o;
}

// Remove Admin menu items that are not necessary
function remove_menus () {
global $menu;
	$restricted = array(__('Posts'), __('Links'), __('Comments'));
	end ($menu);
	while (prev($menu)){
		$value = explode(' ',$menu[key($menu)][0]);
		if(in_array($value[0] != NULL?$value[0]:"" , $restricted)){unset($menu[key($menu)]);}
	}
}
add_action('admin_menu', 'remove_menus');

if ( function_exists( 'wp_generator' ) ) {
        remove_action( 'wp_head', 'wp_generator' );
}

// Remove irrelevant dashboard items
add_action('admin_init', 'rw_remove_dashboard_widgets');
function rw_remove_dashboard_widgets() {
 remove_meta_box('dashboard_right_now', 'dashboard', 'normal');   // right now
 remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal'); // recent comments
 remove_meta_box('dashboard_incoming_links', 'dashboard', 'normal');  // incoming links
 remove_meta_box('dashboard_plugins', 'dashboard', 'normal');   // plugins

 remove_meta_box('dashboard_quick_press', 'dashboard', 'normal');  // quick press
 remove_meta_box('dashboard_recent_drafts', 'dashboard', 'normal');  // recent drafts
 remove_meta_box('dashboard_primary', 'dashboard', 'normal');   // wordpress blog
 remove_meta_box('dashboard_secondary', 'dashboard', 'normal');   // other wordpress news
}

// Remove irrelevant quick dropdown items
function custom_favorite_actions($actions) {
  unset($actions['post-new.php']);
  unset($actions['edit-comments.php']);
  unset($actions['edit.php?post_status=draft']);
  return $actions;
}

add_filter('favorite_actions', 'custom_favorite_actions');

// Remove irrelevant boxes from page editor
function customize_meta_boxes() {
  /* Removes meta boxes from pages */
  remove_meta_box('postcustom','page','normal');
  remove_meta_box('trackbacksdiv','page','normal');
  remove_meta_box('commentstatusdiv','page','normal');
  remove_meta_box('commentsdiv','page','normal'); 
}

add_action('admin_init','customize_meta_boxes');

// Edit Admin footer
function modify_footer_admin () {
  echo '<a href="http://designpresenter.com/">Design Presenter</a> Version 1.4 created by <a href="http://joshualyn.ch">Joshua Lynch</a>. ';
  echo 'Powered by <a href="http://WordPress.org">WordPress</a>.';
}

add_filter('admin_footer_text', 'modify_footer_admin');

// Add styles to the WYSIWYG editor
add_editor_style('editor-style.css');

// Edit user profile fields
function extra_contact_info($contactmethods) {
    unset($contactmethods['aim']);
    unset($contactmethods['yim']);
    unset($contactmethods['jabber']);
    $contactmethods['user_title'] = 'Title <span class="description">(optionally used in theme)</span>';
    $contactmethods['user_phone'] = 'Phone # <span class="description">(optionally used in theme)</span>';
 
    return $contactmethods;
}
add_filter('user_contactmethods', 'extra_contact_info');

// Add special custom fields box to page editor

$prefix = 'pcf_';

$meta_box = array(
    'id' => 'page-custom-fields',
    'title' => 'Display Controls for Design View Pages',
    'page' => 'page',
    'context' => 'normal',
    'priority' => 'high',
    'fields' => array(
        array(
            'name' => 'Background Color',
            'desc' => 'For solid color backgrounds, enter the hexadecimal color of the background (including the #) above.',
            'id' => $prefix . 'background_color',
            'type' => 'text',
            'std' => ''
        ),
		array(
            'name' => 'Repeating Background Image',
            'desc' => 'For designs requiring a repeating background, enter the full link (including the http://) to where you have uplaoded the background image above.',
            'id' => $prefix . 'bg_repeat',
            'type' => 'text',
            'std' => ''
        ),
        array(
            'name' => 'Background Repeating Type',
            'id' => $prefix . 'repeat_type',
            'type' => 'select',
            'options' => array('repeat-none','repeat-x', 'repeat-y', 'repeat')
        ),
        array(
            'name' => 'Sub-Navigation Link Color',
            'desc' => 'By default, the "Design Description" and "Hide Navigation" link text is blue and may be hard to read against darker backgrounds. Enter a hexadecimal color value (including the #) above if the design background is dark (usually #fff works just fine). Below, adjust the positioning of these buttons to the right or the left to avoid overlapping something at the top of your design.',
            'id' => $prefix . 'jquery_color',
            'type' => 'text',
            'std' => ''
        ),
		array(
            'name' => 'Sub-Navigation Link Positioning',
            'id' => $prefix . 'buttons_left',
            'type' => 'select',
            'options' => array('right','left')
        )
    )
);

add_action('admin_menu', 'presenter_add_box');

// Add meta box
function presenter_add_box() {
    global $meta_box;
    
    add_meta_box($meta_box['id'], $meta_box['title'], 'presenter_show_box', $meta_box['page'], $meta_box['context'], $meta_box['priority']);
}

// Callback function to show fields in meta box
function presenter_show_box() {
    global $meta_box, $post;
    
    // Use nonce for verification
    echo '<input type="hidden" name="presenter_meta_box_nonce" value="', wp_create_nonce(basename(__FILE__)), '" />';
    
    echo '<table class="form-table">';

    foreach ($meta_box['fields'] as $field) {
        // get current post meta data
        $meta = get_post_meta($post->ID, $field['id'], true);
        
        echo '<tr>',
                '<th style="width:20%"><label for="', $field['id'], '">', $field['name'], '</label></th>',
                '<td>';
        switch ($field['type']) {
            case 'text':
                echo '<input type="text" name="', $field['id'], '" id="', $field['id'], '" value="', $meta ? $meta : $field['std'], '" size="30" style="width:97%" />', '<br />', $field['desc'];
                break;
            case 'textarea':
                echo '<textarea name="', $field['id'], '" id="', $field['id'], '" cols="60" rows="4" style="width:97%">', $meta ? $meta : $field['std'], '</textarea>', '<br />', $field['desc'];
                break;
            case 'select':
                echo '<select name="', $field['id'], '" id="', $field['id'], '">';
                foreach ($field['options'] as $option) {
                    echo '<option', $meta == $option ? ' selected="selected"' : '', '>', $option, '</option>';
                }
                echo '</select>';
                break;
            case 'radio':
                foreach ($field['options'] as $option) {
                    echo '<input type="radio" name="', $field['id'], '" value="', $option['value'], '"', $meta == $option['value'] ? ' checked="checked"' : '', ' />', $option['name'];
                }
                break;
            case 'checkbox':
                echo '<input type="checkbox" name="', $field['id'], '" id="', $field['id'], '"', $meta ? ' checked="checked"' : '', ' />';
                break;
        }
        echo     '<td>',
            '</tr>';
    }
    
    echo '</table>';
}

add_action('save_post', 'presenter_save_data');

// Save data from meta box
function presenter_save_data($post_id) {
    global $meta_box;
    
    // verify nonce
    if (!wp_verify_nonce($_POST['presenter_meta_box_nonce'], basename(__FILE__))) {
        return $post_id;
    }

    // check autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return $post_id;
    }

    // check permissions
    if ('page' == $_POST['post_type']) {
        if (!current_user_can('edit_page', $post_id)) {
            return $post_id;
        }
    } elseif (!current_user_can('edit_post', $post_id)) {
        return $post_id;
    }
    
    foreach ($meta_box['fields'] as $field) {
        $old = get_post_meta($post_id, $field['id'], true);
        $new = $_POST[$field['id']];
        
        if ($new && $new != $old) {
            update_post_meta($post_id, $field['id'], $new);
        } elseif ('' == $new && $old) {
            delete_post_meta($post_id, $field['id'], $old);
        }
    }
}

// Add Theme Options Page
include 'adminpage.class.php';

$options = new SubPage(theme, 'Theme Options');

$options->addTitle('Branding');

$options->addSubtitle('Logo');

$options->addUpload(array(
		'id' => 'header_logo',
		'label' => 'Upload your logo',
		'desc' => 'By default, this uploaded file will display in the header on the left-hand side of your pages.'
	));
	
$options->addInput(array(
		'id' => 'logo_height',
		'label' => 'Logo Height',
		'desc' => 'Enter the pixel value (including the px) of your logo height. Keep in mind that the existing header is 68px tall with a 10px top padding.',
		'standard' => '48px',
	));
	
$options->addInput(array(
		'id' => 'logo_width',
		'label' => 'Logo Width',
		'desc' => 'Enter the pixel value (including the px) of your logo width. Recommended not to exceed 180px.',
		'standard' => '180px',
	));	
	
$options->addInput(array(
		'id' => 'logo_link',
		'label' => 'Logo Link',
		'desc' => 'Enter the link, including http://, where you would like your logo to point.',
		'standard' => 'http://example.com',
	));	
	
$options->addInput(array(
		'id' => 'logo_anchor',
		'label' => 'Logo Anchor Text',
		'desc' => 'Enter the logo link anchor text. This does not display visually but will show in the site source.',
		'standard' => 'Company Name',
	));				
	
$options->addSubtitle('Highlighted Yellow Text for Client Page Template');
	
$options->addTextarea(array(
		'id' => 'client_alert',
		'label' => 'Disclaimer (Optional)',
		'desc' => 'Use this textarea to specify the text that visitors will see on the client page template warning them that the design they are viewing is just an image, not a functioning webpage.',
		'standard' => 'Note: While we have simulated how your design will look in a browser, these design copies are just images, not actual web pages. Items on these pages are not clickable or interactive. You may, however, download the design image by right-clicking on it and selecting "Save Image as" or "Save Target as."',
	));	
	
$options->addSubtitle('Help Page Details');

$options->addInput(array(
		'id' => 'help_url',
		'label' => 'Help Page URL (Optional)',
		'desc' => 'It it is recommended that you create a help page using the default plain page template. Enter the full URL, including the http://, for that page here.',
		'standard' => 'http://example.com/help',
	));	
	
$options->addSubtitle('Footer Code');	

$options->addTextarea(array(
		'id' => 'footer_code',
		'label' => 'Insert Scripts Here (Optional)',
		'desc' => 'Use this textarea to drop scripts into the footer like analytics or a feedback button.',
		'standard' => '',
	));	

// Add Theme Instructions Page

$options = new SubPage(theme, 'Theme Instructions');

$options->addTitle('Initial Theme Setup');

$options->addSubtitle('WordPress Settings');

$options->addParagraph('The following WordPress settings will be used by the theme in pages:');

$options->addParagraph('On the <em>General Settings</em> page, the <em>Site Title</em> field will be used next to the logo in the header. The <em>Tagline</em> field is also used in the theme in the title tags. On the <em>Privacy</em> page, it is recommended to keep the default WordPress setting to "I would like to block search engines, but allow normal visitors," especially if you are password protecting pages. Finally, on the <em>Permalink</em> page, leave the default setting at <em>/%year%/%monthnum%/%postname%/</em> so that your page permalinks are user friendly.');

$options->addSubtitle('User Settings');

$options->addParagraph('Users who will be creating pages should have at least <em>Editor</em> permissions. After creating the accounts, users may fill in the <em>Title</em> and <em>Phone Number</em> fields of user profiles, which are used in the theme.');

$options->addSubtitle('Theme Options');

$options->addParagraph('Do not forget to fill out the Theme Options page to brand the theme to your liking. Detailed instructions are beneath each field on that page.');

$options->addTitle('Posting a Design');	

$options->addSubtitle('1. Create a Client Page');

$options->addParagraph('When presenting a design to a client, add a new page and title it (reommended that you include the client name in the title and customize the URL, so they feel special!). Under <em>Page Attributes</em>, select "Client Page" as the <em>Template</em>. Then fill the content area with information about your design. Optionally password protect the page in your <em>Publish</em> box by setting <em>Visibility</em> to <em>Password Protected</em> and typing in a password.');

$options->addSubtitle('2. Create Design Pages');

$options->addParagraph('Title a new page with a short descriptive title like "Homepage." Optionally password protect the page in your <em>Publish</em> box by setting <em>Visibility</em> to <em>Password Protected</em> and typing in a password the same as the other pages you make for this client. In the <em>Page Attributes</em> box, select the appropriate Client Page as the <em>Parent</em> from the dropdown menu. Set the page template to <em>Design View Pages with Buttons</em> or <em>Design View Pages with Dropdown</em> (especially handy for when you have lots of design pages that need to be tucked away nicely). Optionally place a numeral (starting with 1) into the <em>Order</em> box to set the order of design pages in the navigation.');

$options->addParagraph('Using the WordPress image uploader, upload a design draft image. Recommended settings: Save your design drafts as PNG-24s. Make sure that only the necessary width is uploaded. If you have a 960px wide design with a solid color background, upload a 980px image (so that some background shows on both sides) and use Design Presenter settings to set the backgound color (or the repeating background). Uploading a 1200px wide image for a 960px wide design is unnecessary and will present users with 1024 resolution with a horizontal scroll bar. When you upload and insert an image, make sure you select <em>Full Size</em> and choose the correct image alighment. If your design is centered, choose <em>Center</em> under <em>Alignment</em>. If it is left-aligned, choose <em>Left</em>. There is no need to fill out any more fields. Just click the <em>Insert into Post</em> button.');

$options->addParagraph('Underneath the main content editor, the section labeled <em>Display Controls for Design View Pages</em> will allow you to enter in the <em>Sub-Navigation Link Color</em> to make the "Design Description" and "Hide Navigation" buttons stand out against darker color design backgrounds, if necessary. You may also adjust the positioning of those buttons to the right (default) or left to avoid overlapping anything important at the top of your design.');

$options->addParagraph('If the design has a solid colored body background, fill in that color in hexadecimal (including the #) in the <em>Background Color</em> field. If it has a repeating image background of some kind, upload the background image using the WordPress image uploader. Then paste the full URL to the image location in the <em>Repeating Background Image</em> field. Finally, select the repeat type from the <em?Background Repeating Type</em> dropdown menu.');

$options->addParagraph('Selecting the author from the <em>Author</em> dropdown will determine whose contact information will display on the page.');

$options->addSubtitle('3. Send Design to Clients');

$options->addParagraph('Once you have published all of your pages, you should have a client page URL (and possibly password) to send to your client. The client page will automatically list the subpages (your design drafts). On the design pages, all of the design drafts, including the one being viewed, will be displayed in the navigation.');

$options->addSubtitle('Need Help?');

$options->addParagraph('Try me at support@designpresenter.com, and I may be able to help.');	
?>