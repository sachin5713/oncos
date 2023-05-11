<?php
// error_reporting(0);
/*Loadmore Functionality*/
if(!function_exists('load_more_research')){
    function load_more_research() {
        $page = $_POST['page'];
        $posts_per_page = 2;
        $user_id   = get_current_user_id();
        $is_like   = get_user_meta($user_id,'research_like',true);
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
                ?>
                <script src="https://static.addtoany.com/menu/page.js"></script>
                <div class="research-item">
                    <div class="research-avatar">
                        <?php echo get_avatar(get_the_author_meta('ID'), 60); ?>
                    </div>
                    <div class="dots">
                        <a href="javascript:"><i class="fa fa-ellipsis-h"></i></a>
                    </div>
                    <div class="research-content">
                        <h3><?php the_title(); ?></h3>
                        <?php the_content(); ?>
                    </div>
                    <?php if (get_the_post_thumbnail_url(get_the_ID())) : ?>
                        <img src="<?= get_the_post_thumbnail_url(get_the_ID()) ?>" height="200" width="200"/>
                    <?php endif; ?>
                    <hr/>
                    <?php 
                        $fa_class   = (!empty($is_like) && in_array(get_the_ID(), $is_like)) ? 'fa-thumbs-up' : 'fa-thumbs-o-up';
                        $like_class = (!empty($is_like) && in_array(get_the_ID(), $is_like)) ? 'liked' : '';
                        $url = get_permalink();
                    ?>
                    <div class="research-interactions <?=$like_class?>" data-id="<?= get_the_ID() ?>">
                        <a href="javascript:" id="like" class="like-button"><i class="fa <?=$fa_class?>"></i> Like</a>
                        <a href="<?=$url?>" id="comment" class="comment-button"><i class="fa fa-comment"></i> Comment</a>
                        <a class="share" id="share" href="#" data-toggle="modal" data-target="#shareModal_<?=$i?>"><i class="fa fa-share"></i> Share</a>
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

/*Like Functionality*/
if(!function_exists('research_like_functionality')){
    function research_like_functionality(){
        $user_id        = get_current_user_id();
        $is_like        = get_user_meta($user_id,'research_like',true);
        $is_post_like   = get_post_meta($_POST['post_id'],'research_like_count',true);
        $user_like      = [];
        $json           = [];
        if($_POST['like'] == 'liked'){
            if(empty($is_like)){
                $user_like = [$_POST['post_id']];
                update_user_meta($user_id,'research_like',$user_like);
            } else {
                $user_like = array_merge($is_like,[$_POST['post_id']]);
                update_user_meta($user_id,'research_like',$user_like);
            }

            /*post like count*/
            if(empty($is_post_like)){
                update_post_meta($_POST['post_id'],'research_like_count',1);
            } else {
                update_post_meta($_POST['post_id'],'research_like_count',($is_post_like+1));
            }
            $json['status'] = 'liked';
        } elseif($_POST['like'] == 'unlike'){
            /*post unlike count*/
            if($is_post_like == 0){
                delete_post_meta($_POST['post_id'],'research_like_count');
            } else {
                update_post_meta($_POST['post_id'],'research_like_count',($is_post_like-1));
            }
            if(!empty($is_like)){

                $key = array_search($_POST['post_id'], $is_like);
                if ($key !== false) {
                    unset($is_like[$key]);
                }
                if(empty($is_like)){
                    delete_user_meta($user_id, 'research_like');
                } else {
                    update_user_meta($user_id,'research_like',$is_like);
                }
                $json['status'] = 'unliked';
            }
        }
        exit(wp_send_json($json));
    }
    add_action('wp_ajax_research_like_functionality', 'research_like_functionality');
    add_action('wp_ajax_nopriv_research_like_functionality', 'research_like_functionality');
}
