<?php
/**
 * Plugin Name: BWW Lesson Editor Plugin
 * Plugin URI: http://www.bestworldsweb.com
 * Description: Functions and Procedures for the Myneurogym Member site
 * Version: 1.0.0
 * Author: Best Worlds Web
 * Author http://www.bestworldsweb.com
 * License: GPL2
 */

add_action('admin_menu', 'lesson_editor_menu');
add_action( 'admin_enqueue_scripts', 'bww_lesson_editor_admin_init' );

function bww_lesson_editor_admin_init() {
    wp_enqueue_script( 'bww-js-lesson-admin', plugins_url( 'bww-lesson-editor/js/scripts.js' ),'jquery');
}

function lesson_editor_menu(){
	  add_menu_page('Lesson Editor','Lesson Editor','administrator','lesson-editor','lesson_editor_page','dashicons-admin-generic');
}

//add_action('admin_init', 'lesson_editor_page');
function lesson_editor_page(){
    global $title;
	if($_POST['submit']){
		global $wpdb;
		$lessons_meta['_journal_questions'] = $_POST['journal_populated'];
		$lessons_meta['_journal_questions_link'] = $_POST['journal_assignment'];
		
		$video_code=$_POST['level_video'];
		if(strpos($video_code,"playerPreference=html5") === false && strpos($video_code,"videoFoam=true") !== false){
			$video_code=explode("videoFoam=true",$video_code);
			$video_code=$video_code[0]." videoFoam=true playerPreference=html5".$video_code[1];
		}
		// Add values of $lessons_meta as custom fields
		foreach ($lessons_meta as $key => $value) { // Cycle through the $lessons_meta array!
			if( $post->post_type == 'revision' ) return; // Don't store custom data twice
			$value = implode(',', (array)$value); // If $value is an array, make it a CSV (unlikely)
			if(get_post_meta($_POST['lesson'], $key, FALSE)) { // If the custom field already has a value
				update_post_meta($_POST['lesson'], $key, $value);
			} else { // If the custom field doesn't have a value
				add_post_meta($_POST['lesson'], $key, $value);
			}
			if(!$value) delete_post_meta($_POST['lesson'], $key); // Delete if blank
		}
		$lesson_table_count = $wpdb->get_var( "SELECT COUNT(*) FROM bww_lessons_table WHERE course = '".$_POST['course']."' AND lesson = '".$_POST['lesson']."'" );
		if($lesson_table_count>0){
			$wpdb->update( 
				'bww_lessons_table', 
				array( 
					'course' => $_POST['course'], 
					'lesson' => $_POST['lesson'],
					'title' => $_POST['level_title'],
					'video' => $video_code,
					'speed_selector' => $_POST['video_speed'],
					'video_title' => $_POST['level_video_title'],
					'video_author' => $_POST['level_video_author'],
					'audio_title' => $_POST['level_audio_title'],
					'audio' => $_POST['level_audio'],
					'about' => $_POST['about_level'],
					'journal_link' => $_POST['journal_assignment'],
					'journal_text' => $_POST['journal_populated'],
					'additional_resources_title' => $_POST['additional_resources']
				), 
				array( 				
					'course' => $_POST['course'], 
					'lesson' => $_POST['lesson']
				)
			);
		}else{
			$wpdb->insert( 
				'bww_lessons_table', 
				array( 
					'course' => (isset($_POST['course'])?$_POST['course']:''), 
					'lesson' => (isset($_POST['lesson'])?$_POST['lesson']:''),
					'title' => (isset($_POST['level_title'])?$_POST['level_title']:''),
					'video' => $video_code,
					'speed_selector' => (isset($_POST['video_speed'])?$_POST['video_speed']:''),
					'video_title' => (isset($_POST['level_video_title'])?$_POST['level_video_title']:''),
					'video_author' => (isset($_POST['level_video_author'])?$_POST['level_video_author']:''),
					'audio_title' => (isset($_POST['level_audio_title'])?$_POST['level_audio_title']:''),
					'audio' => (isset($_POST['level_audio'])?$_POST['level_audio']:''),
					'about' => (isset($_POST['about_level'])?$_POST['about_level']:''),
					'journal_link' => (isset($_POST['journal_assignment'])?$_POST['journal_assignment']:''),
					'journal_text' => (isset($_POST['journal_populated'])?$_POST['journal_populated']:''),
					'additional_resources_title' => (isset($_POST['additional_resources'])?$_POST['additional_resources']:'')				
				)
			);	
		}
		$course=$_POST['course'];
		$lesson=$_POST['lesson'];
		// Update post Title
		$new_title = array(
		  'ID'           => $lesson,
		  'post_title'   => $_POST['level_title']
		);
		wp_update_post( $new_title );
		$lesson_data = $wpdb->get_results( 'SELECT id FROM bww_lessons_table WHERE course = '.$course.' and lesson = '.$lesson, OBJECT );
		$wpdb->delete( 'bww_lessons_buttons', array( 'lesson_table_id' => $lesson_data[0]->id ) );
		foreach($_POST["additional_resources_url"] as $key=>$url){
			/*print_r($lesson_data[0]->id);
			echo"<br />";
			print_r($url);
			echo"<br />";
			print_r($_POST["additional_resources_btn"][$key]);
			echo"<br />";*/
			$wpdb->insert( 
				'bww_lessons_buttons', 
				array( 
					'id' => null, 
					'lesson_table_id' => $lesson_data[0]->id, 
					'url' => $url,
					'text' => $_POST["additional_resources_btn"][$key]
				)
			);
		}
	}
    print '<div class="wrap">';
    print "<h1>$title</h1>";
	print '<form action="" method="post">
            <div>
		<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Save">&nbsp;&nbsp;<a href="#" id="view_top" target="_blank" style="display: none;">View Lesson</a></p>		</div>
	<table class="form-table">
		<tbody>
			<tr>
				<th scope="row"><label for="course">Course</label></th>
				<td>
					'.courses_dropdown().'<br />
				<small>Select the course</small></td>
			</tr>
			<tr>
				<th scope="row"><label for="level">Level</label></th>
				<td>
					<select name="lesson" id="lesson_select"><option>-- Select the level --</option>
					</select><br />
				<small>Select the level</small></td>
			</tr>
			<tr>
			<tr>
				<th scope="row"><label for="level_title">Level Title</label></th>
				<td>
					<input type="text" name="level_title" id="level_title"  size="80" placeholder="level title"><br />
				<small>The text entered in this field will show up in the &lt;title&gt; tag so that the page name display correctly in the browser. Currently all pages just display as "NeuroGym."</small></td>
			</tr>
			<tr>
				<th scope="row"><label for="level_video_title">Level Video Title</label></th>
				<td>
					<input name="level_video_title" id="level_video_title" placeholder="Video title" size="80" type="text" /><br />
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="level_video_author">Level Video Author</label></th>
				<td>
					<input name="level_video_author" id="level_video_author" placeholder="Video author" size="80" type="text" /><br />
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="level_video">Level Video</label></th>
				<td>
					<textarea name="level_video" id="level_video" cols="80" rows="5" placeholder="Video &lt;code&gt;"></textarea><br />
					<small>This field will contain the code for our Wistia video players.</small><br />
					<label for="video_speed" class="checkbox_video" style="opacity:.5"><input disabled="disabled" type="checkbox" name="video_speed" value="1" /> Enable Playback Speed Selector?</label>
				</td> 
			</tr>
			<tr>
				<th scope="row"><label for="level_audio_title">Level Audio Title</label></th>
				<td>
					<input type="text" name="level_audio_title" id="level_audio_title" placeholder="Audio title" size="80"/ ><br />
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="level_audio">Level Audio</label></th>
				<td>
					<input type="text" name="level_audio" id="level_audio"  size="80" placeholder="&lt;MP3 audio file URL&gt;"><br />
				<small>This field will contain the .mp3 URL for the audio player.</small></td>
			</tr>
			<tr>
				<th scope="row"><label for="about_level">About this Level</label></th>
				<td>
					<textarea name="about_level" id="about_level" placeholder="about this level text" cols="80" rows="5"></textarea><br />
				<small>This field will contain all of the "About This Level" text.</small></td>
			</tr>
			<tr>
				<th scope="row"><label for="journal_assignment">Journal Assignment</label></th>
				<td>
					<input type="text" name="journal_assignment" id="journal_assignment"  size="80" placeholder="journal assignment link text"><br />
					<small>This field contains the text that will be used for the link that users click to pre-populate the journal assignment</small></td>
					</tr>
					<tr><th></th><td>
					<textarea name="journal_populated" id="journal_populated" placeholder="text that will be populated in the journal when the assignment link is clicked"></textarea><br />
				<small>This field contains the text that will be populated when the assignment link is clicked</small></td>
			</tr>
			<tr>
				<th scope="row"><label for="additional_resources">Additional Resources</label></th>
				<td>
					<input type="text" name="additional_resources" id="additional_resources"  size="80" placeholder="[existing wtgx_ar shortcode]"><br />
					<small>These fields contain the links and button text for the Additional Resources button</small>
				<br /><div style="width:660px">
					<input type="text" name="additional_resources_url[1]" id="additional_resources_url1"  style="width:49%" placeholder="button URL">
					<input type="text" name="additional_resources_btn[1]" id="additional_resources_btn1"  style="width:49%" placeholder="button text"><br />
					<input type="text" name="additional_resources_url[2]" id="additional_resources_url2"  style="width:49%" placeholder="button URL">
					<input type="text" name="additional_resources_btn[2]" id="additional_resources_btn2"  style="width:49%" placeholder="button text"><br />
					<input type="text" name="additional_resources_url[3]" id="additional_resources_url3"  style="width:49%" placeholder="button URL">
					<input type="text" name="additional_resources_btn[3]" id="additional_resources_btn3"  style="width:49%" placeholder="button text"><br />
					<input type="text" name="additional_resources_url[4]" id="additional_resources_url4"  style="width:49%" placeholder="button URL">
					<input type="text" name="additional_resources_btn[4]" id="additional_resources_btn4"  style="width:49%" placeholder="button text">
					<input type="text" name="additional_resources_url[5]" id="additional_resources_url5"  style="width:49%" placeholder="button URL">
					<input type="text" name="additional_resources_btn[5]" id="additional_resources_btn5"  style="width:49%" placeholder="button text">
				</div></th>
			</tr>
		</tbody>
	</table>
<div>
		<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Save">&nbsp;&nbsp;<a href="#" id="view_bottom" target="_blank" style="display: none;">View Lesson</a></p>		</div>
	</form>';
    print '</div>';
    if($_POST['submit']){
        echo '<script> jQuery(\'#course_select option[value="'.$_POST['course'].'"]\').attr("selected", "selected");</script>';
        $output ='<option>-- Select the level --</option>';
        $course=$_POST['course'];
        $lessons=learndash_get_course_lessons_list($course);
        foreach($lessons as $lesson){
                $lesson=$lesson['post'];
                $output .='<option value="'.$lesson->ID.'">'.$lesson->post_title.'</option>';
        }
        echo '<script>jQuery("#lesson_select").html(\''. $output .'\');'
                . 'jQuery(\'#lesson_select option[value="'.$lesson=$_POST['lesson'].'"]\').attr("selected", "selected");'
                . 'jQuery("#lesson_select").trigger("change");</script>';
    }
 }
 
