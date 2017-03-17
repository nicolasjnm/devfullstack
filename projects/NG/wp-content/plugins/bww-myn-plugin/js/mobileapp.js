jQuery(document).ready(function(){
            setInterval(function(){
                jQuery('.white-box-left').css('margin-bottom', '20px');
                jQuery('#facebookLink').attr("href", "fb://group/344601295735066/");
                jQuery('#facebookLink2').attr("href", "fb://group/344601295735066/");
                jQuery('#hscdnpraxisnowcoms3amazonawscommemberswpcontentuploads201504ProgramOverviewpdf').attr("target", "_blank");
            }, 1000);

            jQuery('#audio_app_link').click(function(){
                jQuery('#audio_down').show();
            });
			jQuery("<div class='loaging_anchor'></div>").appendTo("body");
			//jQuery("#audio-box-tool a").not("#audio_app_link").removeAttr("href")
			jQuery(".remove_href").removeAttr("href");
    });
jQuery(document).on("touchend", "a", function(){
	var link_a = jQuery(this).attr("href");
	//console.log(link_a.indexOf('javascript:'));
	if(typeof link_a === 'undefined' || jQuery(this).hasClass("slideToggle")){
	}else if((link_a.indexOf('#') < 0 || link_a.indexOf('javascript:') < 0) && link_a != '' && link_a.indexOf("mp3") < 0){
		window.location = link_a;
		jQuery(".loaging_anchor").fadeIn(100, function(){
			setInterval(function(){
				jQuery(".loaging_anchor").fadeOut("fast");
			}, 2000);
		});
	}
})
jQuery(document).on("touchend", "#sfwd-mark-complete input[type='submit']", function(){
	jQuery(".loaging_anchor").fadeIn(100);
	jQuery.ajax({
		type        : 'POST', // define the type of HTTP verb we want to use (POST for our form)
		url         : window.location.href, // the url where we want to POST
		data        : {'post':jQuery("#sfwd-mark-complete input[name='post']").val(),'sfwd_mark_complete':'Mark Complete'}, // our data object
		success: function(data){
			window.location = jQuery(".swiper-slide-next a").attr("linkjs");
		}
	})
})
