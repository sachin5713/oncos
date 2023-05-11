<?php
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
    wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css' );
}
add_action( 'wp_enqueue_scripts', 'child_enqueue__parent_scripts');

add_action( 'wp_footer', 'custom_scripts');
function custom_scripts() {
    wp_enqueue_script('lottie', 'https://cdnjs.cloudflare.com/ajax/libs/bodymovin/5.7.2/lottie.min.js');
    wp_enqueue_script('app', get_stylesheet_directory_uri().'/assets/js/app.js');
}
function enqueue_bootstrap() {
    // Register Bootstrap CSS
    wp_register_style('bootstrap-css', 'https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css', array(), '4.5.2', 'all');

    // Enqueue Bootstrap CSS
    wp_enqueue_style('bootstrap-css');

    // Register Bootstrap JS
    wp_register_script('bootstrap-js', 'https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js', array('jquery'), '4.5.2', true);

    // Enqueue Bootstrap JS
    wp_enqueue_script('bootstrap-js');
}
add_action('wp_enqueue_scripts', 'enqueue_bootstrap');

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
                    <ul class="share-icons">
                        <li>
                            <a href="https://www.facebook.com/sharer.php?u=<?= urlencode(get_permalink($post_id)); ?>">
                                <i class="fa fa-facebook"></i> Facebook
                            </a>
                        </li>
                        <li>
                            <a href="https://twitter.com/intent/tweet?url=<?= urlencode(get_permalink($post_id)); ?>&text=<?= urlencode(get_the_title($post_id)); ?>">
                                <i class="fa fa-twitter"></i> Twitter
                            </a>
                        </li>
                        <li>
                            <a href="https://www.linkedin.com/shareArticle?url=<?= urlencode(get_permalink($post_id)); ?>">
                                <i class="fa fa-linkedin"></i> LinkedIn
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
