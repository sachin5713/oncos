(function ($) {
	$(document).on('click','.btn_login',function(e){
		e.preventDefault();
		var loader_html = '<div class="loader-overlay"><div class="loader"></div></div>';
	    var _form   = jQuery('#login_master').serialize();
	    var page_id = $(this).data('id');
	    var dataString = _form+ '&action=wp_custom_login&page_id='+page_id;
	    jQuery('body').append(loader_html);
	    jQuery.ajax({
	        type: "POST",
	        url: ajax.ajax_url,
	        data: dataString,
	        dataType: "json",
	        success: function (response) {
	            jQuery('body').find('.loader-overlay').remove();
	            if(response.status === 'error'){
	            	jQuery('body').append('<div class="alert-error" style="background: red;">'+response.message+'</div>');
	            	setTimeout(function() { $('.alert-error').remove(); }, 2000);
	            } else {
	            	jQuery('body').append('<div class="alert-error" style="background: green;">'+response.message+'</div>');
	            	setTimeout(function() { $('.alert-error').remove(); }, 2000);
	            	if(response.url != ''){
	            		window.location.href = response.url;
	            	} else {
	            		window.location.reload();
	            	}
	            }
	        }
	    });
	})
})(jQuery);
