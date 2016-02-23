jQuery(document).ready(function() {
								
	 jQuery('.iplogin').click(function(event) {
		event.preventDefault();
		jQuery('#vibe_bp_login').fadeToggle(300);
		jQuery('#vibe_bp_login').toggleClass('active');
		event.stopPropagation();
    });
	
	/* ---- ---- Header ---- ---- */
	jQuery( "#browse_courses .dropdown-menu-list > li" )
		.mouseenter(function() {
			jQuery( "#browse_courses .dropdown-menu-list > li > a" ).removeClass( "maintainHover" );
			jQuery( "a:first", this ).addClass( "maintainHover" );
		});
	jQuery( "#browse_courses" )
		.mouseleave(function() {
			jQuery( "#browse_courses .dropdown-menu-list > li > a" ).removeClass( "maintainHover" );
	});
	jQuery('.dropdown-menu ul.dropdown-menu-list').click(function(e) {
        e.stopPropagation();
    });  // removed .dropdown-menu ul.dropdown-menu-list > li > a, as not needed


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
	
	// Fixes header tag when scrolled  --makarand
	jQuery(window).scroll(function(event){
		var st = jQuery(this).scrollTop();
		if(jQuery('#header-main').hasClass('fix')){
		  var headerheight = jQuery('header').height();
		  if(st > headerheight){
			jQuery('#header-main').addClass('fixed');
			jQuery('#support-process-wrap').addClass('fixed');
		  }else{
			jQuery('#header-main').removeClass('fixed');
			jQuery('#support-process-wrap').removeClass('fixed');			
		  }
		}
	});
	
	/* ---- ---- Footer ---- ---- */
	jQuery('#live_chat').click(function(e){
		e.preventDefault();		
		jQuery('#habla_topbar_div').trigger('click');
	});		
	
	jQuery('#how-it-works').click(function(e){
		e.preventDefault();		
		jQuery('#support-process-wrap').toggleClass('hidden');
		jQuery(this).toggleClass('active');
	});
	
	jQuery('button.close-popup').click(function(e){
		jQuery('#how-it-works').trigger('click');
	});
	
	
	/* ---- ---- Home Page ---- ---- */
	//flexsliver for thumb_carsoul shortcode ----makarand
	/*jQuery('.thumb_carousel.flexslider').flexslider({
													
		animation: "slide",
		animationLoop: true,
		controlNav: false,
		directionNav: true,
		itemWidth: 150,
		minItems: 2,
		maxItems: 7,
		itemMargin: 30,
		prevText: "<i class='icon-arrow-1-left'></i>",
		nextText: "<i class='icon-arrow-1-right'></i>",
		start: function() {
			   jQuery(this).removeClass('loading');
		   }    
	  });
	
	jQuery('.flexslider.review_carousel').flexslider({
													
		animation: true,
		animationLoop: true,
		controlNav: false,
		directionNav: true,
		itemWidth: 300,
		minItems:1,
		maxItems: 1,
		itemMargin: 30,
		prevText: "<i class='icon-arrow-1-left'></i>",
		nextText: "<i class='icon-arrow-1-right'></i>",
		
	  }).removeClass('loading').addClass('widget_carousel');*/
	
	/* ---- ---- Single Page/Post ---- ---- */
	if(jQuery('body').hasClass('single') || jQuery('body').hasClass('register')){	
				
		
		/* ----- All about visitor form ----- */
		function bind_login_form(){
			jQuery('.popup-with-form').magnificPopup({
				type: 'inline',
				preloader: true,
				focus: '#user_email',
				fixedContentPos: false,
				fixedBgPos: true,
				disableOn: 200,
				overflowY: 'auto',
		
				closeBtnInside: true,				
				midClick: true,
				removalDelay: 300,
				mainClass: 'my-mfp-slide-bottom',
		
				// When elemened is focused, some mobile browsers in some cases zoom in
				// It looks not nice, so we disable it:
				callbacks: {
					Open: function() {
						if(jQuery(window).width() < 700) {
							this.st.focus = false;
						} else {
							this.st.focus = '#user_email';
						}
					},
					afterClose: function() {
					  // Will fire when popup is closed
					  jQuery('.accessed:not(.popup-with-form)').removeClass('accessed').trigger('click');
					}
				}
			});
		}
		
		function bind_popup_youtube(){
			//show youtube video in popups
			if(jQuery('.popup-youtube').length > 0 || jQuery('.popup-vimeo').length > 0 || jQuery('.popup-gmaps').length > 0){
				jQuery('.popup-youtube, .popup-vimeo, .popup-gmaps').magnificPopup({
					disableOn: 160,
					type: 'iframe',
					mainClass: 'mfp-fade',
					removalDelay: 160,
					preloader: false,
					fixedContentPos: false
				});
			}
		}
		function bind_slideshare(){
			//Toggle tabs in course from sidebar menu -makarand
			if(jQuery(".slideshare-link").length > 0){
				jQuery(".slideshare-link").click(function(e){
						var slideshare = jQuery(this).next('.slideshare');
						slideshare.toggleClass('hidden');
						if(!slideshare.hasClass('hidden')){
							jQuery('body,html').animate({
							  scrollTop: jQuery(this).offset().top -90
							}, 1200);
						}
						e.preventDefault();
				});
			}
		}	
		
		//if(/*(jQuery.cookie('intellipaat_visitor')== null  && jQuery.cookie('intellipaat_visitor_email')==null) && */!jQuery('body').hasClass('logged-in') && jQuery('#login-form').length > 0){
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
	
	/* ---- ---- Course Page ---- ---- */
	//Scroll on course page when clicked on right sidebar menu link linked to hash tags.
	if(jQuery('body').hasClass('single-course')){
		
		
		/* ----- Mobile look -----*/
		var mobilePrepared=0;
		function prepareMobileContent(){
			jQuery("#mobile_about_us > div").append(jQuery('div.about-course').clone());
			jQuery("#mobile_course_content > div").append(jQuery('div.course_curriculum'));
			jQuery("#mobile_selfpacevsinstrcutor > div").append(jQuery('#self-instructor').html()).find("h4").remove();
			moveMobileContent();
			bind_slideshare();	
			bind_popup_youtube();
			bind_login_form();
			mobilePrepared=1;
		}
		function moveMobileContent(){
			jQuery("#reviews").appendTo('#review-mobile');
			jQuery("#related-courses").insertAfter('#review-mobile');
			jQuery("#key-features").appendTo('#mobile_keyfeature > div');
		}
		function restoreMobileContent(){
			jQuery("#reviews").appendTo('#item-body');
			jQuery("#related-courses").insertAfter('#item-nav');
			jQuery("#key-features").insertAfter('#keyfeature-heading');
		}
		
		if(jQuery(window).width() <768){
			prepareMobileContent();
		}
		jQuery(window).resize(function(){
									   
			if(jQuery(window).width() <768){
				if(mobilePrepared == 0)
					prepareMobileContent();
				moveMobileContent();
			}
			else if(mobilePrepared == 1)
				 restoreMobileContent();
			
		});
		
		jQuery('#course-mobile-details').on('shown.bs.collapse',function(){
			jQuery('body,html').animate({
			  scrollTop: jQuery('.accordion-body.collapse.in').offset().top -90
			}, 1200);																						 
		});
		
		/* ----- All about tabs and left side navigation -----*/
		 jQuery('.single-course #object-nav li a.scrollMe, .review.link').click(function(event) {
			var myid = jQuery(this).attr('href');
			event.preventDefault();
			jQuery('body,html').animate({
				  scrollTop: jQuery(myid).offset().top -90
				}, 1200);
			return false;
		});
		 
		//Toggle tabs in course from sidebar menu -makarand
		jQuery(".single-course #object-nav li a[data-index]").click(function(e){
			var index = jQuery(this).data('index');
			if( index >= 0){
				jQuery(".single-course #object-nav li").removeClass('selected');
				jQuery(this).parent('li').addClass('selected');			
				jQuery('.nav-tabs li:eq('+index+') a').tab('show');	
				jQuery('body,html').animate({
				  scrollTop: 80
				}, 1200);
				e.preventDefault();
			}
		});
		
		//toggle sidebar menu from tabs in course page ---makarand
		jQuery(".single-course .nav-tabs a").click(function(e){
			var index = jQuery(this).parent('li').index();
			if( index >= 0){
				var sidebar =jQuery(".single-course #object-nav li") ;
				sidebar.removeClass('selected');
				sidebar.find('a[data-index='+index+']').first().parent('li').addClass('selected');
			}
		});
		

		
		
		
	}
	
	
	jQuery('#all_courses_header .contact-title.collapsed').click(function(){
		jQuery(this).removeClass('collapsed');																	  
	});
	
	
	jQuery('form').on('focus', '.billing_phone, #billing_phone, .phone', function(){
		jQuery(".billing_phone, #billing_phone, .phone").bind('keyup change', function (e) {
			this.value = this.value.replace(/[^0-9\+]/g,'');					
		});														  
	});
	
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
	
	jQuery(document).on('click', 'a[href$=".pdf"]',function(){
		jQuery(this).attr('target','_blank');
	});
	
	
});

vibe_course_module_strings.unable_add_students = 'Student/Email address/Username is already exist';