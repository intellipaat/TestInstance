jQuery(window).load(function(){
   jQuery('#subscribe_thickbox').trigger('click');
   jQuery("#TB_closeWindowButton").click(function() {
        jQuery.post(ajaxurl,
        {
            'action': 'close_tab'
        });
   });
});