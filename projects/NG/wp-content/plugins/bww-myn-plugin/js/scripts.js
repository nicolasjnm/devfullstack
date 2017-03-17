function hiddeAll(divid){
	jQuery('.badge-text').hide();
	document.getElementById(divid).style.display = 'block';
        document.getElementById(divid+'b').style.display = 'block';
}
var error;

Notify = function(text, callback, close_callback, style) {

	var time = '10000';
	var $container = $('#notifications');
	var icon = '<i class="fa fa-info-circle "></i>';
 
	if (typeof style == 'undefined' ) style = 'warning'
  
	var html = $('<div class="alert alert-' + style + '  hide">' + icon +  " " + text + '</div>');
  
	$('<a>',{
		text: '×',
		class: 'button close',
		style: 'padding-left: 10px;',
		href: '#',
		click: function(e){
			e.preventDefault()
			close_callback && close_callback()
			remove_notice()
		}
	}).prependTo(html)

	$container.prepend(html)
	html.removeClass('hide').hide().fadeIn('slow')

	function remove_notice() {
		html.stop().fadeOut('slow').remove()
	}
	
	var timer =  setInterval(remove_notice, time);

	$(html).hover(function(){
		clearInterval(timer);
	}, function(){
		timer = setInterval(remove_notice, time);
	});
	
	html.on('click', function () {
		clearInterval(timer)
		callback && callback()
		remove_notice()
	});
  
  
}

function checkPass()
{
    //Store the password field objects into variables ...
    var pass1 = jQuery('#newpass1');
    var pass2 = jQuery('#newpass2');
    //Store the Confimation Message Object ...
    var message = jQuery('#confirmMessage');
    //Set the colors we will be using ...
    var goodColor = "#66cc66";
    var badColor = "#ff6666";
    //Compare the values in the password field 
    //and the confirmation field
    if(pass1.val() == pass2.val()){
		jQuery(".memberium_change_pass input[name='password1']").val(pass1.val())
		jQuery(".memberium_change_pass input[name='password2']").val(pass2.val())
        pass1.css({"background":goodColor});
        pass2.css({"background":goodColor});
        message.css({"color":goodColor});
        message.html("Passwords Match!");
		console.log(checkCurrentPass());
		if(error==0){
        	jQuery('#pass_sub').css({"display":"block"});
			return "1";
		}else{
			return "0"
		}
        
    }else{
        //The passwords do not match.
        //Set the color to the bad color and
        //notify the user.
        pass2.css({"background":badColor});
        message.css({"color":badColor});
        message.html("Passwords Do Not Match!");
		return "0";
    }
}  
function checkCurrentPass(){
		var message = jQuery('#confirmMessage');
		//Set the colors we will be using ...
		var goodColor = "#66cc66";
		var badColor = "#ff6666";

		var currentpass = jQuery('#currentpass').val();
		jQuery.ajax({
			type: 'POST',   // Adding Post method
			url: ajaxurl, // Including ajax file
			data: {"action": "check_pass", "currentpass":currentpass}, // Sending data dname to post_word_count function.
			success: function(data){ // Show returned data using the function.
				console.log(data);
				if(data==1){
					jQuery('#currentpass').css({"background":goodColor});
			        message.html("");
					error= "0"
				}else{
					jQuery('#currentpass').css({"background":badColor});
			        message.css({"color":badColor});
			        message.html("Current Password Incorrect!");
					error= "1"
				}
				return data;
			}
		});
}

jQuery( window ).resize(function() {
    //console.log(jQuery(window).width());
    if(window.location.href.indexOf("my-profile") > -1) {
       jQuery('.product-rectangle').css("width",  "40%")
    }
    tWidth = jQuery('.product-rectangle').width();
    if(tWidth < 46)
            tWidth = 159;
    vHeight = tWidth * 1.4;
    if(vHeight > 200)
        vHeight = 200;
    if(jQuery(window).width() < 752 && jQuery(window).width() > 415)
        vHeight = 250;
    jQuery('.product-rectangle').css("height",  vHeight + "px");
tWidth = jQuery('.product-rectangle2').width();
    if(tWidth < 46)
            tWidth = 159;
    vHeight = tWidth * 1.4;
    if(vHeight > 200)
        vHeight = 200;
    if(jQuery(window).width() < 752 && jQuery(window).width() > 415)
        vHeight = 250;
    jQuery('.product-rectangle2').css("height",  vHeight + "px");
});

