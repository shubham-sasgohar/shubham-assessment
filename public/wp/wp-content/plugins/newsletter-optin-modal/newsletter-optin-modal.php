<?php
/*
Plugin Name: Newsletter Opt-In Modal
Plugin URI: https://github.com/prunderground-dev/wordpress-frontend-interview-starter
Description: Adds a newsletter opt-in modal to your WordPress site.
Version: 1.0
Author: Shubham
Author URI: https://github.com/prunderground-dev/wordpress-frontend-interview-starter
*/

// Create custom database table for newsletter signups
function create_newsletter_signup_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'newsletter_signups';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
      id mediumint(9) NOT NULL AUTO_INCREMENT,
      name varchar(100) NOT NULL,
      email varchar(100) NOT NULL,
      PRIMARY KEY  (id)
    ) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );
}
register_activation_hook( __FILE__, 'create_newsletter_signup_table' );

// Add newsletter opt-in modal to site
function newsletter_optin_modal() {
    $modal_html = '  
        <div id="newsletter-optin-modal" class="modal newsletter-optin-modal">
          <div class="modal-content">
            <h2>PRUNDERGROUND NEWSLETTER </h2>
            <form id="newsletter-optin-form" method="post" class="newsletter-form">
              <input type="text" name="name" placeholder="Your Name"  id="newsletter-optin-name"  required>
              <input type="email" name="email" placeholder="Email Address" id="newsletter-optin-email" required>
              <input type="submit" value="Signup">
            </form>
          </div>
        </div>  
    ';

    echo $modal_html;
}
add_action( 'wp_footer', 'newsletter_optin_modal' );

// Enqueue stylesheet and javascript files
function newsletter_optin_modal_scripts() {
    wp_enqueue_style( 'modal-style', plugins_url( 'assets/style.css', __FILE__ ) );
    wp_enqueue_script( 'jquery' ); // Load jQuery library
    wp_enqueue_script( 'newsletter-optin-form', plugins_url( 'assets/script.js', __FILE__ ), array('jquery'), '', true );
    wp_localize_script( 'newsletter-optin-form', 'newsletter_optin_form_submit', array(
        'ajax_url' => admin_url( 'admin-ajax.php' )
    ) );

}
add_action( 'wp_enqueue_scripts', 'newsletter_optin_modal_scripts' );

// Add form submission handler
function newsletter_optin_form_submit() {
    global $wpdb;

    if ( isset( $_POST['name'] ) && isset( $_POST['email'] ) ) {
        $name = sanitize_text_field( $_POST['name'] );
        $email = sanitize_email( $_POST['email'] );

        $table_name = $wpdb->prefix . 'newsletter_signups';

        $wpdb->insert(
            $table_name,
            array(
                'name' => $name,
                'email' => $email
            ),
            array(
                '%s',
                '%s'
            )
        );
    }
}
add_action( 'wp_ajax_newsletter_optin_form_submit', 'newsletter_optin_form_submit' );
add_action( 'wp_ajax_nopriv_newsletter_optin_form_submit', 'newsletter_optin_form_submit' );


// Add shortcode for newsletter opt-in modal
function newsletter_optin_modal_shortcode() {
    ob_start();
    newsletter_optin_modal();
    return ob_get_clean();
}
add_shortcode( 'newsletter_optin_modal', 'newsletter_optin_modal_shortcode' );
