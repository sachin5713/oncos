<?php
// error_reporting(0);
if (!function_exists('header_menu')) {
    function header_menu(){

        $html = '';
        $html .= '<div class="navbar_menu">';
        $html .= '<button id="navbar-toggler" class="navbar-toggler" type="button"></button>';
            wp_nav_menu( [
              'theme_location' => 'menu-1',
              'container' => 'nav',
              'container_class' => 'main_nav',
              'fallback_cb' => false,
              'echo' => true,
            ] );
        $html .= "</div>";
        return $html;
    }
    add_shortcode('header_menu', 'header_menu');
}

/* Function to enqueue stylesheet from parent theme */
function child_enqueue__parent_scripts() {
    wp_enqueue_style('parent', get_template_directory_uri().'/style.css' );
    wp_enqueue_style('design', get_stylesheet_directory_uri().'/assets/css/style.css' );
    wp_enqueue_style('responsive', get_stylesheet_directory_uri().'/assets/css/responsive.css' );
    // wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css' );
    wp_enqueue_style('font-awesome', get_stylesheet_directory_uri().'/assets/css/font-awesome.css' );
}
add_action( 'wp_enqueue_scripts', 'child_enqueue__parent_scripts');

add_action( 'wp_footer', 'custom_scripts');
function custom_scripts() {
    // wp_enqueue_script('lottie', 'https://cdnjs.cloudflare.com/ajax/libs/bodymovin/5.7.2/lottie.min.js');
    wp_enqueue_script('app-js', get_stylesheet_directory_uri().'/assets/js/app.js');
}
function enqueue_bootstrap() {
    // Register Bootstrap CSS
    // wp_register_style('bootstrap-css', 'https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css', array(), '4.5.2', 'all');
    
    wp_register_style('bootstrap-css', get_stylesheet_directory_uri().'/assets/css/bootstrap.css', array(), '4.5.2', 'all');

    // Enqueue Bootstrap CSS
    wp_enqueue_style('bootstrap-css');

    // Register Bootstrap JS
    wp_register_script('bootstrap-js', 'https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js', array('jquery'), '4.5.2', true);

    // Enqueue Bootstrap JS
    wp_enqueue_script('bootstrap-js');
}
add_action('wp_enqueue_scripts', 'enqueue_bootstrap',1);

