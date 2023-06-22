<?php
// if (!function_exists('display_research_grid')) {
    function display_research_grid(){
        $user_id   = get_current_user_id();
        $is_like   = get_user_meta($user_id,'research_like',true);
		ob_start();
        if(pmpro_hasMembershipLevel('1', $user_id)){
             ?>
<section class="on_research_main">
    <div class="inner_main">
        <div class="research-grid" id="research-grid-container">
            <?php
                $args = [
                    'posts_per_page' => 6,
                    'post_type' => 'research',
                    'post_status' => 'publish',
                    'orderby' => 'date',
                    'order' => 'DESC',
                ];
                $research_posts = new WP_Query($args);
                $i = 0;
                if ($research_posts->have_posts()) {
                    while ($research_posts->have_posts()) {
                        $i++;
                        $research_posts->the_post();
                        global $post;
                        
                        $url = get_permalink(); ?>
                        <div class="research-item">
                            <div class="research_item_card">
                                <div class="research_item_card_top">
                                    <div class="research-avatar">
                                        <?php echo get_avatar(get_the_author_meta('ID'), 60); ?>
                                    </div>
                                    <div class="card_title">
                                        <h3><a href="<?=$url?>"><?php the_title(); ?></a></h3>
										<span style="font-size:12px"><?php echo date('d/M/Y',strtotime($post->post_date))?></span> |
										<span style="font-size:12px"><?php echo get_the_author_meta('display_name', get_the_author_meta('ID')); ?></span>
                                    </div>
                                    <div class="dots">
                                        <a href="javascript:"><i class="fa fa-ellipsis-h"></i></a>
                                    </div>
                                </div>
                                <div class="research-content">
                                    <?php 
                                        $content = get_the_content();
                                        $content = strip_tags($content);
                                        $trimmed_content = wp_trim_words($content, 15, '...');
                                        echo substr($trimmed_content, 0, 110);
                                    ?>
                                </div>
                                <?php if (get_the_post_thumbnail_url(get_the_ID())) : ?>

                                <?php endif; ?>
                                <hr />
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
                        </div>
                        <?php
                        generate_share_popup(get_the_ID(), $i);
                    }
                }
                wp_reset_postdata();
            ?>
        </div>
        <!-- Load more button -->
        <div class="load-more-container">
            <button class="load-more-button" id="load-more-button">Load More</button>
        </div>
    </div>
</section>
<?php
        }
        return ob_get_clean(); // Return the buffered content
    }
    add_shortcode('display_research', 'display_research_grid');
// }