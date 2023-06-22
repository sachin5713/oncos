<?php
/**
 * The template for displaying singular post-types: posts, pages and user-defined custom post types.
 *
 * @package HelloElementor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

while ( have_posts() ) :
	the_post();
	?>
<section class="reasearch-title" style="background-image: url(<?=get_the_post_thumbnail_url(get_the_ID())?>);">
	<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
</section>
<main id="content" <?php post_class( 'site-main' ); ?>>
	<?php if ( apply_filters( 'hello_elementor_page_title', true ) ) : ?>
	<?php endif; ?>
	<div class="page-content">
		<?php the_content(); ?>
		<?php
			$post_id = get_the_ID();

			// Get the attachment ID
			$attachment_id = get_post_meta($post_id,'attachment_id',$post_id);

			if ($attachment_id) {
			    $attachment = get_post($attachment_id);
			    $mime_type = $attachment->post_mime_type;
			    if (strpos($mime_type, 'video/') === 0) {
			        // Video file
			        $video_url = wp_get_attachment_url($attachment_id);
			        ?>
			        <video controls width="100%">
			            <source src="<?php echo esc_url($video_url); ?>" type="<?php echo esc_attr($mime_type); ?>">
			            Your browser does not support the video tag.
			        </video>
			        <?php
			    } elseif (strpos($mime_type, 'application/') === 0) {
			        // Document file
			        $file_url = wp_get_attachment_url($attachment_id);
			        ?>
			        <a href="<?php echo esc_url($file_url); ?>" target="_blank" rel="noopener noreferrer">Download Document</a>
			        <?php
			    }
			}
			?>
	</div>
	<?php comments_template(); ?>
</main>

	<?php
endwhile;
