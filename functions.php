<?php
/**
 * Author: Ole Fredrik Lie
 * URL: http://olefredrik.com
 *
 * FoundationPress functions and definitions
 *
 * Set up the theme and provides some helper functions, which are used in the
 * theme as custom template tags. Others are attached to action and filter
 * hooks in WordPress to change core functionality.
 *
 * @link https://codex.wordpress.org/Theme_Development
 * @package FoundationPress
 * @since FoundationPress 1.0.0
 */

/** Various clean up functions */
require_once( 'library/cleanup.php' );

/** Required for Foundation to work properly */
require_once( 'library/foundation.php' );

/** Custom Post Types */
require_once( 'library/custom-post-types.php' );

/** Format comments */
require_once( 'library/class-foundationpress-comments.php' );

/** Register all navigation menus */
require_once( 'library/navigation.php' );

/** Add menu walkers for top-bar and off-canvas */
require_once( 'library/class-foundationpress-top-bar-walker.php' );
require_once( 'library/class-foundationpress-mobile-walker.php' );

/** Create widget areas in sidebar and footer */
require_once( 'library/widget-areas.php' );

/** Return entry meta information for posts */
require_once( 'library/entry-meta.php' );

/** Enqueue scripts */
require_once( 'library/enqueue-scripts.php' );

/** Add theme support */
require_once( 'library/theme-support.php' );

/** Add Nav Options to Customer */
require_once( 'library/custom-nav.php' );

/** Change WP's sticky post class */
require_once( 'library/sticky-posts.php' );

/** Configure responsive image sizes */
require_once( 'library/responsive-images.php' );

/** Gutenberg editor support */
require_once( 'library/gutenberg.php' );


@ini_set( 'upload_max_size' , '64M' );
@ini_set( 'post_max_size', '64M');
@ini_set( 'max_execution_time', '300' );

/**
 * AJAX handler for the Concept Request Form.
 * This function securely processes the form data and sends an email.
 */
function handle_concept_request_ajax() {
    // 1. Security Check: Verify the nonce to prevent CSRF attacks.
    check_ajax_referer('concept_request_nonce', 'nonce');

    // 2. Sanitize and retrieve all form data.
    $name    = isset($_POST['name']) ? sanitize_text_field($_POST['name']) : 'Not provided';
    $email   = isset($_POST['email']) ? sanitize_email($_POST['email']) : '';
    $message = isset($_POST['message']) ? sanitize_textarea_field($_POST['message']) : 'No message.';

    // 3. Validate the sanitized data (especially the email).
    if (!is_email($email)) {
        wp_send_json_error('Invalid email address provided.');
    }

    // 4. Prepare the email.
    $to      = 'example@exa.com'; // ** YOUR DESTINATION EMAIL ADDRESS **
    $subject = 'New Concept Request from Synapse Guild';
    
    $body    = "You have received a new concept request from your website:\n\n";
    $body   .= "--------------------------------------------------\n\n";
    $body   .= "Name: " . $name . "\n";
    $body   .= "Email: " . $email . "\n\n";
    $body   .= "Challenge/Idea:\n" . $message . "\n\n";
    $body   .= "--------------------------------------------------\n";

    // Set headers
    $headers = array(
        'Content-Type: text/plain; charset=UTF-8',
        'From: ' . $name . ' <' . $email . '>',
        'Reply-To: ' . $name . ' <' . $email . '>'
    );

    // 5. Send the email using wp_mail()
    $sent = wp_mail($to, $subject, $body, $headers);

    // 6. Send back a JSON response to the JavaScript.
    if ($sent) {
        wp_send_json_success('Email sent successfully!');
    } else {
        wp_send_json_error('The server failed to send the email. ' . print_r(error_get_last(), true));
    }
    
    wp_die(); // Always include this at the end of AJAX functions
}
add_action('wp_ajax_concept_request', 'handle_concept_request_ajax');
add_action('wp_ajax_nopriv_concept_request', 'handle_concept_request_ajax');

error_log('Form submission received');
error_log('Name: ' . $name);
error_log('Email: ' . $email);
error_log('Message: ' . $message);

// Add this to your theme's functions.php to test if wp_mail is working
function test_wp_mail() {
    $to = 'watchoutfreedom@gmail.com';
    $subject = 'Test Email';
    $message = 'This is a test email.';
    
    $sent = wp_mail($to, $subject, $message);
    var_dump($sent);
    wp_die();
}
add_action('wp_ajax_test_wp_mail', 'test_wp_mail');
add_action('wp_ajax_nopriv_test_wp_mail', 'test_wp_mail');


// Enqueue your script and localize it
function enqueue_custom_scripts() {
    wp_enqueue_script('your-script-handle', get_template_directory_uri() . '/js/your-script.js', array('jquery'), null, true);
    
    wp_localize_script('your-script-handle', 'ajax_object', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce'    => wp_create_nonce('concept_request_nonce')
    ));
}
add_action('wp_enqueue_scripts', 'enqueue_custom_scripts');


/**
 * WordPress admin customisation
 */
function my_login_logo() { ?>
<?php }
// add_action( 'login_enqueue_scripts', 'my_login_logo' );

function add_single_body_class($classes) {
  if (is_single()) {
      $classes[] = 'single-template'; // Adds 'single-template' class for single post pages
  }
  return $classes;
}
add_filter('body_class', 'add_single_body_class');

function slider_template_assets() {
  // Enqueue Montserrat font
  wp_enqueue_style( 'montserrat-font', 'https://fonts.googleapis.com/css?family=Montserrat:400,700&display=swap', array(), null );

  // Enqueue custom style for the slider
  wp_enqueue_style( 'slider-template-style', get_template_directory_uri() . '/css/slider-template.css', array(), '1.0' );

  // Enqueue custom script for the slider
  wp_enqueue_script( 'slider-template-script', get_template_directory_uri() . '/js/slider-template.js', array('jquery'), '1.0', true );
}
add_action( 'wp_enqueue_scripts', 'slider_template_assets' );



// remove admin menu items we don't use / need

function remove_menu_items() {
  global $menu;
  $restricted = array(__('Links'), __('Comments'), __('Posts'),__('Tools'),__('Users'));
  end ($menu);
  while (prev($menu)){
    $value = explode(' ',$menu[key($menu)][0]);
    if(in_array($value[0] != NULL?$value[0]:"" , $restricted)){
      unset($menu[key($menu)]);}
    }
  }

//add_action('admin_menu', 'remove_menu_items');


//  remove admin item we don't use / need
function remove_submenus() {
  global $submenu;
  unset($submenu['index.php'][10]); // Removes 'Updates'.
  unset($submenu['themes.php'][5]); // Removes 'Themes'.
  unset($submenu['options-general.php'][10]); // Removes 'Writing'.
}

//add_action('admin_menu', 'remove_submenus');

// allow svg upload

function add_file_types_to_uploads($file_types){
	$new_filetypes = array();
	$new_filetypes['svg'] = 'image/svg+xml';
	$file_types = array_merge($file_types, $new_filetypes );
	return $file_types;
}

// add_filter('upload_mimes', 'add_file_types_to_uploads');

// disable parent menu link
function disable_parent_menu_link()
{
    wp_print_scripts('jquery');
?>

<?php
}


// add_action('wp_footer', 'disable_parent_menu_link');
?>