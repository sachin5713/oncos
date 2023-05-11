<?php
/*
Template Name: Research Template
Template Post Type: post, page
*/
get_header();
?>
<main id="content" <?php post_class( 'site-main' ); ?>>
	<?php if ( apply_filters( 'hello_elementor_page_title', true ) ) : ?>
		<header class="page-header">
			<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
		</header>
	<?php endif; ?>
	<div class="page-content">
		<?php the_content(); ?>
	</div>
</main>
<?php get_footer(); ?>
<script>
// Loadmore functionality
jQuery(document).ready(function($) {
    var page = 2;
    var loading = false;
    var $loadMoreButton = $('#load-more-button');
    var $researchGridContainer = $('#research-grid-container');
    
    $loadMoreButton.on('click', function() {
        if (!loading) {
            loading = true;
            $loadMoreButton.text('Loading...');
            
            $.ajax({
                url: '<?php echo admin_url('admin-ajax.php'); ?>',
                type: 'POST',
                data: {
                    action: 'load_more_research',
                    page: page,
                },
                success: function(response) {
                    if (response) {
                        $researchGridContainer.append(response);
                        page++;
                        loading = false;
                        $loadMoreButton.text('Load More');
                    } else {
                        $loadMoreButton.text('No more posts');
                    }
                },
                error: function() {
                    $loadMoreButton.text('Error');
                }
            });
        }
    });
});
// Research like functionality
	jQuery(document).on('click','.like-button',function(e){
		e.preventDefault();
		$post_id = jQuery(this).parent().data('id');
		jQuery(this).parent().toggleClass('liked');
		if(jQuery(this).parent().hasClass('liked')){
			jQuery(this).find('i').removeClass('fa-thumbs-o-up').addClass('fa-thumbs-up');
			var like = 'liked';
		} else {
			jQuery(this).find('i').removeClass('fa-thumbs-up').addClass('fa-thumbs-o-up');
			var like = 'unlike';
		}
		jQuery.ajax({
            url: '<?php echo admin_url('admin-ajax.php'); ?>',
            type: 'POST',
            data: {
                action: 'research_like_functionality',
                like: like,
                post_id: $post_id,
            },
            success: function (response) {
                a2a.init('page');
            },
        });
	});

</script>
