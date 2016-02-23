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
	
}


jQuery(document).ready(function() {
	
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
	/*jQuery('#live_chat').click(function(e){
		e.preventDefault();		
		jQuery('#habla_topbar_div').trigger('click');
	});		*/
	
	jQuery('#how-it-works').click(function(e){
		e.preventDefault();		
		jQuery('#support-process-wrap').toggleClass('hidden');
		jQuery(this).toggleClass('active');
	});
	
	jQuery('button.close-popup').click(function(e){
		jQuery('#how-it-works').trigger('click');
	});
		
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
	
	jQuery(document).on('click', 'a[href$=".pdf"]',function(){
		jQuery(this).attr('target','_blank');
	});
	
	
});