var the_id;
jQuery(document).ready(function(){



	//jQuery('#wtbutton').removeAttr("href")
                jQuery('#wtbutton').click(function(){
                    jQuery('#wdiv').show();
                });
                jQuery('#cancel-weight').click(function(){
                    jQuery('#wdiv').hide()
                })
                
                
                if(window.location.href.indexOf("my-profile") > -1) {
                    jQuery('.product-rectangle').css("width",  "40%");
                 }
                tWidth = jQuery('.product-rectangle').width();
                if(tWidth < 46)
                        tWidth = 159;
                vHeight = tWidth * 1.4;
                if(vHeight > 200)
                        vHeight = 200;
                if(jQuery(window).width() < 752 && jQuery(window).width() > 415)
                        vHeight = 250;                    
                jQuery('.product-rectangle').css("height",  vHeight + "px");
                tWidth = jQuery('.product-rectangle2').width();
                if(tWidth < 46)
                    tWidth = 159;
                    vHeight = tWidth * 1.4;
                if(vHeight > 200)
                    vHeight = 200;
                if(jQuery(window).width() < 752 && jQuery(window).width() > 415)
                    vHeight = 250;
                jQuery('.product-rectangle2').css("height",  vHeight + "px");
                
                
                
                
		jQuery(".remove-item").bind("click", function(){
			jQuery("#popup_remove_item input#id_journal").val(jQuery(this).attr('rem-id'));
			jQuery("#popup_remove_item").fadeIn("fast");
			the_id=jQuery("#popup_remove_item input#id_journal").val();
			
		})
		jQuery('.remove_item_btn').bind('click', function(){
//			var the_id=$(this).attr('rem-id');

			jQuery.ajax({
				url: ajaxurl,
				data: {
					'action': 'remove_journal',
					'id': the_id
				},
				success:function(data){
						jQuery('.white-box-journal1 #journalid'+the_id).fadeOut('fast', function(){
							jQuery("#popup_remove_item").fadeOut("fast");
						});
						jQuery('.white-box-journal2 #journalid'+the_id).fadeOut('fast', function(){
							jQuery("#popup_remove_item").fadeOut("fast");
						});
						jQuery('#journalid'+the_id).fadeOut('fast', function(){
							jQuery("#popup_remove_item").fadeOut("fast");
						});
						console.log('removed '+data)
						console.log(the_id)
				},
				error:function(error){
					console.log(error)
				}
				})
		})
	jQuery(".submit_journal").click(function(){
		var thejournal = jQuery("textarea[name='thejournal_lesson']").val();
		var userid = jQuery("input[name='userid']").val();
		var lesson = jQuery("input[name='lesson']").val();
		if(thejournal !='')
                {
                    jQuery.ajax({
                            type: 'POST',   // Adding Post method
                            url: ajaxurl, // Including ajax file
                            data: {"action": "process_post_ajax", "thejournal":thejournal, "userid":userid, "lesson":lesson}, // Sending data dname to post_word_count function.
                            success: function(data){ // Show returned data using the function.
                                    jQuery("#journal_entry_added").css({"display":"block"});
                                    jQuery("textarea[name='thejournal_lesson']").val("");

                                    console.log(data)
                            }
                    });
                }
	});
        
 jQuery("#update-weight").click(function(){
		var sw = jQuery("#start_w").val();
                var tw = jQuery("#target_w").val();
                var w1 = jQuery("#lvl1").val();
                var w2 = jQuery("#lvl2").val();
                var w3 = jQuery("#lvl3").val();
                var w4 = jQuery("#lvl4").val();
                var w5 = jQuery("#lvl5").val();
                var w6 = jQuery("#lvl6").val();
                var w7 = jQuery("#lvl7").val();
                var w8 = jQuery("#lvl8").val();
                var w9 = jQuery("#lvl9").val();
                var w10 = jQuery("#lvl10").val();
                var w11 = jQuery("#lvl11").val();
                var ew = jQuery("#end_w").val();
		var id = jQuery("#ifsid").val();
                var myRadio = $('input[name=wunit]');
                var wun = myRadio.filter(':checked').val();
		jQuery.ajax({
			type: 'POST',   // Adding Post method
			url: ajaxurl, // Including ajax file
			data: {"action": "process_weight_ajax", "ifsid":id, "startw":sw, "targetw":tw, "lvl1w":w1, "lvl2w":w2, "lvl3w":w3, 
                            "lvl4w":w4, "lvl5w":w5, "lvl6w":w6, "lvl7w":w7, "lvl8w":w8, "lvl9w":w9, "lvl10w":w10, "lvl11w":w11, "endw":ew, "wun":wun }, // Sending data dname to post_word_count function.
			success: function(data){ // Show returned data using the function.
				jQuery("#wdiv").hide();
				console.log(data)
			}
		});
	});

	jQuery("#newpass2").bind("keyup", function(){
		checkPass(); 
		return false;
	})
	jQuery("#currentpass").bind("blur", function(){
		checkCurrentPass();
	})
	/*jQuery("#pass_sub a.submit-pass").bind("click", function(e){
		jQuery.post('/members/my-profile/', jQuery('#changepass_form').serialize(),function(){
			location.reload();
		})
	})*/
	jQuery(document).on("click","#pass_sub a.submit-pass", function(e){
		jQuery.post('/my-profile/', jQuery('#changepass_form').serialize(),function(){
			location.reload();
		})
		/*jQuery("#changepass_form").submit();
		jQuery(".memberium_change_pass input[name='submit']").trigger("click")*/
	})
	var count_play=0;
	var count_dload=0;
	var pathArray = window.location.pathname.split( '/' );
	setTimeout(function(){
		jQuery('.mejs-button button').click(function(){
			if(count_play==0){
				if(pathArray[2] == 'lessons'){
					ga('send', 'event', 'WTGWL Audio', 'Audio Play',pathArray[3], 1);
				}
				count_play=1;
			}
                        //Adding Points on Lessons Audio Play
                        var entry = jQuery('#aref').val();
                        jQuery('#apoi').val('0');
                        jQuery.ajax({
                            type: 'POST',   // Adding Post method
                            url: ajaxurl, // Including ajax file
                            data: {"action": "process_points_ajax", "ref":"Play_Audio", "entry":entry}, // Sending data dname to post_word_count function.
                            success: function(data){ // Show returned data using the function.
                                    console.log(data);
                                        if(data=="Points Added!")
                                            Notify("Points Earned!");   
                            }
                        });
    	}) 
		jQuery('.sc_fancy_player_container').parent().find("> a").click(function(){
			if(count_dload==0){
				if(jQuery(this).text("Download This Audio")){
						if(pathArray[2] == 'lessons'){
							ga('send', 'event', 'WTGWL Audio', 'Audio Download', pathArray[3], 1);
						}
				}
				count_dload=1;
			}

    	})
	},1000)
	jQuery("#back-overview").clone().appendTo(".btn-left");
	jQuery(".form_mark-completed").clone().appendTo(".btn-right");
        
        jQuery('.video-box').mousedown(function(e) {
            var entry = jQuery('#vref').val();
                jQuery('#vpoi').val('0');
                jQuery.ajax({
                            type: 'POST',   // Adding Post method
                            url: ajaxurl, // Including ajax file
                            data: {"action": "process_points_ajax", "ref":"Video_Play", "entry":entry}, // Sending data dname to post_word_count function.
                            success: function(data){ // Show returned data using the function.
                                    console.log(data);
                                        if(data=="Points Added!")
                                        Notify("Points Earned!");
                            }
                    });
        });
        
        
        jQuery('#achieversmyminfo').click(function(e) {
            if(mymcomm != 1)
            {
                tag(8975);
                Notify("Points Earned!");
                
            }
        });
        

        jQuery('.linkOncePoints').click(function(e) {
            var entry = jQuery('#lref').val();
            var vtype = '1t';
                jQuery('#lpoi').val('0');
                jQuery.ajax({
                            type: 'POST',   // Adding Post method
                            url: ajaxurl, // Including ajax file
                            data: {"action": "process_points_ajax", "ref":"Link_Click", "entry":entry, "vtype":vtype}, // Sending data dname to post_word_count function.
                            success: function(data){ // Show returned data using the function.
                                    console.log(data);
									if(data=="Points Added!")
                                        Notify("Points Earned!");
                            }
                    });
        });


        jQuery('.10PointsLink').click(function(e) {
            var points = 10;
            var entry = 'Clicked on the 10 points Link';
                jQuery.ajax({
                            type: 'POST',   // Adding Post method
                            url: ajaxurl, // Including ajax file
                            data: {"action": "process_points_ajax", "ref":"Link_Click", "entry":entry}, // Sending data dname to post_word_count function.
                            success: function(data){ // Show returned data using the function.
                                    console.log(data);
									if(data=="Points Added!")
                                        Notify("Points Earned!");
                            }
                    });
        });
        
        
        jQuery('.p4pclass').click(function(e) {
                jQuery.ajax({
                            type: 'POST',   // Adding Post method
                            url: ajaxurl, // Including ajax file
                            data: {"action": "add_program_4_points", "ref":"Add_Program", "entry": jQuery(this).attr('tag')}, // Sending data dname to post_word_count function.
                            success: function(data){ // Show returned data using the function.
                                    console.log(data);
                            }
                    });
            });
        
     

//ACCESS TO WISTIA API //
     /*   window._wq = window._wq || [];
        _wq.push({ "_all": function(video) {
            console.log("This will run for every video on the page. Right now I'm on this one:", video);
            video.bind("play", function() {
                var entry = jQuery('#vref').val();
                var vtype = jQuery('#vtype').val();
                    jQuery('#vpoi').val('0');
                    jQuery.ajax({
                                type: 'POST',   // Adding Post method
                                url: ajaxurl, // Including ajax file
                                data: {"action": "process_points_ajax", "ref":"Video_Play", "entry":entry, "vtype":vtype}, // Sending data dname to post_word_count function.
                                success: function(data){ // Show returned data using the function.
                                        console.log(data)
                                }
                        });
            });
        }});*/








       
});
jQuery(document).on("change","#PlayBackRate", function() {
    window._wq = window._wq || [];
        _wq.push({ "_all": function(video) {
            video.play();
            video.playbackRate(jQuery("#PlayBackRate").val());
        }});
});
var Mobile = navigator.userAgent.indexOf('Mobile') >= 0; 
var clickEvent = Mobile ? 'touchend' : 'click';
var touchMoving = false;
if (Mobile)
{  
	jQuery(document).on("touchmove", function(){
		 touchMoving = true;
	})
	
	jQuery(document).on("touchend", function(){
		 touchMoving = false;
	})

} 