function courses_dropdown(){
	$courses = get_pages("post_type=sfwd-courses");
	$output='<select name="course" id="course_select"><option>-- Select the course --</option>';
	foreach($courses as $course) { 
		$output .='<option value="'.$course->ID.'">'.$course->post_title.'</option>';
	}
	$output .='</select>';
	return $output;
}


function levels_dropdown(){
    if(isset($_POST['course_id']))
    {
		$output ='<option>-- Select the level --</option>';
		$course=$_POST['course_id'];
		$lessons=learndash_get_course_lessons_list($course);
		foreach($lessons as $lesson){
			$lesson=$lesson['post'];
			$output .='<option value="'.$lesson->ID.'">'.$lesson->post_title.'</option>';
		}
		echo $output;
	}
    die();
}
add_action('wp_ajax_levels_dropdown', 'levels_dropdown');
add_action('wp_ajax_nopriv_levels_dropdown', 'levels_dropdown');

function get_level_data(){
    if(isset($_POST['course_id']) && isset($_POST['lesson_id']))
    {
            $course=$_POST['course_id'];
            $lesson=$_POST['lesson_id'];
            $url = get_permalink($_POST['lesson_id']);
            global $wpdb;
            $array=array();
            $array['results'] = $wpdb->get_results( 'SELECT *, \''.$url.'\' as less_url FROM bww_lessons_table WHERE course = '.$course.' and lesson = '.$lesson, OBJECT );
	    echo json_encode($array['results'][0]);
	}
    die();
}
add_action('wp_ajax_get_level_data', 'get_level_data');
add_action('wp_ajax_nopriv_get_level_data', 'get_level_data');

