var error;

jQuery(document).ready(function(){
	jQuery("#bww_bluesky_admin_submit").click(function(){
            var cid = jQuery("#bww_bs_cid").val();
            var ckey = jQuery("#bww_bs_ckey").val();
            var radio = jQuery("input[name=bww_bs_stage]");
            var stage = radio.filter(':checked').val();
            var uri = jQuery("#bww_bs_path").val();
            jQuery.ajax({
                    type: 'POST',   // Adding Post method
                    url: ajaxurl, // Including ajax file
                    data: {"action": "process_bwwbsky_post_ajax", "cid":cid, "ckey":ckey, "stage":stage, "uri":uri}, // Sending data dname to post_word_count function.
                    success: function(data){ // Show returned data using the function.
                        jQuery("#bww_bs_update").show();

                    }
            });
	});
        
        
        jQuery("#bww_bs_event_button").click(function(){
            var eid = jQuery("#bww_bs_event").val();
            jQuery("#bww_bs_event_button").html("Please Wait....");
            jQuery("#bww_bs_event_button").prop('disabled', true);
            
            jQuery.ajax({
                    type: 'POST',   // Adding Post method
                    url: ajaxurl, // Including ajax file
                    data: {"action": "process_bwwbsky_subscribe_post_ajax", "eid":eid}, // Sending data dname to post_word_count function.
                    success: function(data){ // Show returned data using the function.
                        console.log(data);
                        jQuery('#bww_bs_webinar_iframe').attr('src',data);
                        jQuery('#bww_bs_webinar_iframe').show();
						jQuery('#bww_bs_webinar_iframe').load(function(){
							jQuery("#bww_bs_event_button").html("Watch Webinar");
						})
                    }
            });
	});
        
});