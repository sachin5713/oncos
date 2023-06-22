<?php
/*
Template Name: Research POST
Template Post Type: post, page
*/
get_header();?>
<main id="content" <?php post_class( 'site-main' ); ?>>
	<?php if ( apply_filters( 'hello_elementor_page_title', false ) ) : ?>
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

// Form submission handler
// jQuery('.btn_submit_post').on('click', function(e) {
//     e.preventDefault();
//     var loader_html = '<div class="loader-overlay"><div class="loader"></div></div>';
//     var content = tinymce.activeEditor.getContent();
//     var title   = jQuery('#title').val();
//     var dataString = 'title='+title+'&content=' + encodeURIComponent(content) + '&action=wp_send_ajax_create_post';
//     jQuery('body').append(loader_html);
//     jQuery.ajax({
//         type: "POST",
//         url: '<?php echo admin_url('admin-ajax.php'); ?>',
//         data: dataString,
//         dataType: "json",
//         success: function (response) {
//             jQuery('body').find('.loader-overlay').remove();
//             if(response.status === 'error'){
//                 jQuery('body').append('<div class="alert-error" style="background: red;">'+response.message+'</div>');
//                 setTimeout(function() { jQuery('.alert-error').remove(); }, 2000);
//             } else {
//                 jQuery('body').append('<div class="alert-error" style="background: green;">'+response.message+'</div>');
//                 setTimeout(function() { jQuery('.alert-error').remove(); }, 2000);
//                 window.location.reload();
//             }
//         }
//     });
// });


jQuery('.btn_submit_post').on('click', function(e) {
    e.preventDefault();
    var loader_html = '<div class="loader-overlay"><div class="loader"></div></div>';
    var content = tinymce.activeEditor.getContent();
    var title = jQuery('#title').val();
    var form_data = new FormData();
    form_data.append('title', title);
    form_data.append('content', content);
    form_data.append('attachment', jQuery('#attachment')[0].files[0]);
    form_data.append('action', 'wp_send_ajax_create_post');

    jQuery('body').append(loader_html);
    jQuery.ajax({
        type: "POST",
        url: '<?php echo admin_url('admin-ajax.php'); ?>',
        data: form_data,
        dataType: "json",
        processData: false,
        contentType: false,
        success: function(response) {
            jQuery('body').find('.loader-overlay').remove();
            if (response.status === 'error') {
                jQuery('body').append('<div class="alert-error" style="background: red;">' + response.message + '</div>');
                setTimeout(function() { jQuery('.alert-error').remove(); }, 2000);
            } else {
                jQuery('body').append('<div class="alert-error" style="background: green;">' + response.message + '</div>');
                setTimeout(function() { jQuery('.alert-error').remove(); }, 2000);
                document.getElementById('research-form').reset();
                window.location.reload();
            }
        }
    });
});

</script>