jQuery(document).bind('ready', function() 
{
  jQuery('#lessons_list a').bind(clickEvent, function()
  {
    if (touchMoving) {
		touchMoving = false;
		return false;
	}else{
		jQuery(this).trigger("click")
	}
  });
  jQuery(".circle-big").wrapAll("<div class='circles-bag' />")
  if(jQuery("aside.sidebar").is(":empty")){
  	jQuery("aside.sidebar").parent().removeClass("content-sidebar-wrap");
	jQuery("aside.sidebar").remove();
  }
  if(jQuery("aside.sidebar").length == 0){
  	jQuery(".content-sidebar-wrap").removeClass("content-sidebar-wrap")
  }
 // jQuery("#journal_form .button-medium a").removeAttr("onclick")
})
jQuery(document).on("click","#journal_form .button-medium a.submit_journal_pr", function(){
	if(jQuery("#journal_form textarea").val()!='')
		jQuery('#journal_form').submit();
	else{
		jQuery('#not_available center').text("Text area is empty!");
		jQuery('#not_available').show();
	}
})
/*jQuery(document).on("touchend", ".mejs-button", function(){
	jQuery(this).find("button").trigger("click");
})*/
jQuery(document).ready(function(){
	jQuery(".update_fb_name a").removeAttr("href");
	jQuery("#change_email_1").submit(function(event){
		if(jQuery("input[name='email1']").val() != jQuery("input[name='email2']").val()){
			event.preventDefault();
			jQuery("#popup_error_email").show();
		}
	})
        
        jQuery(document).on(clickEvent,".flipcard-close",function(e){
		if(jQuery(e.target).not(".flipcard-zoom-btns a") && jQuery(e.target).not("p")){
			jQuery('.popup-explore').fadeOut()
		}
	})
})
jQuery(document).on("keyup","input.fb_name_set", function(){
	if(jQuery(this).val()!=''){
		jQuery(".update_fb_name a").attr({"href":"https://www.facebook.com/groups/344601295735066/"})
	}
})
jQuery(document).on("click", ".update_fb_name a", function(ev){
	if(jQuery(".fb_name_set").val()!=''){
	jQuery.ajax({
		type: 'POST',   // Adding Post method
		url: ajaxurl, // Including ajax file
		data: {"action": "set_facebook_name", "fb_name_set":jQuery(".fb_name_set").val() }, // Sending data dname to post_word_count function.
		success: function(data){ // Show returned data using the function.
			console.log(data)
			jQuery(".popup").hide();
			jQuery(".popup#facebook_name_updated center").text("Facebook Name Updated!")
			jQuery(".popup#facebook_name_updated").css({"display":"block"})
			__gaTracker('send', 'event', 'outbound-article', 'https://www.facebook.com/groups/344601295735066/', 'Enter');
			jQuery("#community-box .button-big a").attr({"href":"https://www.facebook.com/groups/344601295735066/"});
			jQuery("#myfacebookname").remove();

		}
	});
	}else{
			ev.preventDefault();
			jQuery(".popup#facebook_name_updated center").text("Facebook Name field is empty!");
			jQuery(".popup#facebook_name_updated").css({"display":"block"});
			return false;
	}

})  
/*Restrict input numbers*/
$(function() {
  $('.weight-form').on('keydown', 'input[type="text"]', function(e){-1!==$.inArray(e.keyCode,[46,8,9,27,13,110,190])||/65|67|86|88/.test(e.keyCode)&&(!0===e.ctrlKey||!0===e.metaKey)||35<=e.keyCode&&40>=e.keyCode||(e.shiftKey||48>e.keyCode||57<e.keyCode)&&(96>e.keyCode||105<e.keyCode)&&e.preventDefault()});
})


