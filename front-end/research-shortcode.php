<?php
// if (!function_exists('display_research_grid')) {
    function display_research_grid(){
        ob_start(); // Start output buffering
        ?>
        <section class="on_research_main">
            <div class="inner_main">
                <div class="research-grid" id="research-grid-container">
                    <?php
                    $user_id   = get_current_user_id();
			        $is_like   = get_user_meta($user_id,'research_like',true);
                    $args = [
                        'posts_per_page' => 2,
                        'post_type' => 'research',
                        'post_status' => 'publish',
                        'orderby' => 'name',
                        'order' => 'DESC',
                    ];
                    $research_posts = new WP_Query($args);
                    $i = 0;
                    if ($research_posts->have_posts()) {
                        while ($research_posts->have_posts()) {
                            $i++;
                            $research_posts->the_post(); ?>
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
	                            	$url        = get_permalink();
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
                    ?>
                </div>
                <!-- Load more button -->
                <div class="load-more-container">
                    <button class="load-more-button" id="load-more-button">Load More</button>
                </div>
            </div>
        </section>
        <?php
        return ob_get_clean(); // Return the buffered content
    }
    add_shortcode('display_research', 'display_research_grid');
// }
