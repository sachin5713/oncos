<?php
require( get_stylesheet_directory().'/front-end/hooks.php' );
require( get_stylesheet_directory().'/front-end/research-cpt.php' );
require( get_stylesheet_directory().'/front-end/research-shortcode.php' );
require( get_stylesheet_directory().'/front-end/post-research-shortcode.php' );
require( get_stylesheet_directory().'/front-end/ajax-hooks.php' );

function prevent_contributor_admin_access() {
    if (current_user_can('contributor') && !wp_doing_ajax()) {
        wp_redirect(home_url());
        exit;
    }
}
add_action('admin_init', 'prevent_contributor_admin_access');

function register_admin_scripts() {
    wp_register_script('admin-script', get_stylesheet_directory_uri() . '/assets/js/admin-script.js', array('jquery'), '1.0', true);
    wp_enqueue_script('admin-script');
}
add_action('admin_enqueue_scripts', 'register_admin_scripts');

// Manually approve comments for a custom post type
function manually_approve_comments_for_custom_post_type( $commentdata ) {
    // Specify the slug of your custom post type
    $custom_post_type = 'research';

    // Check if the comment is being submitted for your custom post type
    if ( $commentdata['comment_post_type'] === $custom_post_type ) {
        // Set the comment approval status to 0 (pending)
        $commentdata['comment_approved'] = 0;
    }

    return $commentdata;
}
add_filter( 'wp_insert_comment_data', 'manually_approve_comments_for_custom_post_type' );


