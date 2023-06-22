<?php
// error_reporting(0);
/* Loadmore Functionality */
if (!function_exists('load_more_research')) {
    function load_more_research()
    {
        $page = $_POST['page'];
        $posts_per_page = 6;
        $user_id   = get_current_user_id();
        $is_like   = get_user_meta($user_id, 'research_like', true);
        $args = [
            'posts_per_page' => $posts_per_page,
            'post_type'      => 'research',
            'post_status'    => 'publish',
            'orderby'        => 'name',
            'order'          => 'DESC',
            'paged'          => $page,
        ];

        $research_posts = new WP_Query($args);

        $i = $posts_per_page;
        if ($research_posts->have_posts()) {
            while ($research_posts->have_posts()) {
                $research_posts->the_post();
                $i++;
                $thumbnail_id = get_post_thumbnail_id(get_the_ID());
                $url        = get_permalink();
                ?>
                <div class="research-item">
                    <div class="research_item_card">
                        <div class="research_item_card_top">
                            <div class="research-avatar">
                                <?php echo get_avatar(get_the_author_meta('ID'), 60); ?>
                            </div>
                            <div class="card_title">
                                <h3><a href="<?=$url?>"><?php the_title(); ?></a></h3>
								<span style="font-size:12px"><?php echo date('d/M/Y',strtotime($post->post_date))?></span>
                            </div>
                            <div class="dots">
                                <a href="javascript:"><i class="fa fa-ellipsis-h"></i></a>
                            </div>
                        </div>
                        <div class="research-content">
                            <?php //the_content(); ?>
                            <?php echo wp_trim_words( get_the_excerpt(), 15, '...'); ?>
                        </div>
                        <?php if (get_the_post_thumbnail_url(get_the_ID())) : ?>
<!--                         <div class="research_content_img">
                            <img src="<?= get_the_post_thumbnail_url(get_the_ID()) ?>" height="200" width="200" />
                        </div> -->
                        <?php endif; ?>
                        <hr />
                        <?php
                            $fa_class   = (!empty($is_like) && in_array(get_the_ID(), $is_like)) ? 'fa-thumbs-up' : 'fa-thumbs-o-up';
                            $like_class = (!empty($is_like) && in_array(get_the_ID(), $is_like)) ? 'liked' : '';
                        ?>
                        <div class="research-interactions <?=$like_class?>" data-id="<?= get_the_ID() ?>">
                            <a href="javascript:" id="like" class="like-button"><i class="fa <?=$fa_class?>"></i> Like</a>
                            <a href="<?=$url?>" id="comment" class="comment-button"><i class="fa fa-comment"></i> Comment</a>
                            <a class="share" id="share" href="#" data-toggle="modal" data-target="#shareModal_<?=$i?>"><i class="fa fa-share"></i> Share</a>
                        </div>
                    </div>
                </div>
                <?php
                generate_share_popup(get_the_ID(), $i);
            }
        }
        wp_reset_postdata();

        die();
    }

    add_action('wp_ajax_load_more_research', 'load_more_research');
    add_action('wp_ajax_nopriv_load_more_research', 'load_more_research');
}


/* Like Functionality */
if (!function_exists('research_like_functionality')) {
    function research_like_functionality()
    {
        $user_id = get_current_user_id();
        $is_like = get_user_meta($user_id, 'research_like', true);
        $is_post_like = get_post_meta($_POST['post_id'], 'research_like_count', true);
        $user_like = [];
        $json = [];
        if ($_POST['like'] == 'liked') {
            if (empty($is_like)) {
                $user_like = [$_POST['post_id']];
                update_user_meta($user_id, 'research_like', $user_like);
            } else {
                $user_like = array_merge($is_like, [$_POST['post_id']]);
                update_user_meta($user_id, 'research_like', $user_like);
            }

            /*post like count*/
            if (empty($is_post_like)) {
                update_post_meta($_POST['post_id'], 'research_like_count', 1);
            } else {
                update_post_meta($_POST['post_id'], 'research_like_count', ($is_post_like + 1));
            }
            $json['status'] = 'liked';
        } elseif ($_POST['like'] == 'unlike') {
            /*post unlike count*/
            if ($is_post_like == 0) {
                delete_post_meta($_POST['post_id'], 'research_like_count');
            } else {
                update_post_meta($_POST['post_id'], 'research_like_count', ($is_post_like - 1));
            }
            if (!empty($is_like)) {

                $key = array_search($_POST['post_id'], $is_like);
                if ($key !== false) {
                    unset($is_like[$key]);
                }
                if (empty($is_like)) {
                    delete_user_meta($user_id, 'research_like');
                } else {
                    update_user_meta($user_id, 'research_like', $is_like);
                }
                $json['status'] = 'unliked';
            }
        }
        exit(wp_send_json($json));
    }

    add_action('wp_ajax_research_like_functionality', 'research_like_functionality');
    add_action('wp_ajax_nopriv_research_like_functionality', 'research_like_functionality');
}