jQuery(document).on(clickEvent, ".show_welcome",function(){
	jQuery(".welcome-contents").fadeIn('fast');
	jQuery(".hide_welcome").show();
	jQuery(".show_welcome").hide();
});
jQuery(document).on(clickEvent, ".hide_welcome",function(){
	jQuery.ajax({
		type: 'POST',   // Adding Post method
		url: ajaxurl, // Including ajax file
		data: {"action": "hide_welcome_section"}, // Sending data dname to post_word_count function.
		success: function(data){ // Show returned data using the function.
			console.log(data);
		}
	});

	jQuery(".welcome-contents").fadeOut('fast');
	jQuery(".hide_welcome").hide();
	jQuery(".show_welcome").show();
});
jQuery(document).on(clickEvent, ".show_welcome-hia",function(){
	jQuery(".welcome-contents").fadeIn('fast');
	jQuery(".hide_welcome-hia").show();
	jQuery(".show_welcome-hia").hide();
});
jQuery(document).on(clickEvent, ".hide_welcome-hia",function(){
	jQuery.ajax({
		type: 'POST',   // Adding Post method
		url: ajaxurl, // Including ajax file
		data: {"action": "hide_welcome_section", "type":"hia"}, // Sending data dname to post_word_count function.
		success: function(data){ // Show returned data using the function.
			console.log(data);
		}
	});

	jQuery(".welcome-contents").fadeOut('fast');
	jQuery(".hide_welcome-hia").hide();
	jQuery(".show_welcome-hia").show();
});