function get_level_additional_resources(){
    if(isset($_POST['course_id']) && isset($_POST['lesson_id']))
    {
            $course=$_POST['course_id'];
            $lesson=$_POST['lesson_id'];
            global $wpdb;
            $array=array();
            $array['results'] = $wpdb->get_results( 'SELECT * FROM bww_lessons_table WHERE course = '.$course.' and lesson = '.$lesson, OBJECT );
            $results = $wpdb->get_results( 'SELECT * FROM bww_lessons_buttons WHERE lesson_table_id = '.$array['results'][0]->id, OBJECT );
	    echo json_encode($results);
	}
    die();
}
add_action('wp_ajax_get_level_additional_resources', 'get_level_additional_resources');
add_action('wp_ajax_nopriv_get_level_additional_resources', 'get_level_additional_resources');

function customlevel($atts) {
	global $post, $wpdb;
	$section=$atts[0];
	$lessons_table_select = $wpdb->get_row( "SELECT * FROM bww_lessons_table WHERE lesson = ".$post->ID, ARRAY_A );
	$id_lesson = $lessons_table_select;
	if($section=='additional_resources'){
		$additional_sc = $lessons_table_select;
		$additionals = $wpdb->get_results( "SELECT * FROM bww_lessons_buttons WHERE lesson_table_id = ".$id_lesson['id'], OBJECT );
		$output=do_shortcode($additional_sc['additional_resources_title']);
		
		foreach($additionals as $additional){
			if(!empty($additional->text))
				$output .='<a href="'.$additional->url.'" class="download-button" target="_blank">'.$additional->text.'</a><br />';
		}
	}elseif($section=='video'){
		$output = $lessons_table_select;
		$output_video_speed = $lessons_table_select;
		$video_code=$output[$section];
		if($output_video_speed['speed_selector'] == 1){
			$video_code="<div class='video_speed'>".$video_code."<div> <center>Speed up your experience&nbsp;&nbsp;<select id='PlayBackRate' style='color: black; margin-top: 10px; display:inline;'>
              <option></option>
              <option value='1'>1x</option>
              <option value='1.2'>1.2x</option>
              <option value='1.5'>1.5x</option>
              <option value='1.8'>1.8x</option>
              <option value='2.0'>2x</option>
            </select></center>
          </div></div>";
		}

		$output=$video_code;
	}elseif($section=='audio'){
		$url_audio=$lessons_table_select['audio'];
		if(strpos($url_audio,".mp3") === false)
			$url_audio=$url_audio.".mp3";
		$thispost = get_post($id);
		$level_number = $thispost->menu_order;
		if(strpos($_SERVER[REQUEST_URI], 'wtgwl')!==false || strpos($_SERVER[REQUEST_URI], 'wtgb')!==false) $listening_text='Listen to the <b>Level '.$level_number.'</b> Innercise audio every day for 7 days.';
		if(strpos($_SERVER[REQUEST_URI], 'wtgm')!==false) $listening_text='Listen to the <b>Level '.$level_number.'</b> Brain Retraining audio every day for 7 days.';
		if(strpos($_SERVER[REQUEST_URI], 'wtgf32')!==false) $listening_text='Listen to the <b>Level '.$level_number.'</b> Innercise&reg; audio every day for 10 days.';
		if(strpos($_SERVER[REQUEST_URI], 'wtgm32')!==false) $listening_text='Listen to the Innercise&#8482; Audio for 7 consecutive days';

		$output[$section]='<div id="audio-box" class="white-box-left h200">
        <h3>Step 2 of 2</h3>
        <div class="boxed-content">
            '.$listening_text.'
            <h4>'.$lessons_table_select['audio_title'].'</h4>
            '.do_shortcode('[sc_embed_player_template1 fileurl="'.$url_audio.'"]');
			$isMobileApp = identify_mobile_app();
			if(empty($isMobileApp)){
				$output[$section] .=' Headphones required!<br>';
			}
			if(fn_is_not_iphone()){
				if(strpos($_SERVER[REQUEST_URI], 'wtgm32')==false && strpos($_SERVER[REQUEST_URI], 'wtgm31')==false && strpos($_SERVER[REQUEST_URI], 'wtgwl4')==false && strpos($_SERVER[REQUEST_URI], 'wtgf32')==false && strpos($_SERVER[REQUEST_URI], 'wtgwl31')==false) {
					$output[$section] .='<br><a href="javascript:;" onclick="jQuery(\'#audio-box\').hide();jQuery(\'#audio-box-tool\').fadeIn()">Download Audio</a>';
				}
				if(strpos($_SERVER[REQUEST_URI], 'wtgm32')!==false) {
					if(empty($isMobileApp)){
						$output[$section] .='<br><a href="'.$url_audio.'">Download This Audio</a>';
					}
					if(!empty($isMobileApp)){
						$output[$section] .='<br><a href="'.$url_audio.'" id="audio_app_link">Download This Audio</a>';
					}
				}
				if(strpos($_SERVER[REQUEST_URI], 'wtgm31')!==false) {
					if(empty($isMobileApp)){
						$output[$section] .='<br><a href="'.$url_audio.'">Download This Audio</a>';
					}
					if(!empty($isMobileApp)){
						$output[$section] .='<br><a href="'.$url_audio.'" id="audio_app_link">Download This Audio</a>';
					}
				}
				if(strpos($_SERVER[REQUEST_URI], 'wtgwl4')!==false) {
					if(empty($isMobileApp)){
						$output[$section] .='<br><a href="'.$url_audio.'">Download This Audio</a>';
					}
					if(!empty($isMobileApp)){
						$output[$section] .='<br><a href="'.$url_audio.'" id="audio_app_link">Download This Audio</a>';
					}
				}
				if(strpos($_SERVER[REQUEST_URI], 'wtgf32')!==false) {
					if(empty($isMobileApp)){
						$output[$section] .='<br><a href="'.$url_audio.'">Download This Audio</a>';
					}
					if(!empty($isMobileApp)){
						$output[$section] .='<br><a href="'.$url_audio.'" id="audio_app_link">Download This Audio</a>';
					}
				}
				if(strpos($_SERVER[REQUEST_URI], 'wtgwl31')!==false) {
					if(empty($isMobileApp)){
						$output[$section] .='<br><a href="'.$url_audio.'">Download This Audio</a>';
					}
					if(!empty($isMobileApp)){
						$output[$section] .='<br><a href="'.$url_audio.'" id="audio_app_link">Download This Audio</a>';
					}
				}
			}
		$output[$section] .='</div>
     </div>
<!-- Audio Tooltip -->
 <div id="audio-box-tool" class="white-box-left h200" style="display: none; background: rgb(255, 178, 0);">
         <h3>Download Instructions</h3>
</center>';
			if(empty($isMobileApp)){
				$output[$section] .='Right-click <a href="'.$url_audio.'">';
			}
			if(!empty($isMobileApp)){
				$output[$section] .='Tap <a href="'.$url_audio.'" id="audio_app_link">';
			}
		$output[$section] .='<b>here</b></a> to download the audio file. <br><br> <i>Note: This feature may not be available directly on some mobile devices. In that case, download the audio to your computer first, then transfer it to your device for listening on-the-go!</i><br><br><center>
           <a href="javascript:" class="remove_href" onclick="jQuery(\'#audio-box\').fadeIn();jQuery(\'#audio-box-tool\').hide();">Got it!</a>
     </div>
<input type="hidden" id="vref" value="Watched '. $thispost->post_title .' video" />
<input type="hidden" id="aref" value="Listened '. $thispost->post_title .' audio" />
[isMobileApp]
<!-- Mobile Popup -->
<div class="popup" id="audio_down">
<center>
Your Audio is being Downloaded. Please check for an Arrow in the upper left corner.<br>
<a href="#" onclick="document.getElementById(\'audio_down\').style.display = \'none\';">Close</a>
</center>
</div>
[/isMobileApp]
';
            $output=do_shortcode($output[$section]);
	}else{
		$output = $lessons_table_select;
		//$output['results'][0]['audio']=do_shortcode($array['results'][0]['audio']);
		$output=$output[$section];
	}
    return stripslashes($output);
}
add_shortcode('customlevel', 'customlevel');
function fn_is_not_iphone(){
	$device = true;
	if(strpos($_SERVER["HTTP_USER_AGENT"],"iPhone") !== false) $device = false;
	return $device;
}