add_action('wp_ajax_wp_send_ajax_create_post', 'wp_send_ajax_create_post');
add_action('wp_ajax_nopriv_wp_send_ajax_create_post', 'wp_send_ajax_create_post');

function wp_send_ajax_create_post(){
    $json = [];

    // Check if user is logged in
    if (!is_user_logged_in()) {
        wp_send_json_error('You must be logged in to perform this action.');
    }

    // Get current user ID
    $user_id = get_current_user_id();

    // Get form data
    $title = sanitize_text_field($_POST['title']);
    $content = wp_kses_post($_POST['content']);
    $file = $_FILES['attachment'];

    if (empty($title)) {
        $json['status'] = 'error';
        $json['message'] = 'Please enter title...';
        wp_send_json($json);
        wp_die();
    } elseif (empty($content)) {
        $json['status'] = 'error';
        $json['message'] = 'Please enter content...';
        wp_send_json($json);
        wp_die();
    } else {
        // Create a new post
        $post_data = array(
            'post_title' => $title,
            'post_content' => $content,
            'post_type' => 'research',
            'post_author' => $user_id,
            'post_status' => 'publish',
            'comment_status' => 'open' // Set comment status to open for this post
        );

        // Insert the post
        $post_id = wp_insert_post($post_data);

        // Upload file
        $image_url = get_site_url().'/wp-content/uploads/2023/05/Header-image.png';
        $image_id = attachment_url_to_postid($image_url); // Get the attachment ID from the URL
        if ($image_id) {
            set_post_thumbnail($post_id, $image_id); // Set the default featured image
        }
        if (!empty($file)) {
            $attachment_id = media_handle_upload('attachment', $post_id);
            if (is_wp_error($attachment_id)) {
                $json['status'] = 'error';
                $json['message'] = 'File upload failed.';
                wp_send_json($json);
                wp_die();
            } else {
                update_post_meta($post_id,'attachment_id',$attachment_id);
            }
        }

        $json['status'] = 'success';
        $json['message'] = 'Research added successfully...';
        wp_send_json($json);
        wp_die();
    }
    exit;
}





/*custom login*/
function wp_custom_login() {
    $json = [];
    $username = !empty($_POST['username']) ? sanitize_email($_POST['username']) : '';
    $password = !empty($_POST['password']) ? $_POST['password'] : '';

    if (empty($username) || empty($password)) {
        $json['status'] = 'error';
        $json['message'] = 'Please provide both email and password.';
        wp_send_json($json);
        wp_die();
    }
    if (!filter_var($username, FILTER_VALIDATE_EMAIL)) {
        $json['status'] = 'error';
        $json['message'] = 'Invalid email format';
        wp_send_json($username);
        wp_die();
    }

    $is_user     = get_user_by('email', $username);
    $is_approved = get_user_meta($is_user->ID,'approval_status',true);

    if($is_approved  === 'pending'){
        $json['status'] = 'error';
        $json['message'] = 'Your account is under review please try again...';
        wp_send_json($json);
        wp_die();
    } else if($is_approved  === 'rejected'){
        $json['status'] = 'error';
        $json['message'] = 'Your account is rejected please contact support...';
        wp_send_json($json);
        wp_die();
    } else if($is_approved  === 'approved'){
        if ( is_wp_error($user) ){
            $json['status'] = 'error';
            $json['message'] = !empty($user->errors['invalid_email']) ? $user->errors['invalid_email'][0] : 'Invalid Password!';
            wp_send_json($json);
            wp_die();
        } else {
            $creds = [];
            $creds['user_login'] = $username;
            $creds['user_password'] = $password;
            $creds['remember'] = true;
            $user = wp_signon( $creds, false );

            $json['status'] = 'success';
            $json['message'] = 'Login Success!';
            $json['url']     = ($_POST['page_id'] === '236') ? get_site_url() : '';
            wp_send_json($json);
            wp_die();
        }
    }
}
add_action('wp_ajax_wp_custom_login', 'wp_custom_login');
add_action('wp_ajax_nopriv_wp_custom_login', 'wp_custom_login');