jQuery(document).on(clickEvent, ".hide_bonus_section",function(){
	var id_lesson=jQuery(".content-bonus-available").attr("id")
	jQuery.ajax({
		type: 'POST',   // Adding Post method
		url: ajaxurl, // Including ajax file
		data: {"action": "hide_welcome_section_bonus", "type":"available_bonus_"+id_lesson}, // Sending data dname to post_word_count function.
		success: function(data){ // Show returned data using the function.
			console.log(data);
		}
	});

	jQuery(".content-bonus-available").slideUp();
	jQuery(".hide_bonus_section").hide();
	jQuery(".show_bonus_section").show();
});
jQuery(document).on(clickEvent, ".show_bonus_section",function(){
	var id_lesson=jQuery(".content-bonus-available").attr("id")
	jQuery.ajax({
		type: 'POST',   // Adding Post method
		url: ajaxurl, // Including ajax file
		data: {"action": "show_welcome_section_bonus", "type":"available_bonus_"+id_lesson}, // Sending data dname to post_word_count function.
		success: function(data){ // Show returned data using the function.
			console.log(data);
		}
	});

	jQuery(".content-bonus-available").slideDown();
	jQuery(".hide_bonus_section").css({"display":"inline-block"});
	jQuery(".show_bonus_section").hide();
});




