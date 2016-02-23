jQuery(document).ready(function() {
		
	/* ---- ---- Single Page/Post ---- ---- */
	if(jQuery('body').hasClass('single')){
				
		if((jQuery.cookie('intellipaat_visitor')== null  && jQuery.cookie('intellipaat_visitor_email')==null) && !jQuery('body').hasClass('logged-in') && jQuery('#login-form').length > 0){
					
			jQuery('.popup-youtube, .slideshare-link, .pdf-link, .quiz-link').each(function(){
				jQuery(this).attr('data-class',jQuery(this).attr('class')).attr('class','').addClass('popup-with-form').attr('data-href',jQuery(this).attr('href')).attr('href','#login-form');
			});
			
			var accessed_class;
			jQuery('.popup-with-form').on('click',function(){
				jQuery('.popup-with-form').removeClass('accessed');
				accessed_class = jQuery(this).attr('data-class');
				jQuery(this).addClass('accessed');
			});
			jQuery(".chosen-select").chosen({width: "100%"});
			
			bind_login_form();
			
			jQuery.get(intellipaat.SecKeyLink, function(data){
			  jQuery('input.SecKey').val(data);
			});
			
			var signup_form= jQuery('#signup_form');
			/*var currentPage = intellipaat.currentPage;*/
			signup_form.submit(function(ev) {
				var error = 0;
						
				var user_email = jQuery('#user_login').val();
				var atpos=user_email.indexOf("@");
				var dotpos=user_email.lastIndexOf(".");
				
				if ( user_email =='' || atpos<1 || dotpos<atpos+2 || dotpos+2>=user_email.length ){					
						alert('Please enter valid email address!');
						console.log("Not a valid e-mail address");
						jQuery("#user_login").trigger('focus');
						error=1;
						return false;				
				}
						
				ev.preventDefault();
				jQuery('p.loading').removeClass('hide');
				if(!error){
					jQuery.ajax({
						 type : "post",
						 dataType : "json",
						 url : signup_form.attr('action'),
						 data : signup_form.serializeArray(),
						 success: function(response) {
							if(response.type == "success") {
							   console.log('Lead Captured.');
							   if(accessed_class == 'quiz-link')
							   		window.location = jQuery('.quiz-link').attr('href');
							}
							else {
								console.log("Oops! Error occured. Please try again.");
							}
						 }
					}); 	
					
					jQuery('.popup-with-form').off( "click" );
					jQuery('.popup-with-form').each(function(){
						jQuery(this).removeClass('popup-with-form').addClass(jQuery(this).attr('data-class')).attr('href',jQuery(this).attr('data-href'));
					});
					bind_slideshare();
					bind_popup_youtube();
					if(accessed_class != 'quiz-link')
						jQuery('#login-form .mfp-close').trigger('click');
					//jQuery('.popup-youtube.accessed').delay(700).removeClass('accessed').trigger('click');
				}
				
			});
		}
		else{
			bind_popup_youtube();
			bind_slideshare();	
		}
		
	}
	
});