function generate_share_popup($post_id, $count) {
    ?>
    <div class="modal fade" id="shareModal_<?=$count?>" tabindex="-1" role="dialog" aria-labelledby="shareModalLabel_<?=$count?>" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="shareModalLabel_<?=$count?>">Share: <?php echo get_the_title($post_id); ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h4>Share this link via</h4>
                    <ul class="share-icons">
                        <li>
                            <a href="https://www.facebook.com/sharer.php?u=<?= urlencode(get_permalink($post_id)); ?>">
                                <i class="fa fa-facebook"></i>
                            </a>
                        </li>
                        <li>
                            <a href="https://twitter.com/intent/tweet?url=<?= urlencode(get_permalink($post_id)); ?>&text=<?= urlencode(get_the_title($post_id)); ?>">
                                <i class="fa fa-twitter"></i>
                            </a>
                        </li>
                        <li>
                            <a href="https://www.linkedin.com/shareArticle?url=<?= urlencode(get_permalink($post_id)); ?>">
                                <i class="fa fa-linkedin"></i>
                            </a>
                        </li>
                        <!-- Add more social media platforms here -->
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <?php
}
function my_enqueue() {
    wp_enqueue_script( 'ajax-script', get_stylesheet_directory_uri() . '/js/app.js', array('jquery') );
    wp_localize_script( 'ajax-script', 'ajax', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
}
add_action( 'wp_enqueue_scripts', 'my_enqueue' );

/*To change Login Screen*/
function custom_pmpro_not_logged_in_text_filter( $content ) {
    ob_start(); ?>
    <section class="oncos_authantication">
        <div class="container login_main">
            <form id="login_master">
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="email" class="form-control" id="username" placeholder="Enter email" name="username">
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" class="form-control" id="password" placeholder="Enter password" name="password">
                </div>
                <button type="submit" data-id="<?php echo get_the_ID(); ?>" class="btn btn-default btn_login">Submit</button>
                <a href="<?php echo get_site_url(); ?>/login/?action=reset_pass">Forgot Password</a>
            </form>
        </div>
    </section>
    <?php
    return ob_get_clean();
}
add_action( 'pmpro_not_logged_in_text_filter', 'custom_pmpro_not_logged_in_text_filter' );
add_shortcode( 'custom_login', 'custom_pmpro_not_logged_in_text_filter' );

function change_forgot_password_lable( $nav_links, $pmpro_form ) {
    $nav_links['lost_password'] = '<a href="'.get_site_url().'/login/?action=reset_pass">Forgot Password?</a>';
    return $nav_links;
}
add_filter( 'pmpro_login_forms_handler_nav', 'change_forgot_password_lable', 10, 2 );

function custom_login_redirect( $redirect_to, $request = NULL, $user = NULL ) {
    $redirect_to = get_site_url();
    return apply_filters( 'pmpro_login_redirect_url', $redirect_to, $request, $user );
}
add_filter( 'login_redirect','custom_login_redirect', 10, 3 );


// Add custom button
function add_custom_checkout_button()
{
    global $pmpro_requirebilling;

    if ($pmpro_requirebilling) {
        // Custom button HTML for when billing is required
        ?>
        <button type="submit" id="custom_btn-submit" class="<?php echo pmpro_get_element_class('pmpro_btn pmpro_btn-submit-checkout', 'pmpro_btn-submit-checkout'); ?>">
            <?php esc_html_e('Submit', 'paid-memberships-pro'); ?>
        </button>
        <?php
    } else {
        // Custom button HTML for when billing is not required
        ?>
        <button type="submit" id="custom_btn-submit" class="<?php echo pmpro_get_element_class('pmpro_btn pmpro_btn-submit-checkout', 'pmpro_btn-submit-checkout'); ?>">
            <?php esc_html_e('Submit', 'paid-memberships-pro'); ?>
        </button>
        <?php
    }
}
add_action('pmpro_checkout_boxes', 'add_custom_checkout_button');

function change_login_reg_link() {
    if (is_user_logged_in() && current_user_can('contributor')) { ?>
        <script>
            $account_url = '<?=get_site_url().'/account/'?>';
            $logout_url  = '<?=wp_logout_url()?>';
            jQuery('.footer_login_link a').text('Logout').attr('href',$logout_url);
            jQuery('.main_header_login a').text('Logout').attr('href',$logout_url);
            jQuery('.footer_registration_link a').text('Account').attr('href',$account_url);
            jQuery('.main_header_register a').text('Account').attr('href',$account_url);
        </script>
        <?php
    }
}
add_action('wp_footer', 'change_login_reg_link', 1);


function redirect_after_logout() {
    wp_redirect(home_url());
    exit;
}
add_action('wp_logout', 'redirect_after_logout');
function custom_change_username_email_label($translated_text, $text, $domain){
    if ($text === 'Username or Email Address') {
        $translated_text = 'Username*';
    }
     if ($text === 'Password') {
        $translated_text = 'Password*';
    }
    return $translated_text;
}
add_filter('gettext', 'custom_change_username_email_label', 10, 3);

function remove_comment_form_text($defaults) {
    $defaults['logged_in_as'] = '';
    $defaults['must_log_in'] = '';
    $defaults['comment_notes_before'] = '';

    return $defaults;
}
add_filter('comment_form_defaults', 'remove_comment_form_text');

function change_comment_form_label($defaults) {
    $defaults['title_reply'] = 'Add your feedback';
    return $defaults;
}
add_filter('comment_form_defaults', 'change_comment_form_label');


// Add approve/reject buttons in the user listing
function add_user_approval_column($columns) {
    $columns['approval_status'] = 'Approval Status';
    return $columns;
}
add_filter('manage_users_columns', 'add_user_approval_column');

// Add a custom user registration action hook
function custom_user_registration_action($user_id) {
    update_user_meta($user_id, 'approval_status', 'pending');
}
add_action('user_register', 'custom_user_registration_action');

// Handle user approval/rejection actions
function handle_user_approval_actions() {
    if (isset($_GET['action']) && isset($_GET['user_id'])) {
        $action = $_GET['action'];
        $user_id = $_GET['user_id'];

        switch ($action) {
            case 'approve_user':
                update_user_meta($user_id, 'approval_status', 'approved');
                // Update membership level using Paid Memberships Pro function
                pmpro_changeMembershipLevel(1, $user_id); // Replace 1 with the desired membership level ID
                wp_mail(get_userdata($user_id)->user_email, 'User Approval', 'Your account has been approved.');
                break;
            case 'reject_user':
                update_user_meta($user_id, 'approval_status', 'rejected');
                wp_mail(get_userdata($user_id)->user_email, 'User Rejection', 'Your account has been rejected.');
                break;
        }
    }
}
add_action('admin_init', 'handle_user_approval_actions');

// Display approval status column in the user listing
function display_user_approval_column_content($value, $column_name, $user_id) {
    if ('approval_status' === $column_name && in_array('contributor', get_userdata($user_id)->roles)) {
        $status = get_user_meta($user_id, 'approval_status', true);
        if ($status === 'approved') {
            $value = 'Approved';
        } elseif ($status === 'rejected') {
            $value = 'Rejected';
        } else {
            $approve_url = wp_nonce_url(add_query_arg(array('action' => 'approve_user', 'user_id' => $user_id), admin_url('users.php')), 'approve_user_' . $user_id);
            $reject_url = wp_nonce_url(add_query_arg(array('action' => 'reject_user', 'user_id' => $user_id), admin_url('users.php')), 'reject_user_' . $user_id);
            $value = '<a href="' . $approve_url . '">Approve</a> | <a href="' . $reject_url . '">Reject</a>';
        }
    }
    return $value;
}
add_filter('manage_users_custom_column', 'display_user_approval_column_content', 10, 3);

function restrict_custom_post_type_with_pmp() {
    // Check if Paid Memberships Pro is active
    if ( ! function_exists( 'pmpro_getMembershipLevelForUser' ) ) {
        return;
    }
    global $post;
    if ( $post && $post->post_type === 'research' ) {
        // Get the membership level for the current user
        $membership_level = pmpro_getMembershipLevelForUser();
        $allowed_levels = array( 'Yearly Membership' ); 
        if ( empty( $membership_level )) {
            wp_redirect( home_url() );
            exit;
        }
    }
}
add_action( 'template_redirect', 'restrict_custom_post_type_with_pmp' );

if (!function_exists('check_user_approved_or_not')) {
    function check_user_approved_or_not() {
        $user = wp_get_current_user();
        $user_id = $user->ID;
        $status = get_user_meta($user_id, 'approval_status', true);
        $is_approved = !empty($status) ? $status : 'pending';
        
        if(in_array('contributor', (array) $user->roles) && $is_approved !== 'approved' && get_the_ID() != 233 && !is_front_page()){
            wp_clear_auth_cookie();
            wp_redirect(home_url('/'));
            exit();
        } elseif(in_array('contributor', (array) $user->roles) && is_front_page() && $is_approved !== 'approved'){
            wp_clear_auth_cookie();
        }
    }
    add_action('wp', 'check_user_approved_or_not');
}

function require_comment_approval($approved, $commentdata) {
    $approved = 0;
    return $approved;
}
add_filter('pre_comment_approved', 'require_comment_approval', 10, 2);