function printContent(el){
	var restorepage = document.body.innerHTML;
	var printcontent = "<html><head><style>body html * { background-color: #ffffff; color: #000000;}img {-webkit-print-color-adjust:exact;}</style></head><body style=\"width: 100%;\"><img src=\"http://beta.myneurogym.com/members/wp-content/themes/genesis-ng/images/logo_1.png\" style=\"margin-left: 25px;\"><span style=\"font-family: Open Sans; font-size: 30px; font-weight: 400; text-align: center; margin-left: 30%;\"> Journal Entry</span><br />";
        printcontent = printcontent + "<hr style=\"width: 100%; height: 1px; border: 1px solid #000000;\" /><div style=\"margin-left: 25px;\"><span style=\"font-family: Open Sans; font-size: 20px; font-weight: 400;\">Date: &nbsp;</span>"+jQuery('#'+el).val()+"</body></html>";
	document.body.innerHTML = printcontent;
	console.log(printcontent);
        window.print();
	document.body.innerHTML = restorepage;
}
jQuery(document).on(clickEvent, ".slideToggle",function(){
	jQuery(".mejs-controls button").trigger("mouseenter");
	setTimeout(function(){jQuery(document).resize();}, 500)
  jQuery('a').each(function(){
   if(jQuery(this).attr("href")=='#not'){
    jQuery(this).css({'color':'rgba(255,255,255,.5)'})
   }
  }) 
})

jQuery(document).on(clickEvent, ".wp-playlist-caption, .download-item",function(event){
   if(jQuery(this).attr("href")=='#not'){
     event.preventDefault();
     jQuery('#not_available').show();
   }
   
})

function vLibrary(showbox) {
    jQuery('#congrats').hide();
    jQuery('#congrats-space').show();
    jQuery('li').removeClass("current_page_item");
    jQuery('#li_'+showbox).addClass("current_page_item");
    jQuery('#li_'+showbox+'_m').addClass("current_page_item");
   
    if(showbox == 'all')
    {
        jQuery('.video-big-box').show();
        jQuery('#vl_Welcome').hide();
        jQuery('.video-clear').show();
        jQuery('.video-clear-welcome').hide();
    }
    else
    {
        jQuery('.video-big-box').hide();
        jQuery('.video-clear').hide();
        jQuery('#'+showbox+'_clear').show();
        jQuery('#'+showbox).show();
    }
}

function tag(tag){
		jQuery.ajax({
			type: 'POST',   // Adding Post method
			url: ajaxurl, // Including ajax file
			data: {"action": "apply_tag", "apply_tag":"true", "tag_id":tag}, // Sending data dname to post_word_count function.
			success: function(data){ // Show returned data using the function.
				console.log(data);
			}
		});
}

var fb_community_url=jQuery("a.community_button_lets").attr("href");
jQuery(document).ready(function(){
	jQuery("a.community_button_lets").removeAttr("onClick");
	
})

jQuery(document).on("click", "a.community_button_lets", function(ev){
	if(jQuery("#fb_name").val()!=''){
		jQuery.ajax({
			type: 'POST',   // Adding Post method
			url: ajaxurl, // Including ajax file
			data: {"action": "community_page_set_fb_name", "fb_name":jQuery("#fb_name").val() }, // Sending data dname to post_word_count function.
			success: function(data){ // Show returned data using the function.
				console.log(data)
			}
		});
	}else{
		//ev.preventDefault();
		//jQuery(".popup#not_available_fb").show();
	}

})  

