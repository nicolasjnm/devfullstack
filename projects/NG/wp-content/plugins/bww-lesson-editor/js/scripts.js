jQuery(document).ready(function(){
});
var course_id;
var lesson_id;
var video_code, n_video_code;
var lesson_id;
function checkVideoCode(val_vid){
	video_code = val_vid;
    n_video_code = video_code.indexOf("wistia");
	if(n_video_code>=0){
		jQuery(".checkbox_video").css({"opacity":"1"})
		jQuery(".checkbox_video input").removeAttr("disabled")
	}else{
		jQuery(".checkbox_video").css({"opacity":".5"})
		jQuery(".checkbox_video input").attr({"disabled":"disabled"})
	}
}
jQuery(document).on("keyup", "#level_video", function(){
	checkVideoCode(jQuery(this).val());
})
jQuery(document).on("change", "#course_select", function(){
	course_id=jQuery(this).val();
	jQuery.ajax({
			type: 'POST',   // Adding Post method
			url: ajaxurl, // Including ajax file
			data: {"action": "levels_dropdown", "course_id":course_id}, // Sending data dname to post_word_count function.
			success: function(data){ // Show returned data using the function.
				jQuery("#lesson_select").html(data);
			}
	});

})
jQuery(document).on("change", "#lesson_select", function(){
	course_id=jQuery("#course_select").val();
	lesson_id=jQuery(this).val();
	jQuery.ajax({
			type: 'POST',   // Adding Post method
			url: ajaxurl, // Including ajax file
			data: {"action": "get_level_data", "course_id":course_id, "lesson_id":lesson_id}, // Sending data dname to post_word_count function.
			success: function(data){ // Show returned data using the function.
				console.log(data);
                                var obj = jQuery.parseJSON( data );
					jQuery("#level_title").val('');
					jQuery("#level_video").val('');
					jQuery(".checkbox_video input").prop("checked", false)
					jQuery("#level_video_title").val('');
					jQuery("#level_video_author").val('');
					jQuery("#level_audio_title").val('');
					jQuery("#level_audio").val('');
					jQuery("#about_level").val('');
					jQuery("#journal_assignment").val('');
					jQuery("#journal_populated").val('');
					jQuery("#additional_resources").val('');
					if(obj!=null){
                                                jQuery("#view_top").attr("href", obj.less_url);
                                                jQuery("#view_bottom").attr("href", obj.less_url);
                                                jQuery("#view_top").show();
                                                jQuery("#view_bottom").show();
                                                if ('title' in obj)
							jQuery("#level_title").val(stripslashes(obj.title));
						if ('video' in obj){
							jQuery("#level_video").val(stripslashes(obj.video));
							checkVideoCode(stripslashes(obj.video));
						}
						if ('speed_selector' in obj){
							if(obj.speed_selector==1){
								jQuery(".checkbox_video input").prop("checked", true)
							}else{
								jQuery(".checkbox_video input").prop("checked", false)
							}
						}
						if ('video_title' in obj)
							jQuery("#level_video_title").val(stripslashes(obj.video_title));
						if ('video_author' in obj)
							jQuery("#level_video_author").val(stripslashes(obj.video_author));
						if ('audio_title' in obj)
							jQuery("#level_audio_title").val(stripslashes(obj.audio_title));
						if ('audio' in obj)
							jQuery("#level_audio").val(stripslashes(obj.audio));
						if ('about' in obj)
							jQuery("#about_level").val(stripslashes(obj.about));
						if ('journal_link' in obj)
							jQuery("#journal_assignment").val(stripslashes(obj.journal_link));
						if ('journal_text' in obj)
							jQuery("#journal_populated").val(stripslashes(obj.journal_text));
						if ('additional_resources_title' in obj)
							jQuery("#additional_resources").val(stripslashes(obj.additional_resources_title));
					}
			}
	});
	jQuery.ajax({
			type: 'POST',   // Adding Post method
			url: ajaxurl, // Including ajax file
			data: {"action": "get_level_additional_resources", "course_id":course_id, "lesson_id":lesson_id}, // Sending data dname to post_word_count function.
			success: function(data){ // Show returned data using the function.
				var obj = jQuery.parseJSON( data );
				var nl;
				jQuery("input[id*=additional_resources_url]").val('');
				jQuery("input[id*=additional_resources_btn]").val('');
				if(obj!=null){
					jQuery.each(obj, function(k, additional){
						nl=k+1;
							if ('url' in additional)
								jQuery("#additional_resources_url"+nl).val(stripslashes(additional.url));
							if ('text' in additional)
								jQuery("#additional_resources_btn"+nl).val(stripslashes(additional.text));
					})
				}
			}
	});

})
function stripslashes(str) {
  return (str + '')
    .replace(/\\(.?)/g, function(s, n1) {
      switch (n1) {
        case '\\':
          return '\\';
        case '0':
          return '\u0000';
        case '':
          return '';
        default:
          return n1;
      }
    });
}
