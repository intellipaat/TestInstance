jQuery(document).ready(function() {
									
	 jQuery('.iplogin').click(function(event) {
		event.preventDefault();
		jQuery('#vibe_bp_login').fadeToggle(300);
		jQuery('#vibe_bp_login').toggleClass('active');
		event.stopPropagation();
    });	 

	//show youtube video in popups  //*** --- && jQuery(window).width() >= 768
	if(jQuery('a.ajax-cart-link').length >0 && intellipaat.cart_page != 1 ){
		jQuery('a.ajax-cart-link').attr('href', intellipaat.cart_ajax_url)
				.magnificPopup({
					disableOn: 767,
					type: 'ajax',
					mainClass: 'mfp-fade',
					removalDelay: 160,
					preloader: true,
					fixedContentPos: false,
					ajax: {
					  settings: null,		
					  cursor: 'mfp-ajax-cur', // CSS class that will be added to body during the loading (adds "progress" cursor)
					  tError: '<a href="'+intellipaat.cartUrl+'">Cart content</a> could not be loaded.' //  Error message, can contain %curr% and %total% tags if gallery is enabled
					}
				});
		/*if(intellipaat.new_product_added == 1){
			jQuery('a.ajax-cart-link').trigger('click');	
		}*/
	}
	 
	if(jQuery('body').hasClass('single') || jQuery('body').hasClass('register')){
		
		if(!jQuery('body').hasClass('logged-in') && jQuery('#login-form').length > 0){
			
			//initial display actions. preparing login form
			jQuery('.login-text').hide();
			jQuery('#login-div').hide();
			jQuery('.mfp-footer a').on('click',function(e){
				jQuery('.alert').alert('close');
				jQuery('.signup-text').toggle();
				jQuery('.login-text').toggle();
				jQuery('#signup-div').toggle();
				jQuery('#login-div').toggle();
				e.preventDefault();
			});
			
			jQuery('.popup-youtube, .slideshare-link, .pdf-link, .quiz-link').each(function(){
				jQuery(this).attr('data-class',jQuery(this).attr('class')).attr('class','').addClass('popup-with-form').attr('data-href',jQuery(this).attr('href')).attr('href','#login-form');
			});
			
			var accessed_class;
			jQuery('.popup-with-form').on('click',function(){
				jQuery('.popup-with-form').removeClass('accessed');
				accessed_class = jQuery(this).attr('data-class');
				jQuery(this).addClass('accessed');
			});
			
			bind_login_form();
			
			/**************************** Login form ************************/
			var login_form= jQuery('#login_form');
			login_form.submit(function(ev) {
				jQuery('.alert').alert('close');
				var $btn = jQuery('#submit').button('loading');
				jQuery.ajax({
					 type 		: "post",
					 dataType 	: "json",
					 url 		: ajaxurl+"?action=intellipaat_visitor_secure_login&ajaxform=set&nonce=",
					 data 		: login_form.serializeArray(),
					 success	: function(response) {
						if(response.result == true) {
							$btn.button('complete');
							jQuery('#loginerrors').append(response.message);
						   	window.location.reload();
						}
						else {
							jQuery('#loginerrors').append(response.error);
							jQuery('p.login-loading').addClass('hide');
							$btn.button('reset');
						}
					 }
				});
			
				ev.preventDefault();				
			});
			
		}
		else{
			bind_popup_youtube();
			bind_slideshare();	
		}
		
		if(jQuery('#signup_form').length > 0){
			
			jQuery(".chosen-select").chosen({width: "100%"});
			jQuery.get(intellipaat.SecKeyLink, function(data){
			  jQuery('input.SecKey').val(data);
			});
			
			/**************************** Sign up form ************************/
			var signup_form= jQuery('#signup_form');
			/*var currentPage = intellipaat.currentPage;*/
			signup_form.submit(function(ev) {
				jQuery('.alert').alert('close');
						
				var $signupbtn = jQuery('#signup_submit').button('loading');
				jQuery.ajax({
					 type : "post",
					 dataType : "json",
					 url : signup_form.attr('action')+'&ajaxform=set',
					 data : signup_form.serializeArray(),
					 success: function(response) {
						 if(response.result == true) {
							$signupbtn.button('complete');
							jQuery('#signuperrors').append(response.message);
						   	window.location.reload();
						}
						else {
							jQuery('#signuperrors').append(response.error);
							jQuery('p.login-loading').addClass('hide');
							$signupbtn.button('reset');
						}
					 }
				}); 
				ev.preventDefault();
				
			});
		}
	 }
	 
	 	
	/* ---- ---- Checkout Page ---- ---- */
	//On checkout page send email to admin to capture records before sale initiates
	if(jQuery('body.woocommerce-checkout .woocommerce form.login').length > 0){
		var login_form= jQuery('form.login');
		jQuery('input.button').attr('data-loading-text',"Logging in...").attr('data-complete-text','Logged in');
		login_form.submit(function(ev) {
			jQuery('.alert').alert('close');
			var $btn = jQuery('input.button').button('loading');
			jQuery.ajax({
				 type 		: "post",
				 dataType 	: "json",
				 url 		: ajaxurl+"?action=intellipaat_checkout_login&ajaxform=set&nonce=",
				 data 		: login_form.serializeArray(),
				 success	: function(response) {
					if(response.result == true) {
						$btn.button('complete');
						jQuery('#loginerrors').append(response.message);
						window.location.reload();
					}
					else {
						jQuery('#loginerrors').append(response.error);
						jQuery('p.login-loading').addClass('hide');
						$btn.button('reset');
					}
				 }
			});
		
			ev.preventDefault();
		});
	}

	if(jQuery('form.checkout').length > 0){
		
		//jQuery('#createaccount').trigger('click').attr("checked","checked").parent('p').hide();
		if(!jQuery('body').hasClass('logged-in')){
			if(jQuery(window).width() >640)
				jQuery("#billing_email_field").insertBefore('.woocommerce-shipping-fields > h3');	
			jQuery(".create-account").insertAfter('#billing_email_field').toggleClass('create-account createaccount');
			//jQuery("#account_password_field > label").append('<div class="fr" class="showlogin">If exising customer, <a>Click here</a></div>');
		
			jQuery("#showlogin").on('click', function(e){
						jQuery('body,html').animate({
						  scrollTop: jQuery('#showlogin').offset().top -120
						}, 1200);
						jQuery('#loginwrapper').slideToggle();
						e.preventDefault();
			});
		}
			
		function initiateSale(){
			jQuery.ajax({
				 type : "post",
				 dataType : "json",
				 url : intellipaat.saleInitiateUrl ,
				 data : jQuery('form.checkout').serializeArray(),
				 success: function(response) {
					if(response.type == "success") {
					   console.log('Mail sent to admin');
					}
					else {
					   console.log("Oops! Error occured. Please try again.");
					}
				 }
			}); 
		}
		
		/*jQuery("#billing_phone").on("blur",function() {
			if(jQuery(this).val().length <= 9){
				alert('Please enter valid phone number!');
				jQuery("#billing_phone").trigger('focus');
			}
		});*/
				
		jQuery("#billing_email, #billing_phone").on("change",function() {
			
			/*if(jQuery('#billing_first_name').val()=='' || jQuery('#billing_last_name').val()=='' || jQuery('#billing_email').val()=='' || jQuery('#billing_phone').val()=='' ){
				alert('Please fill in all the required fields (indicated by *).');
				return false;
			}*/
			var billing_email = jQuery('#billing_email').val();
			var billing_phone = jQuery('#billing_phone').val();
			var atpos=billing_email.indexOf("@");
			var dotpos=billing_email.lastIndexOf(".");
			
			if ( billing_email !='' /*&& billing_phone!='' && billing_phone.length > 9*/){
				if (atpos<1 || dotpos<atpos+2 || dotpos+2>=billing_email.length )
				{
					alert('Please enter valid email address!');
					console.log("Not a valid e-mail address");
					jQuery("#billing_email").trigger('focus');
					//return false;
				}
				else
				{
					 initiateSale();
				}
			}
		});
		if(jQuery('body').hasClass('logged-in') ){
			initiateSale();
		}
		
		/* ---- Mobile look behaviour ----*/
		var couponFormMoved=0;		
		function movecouponForm(){
			jQuery(".coupon").insertBefore('div.content');
			couponFormMoved =1;
		}
		function restorecouponForm(){
			jQuery(".coupon").appendTo('#sidebar');
			couponFormMoved =0;
		}
		
		if(jQuery(window).width() <992){
			movecouponForm();
		}
		jQuery(window).resize(function(){									   
			if(jQuery(window).width() <992){
				if(couponFormMoved == 0)
					movecouponForm();
			}
			else if(couponFormMoved == 1)
				 restorecouponForm();
			
		});
	}

	
});

vibe_course_module_strings.unable_add_students = 'Student/Email address/Username is already exist';