function showTry (program) {
    jQuery(".popup-explore").hide();
    jQuery("#popup-" +program + "-trial").show();
}
function startTrial (program) {
    console.log(program);
    if(program == 'wtgwl')
    {
        tag = 8474;
        tag2 = 8537;
        url = '/lessons/wtgwl310/';
        
    } else if(program == 'wtgm') 
    {
        tag = 6112;
        tag2 = 8535;
        url = '/lessons/wtgm311/';
        
    } else if(program == 'hia')
    {
        tag = 7380;
        tag2 = 8539;
        url = '/having-it-all-3/';

    }  else if(program == 'cobs')
    {
        tag = 8204;
        tag2 = 8541;
        url = '/cloningvs/';

    }else if(program == 'wtgf')
    {
        tag = 9669;
        tag2 = 9669;
        url = '/lessons/wtgf321/';

    }
    //htgmilt = 7396
    jQuery.ajax({
                type: 'POST',   // Adding Post method
                url: ajaxurl, // Including ajax file
                data: {"action": "apply_tag", "apply_tag":"true", "tag_id":tag, "program":"Trial", "programEnroll":program}, // Sending data dname to post_word_count function.
                success: function(data){ // Show returned data using the function.
                }
		});

        jQuery.ajax({
                type: 'POST',   // Adding Post method
                url: ajaxurl, // Including ajax file
                data: {"action": "apply_tag", "apply_tag":"true", "tag_id":tag2, "program":"Trial", "programEnroll":program}, // Sending data dname to post_word_count function.
                success: function(data){ // Show returned data using the function.
                        window.location.href = url;
                }
		});
    
}
function apply_tag_explore(program, button){
	if(program == 'mym' && button=='buy'){tag = 8675;} 
	else if(program == 'mym' && button=='try'){tag = 8673;} 
	else if(program == 'mym' && button=='learn'){tag = 8671;} 
	else if(program == 'cobs' && button=='buy'){tag = 8669;} 
	else if(program == 'cobs' && button=='try'){tag = 8667;} 
	else if(program == 'cobs' && button=='learn'){tag = 8665;} 
	else if(program == 'htgmdilt' && button=='buy'){tag = 8663;} 
	else if(program == 'htgmdilt' && button=='try'){tag = 8661;} 
	else if(program == 'htgmdilt' && button=='learn'){tag = 8659;} 
	else if(program == 'hia' && button=='buy'){tag = 8657;} 
	else if(program == 'hia' && button=='try'){tag = 8655;} 
	else if(program == 'hia' && button=='learn'){tag = 8653;} 
	else if(program == 'wtgwl' && button=='buy'){tag = 8651;} 
	else if(program == 'wtgwl' && button=='try'){tag = 8649;} 
	else if(program == 'wtgwl' && button=='learn'){tag = 8647;} 
	else if(program == 'wtgb' && button=='buy'){tag = 8645;} 
	else if(program == 'wtgb' && button=='try'){tag = 8643;} 
	else if(program == 'wtgb' && button=='learn'){tag = 8641;} 
	else if(program == 'wtgf' && button=='buy'){tag = 8639;} 
	else if(program == 'wtgf' && button=='try'){tag = 8637;} 
	else if(program == 'wtgf' && button=='learn'){tag = 8635;} 
	else if(program == 'wtgm' && button=='buy'){tag = 8633;} 
	else if(program == 'wtgm' && button=='try'){tag = 8631;} 
	else if(program == 'wtgm' && button=='learn'){tag = 8629;} 
	jQuery.ajax({
			type: 'POST',   // Adding Post method
			url: ajaxurl, // Including ajax file
			data: {"action": "apply_tag", "apply_tag_button":"true", "tag":tag}, // Sending data dname to post_word_count function.
			success: function(data){ // Show returned data using the function.
				console.log(data)
			}
	});
}
jQuery(document).on(clickEvent, "#popup-mym .flipcard-zoom-btns a.popup-bt-buy",function(event){
	apply_tag_explore('mym', "buy");
})
jQuery(document).on(clickEvent, "#popup-mym .flipcard-zoom-btns a.popup-bt-try",function(event){
	apply_tag_explore('mym', "try");
})
jQuery(document).on(clickEvent, "#popup-mym .flipcard-zoom-btns a.popup-bt-learn",function(event){
	apply_tag_explore('mym', "learn");
})
jQuery(document).on(clickEvent, "#popup-cobs .flipcard-zoom-btns a.popup-bt-buy",function(event){
	apply_tag_explore('cobs', "buy");
})
jQuery(document).on(clickEvent, "#popup-cobs .flipcard-zoom-btns a.popup-bt-try",function(event){
	apply_tag_explore('cobs', "try");
})
jQuery(document).on(clickEvent, "#popup-cobs .flipcard-zoom-btns a.popup-bt-learn",function(event){
	apply_tag_explore('cobs', "learn");
})
jQuery(document).on(clickEvent, "#popup-htgmdilt .flipcard-zoom-btns a.popup-bt-buy",function(event){
	apply_tag_explore('htgmdilt', "buy");
})
jQuery(document).on(clickEvent, "#popup-htgmdilt .flipcard-zoom-btns a.popup-bt-try",function(event){
	apply_tag_explore('htgmdilt', "try");
})
jQuery(document).on(clickEvent, "#popup-htgmdilt .flipcard-zoom-btns a.popup-bt-learn",function(event){
	apply_tag_explore('htgmdilt', "learn");
})
jQuery(document).on(clickEvent, "#popup-hia .flipcard-zoom-btns a.popup-bt-buy",function(event){
	apply_tag_explore('hia', "buy");
})
jQuery(document).on(clickEvent, "#popup-hia .flipcard-zoom-btns a.popup-bt-try",function(event){
	apply_tag_explore('hia', "try");
})
jQuery(document).on(clickEvent, "#popup-hia .flipcard-zoom-btns a.popup-bt-learn",function(event){
	apply_tag_explore('hia', "learn");
})
jQuery(document).on(clickEvent, "#popup-wtgwl .flipcard-zoom-btns a.popup-bt-buy",function(event){
	apply_tag_explore('wtgwl', "buy");
})
jQuery(document).on(clickEvent, "#popup-wtgwl .flipcard-zoom-btns a.popup-bt-try",function(event){
	apply_tag_explore('wtgwl', "try");
})
jQuery(document).on(clickEvent, "#popup-wtgwl .flipcard-zoom-btns a.popup-bt-learn",function(event){
	apply_tag_explore('wtgwl', "learn");
})
jQuery(document).on(clickEvent, "#popup-wtgb .flipcard-zoom-btns a.popup-bt-buy",function(event){
	apply_tag_explore('wtgb', "buy");
})
jQuery(document).on(clickEvent, "#popup-wtgb .flipcard-zoom-btns a.popup-bt-try",function(event){
	apply_tag_explore('wtgb', "try");
})
jQuery(document).on(clickEvent, "#popup-wtgb .flipcard-zoom-btns a.popup-bt-learn",function(event){
	apply_tag_explore('wtgb', "learn");
})
jQuery(document).on(clickEvent, "#popup-wtgf .flipcard-zoom-btns a.popup-bt-buy",function(event){
	apply_tag_explore('wtgf', "buy");
})
jQuery(document).on(clickEvent, "#popup-wtgf .flipcard-zoom-btns a.popup-bt-try",function(event){
	if(jQuery(this).text()=='Try')
	apply_tag_explore('wtgf', "try");
})
jQuery(document).on(clickEvent, "#popup-wtgf .flipcard-zoom-btns a.popup-bt-learn",function(event){
	apply_tag_explore('wtgf', "learn");
})
jQuery(document).on(clickEvent, "#popup-wtgm-exp .flipcard-zoom-btns a.popup-bt-buy",function(event){
	apply_tag_explore('wtgm', "buy");
})
jQuery(document).on(clickEvent, "#popup-wtgm-exp .flipcard-zoom-btns a.popup-bt-try",function(event){
	apply_tag_explore('wtgm', "try");
})
jQuery(document).on(clickEvent, "#popup-wtgm-exp .flipcard-zoom-btns a.popup-bt-learn",function(event){
	apply_tag_explore('wtgm', "learn");
})
var video_position,video_id, video_div;
var isMobile = false; //initiate as false
jQuery(document).on(clickEvent, ".video-library-box",function(event){
// device detection
if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|ipad|iris|kindle|Android|Silk|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i.test(navigator.userAgent) 
    || /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(navigator.userAgent.substr(0,4))) isMobile = true;
	video_position=jQuery(document).scrollTop + 10;
	video_id=jQuery(this).attr("id");
	//WTGW3.1 Video Library Popup
    if(jQuery(window).width() <= 580 || isMobile){
		video_div = jQuery("#popup-"+video_id);
		video_div.style('top', video_position+"px", 'important');
	}
})
function _preventDefault(e){
	 e.preventDefault();
}
jQuery(document).on(clickEvent, ".show_upgrade",function(event){
    var href_product = jQuery(this).find('a').attr('href');
	jQuery('.no_thanks_bt').attr({'href':href_product})
});
jQuery(document).on(clickEvent, ".dont_show_again", function(event){
	if(jQuery(this).is(':checked')){
		jQuery.ajax({
				type: 'POST',   // Adding Post method
				url: ajaxurl, // Including ajax file
				data: {"action": "dont_show_again", "dont_show_again_popup":jQuery(this).val()}, // Sending data dname to post_word_count function.
				success: function(data){ // Show returned data using the function.
						console.log(data)
				}
		});
	}
})
/*jQuery(document).ready(function(){
	var d = new Date();
	var timezone = d.getTimezoneOffset();
	timezone = timezone /-60;
	var is_home=jQuery("body").hasClass("home");
	if(window.location.href.indexOf("my-profile") > -1 || window.location.href.indexOf("journal") > -1 || is_home){
		jQuery.ajax({
				type: 'POST',   // Adding Post method
				url: ajaxurl, // Including ajax file
				data: {"action": "setTimeZoneName","tz":timezone}, // Sending data dname to post_word_count function.
				success: function(data){ // Show returned data using the function.
					console.log(data);
				}
		});
	}

})*/
var scrollTop_popup;
jQuery(document).on(clickEvent, ".show_upgrade a", function(){
	if(jQuery(window).height() <= 320 || jQuery(window).width() == 568){
		scrollTop_popup=jQuery("body").scrollTop();
		jQuery(".upgrade_popup").css({"margin-top":scrollTop_popup, "position":"absolute"});
	}
})
