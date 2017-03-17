<?php
/*
Plugin Name: BWW NeuroGym Plugin
Description: Functions and Procedures for the Myneurogym Member site
Version:     0.1
Author:      Best Worlds Web
Author URI:  http://www.bestworldsweb.com
*/
//require("F:/xampp/htdocs/betaneuro/neurogym/ajax-api/isdk.php");
require("/var/www/members/ajax-api/isdk.php");
add_action('wp_enqueue_scripts','bww_js_init');
add_action( 'admin_enqueue_scripts', 'bww_js_admin_init' );

function bww_js_init() {
    wp_enqueue_style( 'bww-cssa', plugins_url( 'bww-myn-plugin/css/styles.css' ),'',false,'all');
    wp_enqueue_style( 'bww-cssb', plugins_url( 'bww-myn-plugin/js/swiper/css/swiper.min.css' ),'',false,'all');
    wp_enqueue_script( 'bww-jsa', plugins_url( 'bww-myn-plugin/js/jquery.bxslider.js' ),'jquery',false,true);
    wp_enqueue_script( 'bww-jsb', plugins_url( 'bww-myn-plugin/js/jquery.easing.1.3.js' ),'jquery',false,true);
    wp_enqueue_script( 'bww-jsc', plugins_url( 'bww-myn-plugin/js/jquery.fitvids.js' ),'jquery',false,true);
    wp_enqueue_script( 'bww-jsd', plugins_url( 'bww-myn-plugin/js/scripts.js' ),'jquery',false,true);
    wp_enqueue_script( 'bww-jse', plugins_url( 'bww-myn-plugin/js/select2.js' ),'jquery',false,true);
    wp_enqueue_script( 'bww-jsg', plugins_url( 'bww-myn-plugin/js/swiper/js/swiper.min.js' ),'jquery',false,true);
    wp_enqueue_script('jquery-ui-core', 'http://code.jquery.com/ui/1.11.4/jquery-ui.js', 'jquery', false,true);

}


function bww_js_admin_init() {
    wp_enqueue_script( 'bww-jsadmin', plugins_url( 'bww-myn-plugin/js/select2.js' ),'jquery');
}

// Register "Journal" custom post type
function create_journal_post_type() 
{
    register_post_type( 'lesson_journal',
        array
        (
            'text' => array(
                'name' => __( 'Journals' ),
                'singular_name' => __( 'Journal' )
                ),
            'public' => false,
            'has_archive' => true,
            'exclude_from_search' => true,
            'show_ui' => false,
            'show_in_menu' => false,
            'show_in_nav_menus' => false,
            'show_in_admin_bar' => false,
            'taxonomies' => array('pots_tag'),
            'supports' => array('title', 'editor', 'author'),
        )
    );
}

add_action( 'init', 'create_journal_post_type' ); 
function bwwmynplugin_install() {
 
    // Trigger all our function 
    create_journal_post_type();
 
    // Clear the permalinks after the post type has been registered
    flush_rewrite_rules();
 
}
register_activation_hook( __FILE__, 'bwwmynplugin_install' );

function identify_mobile_app() {
    $isMobileApp = strpos($_SERVER["REQUEST_URI"], 'mobile-app') > 0 || strpos($_SERVER['HTTP_REFERER'], 'mobile-app') > 0 || (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == "com.myneurogym.wtgwl") || (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == "com.myneurogym.wtgm") ||
            (strpos($_SERVER['HTTP_USER_AGENT'], 'Mobile/') !== false) && (strpos($_SERVER['HTTP_USER_AGENT'], 'Safari/') == false);
    if($isMobileApp)
    {
        wp_enqueue_script( 'bww-jsf', plugins_url( 'bww-myn-plugin/js/mobileapp.js' ),'jquery',false,true);
		return true;
    }	
}
function is_mobile_sc($atts, $content = null) {
    $isMobileApp = identify_mobile_app();
    if(!empty($isMobileApp)){
		return $content;
    }
}
function isnot_mobile_sc($atts, $content = null) {
    $isMobileApp = identify_mobile_app();
    if(empty($isMobileApp)){
		return $content;
    }
}

add_action('wp_loaded', 'process_post');
add_action('wp_loaded', 'bww_show_admin_bar');
add_action('init', 'bww_enroll_user_first_visit');
add_action('wp_loaded', 'identify_mobile_app');
add_shortcode('isMobileApp', 'is_mobile_sc');
add_shortcode('isNotMobileApp', 'isnot_mobile_sc');


function process_post()
{
    
    if(isset($_POST['thejournal']) && isset($_POST['thejournal']) !='')
    {
        $thejournalcontent = str_replace("'", "\'", $_POST['thejournal']);
        echo '<!-- Getted the Journal -->';
        $user_id = $_POST['userid'];
                $post = array(
                'post_content'   => $_POST['thejournal'], // The full text of the post.
                'post_author'    => $user_id,
                'post_name'      => $user_id .'-'. $_POST['lesson'],
                'post_title'     => $user_id .'-'. $_POST['lesson'], // The title of your post.
                'post_status'    => 'private',
                'post_type'      => 'lesson_journal',
                'post_parent'    => 0,
                'tags_input'     => $_POST['lesson'], // Default empty.
            );
            wp_insert_post($post);
        
    }
    
    if(isset($_POST['newpass1']))
    {
        //$user_id = get_current_user_id();
		//$password = $_POST['newpass1'];
        //wp_set_password( $password, $user_id );
		wp_redirect('/members/my-profile'); exit;
	}
}

function process_post_ajax()
{
    
    if(isset($_POST['thejournal_lesson']) && isset($_POST['thejournal_lesson']) !='')
    {
        $thejournalcontent = str_replace("'", "\'", $_POST['thejournal_lesson']);
        echo '<!-- Getted the Journal -->';
        $user_id = $_POST['userid'];
                $post = array(
                'post_content'   => $_POST['thejournal_lesson'], // The full text of the post.
                'post_author'    => $user_id,                    
                'post_name'      => $user_id .'-'. $_POST['lesson'],
                'post_title'     => $user_id .'-'. $_POST['lesson'], // The title of your post.
                'post_status'    => 'private',
                'post_type'      => 'lesson_journal',
                'post_parent'    => 0,
                'tags_input'     => $_POST['lesson'], // Default empty.
            );
            wp_insert_post($post);
    }
	die();
}


add_action('wp_ajax_process_post_ajax', 'process_post_ajax');
add_action('wp_ajax_nopriv_process_post_ajax', 'process_post_ajax');
function bww_show_admin_bar() {
	$user_id = get_current_user_id();
	$user = new WP_User( $user_id );
	if(!in_array("administrator", $user->roles))
		add_filter('show_admin_bar', '__return_false');
}
function get_post_id( $slug, $post_type ) {
    $query = new WP_Query(
        array(
            'name' => $slug,
            'post_type' => $post_type
        )
    );

    $query->the_post();

    return get_the_ID();
}
function bww_enroll_user_first_visit(){
		if(strpos($_SERVER["REQUEST_URI"], 'courses')){
			$user_id = get_current_user_id();
			$course_url = $_SERVER['REQUEST_URI'];
			$course_url=explode('/',$course_url);
			$course_name = $course_url[2];
			//GET COURSE ID
			$course_id = get_post_id($course_name,'sfwd-courses');
			$meta_result_course = get_metadata('user', $user_id, 'course_'. $course_id .'_access_from', false);
			//If not enrolled (eroll user to course)			
			if($user_id > 0 && !$meta_result_course[0] && (
			($course_name=='wtgb31' && memb_hasAnyTags(array(8587))) || 
			($course_name=='winning-the-game-of-money-3-2' && memb_hasAnyTags(array(10331,10335))) || 
			
			($course_name=='wtgwl31' && memb_hasAnyTags(array(7994,8474))) || 
			($course_name=='wtgwl' &&memb_hasAnyTags(array(6072,6442,7248))) || 
			($course_name=='wtgm31' && memb_hasAnyTags(array(6962,6968,6112))) || 
			($course_name=='wtgm' && memb_hasAnyTags(array(6487,6493))) || 
			($course_name=='wtgf32' && memb_hasAnyTags(array(9669,9663,9689))))){
				ld_update_course_access($user_id, $course_id, $remove = false);
			}
			
			 if($course_name=='wtgf32' && $user_id > 0 && !$meta_result_course && memb_hasAnyTags(array(9669,9663,9689))){
				ld_update_course_access($user_id, $course_id, $remove = false);
				if(memb_hasAnyTags(array(9663))){
					$app = new iSDK;
					if(!$app->cfgCon("connectionName"))
					{
						echo "Did not connect.";
						exit();
					}
					$conId=get_current_IFS_contact_info(array(  'Id'));
					$conId=$conId[0]['Id'];
					$app->grpAssign($conId, 7342);
				}
			}elseif($course_name=='wtgf' && $user_id > 0 && !$meta_result_course && memb_hasAnyTags(array(6680,6684))){
				ld_update_course_access($user_id, $course_id, $remove = false);
			}
                      
			if($course_name=='winning-the-game-of-money-3-2' && memb_hasAnyTags(array(10331))){
					$app = new iSDK;
					if(!$app->cfgCon("connectionName"))
					{
						echo "Did not connect.";
						exit();
					}
					$conId=get_current_IFS_contact_info(array(  'Id'));
					$conId=$conId[0]['Id'];
					$app->grpAssign($conId, 10371);
					$app->grpAssign($conId, 10574);
			}
			if($course_name=='winning-the-game-of-money-3-2'){
				mark_completed_GS_wtgm32();
			}
			$sinceData = ld_course_access_from($course_id,  $user_id);
			$sinceM = empty($sinceData)? "":date("m", $sinceData);
			$sinceD = empty($sinceData)? "":date("d", $sinceData);
			$sinceY = empty($sinceData)? "":date("Y", $sinceData);
			$dateAccess=strtotime($sinceM."/".$sinceD."/".$sinceY." 00:00:00");
			//update_user_meta($user_id, "course_".$course_id."_access_from", $dateAccess);
                }else if(strpos($_SERVER["REQUEST_URI"], 'winning-the-game-of-weight-loss-4-0')){
                    
                    $user_id = get_current_user_id();
                    if(memb_hasAnyTags(array(9809,9841))){
                        global $wpdb;
                        $course_id=$wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE post_name = 'wtgwle'");
                        ld_update_course_access($user_id, $course_id, $remove = false);
                    }
                    if(memb_hasAnyTags(array(9811,9841))){
                        global $wpdb;
                        $course_id=$wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE post_name = 'wtgwla'");
                        ld_update_course_access($user_id, $course_id, $remove = false);
                    }
			
                }
                
}


/*****************************************************
 * Get Learndash Values
 *****************************************************/

function get_learndash_achivements($course)
{
    global $post;
    global $wpdb;
    $user_id = get_current_user_id();
    $total = 0;
    $geted = 0;
	$results = $wpdb->get_results("SELECT wp.ID FROM wp_posts wp INNER JOIN wp_postmeta wpm ON wp.ID = wpm.post_id  "
		. "WHERE  post_type = 'wpachievements' AND wpm.meta_key =  '_achievement_associated_id' AND "
		. "(wp.ID = 12714 or wpm.meta_value IN (select post_id from wp_postmeta where meta_key = 'course_id' and meta_value = ".$course[0]."))", ARRAY_N);    // The Loop	
    foreach ($results as $pid) {
            $total++;
                //$achimg = get_post_meta( $pid[0], '_achievement_image', true);
                //$result = get_post_meta($pid[0], '_user_gained_'.$user_id, true);
				$result=$wpdb->get_var("SELECT post_id, meta_key, meta_value FROM wp_postmeta WHERE post_id IN (".$pid[0].") AND meta_key = '_user_gained_".$user_id."' ORDER BY meta_id ASC;");
		if($result!='')
                    $geted++;
    }
    return $geted.'/'.$total;
    /* Restore original Post Data */
}

function get_learndash_total_leassons($course)
{
    $user_id = get_current_user_id();
    $meta_result = get_metadata('user', $user_id, '_sfwd-course_progress', false);
    $total = $meta_result[0][$course[0]]['total'];
    if($total <= 0 || $total == '')
                $total = 12;
    return $total;
}

function get_learndash_completed_leassons($course)
{
    $user_id = get_current_user_id();
    $meta_result = get_metadata('user', $user_id, '_sfwd-course_progress', false);
    $completed = $meta_result[0][$course[0]]['completed'];
    if($completed <= 0 || $completed == '')
                $completed = 0;
    return $completed;
}

function get_learndash_percentage($course)
{
    $user_id = get_current_user_id();
    $meta_result = get_metadata('user', $user_id, '_sfwd-course_progress', false);
    $completed = $meta_result[0][$course[0]]['completed'];
    if($completed <= 0 || $completed == '')
                $completed = 0;
    $total = $meta_result[0][$course[0]]['total'];
    if($total <= 0 || $total == '')
                $total = 12;
    $percentage = ($completed/$total)*100;
    return number_format($percentage, 0);
}
add_shortcode('ld_achivements', 'get_learndash_achivements');
add_shortcode('ld_percentage', 'get_learndash_percentage');
add_shortcode('ld_total_leassons', 'get_learndash_total_leassons');
add_shortcode('ld_completed_leassons', 'get_learndash_completed_leassons');



/*******************************************
 * Add WPAchievements Functions
 ******************************************/
function get_achievements_points()
{
    /*$user_id = get_current_user_id();
    $meta_result = get_metadata('user', $user_id, 'achievements_points', false);
    return $meta_result[0];*/
    return do_shortcode('[mycred_my_balance]');
    
}

function get_stripped_points() {
    $the_balance = do_shortcode('[mycred_my_balance]');
    $the_balance = str_replace('<div class="mycred-my-balance-wrapper">', '', $the_balance);
    $the_balance = str_replace('<div>', '', $the_balance);
    $the_balance = str_replace('</div>', '', $the_balance);
    return number_format($the_balance);
}
function get_points_history($arg) {
    $lines = $arg[0];
    global $wpdb;
    $output = '<div class="profilepointstable">';
    $results = $wpdb->get_results("SELECT wcl.creds, wcl.entry FROM wp_myCRED_log wcl WHERE wcl.user_id = ". get_current_user_id() ." ORDER BY wcl.time desc LIMIT ". $lines .";", ARRAY_N);
    foreach ($results as $line) {  
        $output .='<span class="points-color">'. $line[0] .' points</span> for '. str_replace("to: %url%", "",$line[1]) .'<br />';
    }    
    $output .='</div>';
    $output = str_replace('%plural%','',$output);
    $output = str_replace('Post','Level',$output);
    $output = str_replace('for  for','for',$output);
    return $output;
}




function get_user_achivements_badges($cid)
{
    global $post;
    global $wpdb;
    $user_id = get_current_user_id();
   
    $results = $wpdb->get_results("SELECT wp.ID FROM wp_posts wp INNER JOIN wp_postmeta wpm ON wp.ID = wpm.post_id  "
            . "WHERE  post_type = 'wpachievements' AND wpm.meta_key =  '_achievement_associated_id' AND "
            . "(wp.ID = 12714 or wpm.meta_value IN (select post_id from wp_postmeta where meta_key = 'course_id' and meta_value = ".$cid[0]."))", ARRAY_N);
    $theoutput = '<script type="text/javascript">
    function hiddeAll(divid){
        $(\'.badge-text\').hide();
        document.getElementById(divid).style.display = \'block\';
    }
    </script>';
    foreach ($results as $pid) {
            $achimg = get_post_meta( $pid[0], '_achievement_image', true);
            $resulta = get_post_meta($pid[0], '_user_gained_'.$user_id, true);
            if($resulta!='')
                $theoutput = $theoutput .'<img onmouseover="hiddeAll('.$pid[0].');" style="margin-right:5px; margin-top: 4px;" width="35" height="35" src="'.$achimg.'" id="img'. $pid[0].'" /> ';
            else
                $theoutput = $theoutput .'<img onmouseover="hiddeAll('.$pid[0].');"  style="opacity:0.5; margin-right:5px; margin-top: 4px;" width="35" height="35" src="'.$achimg.'" id="img'. $pid[0].'" /> ';
    }
    return $theoutput;
}

add_shortcode('bww_get_user_badges', 'get_user_achivements_badges');
add_shortcode('achievements_points', 'get_achievements_points');
add_shortcode('bww_points_history', 'get_points_history');
add_shortcode('stripped_points', 'get_stripped_points');


/*******************************
 * 
 * Journal Functions
 * save_journal() : Saves the data that the user enters
 * get_the_journal_form : Displays the form to save a Journal Entry
 * get_the_journal_form_popup : Displays the form to save a Journal Entry via Popup
 
 * get_all_journal() : Returns all the Journals entry of the Logued in user
 * get_some_journal() : Returns the passed amount of journals Entries for the Logued in User
 * 
 */

function get_the_journal_form($lesson)
{
    global $post;
    $user_id = get_current_user_id();
    $theoutput = '<form action="' . esc_url( $_SERVER['REQUEST_URI'] ) . '" method="post" id="journal_form">'
            .'<input type="hidden" name="lesson" value="'.$lesson[0].'" />'
            .'<input type="hidden" name="userid" value="'.$user_id.'" />'
            .'<textarea rows="4" cols="50" class="boxed-content-scroll" name="thejournal_lesson"></textarea> '
            .'<p>&nbsp;</p>'
            .'<div class="button-medium"><a href="#" class="submit_journal">Submit</a></div>'            
            .'<div class="button-medium"><a href="/Journal">View All Entries...</a></div>'
            .'</form>';
    return $theoutput; 
}

function get_the_journal_form_popup($lesson)
{
    global $post;
    $user_id = get_current_user_id();
    $theoutput = '<form action="' . esc_url( $_SERVER['REQUEST_URI'] ) . '" method="post" id="journal_form">'
            .'<input type="hidden" name="lesson" value="'.$lesson[0].'" />'
            .'<input type="hidden" name="userid" value="'.$user_id.'" />'
            .'<textarea rows="4" cols="50" class="boxed-content-scroll" name="thejournal"></textarea> '
            .'<p>&nbsp;</p>'
            .'<div class="button-medium"><a href="#" onclick="document.getElementById(\'new_entry_div\').style.display = \'none\';">Cancel</a></div>'
            .'<div class="button-medium"><a href="#" class="submit_journal_pr">Submit</a></div>'
            .'</form>';
    return $theoutput; 
}
function get_the_journal_form_jpage($lesson)
{
    global $post;
    $user_id = get_current_user_id();
    $theoutput = '<form action="' . esc_url( $_SERVER['REQUEST_URI'] ) . '" method="post" id="journal_form">'
            .'<input type="hidden" name="lesson" value="'.$lesson[0].'" />'
            .'<input type="hidden" name="userid" value="'.$user_id.'" />'
            .'<textarea rows="10" cols="50" class="boxed-content-scroll p90" name="thejournal"></textarea> '
            .'<p>&nbsp;</p>'
            .'<div class="button-medium"><a href="#" onclick="document.getElementById(\'journal_form\').submit();">Add Entry</a></div>'
            .'</form>';
    return $theoutput; 
}

function get_all_journal()
{
    global $post;
	if(isset($_GET['tz'])){
		$tz=$_GET['tz'];
		if ($tz <= -11){$timezone = 'Pacific/Midway';}
		if ($tz >= -11 && $tz <= -10){$timezone = 'US/Hawaii';}
		if ($tz >= -10 && $tz <= -9){$timezone = 'US/Alaska';}
		if ($tz >= -9 && $tz <= -8){$timezone = 'US/Pacific';}
		if ($tz >= -8 && $tz <= -7){$timezone = 'America/Los_Angeles';}
		if ($tz > -7 && $tz <= -6){$timezone = 'US/Arizona';}
		if ($tz >= -6 && $tz <= -5){$timezone = 'US/Eastern';}
		if ($tz >= -5 && $tz <= -4){$timezone = 'Canada/Atlantic';}
		if ($tz >= -4 && $tz <= -3){$timezone = 'America/Buenos_Aires';}
		if ($tz > -3 && $tz <= -2){$timezone = 'Atlantic/Stanley';}
		if ($tz >= -2 && $tz <= 1){$timezone = 'Atlantic/Azores';}
		if ($tz >= -1 && $tz <= 0){$timezone = 'Europe/Dublin';}
		if ($tz >= 0 && $tz <= 1){$timezone = 'Europe/Amsterdam';}
		if ($tz >= 1 && $tz <= 2){$timezone = 'Europe/Athens';}
		if ($tz >= 2 && $tz <= 3){$timezone = 'Asia/Baghdad';}
		if ($tz >= 3 && $tz <= 4){$timezone = 'Asia/Baku';}
		if ($tz >= 4 && $tz <= 5){$timezone = 'Asia/Karachi';}
		if ($tz >= 5 && $tz <= 6){$timezone = 'Asia/Yekaterinburg';}
		if ($tz >= 6 && $tz <= 7){$timezone = 'Asia/Novosibirsk';}
		if ($tz >= 7 && $tz <= 8){$timezone = 'Asia/Krasnoyarsk';}
		if ($tz >= 8 && $tz <= 9){$timezone = 'Asia/Irkutsk';}
		if ($tz >= 9 && $tz <= 10){$timezone = 'Australia/Sydney';}
		if ($tz >= 10 && $tz <= 11){$timezone = 'Asia/Vladivostok';}
		if ($tz >= 11){$timezone = 'Pacific/Fiji';}
		
	}else{
		$app = new iSDK;
		$timezone=get_current_IFS_contact_info(array(  '_UserTimeZone'));
		$timezone=$timezone[0]['_UserTimeZone'];
	}

	//date_default_timezone_set( $timezone );
	if(isset($_GET['tz'])){
		//$dateS = date_create(date("F j, Y, g:i a"));
		//date_timezone_set($dateS, timezone_open($timezone));
		//echo date_format($dateS, 'F j, Y, g:i a');  
	}
    $user_id = get_current_user_id();
    $args = array (
	'post_type'              => 'lesson_journal',
	'author'                 => $user_id,
    );

    $the_query = new WP_Query( $args );
    
    // The Loop
    if ( $the_query->have_posts() ) {
	
	while ( $the_query->have_posts() ) {
		$the_query->the_post();
		$date = date_create($post->post_date);
		//print_r(date_format($date, 'F jS H:i:sP'));
		if(isset($timezone) && !empty($timezone)){
			date_timezone_set($date, timezone_open($timezone));
		}
		if(isset($_GET['tz'])){
			$post_date=date_format($date, 'F jS H:i:sP');
		}else{
			$post_date=date_format($date, 'F jS');
		}		
		$theoutput = $theoutput .'<div id="journalid'.get_the_ID().'"><div class="journal-item full_view_journal"><span class="h4"><b>'. $post_date .':</b> '.$post->post_content .'</span> <a class="remove-item" rem-id="'.get_the_ID().'">delete</a><a class="print-item" onclick="printContent('.get_the_ID().')">print</a></div><input id="'.get_the_ID().'" type="hidden" value="<span style=\'font-family: Open Sans; font-size: 20px; font-weight: 800;\'>'. $post_date .'</span><br /><br /><span style=\'font-family: Open Sans; font-size: 20px; font-weight: 400;\'>'.$post->post_content .'</span>" /><br /><hr /></div>';
        }
    }
    return $theoutput;
    /* Restore original Post Data */
    wp_reset_postdata();
}


function get_some_journal($arg)
{
    global $post;
	if(isset($_GET['tz'])){
		$tz=$_GET['tz'];
		if ($tz <= -11){$timezone = 'Pacific/Midway';}
		if ($tz >= -11 && $tz <= -10){$timezone = 'US/Hawaii';}
		if ($tz >= -10 && $tz <= -9){$timezone = 'US/Alaska';}
		if ($tz >= -9 && $tz <= -8){$timezone = 'US/Pacific';}
		if ($tz >= -8 && $tz <= -7){$timezone = 'America/Los_Angeles';}
		if ($tz > -7 && $tz <= -6){$timezone = 'US/Arizona';}
		if ($tz >= -6 && $tz <= -5){$timezone = 'US/Eastern';}
		if ($tz >= -5 && $tz <= -4){$timezone = 'Canada/Atlantic';}
		if ($tz >= -4 && $tz <= -3){$timezone = 'America/Buenos_Aires';}
		if ($tz > -3 && $tz <= -2){$timezone = 'Atlantic/Stanley';}
		if ($tz >= -2 && $tz <= 1){$timezone = 'Atlantic/Azores';}
		if ($tz >= -1 && $tz <= 0){$timezone = 'Europe/Dublin';}
		if ($tz >= 0 && $tz <= 1){$timezone = 'Europe/Amsterdam';}
		if ($tz >= 1 && $tz <= 2){$timezone = 'Europe/Athens';}
		if ($tz >= 2 && $tz <= 3){$timezone = 'Asia/Baghdad';}
		if ($tz >= 3 && $tz <= 4){$timezone = 'Asia/Baku';}
		if ($tz >= 4 && $tz <= 5){$timezone = 'Asia/Karachi';}
		if ($tz >= 5 && $tz <= 6){$timezone = 'Asia/Yekaterinburg';}
		if ($tz >= 6 && $tz <= 7){$timezone = 'Asia/Novosibirsk';}
		if ($tz >= 7 && $tz <= 8){$timezone = 'Asia/Krasnoyarsk';}
		if ($tz >= 8 && $tz <= 9){$timezone = 'Asia/Irkutsk';}
		if ($tz >= 9 && $tz <= 10){$timezone = 'Australia/Sydney';}
		if ($tz >= 10 && $tz <= 11){$timezone = 'Asia/Vladivostok';}
		if ($tz >= 11){$timezone = 'Pacific/Fiji';}
		
	}else{
		$app = new iSDK;
		$timezone=get_current_IFS_contact_info(array(  '_UserTimeZone'));
		$timezone=$timezone[0]['_UserTimeZone'];
	}
    $user_id = get_current_user_id();
    $args = array (
	'post_type'             => 'lesson_journal',
	'author'                => $user_id,
        'posts_per_page'        => $arg[0],
    );

    $the_query = new WP_Query( $args );
    
    // The Loop
    if ( $the_query->have_posts() ) {
	
	while ( $the_query->have_posts() ) {
		$the_query->the_post();
		$date = date_create($post->post_date);
		//print_r(date_format($date, 'F jS H:i:sP'));
		//date_timezone_set($date, timezone_open($timezone));
		if(isset($_GET['tz'])){
			$post_date=date_format($date, 'F jS H:i:sP');
		}else{
			$post_date=date_format($date, 'F jS');
		}		
$theoutput = $theoutput .'<div id="journalid'.get_the_ID().'"><div class="journal-item journal-item-overflow"><span class="h4"><b>'. $post_date .':</b><br /> '.substr($post->post_content,0,130) .'</span> <a class="remove-item" rem-id="'.get_the_ID().'">delete</a><a class="print-item" onclick="printContent('.get_the_ID().')">print</a></div><input id="'.get_the_ID().'" type="hidden" value="<span style=\'font-family: Open Sans; font-size: 20px; font-weight: 800;\'>'. $post_date .'</span><br /><br /><span style=\'font-family: Open Sans; font-size: 20px; font-weight: 400;\'>'.$post->post_content .'</span>" /><br /><hr /></div>';
        }
    }
    return $theoutput;
    /* Restore original Post Data */
    wp_reset_postdata();
}
add_shortcode('bww_the_journal_form', 'get_the_journal_form');
add_shortcode('bww_the_journal_form_popup', 'get_the_journal_form_popup');
add_shortcode('bww_the_journal_form_jpage', 'get_the_journal_form_jpage');
add_shortcode('bww_the_journal', 'get_all_journal');
add_shortcode('bww_some_journal', 'get_some_journal');



/*************
 * Function to Remove Journal Item * 
 */
function remove_journal(){
	if(isset($_REQUEST)){
		/*Update post*/
		$journal=array();
		$journal['ID']=$_REQUEST['id'];
		$journal['post_status']='draft';
		
		// Update the post into the db
		$wpupdate=wp_update_post($journal);
		echo trim($wpupdate);
	}
	die();
}

add_action('wp_ajax_remove_journal', 'remove_journal');
add_action('wp_ajax_nopriv_remove_journal', 'remove_journal');

/*************
 * Function to Display the Lvl Slider Selector * 
 */
function the_lvl_slider($args)
{
    global $wpdb;
	global $post;
    $query = "SELECT COUNT(p.id) FROM wp_posts p INNER JOIN wp_postmeta pm ON p.ID = pm.post_id "
             ."WHERE p.post_type = 'sfwd-lessons' AND p.post_status = 'publish' AND pm.meta_key = 'course_id' AND pm.meta_value = ".$args[0] ;
    $total = $wpdb->get_var($query);
    $user_id = get_current_user_id();
    $meta_result = get_metadata('user', $user_id, '_sfwd-course_progress', false);
    $completed = $meta_result[0][$args[0]]['completed'];
    if($completed <= 0 || $completed == '')
                $completed = 0;
    if($total <= 0 || $total == '')
    {
        $total = 12;
        if(strpos($_SERVER["REQUEST_URI"], 'wtgm31') || strpos($_SERVER["REQUEST_URI"], 'wtgwl4') || strpos($_SERVER["REQUEST_URI"], 'wtgwl31') || strpos($_SERVER["REQUEST_URI"], 'wtgb31'))
            $total = 13;
        if(strpos($_SERVER["REQUEST_URI"], 'wtgf32'))
            $total = 4;
        if(strpos($_SERVER["REQUEST_URI"], 'wtgm32'))
            $total = 12;

    }

	$output .='<div class="span12 buttons-level" style="position:relative"><div class="btn-left"></div><div class="level-selector"><div class="swiper-container">
   <div class="swiper-wrapper">';
	$start_slider=1;
    for($a=$start_slider;$a<$total+1;$a++)
    {	
		$levelNum=$a;
		if(strpos($_SERVER["REQUEST_URI"], 'wtgwl31') || strpos($_SERVER["REQUEST_URI"], 'wtgb31') || strpos($_SERVER["REQUEST_URI"], 'wtgwl4') || strpos($_SERVER["REQUEST_URI"], 'wtgm32'))
			$levelNum=$a-1;

		$classLock='';
		$previus_completed=check_previous_complete($a);
		$access=unlockLessonsByRole(array("administrator","member"));
		if($previus_completed==0&& !$access){
			if((strpos($_SERVER["REQUEST_URI"], 'wtgwl31') || strpos($_SERVER["REQUEST_URI"], 'wtgwl4') || strpos($_SERVER["REQUEST_URI"], 'wtgb31')) && $levelNum == 1){
				$classLock="lvl1LockedFoundation";
			}else{
				$classLock="lockedLevelPrev";
			}
		}

		//$lesson_access_from = ld_lesson_access_from($post->ID, get_current_user_id());
		$level_text='Level '.$levelNum;
		if((strpos($_SERVER["REQUEST_URI"], 'wtgwl31') || strpos($_SERVER["REQUEST_URI"], 'wtgwl4') || strpos($_SERVER["REQUEST_URI"], 'wtgb31')) && $levelNum==0)
		$level_text='Foundation';
		/*WTGM 3.2 LEVEL 0 TEXT*/
		if(strpos($_SERVER["REQUEST_URI"], 'wtgm32') && $levelNum==0)
			$level_text='Start';
       if($levelNum == $args[1]){
			if($completed<$a && $access){
				$class='lockedlevelUser';
			};
            $output.='<div class="swiper-slide selectedlevel '.$class.'"><a href="javascript:;">'.$level_text.'</a></div>';
		}else{
            //if($a > $completed+1){
				if(strpos($_SERVER["REQUEST_URI"], 'wtgwl31')){
					$link='../wtgwl31'.$levelNum;
					$linkJs='../wtgwl31'.$levelNum;
				}elseif(strpos($_SERVER["REQUEST_URI"], 'wtgwl4')){
					$link='../wtgwl4'.$levelNum;
					$linkJs='../wtgwl4'.$levelNum;
				}elseif(strpos($_SERVER["REQUEST_URI"], 'wtgwl')){
					$link='../wtgwl'.$levelNum;
					$linkJs='../wtgwl'.$levelNum;
				}elseif(strpos($_SERVER["REQUEST_URI"], 'wtgm32')){
					$link='../wtgm32'.$levelNum;
					$linkJs='../wtgm32'.$levelNum;
					if($levelNum==0){
						$link='../wtgm32gettingstarted';
						$linkJs='../wtgm32gettingstarted';
					}
				}elseif(strpos($_SERVER["REQUEST_URI"], 'wtgm31')){
					$link='../wtgm31'.$levelNum;
					$linkJs='../wtgm31'.$levelNum;
				}elseif(strpos($_SERVER["REQUEST_URI"], 'wtgm')){
					$link='../wtgm'.$levelNum;
					$linkJs='../wtgm'.$levelNum;
                }elseif(strpos($_SERVER["REQUEST_URI"], 'wtgf32')){
					$link='../wtgf32'.$levelNum;
					$linkJs='../wtgf32'.$levelNum;
				}elseif(strpos($_SERVER["REQUEST_URI"], 'wtgf')){
					$link='../wtgf'.$levelNum;
					$linkJs='../wtgf'.$levelNum;
				}elseif(strpos($_SERVER["REQUEST_URI"], 'wtgb31')){
					$link='../wtgb31'.$levelNum;
					$linkJs='../wtgb31'.$levelNum;
				}

				$onclick='';
				$class='';
				$available=check_next_lesson($a);
				if($available==0 && !$access){
					$link='javascript:;';
					$onclick='onclick="document.getElementById(\'not_available\').style.display = \'block\';"';
					$class='lockedlevel';
				}
               $output.='<div class="swiper-slide '.$class.'"><a href="'.$link.'" '.$onclick.' class='.$classLock.' linkjs="'.$linkJs.'">'.$level_text.'</a></div>';
//$output.='<li class="lockedlevel"><a href="javascript:;" onclick="document.getElementById(\'not_available\').style.display = \'block\';">Level '.$a.'</a></li>';
			//}
           // else{
//				$link='../wtgwl'.$a;
//				$onclick='';
//				$class='';
//				if($available==0){
//					$link='javascript:;';
//					$onclick='onclick="document.getElementById(\'not_available\').style.display = \'block\';"';
//					$class=' class="lockedlevel"';
//				}
//               $output.='<li'.$class.'><a href="'.$link.'" '.$onclick.'>Level '.$a.'</a></li>';
//			}
	   }
    }       
	$slider_pos = $args[1]-1;
	if(strpos($_SERVER["REQUEST_URI"], 'wtgwl31') || strpos($_SERVER["REQUEST_URI"], 'wtgwl4') || strpos($_SERVER["REQUEST_URI"], 'wtgb31'))
		$slider_pos=$args[1];
 $output.=' </div></div><div class="swiper-button-next"></div><div class="swiper-button-prev"></div></div><div class="btn-right"></div></div>
 
<script type="text/javascript">
	$(document).ready(function(){
		var swiper = new Swiper(\'.swiper-container\', {
			initialSlide: '.$slider_pos.',
			slidesPerView: \'auto\',
			centeredSlides: true,
			speed: 500,
			preventClicks:false,
			nextButton: \'.swiper-button-next\',
			prevButton: \'.swiper-button-prev\',
			spaceBetween: 0
		});
	});
    </script>';

    return $output;

}
add_shortcode('bww_the_slider', 'the_lvl_slider');


/*
 * USER INFORMATION FUNCTIONS AND SHORTCODES 
 */
function get_user_fname()
{
    global $current_user;
    get_currentuserinfo();
    return $current_user->user_firstname;
}

function get_user_lname()
{
    global $current_user;
    get_currentuserinfo();
    return $current_user->user_lastname;
}

function get_user_email()
{
    global $current_user;
    get_currentuserinfo();
    return $current_user->user_email;
}
add_shortcode('user_fname', 'get_user_fname');
add_shortcode('user_lname', 'get_user_lname');
add_shortcode('user_email', 'get_user_email');




/*
 * get_the_change_pass_form : Returns the form that allows the user to change the password *
 */
function get_the_change_pass_form()
{
    $user_id = get_current_user_id();
    $theoutput = 'Change your Password:<br /><br />
<form method="post" name="changepass_form" id="changepass_form">
Please enter current password: <input type="password" name="currentpass" id="currentpass" required pattern=".{6,}"/><br /><br />
Please enter your new password: <input type="password" name="newpass1" id="newpass1" required pattern=".{6,}"/><br /><br />
Please repeat your new password: <input type="password" name="newpass2" id="newpass2" required onkeyup="checkPass(); return false;"/><br /><br />
<span id="confirmMessage" class="confirmMessage"></span><br />
<input type="submit" style="display: none;" id="submit" />
<div class="button-medium" style="display: none;" id="pass_sub"><a href="#" onclick="document.getElementById(\'submit\').click();">Submit</a></div><br />
<a href="#" onclick="document.getElementById(\'change_pass_div\').style.display = \'none\';">Close</a>
</form>';
    return $theoutput; 
	
}
add_shortcode('bww_pass_form', 'get_the_change_pass_form');





function get_programs_for_profile()
{
    //$access=unlockLessonsByRole(array("administrator","member"));
	$user_id = get_current_user_id();
	$output = '';
	$class_upgrade_WTGM='';
	$onclick_upgrade_wtgm='';
	$checkbox_upgrade_wtgm='';
	$hideBonus=!memb_hasAnyTags(6964);
	if($hideBonus){
	$bonusContent='[memb_has_any_tag tagid="6493,6487,6072,6442,6680,6684,6962,6968,7994,9663,9689"]<div class="product-rectangle"><span><a href="/bonus-content"><img src="'.get_template_directory_uri().'/images/newprograms/asides/icon-bonuscontent-a.png" /><br />Bonus Content</a></span></div>[/memb_has_any_tag]<br>';
	}
	else{
	$bonusContent='[memb_has_any_tag tagid="6493,6487,6072,6442,6680,6684,6962,6968,7994,9663,9689"]<div class="product-rectangle" style="display:none;"><span><a href="/bonus-content"><img src="'.get_template_directory_uri().'/images/newprograms/asides/icon-bonuscontent-a.png" /><br />Bonus Content</a></span></div>[/memb_has_any_tag]<br>';
	}
	if(memb_hasAnyTags(array(5439,5824)) && !memb_hasAnyTags(array(6493,6487,6112)) && !get_user_meta( $user_id, '_dont_show_again_WTGM' )){
		$class_upgrade_WTGM ='show_upgrade_wtgm show_upgrade';
		$onclick_upgrade_wtgm='onclick="_preventDefault(event);jQuery(\'.popup_upgrade_wtgm\').show();"';
		$checkbox_upgrade_wtgm='<label style="display:inline-block; font-weight:normal"><input type="checkbox" class="dont_show_again" value="_dont_show_again_WTGM" style="position: relative; top: 2px;"> Do not display this message again</label>';
	}
	$href_upgrade_WTGM='https://www.myneurogym.com/WTGM/upgrade/';
	$output .= '<div class="popup upgrade_popup popup_upgrade_wtgm" id="popup_upgrade_wtgm"  style="display: none; top:20%">
					<center><h3>Upgrade Details</h3></center> <div style="text-align:left">As an owner of The Winning the Game of Money program, youÃ¢â‚¬â„¢ve experienced the amazing process of retraining your brain to upgrade your beliefs, habits, perceptions and behaviors.<br /><br />Now, with our ongoing research and new discoveries, we have added many new compelling training modules, manuals, strategies, tools and resources for you to plan and achieve your financial and life success.<br /><br /></div>
<center>			<a class="button_popup" href="'.$href_upgrade_WTGM.'">Get the Upgrade!</a>
					<a class="button_popup no_thanks_bt" href="">No Thanks</a><br />
					'.$checkbox_upgrade_wtgm.'</center>
					<a onclick="jQuery(\'.popup_upgrade_wtgm\').hide();">Close</a>
				</div>
				<div class="popup upgrade_popup popup_upgrade_wtgm_explore" id="popup_upgrade_wtgm_explore"  style="display: none; top:20%">
				<center><h3>Upgrade Details</h3></center> <div style="text-align:left">As an owner of The Winning the Game of Money program, youÃ¢â‚¬â„¢ve experienced the amazing process of retraining your brain to upgrade your beliefs, habits, perceptions and behaviors.<br /><br />Now, with our ongoing research and new discoveries, we have added many new compelling training modules, manuals, strategies, tools and resources for you to plan and achieve your financial and life success.<br />	<br />				</div><center>
					<a class="button_popup" href="'.$href_upgrade_WTGM.'">Get the Upgrade!</a>
					<a class="button_popup no_thanks_bt" href="">No Thanks</a><br /></center>
					<a onclick="jQuery(\'.popup_upgrade_wtgm_explore\').hide();">Close</a>
				</div>';
 $output .= '[memb_has_any_tag tagid="1192,4294,5439,5459"]<div class="product-rectangle '.$class_upgrade_WTGM.'"><a href="/wtgm/" '.$onclick_upgrade_wtgm.'><img src="'.get_template_directory_uri().'/images/newprograms/asides/icon-wtgm20-a.png" /><br />Winning The Game<br class="will-hide" /> Of Money</a></div>[/memb_has_any_tag]'            
 			. '[memb_has_any_tag tagid="11126,11130"]<div class="product-rectangle"><a href="/the-vault/" class="program-wtgm31"><img src="'.get_template_directory_uri().'/images/newprograms/asides/vault_icon.png" /><br />The Vault</a></div>[/memb_has_any_tag]'
            . '[memb_has_any_tag tagid="5824"]<div class="product-rectangle '.$class_upgrade_WTGM.'"><a href="/wtgmsuccess/" '.$onclick_upgrade_wtgm.'><img src="'.get_template_directory_uri().'/images/newprograms/asides/icon-wtgm25-a.png" /><br />Winning The Game<br class="will-hide" /> Of Money</a></div>[/memb_has_any_tag]'            
            . '[memb_has_any_tag tagid="6493,6487"]<div class="product-rectangle"><a href="/courses/wtgm/"><img src="'.get_template_directory_uri().'/images/newprograms/asides/icon-wtgm30-a.png" /><br />Winning The Game<br class="will-hide" /> Of Money</a></div>[/memb_has_any_tag]'
            . '[memb_has_any_tag tagid="6112" except_tagid="6962,6968"]<div class="product-rectangle"><a href="/courses/wtgm31/" class="program-wtgm31 program-wtgm31trial"><img src="'.get_template_directory_uri().'/images/newprograms/asides/icon-wtgm31-trial-a.png" /><br />Winning The Game<br class="will-hide" /> Of Money</a></div>[/memb_has_any_tag]'
            . '[memb_has_any_tag tagid="7934,8230" except_tagid="7936"]<div class="product-rectangle"><a href="/maximize-your-potential-to-create-wealth/" class="program-wtgm31 program-wtgm31trial"><img src="https://d3atjl2sg13ne.cloudfront.net/members/wp-content/uploads/2016/04/WTGM-package-2-401x401.png" /><br />Maximize your Potential<br class="will-hide" /> to Create Wealth</a></div>[/memb_has_any_tag]'
            . '[memb_has_any_tag tagid="6962,6968"]<div class="product-rectangle"><a href="/courses/wtgm31/" class="program-wtgm31"><img src="'.get_template_directory_uri().'/images/newprograms/asides/icon-wtgm31-a.png" /><br />Winning The Game<br class="will-hide" /> Of Money</a></div>[/memb_has_any_tag]'            
            . '[memb_has_any_tag tagid="10331,10333"]<div class="product-rectangle"><a href="/courses/winning-the-game-of-money-3-2/" class="program-wtgm31"><img src="'.get_template_directory_uri().'/images/newprograms/asides/icon-wtgm32-a.png" /><br />Winning The Game<br class="will-hide" /> Of Money</a></div>[/memb_has_any_tag]'
<<<<<<< HEAD
            . '[memb_has_any_tag tagid="9809,9811,9841"]<div class="product-rectangle"><a href="/winning-the-game-of-weight-loss-4-0/" class="program-wtgm31">//icon missing//<br />Winning The Game<br class="will-hide" /> Of Weight Loss 4.0</a></div>[/memb_has_any_tag]'
=======
			. '[memb_has_any_tag tagid="10990"]<div class="product-rectangle"><a href="/business-black/" class="program-wtgm31"><img src="'.get_template_directory_uri().'/images/newprograms/asides/suitcase_icon_black.png" /><br />NeuroGym<br class="will-hide" />Business Black</a></div>[/memb_has_any_tag]'
			. '[memb_has_any_tag tagid="10992"]<div class="product-rectangle"><a href="/business-black/" class="program-wtgm31"><img src="'.get_template_directory_uri().'/images/newprograms/asides/suitcase_icon_black.png" /><br />NeuroGym<br class="will-hide" />Business Black</a></div>[/memb_has_any_tag]'
                        . '[memb_has_any_tag tagid="10998"]<div class="product-rectangle"><a href="/business-silver/" class="program-wtgm31"><img src="'.get_template_directory_uri().'/images/newprograms/asides/suitcase_icon_silver.png" /><br />NeuroGym<br class="will-hide" />Business Silver</a></div>[/memb_has_any_tag]'
>>>>>>> 194a9fc3c8d367a14a3e0ae1f7db1bac89f54f97
            . '[memb_has_any_tag tagid="10335"]<div class="product-rectangle"><a href="/courses/winning-the-game-of-money-3-2/" class="program-wtgm31"><img src="'.get_template_directory_uri().'/images/newprograms/asides/icon-wtgm32-trial-a.png" /><br />Winning The Game<br class="will-hide" /> Of Money</a></div>[/memb_has_any_tag]'
            . '[memb_has_any_tag tagid="10622"]<div class="product-rectangle"><a href="/winning-the-game-of-money-expert-bonuses/" class="program-wtgm31"><img src="https://d3atjl2sg13ne.cloudfront.net/members/wp-content/uploads/2016/04/WTGM-package-2-401x401.png" /><br />WTGM: Six Expert Bonuses</a></div>[/memb_has_any_tag]'
            . '[memb_has_any_tag tagid="1190"]<div class="product-rectangle"><a href="/wtgb/"><img src="'.get_template_directory_uri().'/images/newprograms/asides/icon-wtgb20-a.png" /><br />Winning The Game<br class="will-hide" /> Of Business</a></div>[/memb_has_any_tag]'
            . '[memb_has_any_tag tagid="5964"]<div class="product-rectangle"><a href="/wtgbsuccess/"><img src="'.get_template_directory_uri().'/images/newprograms/asides/icon-wtgb25-a.png" /><br />Winning The Game<br class="will-hide" /> Of Business</a></div>[/memb_has_any_tag]'
            . '[memb_has_any_tag tagid="8587"]<div class="product-rectangle"><a href="/courses/wtgb31/"><img src="'.get_template_directory_uri().'/images/newprograms/asides/icon-wtgb31-a.png" /><br />Winning The Game<br class="will-hide" /> Of Business</a></div>[/memb_has_any_tag]'
            . '[memb_has_any_tag tagid="2430"]<div class="product-rectangle"><a href="/wtgf/"><img src="'.get_template_directory_uri().'/images/newprograms/asides/icon-wtgf20-a.png" /><br />Winning The Game<br class="will-hide" /> Of Fear</a></div>[/memb_has_any_tag]'
            . '[memb_has_any_tag tagid="5944"]<div class="product-rectangle"><a href="/wtgfsuccess/"><img src="'.get_template_directory_uri().'/images/newprograms/asides/icon-wtgf25-a.png" /><br />Winning The Game<br class="will-hide" /> Of Fear</a></div>[/memb_has_any_tag]'
            . '[memb_has_any_tag tagid="9669" except_tagid="9663,9689"]<div class="product-rectangle"><a href="/courses/wtgf32/" class="program-wtgf32 program-wtgf32trial"><img src="'.get_template_directory_uri().'/images/newprograms/asides/icon-wtgf-3_2-trial.png" /><br />Winning The Game<br class="will-hide" /> Of Fear</a></div>[/memb_has_any_tag]'
            . '[memb_has_any_tag tagid="9663,9689"]<div class="product-rectangle"><a href="/courses/wtgf32/"><img src="'.get_template_directory_uri().'/images/newprograms/asides/icon-wtgf-3_2.png" /><br />Winning The Game<br class="will-hide" /> Of Fear</a></div>[/memb_has_any_tag]'
            . '[memb_has_any_tag tagid="6680,6684"]<div class="product-rectangle"><a href="/courses/wtgf/"><img src="'.get_template_directory_uri().'/images/newprograms/asides/icon-wtgf3-a.png" /><br />Winning The Game<br class="will-hide" /> Of Fear</a></div>[/memb_has_any_tag]'
            . '[memb_has_any_tag tagid="8160" except_tagid="8164"]<div class="product-rectangle"><a href="/fully-expressed-life/" class="program-wtgf"><img src="https://d3atjl2sg13ne.cloudfront.net/members/wp-content/uploads/2016/04/icon-sample-wtgf-a.png" /><br />Releasing Fears to Create <br class="will-hide" />a Fully Expressed Life</a></div>[/memb_has_any_tag]'
            . '[memb_has_any_tag tagid="1940"]<div class="product-rectangle"><a href="/wtgp/"><img src="'.get_template_directory_uri().'/images/newprograms/asides/icon-wtgp25-a.png" /><br />Winning The Game<br class="will-hide" /> Of Procastination</a></div>[/memb_has_any_tag]'
            . '[memb_has_any_tag tagid="6072,6442"]<div class="product-rectangle"><a href="/courses/wtgwl/"><img src="'.get_template_directory_uri().'/images/newprograms/asides/icon-wtgwl30-a.png" /><br />Winning The Game<br class="will-hide" /> Of Weight Loss</a></div>[/memb_has_any_tag]'
            . '[memb_has_any_tag tagid="7248" except_tagid="6072,6442"]<div class="product-rectangle"><a href="/courses/wtgwl/" class="program-wtgwl program-wtgwltrial"><img src="'.get_template_directory_uri().'/images/newprograms/asides/icon-wtgwl30-trial-a.png" /><br />Winning The Game<br class="will-hide" /> Of Weight Loss</a></div>[/memb_has_any_tag]'
            . '[memb_has_any_tag tagid="8162" except_tagid="8166"]<div class="product-rectangle"><a href="/achieve-ultimate-health/" class="program-wtgwl program-wtgwltrial"><img src="https://d3atjl2sg13ne.cloudfront.net/members/wp-content/uploads/2016/04/icon-sample-wtgwl-a.png" /><br />How to Achieve <br class="will-hide" />Ultimate Health</a></div>[/memb_has_any_tag]'
            . '[memb_has_any_tag tagid="7994"]<div class="product-rectangle"><a href="/courses/wtgwl31/"><img src="'.get_template_directory_uri().'/images/newprograms/asides/icon-wtgwl31-a.png" /><br />Winning The Game<br class="will-hide" /> Of Weight Loss</a></div>[/memb_has_any_tag]'
            . '[memb_has_any_tag tagid="8474" except_tagid="7994"]<div class="product-rectangle"><a href="/courses/wtgwl31/" class="program-wtgwl31 program-wtgwl31trial"><img src="'.get_template_directory_uri().'/images/newprograms/asides/icon-wtgwl31-trial-a.png" /><br />Winning The Game<br class="will-hide" /> Of Weight Loss</a></div>[/memb_has_any_tag]'
            . '[memb_has_any_tag tagid="8969"]<div class="product-rectangle"><a href="/repair-recovery/"><img src="https://d3atjl2sg13ne.cloudfront.net/members/wp-content/uploads/2016/05/icon-repairrecovery-a.png " /><br />Repair &<br class="will-hide" />Recovery</a></div>[/memb_has_any_tag]'
            . '[memb_has_any_tag tagid="5261"]<div class="product-rectangle"><a href="/category/wtglife/"><img src="'.get_template_directory_uri().'/images/newprograms/asides/icon-wtgl-a.png" /><br />Winning The Game<br class="will-hide" /> Of Life</a></div>[/memb_has_any_tag]'
            . '[memb_has_any_tag tagid="283,5439,5824"]<div class="product-rectangle"><a href="/having-it-all/"><img src="'.get_template_directory_uri().'/images/newprograms/asides/icon-hia25-a.png" /><br />Having It All</a></div>[/memb_has_any_tag]'            
            . '[memb_has_any_tag tagid="7380" except_tagid="7338,7382"]<div class="product-rectangle"><a href="/having-it-all-3/"><img src="'.get_template_directory_uri().'/images/newprograms/asides/icon-hia30-trial-a.png" /><br />Having It All</a></div>[/memb_has_any_tag]'
            . '[memb_has_any_tag tagid="7382" except_tagid="7338"]'
                . '<div class="popup not_availablehia" id="not_available3"  style="display: none;">
                    <center>
                        <div>Your Free Trial for Having It All has ended.</div> You can order the full version <a href="https://www.myneurogym.com/having-it-all/order/ " style="border-top:none !important; display:inline" class="by_click" target="_blank"><b>by clicking here!</b></a>
                        </center>
                        <a onclick="jQuery(\'.not_availablehia\').hide();">Close</a>
                    </div>
                    <div class="product-rectangle"><a href="#" onclick="jQuery(\'.not_availablehia\').show();"><img src="'.get_template_directory_uri().'/images/newprograms/asides/icon-hia30-a.png" /><br />Having It All</a></div>'
            . '[/memb_has_any_tag]'
            . '[memb_has_any_tag tagid="7338" except_tagid="7380"]<div class="product-rectangle"><a href="/having-it-all-3/"><img src="'.get_template_directory_uri().'/images/newprograms/asides/icon-hia30-a.png" /><br />Having It All</a></div>[/memb_has_any_tag]'
            . '[memb_has_any_tag tagid="1830,5261,5439"]<div class="product-rectangle"><a href="/how-to-get-more-done-in-less-time/"><img src="'.get_template_directory_uri().'/images/newprograms/asides/icon-moredonelesstime25-a.png" /><br />How To Get More Done<br class="will-hide" /> In Less Time</a></div>[/memb_has_any_tag]'
            . '[memb_has_any_tag tagid="7340"]<div class="product-rectangle"><a href="/how-to-get-more-done-in-less-time-3-2/"><img src="'.get_template_directory_uri().'/images/newprograms/asides/icon-moredonelesstime30-a.png" /><br />How To Get More Done<br class="will-hide" /> In Less Time</a></div>[/memb_has_any_tag]'
            . '[memb_has_any_tag tagid="7396"]<div class="product-rectangle"><a href="/how-to-get-more-done-in-less-time-3-2/" class="program-htgmdilt program-htgmdilt-trial"><img src="'.get_template_directory_uri().'/images/newprograms/asides/icon-moredonelesstime30-trial-a.png" /><br />How To Get More Done<br class="will-hide" /> In Less Time</a></div>[/memb_has_any_tag]'
            . '[memb_has_any_tag tagid="7448"]<div class="product-rectangle"><a href="/how-to-get-more-done-in-less-time-sa/"><img src="'.get_template_directory_uri().'/images/newprograms/asides/icon-moredonelesstime30-a.png" /><br />How To Get More Done<br class="will-hide" /> In Less Time</a></div>[/memb_has_any_tag]'
            . '[memb_has_any_tag tagid="3298,5439,7342"]<div class="product-rectangle"><a href="/values-based-living/"><img src="'.get_template_directory_uri().'/images/newprograms/asides/icon-vbl-a.png" /><br />Values-Based Living</a></div>[/memb_has_any_tag]'
            . '[memb_has_any_tag tagid="5648"]<div class="product-rectangle"><a href="/powerhabits/"><img src="'.get_template_directory_uri().'/images/newprograms/asides/icon-powerhabits-a.png" /><br />Power Habits Generator</a></div>[/memb_has_any_tag]'
            . '[memb_has_any_tag tagid="6226"]<div class="product-rectangle"><a href="/newscience/"><img src="'.get_template_directory_uri().'/images/newprograms/asides/icon-nsoga-a.png" /><br />The New Science<br class="will-hide" /> Of Goal Achievement </a></div>[/memb_has_any_tag]'
            . '[memb_has_any_tag tagid="1184,1192,4294,5261,5449,5439"]<div class="product-rectangle"><a href="/4-pillars/"><img src="'.get_template_directory_uri().'/images/newprograms/asides/icon-4pillars-a.png" /><br />The 4 Pillars Of<br class="will-hide" /> Financial Success</a></div>[/memb_has_any_tag]'            
            . '[memb_has_any_tag tagid="1278,5439,5824"]<div class="product-rectangle"><a href="/success-manifestors-2/"><img src="'.get_template_directory_uri().'/images/newprograms/asides/icon-successman-a.png" /><br />Success Manifestors</a></div>[/memb_has_any_tag]'            
            . '[memb_has_any_tag tagid="4450,4985,5602,5071"]<div class="product-rectangle"><a href="http://www.praxisnow.com/members/Cloning_2014_Replays/Day_1.html"><img src="'.get_template_directory_uri().'/images/newprograms/asides/icon-cobs-a.png" /><br />The Cloning Of<br class="will-hide" /> Business Success 8/14</a></div>[/memb_has_any_tag]'            
            . '[memb_has_any_tag tagid="5584,4985,4450,5604"]<div class="product-rectangle"><a href="/cloning12-14/"><img src="'.get_template_directory_uri().'/images/newprograms/asides/icon-cobs-a.png" /><br />The Cloning Of<br class="will-hide" /> Business Success 12/14</a></div>[/memb_has_any_tag] '
            . '[memb_has_any_tag tagid="5972,7950"]<div class="product-rectangle"><a href="/cloningvs/"><img src="'.get_template_directory_uri().'/images/newprograms/asides/icon-cobs-a.png" /><br />The Cloning Of<br class="will-hide" /> Business Success</a></div>[/memb_has_any_tag]'  
            . '[memb_has_any_tag tagid="8204"]<div class="product-rectangle"><a href="/cloningvs/"><img src="https://d3atjl2sg13ne.cloudfront.net/members/wp-content/uploads/2016/04/icon-cobs-trial-a.png" /><br />The Cloning Of<br class="will-hide" /> Business Success</a></div>[/memb_has_any_tag]'            
            . '[memb_has_any_tag tagid="5261,5439,5453,5459,5824,5964"]<div class="product-rectangle"><a href="/assessment/"><img src="'.get_template_directory_uri().'/images/newprograms/asides/icon-assessment-a.png" /><br />Personality<br class="will-hide" /> Profile Assessment</a></div>[/memb_has_any_tag]'            
            . '[memb_has_any_tag tagid="4348"]<div class="product-rectangle"><a href="http://www.praxisnow.com/money2members/?i4w_autologin=5ea2d8&force_login=5ea2d8&redir=http://www.praxisnow.com/money2members/money2/&Id=[memb_contact fields=ID]"><img src="'.get_template_directory_uri().'/images/newprograms/asides/icon-money21213-a.png" /><br />Money<sup>2</sup></a></div>[/memb_has_any_tag]'
            . '[memb_has_any_tag tagid="3969,5117,5261,5439"]<div class="product-rectangle"><a href="/money2-2014/"><img src="'.get_template_directory_uri().'/images/newprograms/asides/icon-money214-a.png" /><br />Money<sup>2</sup></a></div>[/memb_has_any_tag]'
            . '[memb_has_any_tag tagid="1192,5443,5439,5824,5964,5740"]<div class="product-rectangle"><a href="/category/milliondollarvids/"><img src="'.get_template_directory_uri().'/images/newprograms/asides/icon-mdvl-a.png" /><br />The Million-Dollar<br class="will-hide" /> Video Library</a></div>[/memb_has_any_tag]'
            . '[memb_has_any_tag tagid="283,1190,2430,1940,1192,4294,5261,5439,5459,5824,5944,5964"]<div class="product-rectangle"><a href="http://www.facebook.com/groups/praxis.achievers" target="_blank"><img src="'.get_template_directory_uri().'/images/newprograms/asides/icon-fb-a.png" /><br />Vip Coaching & Support</a></div>[/memb_has_any_tag]'
            . '[memb_has_any_tag tagid="7934"]<div class="product-rectangle"><a href="/mym-powerpack/"><img src="'.get_template_directory_uri().'/images/newprograms/asides/icon-sample-mym-a.png" /><br />Mastering Your Mindset<br>Power Pack</a></div>[/memb_has_any_tag]'
            . '[memb_has_any_tag tagid="3983"]<div class="product-rectangle"><a href="/mastering-your-mindset/welcome/"><img src="'.get_template_directory_uri().'/images/newprograms/asides/icon-mym-a.png" /><br />Mastering Your Mindset</a></div>[/memb_has_any_tag]'
            . '[memb_has_any_tag tagid="5790"]<div class="product-rectangle"><a href="/evadv2015/"><img src="'.get_template_directory_uri().'/images/newprograms/asides/icon-ev-a.png" /><br />Escape Velocity</a></div>[/memb_has_any_tag]'
            .$bonusContent; 
/*'[memb_has_any_tag tagid="6493,6487,6072,6442,6680,6684,6962,6968,7994,9663,9689"]<div class="product-rectangle"><span><a href="/bonus-content"><img src="'.get_template_directory_uri().'/images/newprograms/asides/icon-bonuscontent-a.png" /><br />Bonus Content</a></span></div>[/memb_has_any_tag]<br>';*/

    return do_shortcode($output);
}
add_shortcode('get_user_programs', 'get_programs_for_profile');



if( ! function_exists( 'custom_login_empty' ) ) {
    function custom_login_empty(){
        $referrer = $_SERVER['HTTP_REFERER'];
        if ( strstr($referrer,get_home_url()) && $user==null ) { // mylogin is the name of the loginpage.
            if ( !strstr($referrer,'?login=failed') ) { // prevent appending twice
                if ( !strstr($referrer,'?') ) { // prevent appending twice
                    wp_redirect( $referrer . '?login=failed' );
                } else {
                    wp_redirect( str_replace('?', '?login=failed&', $referrer ));
                }
            }  else { wp_redirect( $referrer );}
        }
    }
}
add_action( 'authenticate', 'custom_login_empty');

/*
if( ! function_exists( 'custom_login_empty' ) ) {
    function custom_login_empty(){
        $referrer = $_SERVER['HTTP_REFERER'];
        if ( strstr($referrer,get_home_url()) && $user==null ) { // mylogin is the name of the loginpage.
            if ( !strstr($referrer,'?login=failed') ) { // prevent appending twice
                if ( !strstr($referrer,'?') ) { // prevent appending twice
                    wp_redirect( $referrer . '?login=failed' );
                } else {
                    wp_redirect( str_replace('?', '?login=failed&', $referrer ));
                }
            }  else { wp_redirect( $referrer );}
        }
    }
}
add_action( 'authenticate', 'custom_login_empty');*/

function login_form()
{
    if(isset($_SERVER['HTTP_REFERER']))
    {
        $referrer = $_SERVER['HTTP_REFERER'];
    } elseif ($_GET['r_url']) {
        $referrer = $_GET['r_url'];
    }
    
    echo '<!-- ESTO ES ESTO ' . get_home_url() . ' -->';
    if ( (strstr($referrer,get_home_url()) || isset($_GET['ISOK'])) && $user==null ) {
        $theoutput = do_shortcode('[memb_loginform redirect="'. $referrer .'"]');
    }else {
        $theoutput = do_shortcode('[memb_loginform]');
    }
    return $theoutput;
}

add_shortcode('bww_login_form', 'login_form');

function check_next_lesson($number=0){
		global $post;
		$course_id = learndash_get_course_id($post);
		$posts = learndash_get_lesson_list($course_id);
		if($number==0){
			foreach($posts as $k => $p) {
					if($p->ID == $post->ID)
					{
						$found_at = $k;
						break;
					}
			}
		}else{
			$found_at=$number-2;
		}
		if(isset($found_at) && !empty($posts[$found_at+1]))
		{
			$lesson_id = $posts[$found_at+1]->ID;

		}
		$lesson_access_from = ld_lesson_access_from($lesson_id, get_current_user_id());
		$access=unlockLessonsByRole(array("administrator","member"));
		if(count($posts) == $number-1 || $access)
			return 1;
		if((empty($lesson_access_from) && !empty($lesson_id)))
			return 1;
		else
		{
			return 0;
		}
}
function check_next_lesson_xdays($number=0){
		global $post;
		$course_id = learndash_get_course_id($post);
		$posts = learndash_get_lesson_list($course_id);
		if($number==0){
			foreach($posts as $k => $p) {
					if($p->ID == $post->ID)
					{
						$found_at = $k;
						break;
					}
			}
		}else{
			$found_at=$number-2;
		}
		if(isset($found_at) && !empty($posts[$found_at+1]))
		{
			$lesson_id = $posts[$found_at+1]->ID;

		}
		$access=unlockLessonsByRole(array("administrator","member"));
		$lesson_access_from = ld_lesson_access_from($lesson_id, get_current_user_id());
		$diffdays=$lesson_access_from - strtotime('now');
		$xdays=round($diffdays/60/60/24);
		return $xdays;
		if((empty($lesson_access_from) && !empty($lesson_id)) || $access){
			return "Mark Complete";
		}
		else
		{
			if($xdays>1)
				return "Mark Complete in ".$xdays." days";
			else
				return "Mark Complete in ".$xdays." day";
		}
}
function check_previous_complete($number=0){
		global $post;
		$course_id = learndash_get_course_id($post);
		$posts = learndash_get_lesson_list($course_id);
		if($number==0){
			foreach($posts as $k => $p) {
					if($p->ID == $post->ID)
					{
						$found_at = $k;
						break;
					}
			}
		}else{
			$found_at=$number-2;
		}
		if(isset($found_at) && !empty($posts[$found_at+1]))
		{
			$lesson_post = $posts[$found_at+1];

		}
		
		$lesson_previous_completed = is_previous_complete($lesson_post);
		return $lesson_previous_completed;
}
function check_pass()
{
    
    if(isset($_POST['currentpass']) && isset($_POST['currentpass']) !='')
    {
		$user_id = get_current_user_id();	
		$user = new WP_User( $user_id );
		$check_pass=wp_check_password( $_POST['currentpass'], $user->data->user_pass, $user_id );
		if($check_pass)
			echo "1";
		else
			echo "0";
    }
	die();
}
add_action('wp_ajax_check_pass', 'check_pass');
add_action('wp_ajax_nopriv_check_pass', 'check_pass');


/********************************
 **** Weight Track Functions ****
 *******************************/

function process_weight_ajax()
{
    
    if(isset($_POST['startw']) && isset($_POST['ifsid']))
    {
		global $wpdb;
       /*$app = new iSDK;
        if(!$app->cfgCon("connectionName"))
        {
            echo "Did not connect.";
            exit();
        }
        echo "connected!";
        $conDat = array(
                        '_StartingWeight' => $_POST['startw'],
                        '_TargetWeight' => $_POST['targetw'],
                        '_AftercompletingLevel13' => $_POST['lvl1w'],
                        '_AftercompletingLevel2' => $_POST['lvl2w'],
                        '_AfterfinishingLevel3' => $_POST['lvl3w'],
                        '_AftercompletingLevel4' => $_POST['lvl4w'],
                        '_AftercompletingLevel5' => $_POST['lvl5w'],
                        '_AftercompletingLevel60' => $_POST['lvl6w'],
                        '_AftercompletingLevel7' => $_POST['lvl7w'],
                        '_AftercompletingLevel8' => $_POST['lvl8w'],            
                        '_AftercompletingLevel9' => $_POST['lvl9w'],
                        '_AftercompletingLevel10' => $_POST['lvl10w'],
                        '_AftercompletingLevel11' => $_POST['lvl11w'],
                        '_Afterfinishingtheprogram' => $_POST['endw'],
                        '_UnitsforWeightMeasurement' => $_POST['wun'],
                );
        //$conID = $app->updateCon($_POST['ifsid'], $conDat);*/
		$table_name = $wpdb->prefix.'wtgwlwtracker';
		$wtracker_info = $wpdb->get_var( "SELECT COUNT(*) FROM $table_name WHERE user_email = '".$_POST['ifsid']."'" );
		//print_r($wtracker_info);
		if($wtracker_info >= 1){
			$wpdb->get_var("UPDATE wp_wtgwlwtracker SET _StartingWeight = '".$_POST['startw']."',_TargetWeight = '".$_POST['targetw']."',_AftercompletingLevel13 = '".$_POST['lvl1w']."',_AftercompletingLevel2 = '".$_POST['lvl2w']."',_AfterfinishingLevel3 = '".$_POST['lvl3w']."',_AftercompletingLevel4 = '".$_POST['lvl4w']."',_AftercompletingLevel5 = '".$_POST['lvl5w']."',_AftercompletingLevel60 = '".$_POST['lvl6w']."',_AftercompletingLevel7 = '".$_POST['lvl7w']."',_AftercompletingLevel8 = '".$_POST['lvl8w']."',_AftercompletingLevel9 = '".$_POST['lvl9w']."',_AftercompletingLevel10 = '".$_POST['lvl10w']."',_AftercompletingLevel11 = '".$_POST['lvl11w']."',_Afterfinishingtheprogram = '".$_POST['endw']."',_UnitsforWeightMeasurement = '".$_POST['wun']."' WHERE user_email='".$_POST['ifsid']."'");


			/*$wpdb->update( 
				$table_name, 
				array( 
							'_StartingWeight' => $_POST['startw'],
							'_TargetWeight' => $_POST['targetw'],
							'_AftercompletingLevel13' => $_POST['lvl1w'],
							'_AftercompletingLevel2' => $_POST['lvl2w'],
							'_AfterfinishingLevel3' => $_POST['lvl3w'],
							'_AftercompletingLevel4' => $_POST['lvl4w'],
							'_AftercompletingLevel5' => $_POST['lvl5w'],
							'_AftercompletingLevel60' => $_POST['lvl6w'],
							'_AftercompletingLevel7' => $_POST['lvl7w'],
							'_AftercompletingLevel8' => $_POST['lvl8w'],            
							'_AftercompletingLevel9' => $_POST['lvl9w'],
							'_AftercompletingLevel10' => $_POST['lvl10w'],
							'_AftercompletingLevel11' => $_POST['lvl11w'],
							'_Afterfinishingtheprogram' => $_POST['endw'],
							'_UnitsforWeightMeasurement' => $_POST['wun'],
				), 
				array( 'user_email' => $_POST['ifsid'] ), 
				array( 
					'%d','%d','%d','%d','%d','%d','%d','%d','%d','%d','%d','%d','%d','%d','%d'
				), 
				array( '%s' ) 
			);*/
			echo "Updated!";
		}else{
								$wpdb->get_var( "INSERT INTO wp_wtgwlwtracker ( user_email, _StartingWeight, _TargetWeight, _AftercompletingLevel13, _AftercompletingLevel2, _AfterfinishingLevel3, _AftercompletingLevel4, _AftercompletingLevel5, _AftercompletingLevel60, _AftercompletingLevel7, _AftercompletingLevel8, _AftercompletingLevel9, _AftercompletingLevel10, _AftercompletingLevel11, _Afterfinishingtheprogram, _UnitsforWeightMeasurement ) VALUES ('".$_POST['ifsid']."','".$_POST['startw']."','".$_POST['targetw']."','".$_POST['lvl1w']."','".$_POST['lvl2w']."','".$_POST['lvl3w']."','".$_POST['lvl4w']."','".$_POST['lvl5w']."','".$_POST['lvl6w']."','".$_POST['lvl7w']."','".$_POST['lvl8w']."','".$_POST['lvl9w']."','".$_POST['lvl10w']."','".$_POST['lvl11w']."','".$_POST['endw']."','".$_POST['wun']."' )");

			/*$wpdb->insert( 
				$table_name, 
				array( 
					'user_email' => $_POST['ifsid'], 
					'_StartingWeight' => $_POST['startw'],
					'_TargetWeight' => $_POST['targetw'],
					'_AftercompletingLevel13' => $_POST['lvl1w'],
					'_AftercompletingLevel2' => $_POST['lvl2w'],
					'_AfterfinishingLevel3' => $_POST['lvl3w'],
					'_AftercompletingLevel4' => $_POST['lvl4w'],
					'_AftercompletingLevel5' => $_POST['lvl5w'],
					'_AftercompletingLevel60' => $_POST['lvl6w'],
					'_AftercompletingLevel7' => $_POST['lvl7w'],
					'_AftercompletingLevel8' => $_POST['lvl8w'],            
					'_AftercompletingLevel9' => $_POST['lvl9w'],
					'_AftercompletingLevel10' => $_POST['lvl10w'],
					'_AftercompletingLevel11' => $_POST['lvl11w'],
					'_Afterfinishingtheprogram' => $_POST['endw'],
					'_UnitsforWeightMeasurement' => $_POST['wun'],
				), 
				array( 
					'%s', '%d','%d','%d','%d','%d','%d','%d','%d','%d','%d','%d','%d','%d','%d','%d'
				)
			);*/
			echo "Inserted!";


		}

    }
    else
    {
        echo "notset!";
    }
    die();
}
add_action('wp_ajax_process_weight_ajax', 'process_weight_ajax');
add_action('wp_ajax_nopriv_process_weight_ajax', 'process_weight_ajax');

function get_the_weight_form_popup()
{
    
	global $wpdb;
    $current_user = wp_get_current_user(); 
    $email = $current_user->user_email;
    /*$app = new iSDK;
    if(!$app->cfgCon("connectionName"))
    {
        echo "Did not connect.";
        exit();
    }
    $returnFields = array(  'Id', 
                            '_StartingWeight', 
                            '_TargetWeight', 
                            '_AftercompletingLevel13',
                            '_AftercompletingLevel2',
                            '_AfterfinishingLevel3',
                            '_AftercompletingLevel4',
                            '_AftercompletingLevel5',
                            '_AftercompletingLevel60', 
                            '_AftercompletingLevel7',
                            '_AftercompletingLevel8',
                            '_AftercompletingLevel9',
                            '_AftercompletingLevel10',
                            '_AftercompletingLevel11',
                            '_Afterfinishingtheprogram',
                            '_UnitsforWeightMeasurement');
    $contacts  = $app->findByEmail($email, $returnFields);*/
	$table_name = $wpdb->prefix.'wtgwlwtracker';
	//$wtracker_info = $wpdb->get_results("SELECT * FROM $table_name");
	$wtracker_info["_StartingWeight"]="";
	$wtracker_info["_TargetWeight"]="";
	$wtracker_info["_AftercompletingLevel13"]="";
	$wtracker_info["_AftercompletingLevel2"]="";
	$wtracker_info["_AfterfinishingLevel3"]="";
	$wtracker_info["_AftercompletingLevel4"]="";
	$wtracker_info["_AftercompletingLevel5"]="";
	$wtracker_info["_AftercompletingLevel60"]="";
	$wtracker_info["_AftercompletingLevel7"]="";
	$wtracker_info["_AftercompletingLevel8"]="";
	$wtracker_info["_AftercompletingLevel9"]="";
	$wtracker_info["_AftercompletingLevel10"]="";
	$wtracker_info["_AftercompletingLevel11"]="";
	$wtracker_info["_Afterfinishingtheprogram"]="";
	$wtracker_info["_UnitsforWeightMeasurement"]="";
	$wtracker_info = $wpdb->get_row( "SELECT * FROM $table_name WHERE user_email = '".$email."'", ARRAY_A );
	//print_r($wtracker_info);
   $theoutput = '<div class="weight-form" id="weight-form"><table><input type="hidden" name="ifsid" id="ifsid" value="'.$email.'" />';
    $theoutput = $theoutput .'<tr><td><b>Enter you current (starting) Weight:</b></td><td><input type="text" name="start_w" id="start_w"  value="'.($wtracker_info['_StartingWeight']=='E'?'0':$wtracker_info["_StartingWeight"]).'" /></td></tr>';
    $theoutput = $theoutput .'<tr><td><b>Enter your target weight:</b></td><td><input  type="text" name="target_w" id="target_w" value="'.($wtracker_info["_TargetWeight"]=='E'?'0':$wtracker_info["_TargetWeight"]).'" /></td></tr>';
    $theoutput = $theoutput .'<tr><td>Your weight after Level 1:</td><td><input type="text" name="lvl1" id="lvl1" value="'.($wtracker_info["_AftercompletingLevel13"]=='E'?'0':$wtracker_info["_AftercompletingLevel13"]).' " /></td></tr>';
    if(check_next_lesson(2) > 0)
        $theoutput = $theoutput .'<tr><td>Your weight after Level 2:</td><td><input type="text" name="lvl2" id="lvl2" value="'.($wtracker_info["_AftercompletingLevel2"]=='E'?'0':$wtracker_info["_AftercompletingLevel2"]).' " /></td></tr>';
    else
        $theoutput = $theoutput .'<tr><td>Your weight after Level 2:</td><td><input type="text" name="lvl2" id="lvl2" value="'.($wtracker_info["_AftercompletingLevel2"]=='E'?'0':$wtracker_info["_AftercompletingLevel2"]).' " disabled style="background-color: gray;" /></td></tr>';
    if(check_next_lesson(3) > 0)
        $theoutput = $theoutput .'<tr><td>Your weight after Level 3:</td><td><input type="text" name="lvl3" id="lvl3" value="'.($wtracker_info["_AfterfinishingLevel3"]=='E'?'0':$wtracker_info["_AfterfinishingLevel3"]).'" /></td></tr>';
    else
        $theoutput = $theoutput .'<tr><td>Your weight after Level 3:</td><td><input type="text" name="lvl3" id="lvl3" value="'.($wtracker_info["_AfterfinishingLevel3"]=='E'?'0':$wtracker_info["_AfterfinishingLevel3"]).'" disabled style="background-color: gray;" /></td></tr>';
    if(check_next_lesson(4) > 0)
        $theoutput = $theoutput .'<tr><td>Your weight after Level 4:</td><td><input type="text" name="lvl4" id="lvl4" value="'.($wtracker_info["_AftercompletingLevel4"]=='E'?'0':$wtracker_info["_AftercompletingLevel4"]).'" /></td></tr>';
    else
        $theoutput = $theoutput .'<tr><td>Your weight after Level 4:</td><td><input type="text" name="lvl4" id="lvl4" value="'.($wtracker_info["_AftercompletingLevel4"]=='E'?'0':$wtracker_info["_AftercompletingLevel4"]).'" disabled style="background-color: gray;" /></td></tr>';
    if(check_next_lesson(5) > 0)
        $theoutput = $theoutput .'<tr><td>Your weight after Level 5:</td><td><input type="text" name="lvl5" id="lvl5" value="'.($wtracker_info["_AftercompletingLevel5"]=='E'?'0':$wtracker_info["_AftercompletingLevel5"]).'" /></td></tr>';
    else
        $theoutput = $theoutput .'<tr><td>Your weight after Level 5:</td><td><input type="text" name="lvl5" id="lvl5" value="'.($wtracker_info["_AftercompletingLevel5"]=='E'?'0':$wtracker_info["_AftercompletingLevel5"]).'" disabled style="background-color: gray;" /></td></tr>';
    if(check_next_lesson(6) > 0)
        $theoutput = $theoutput .'<tr><td>Your weight after Level 6:</td><td><input type="text" name="lvl6" id="lvl6" value="'.($wtracker_info["_AftercompletingLevel60"]=='E'?'0':$wtracker_info["_AftercompletingLevel60"]).'" /></td></tr>';
    else
        $theoutput = $theoutput .'<tr><td>Your weight after Level 6:</td><td><input type="text" name="lvl6" id="lvl6" value="'.($wtracker_info["_AftercompletingLevel60"]=='E'?'0':$wtracker_info["_AftercompletingLevel60"]).'" disabled style="background-color: gray;" /></td></tr>';
    if(check_next_lesson(7) > 0)
        $theoutput = $theoutput .'<tr><td>Your weight after Level 7:</td><td><input type="text" name="lvl7" id="lvl7" value="'.($wtracker_info["_AftercompletingLevel7"]=='E'?'0':$wtracker_info["_AftercompletingLevel7"]).'" /></td></tr>';
    else
        $theoutput = $theoutput .'<tr><td>Your weight after Level 7:</td><td><input type="text" name="lvl7" id="lvl7" value="'.($wtracker_info["_AftercompletingLevel7"]=='E'?'0':$wtracker_info["_AftercompletingLevel7"]).'" disabled style="background-color: gray;" /></td></tr>';
    if(check_next_lesson(8) > 0)
        $theoutput = $theoutput .'<tr><td>Your weight after Level 8:</td><td><input type="text" name="lvl8" id="lvl8" value="'.($wtracker_info["_AftercompletingLevel8"]=='E'?'0':$wtracker_info["_AftercompletingLevel8"]).'" /></td></tr>';
    else
        $theoutput = $theoutput .'<tr><td>Your weight after Level 8:</td><td><input type="text" name="lvl8" id="lvl8" value="'.($wtracker_info["_AftercompletingLevel8"]=='E'?'0':$wtracker_info["_AftercompletingLevel8"]).'" disabled style="background-color: gray;" /></td></tr>';
    if(check_next_lesson(9) > 0)
        $theoutput = $theoutput .'<tr><td>Your weight after Level 9:</td><td><input type="text" name="lvl9" id="lvl9" value="'.($wtracker_info["_AftercompletingLevel9"]=='E'?'0':$wtracker_info["_AftercompletingLevel9"]).'" /></td></tr>';
    else
        $theoutput = $theoutput .'<tr><td>Your weight after Level 9:</td><td><input type="text" name="lvl9" id="lvl9" value="'.($wtracker_info["_AftercompletingLevel9"]=='E'?'0':$wtracker_info["_AftercompletingLevel9"]).'" disabled style="background-color: gray;" /></td></tr>';
    if(check_next_lesson(10) > 0)
        $theoutput = $theoutput .'<tr><td>Your weight after Level 10:</td><td><input type="text" name="lvl10" id="lvl10" value="'.($wtracker_info["_AftercompletingLevel10"]=='E'?'0':$wtracker_info["_AftercompletingLevel10"]).'" /></td></tr>';
    else
        $theoutput = $theoutput .'<tr><td>Your weight after Level 10:</td><td><input type="text" name="lvl10" id="lvl10" value="'.($wtracker_info["_AftercompletingLevel10"]=='E'?'0':$wtracker_info["_AftercompletingLevel10"]).'" disabled style="background-color: gray;" /></td></tr>';
    if(check_next_lesson(11) > 0)
        $theoutput = $theoutput .'<tr><td>Your weight after Level 11:</td><td><input type="text" name="lvl11" id="lvl11" value="'.($wtracker_info["_AftercompletingLevel11"]=='E'?'0':$wtracker_info["_AftercompletingLevel11"]).'" /></td></tr>';
    else
        $theoutput = $theoutput .'<tr><td>Your weight after Level 11:</td><td><input type="text" name="lvl11" id="lvl11" value="'.($wtracker_info["_AftercompletingLevel11"]=='E'?'0':$wtracker_info["_AftercompletingLevel11"]).'" disabled style="background-color: gray;" /></td></tr>';
    if(check_next_lesson(12) > 0)
        $theoutput = $theoutput .'<tr><td>Your weight after Level 12:</td><td><input type="text" name="end_w" id="end_w" value="'.($wtracker_info["_Afterfinishingtheprogram"]=='E'?'0':$wtracker_info["_Afterfinishingtheprogram"]).'" /></td></tr>';
    else
        $theoutput = $theoutput .'<tr><td>Your weight after Level 12:</td><td><input type="text" name="end_w" id="end_w" value="'.($wtracker_info["_Afterfinishingtheprogram"]=='E'?'0':$wtracker_info["_Afterfinishingtheprogram"]).'" disabled style="background-color: gray;" /></td></tr>';


    $theoutput = $theoutput .'<tr><td colspan=2>';
    $theoutput = $theoutputs .'<center><b>Unit of Weight</b><br /><input type="radio" name="wunit" value="Pounds"';
    if($wtracker_info["_UnitsforWeightMeasurement"] == 'Pounds')
            $theoutput = $theoutput .' checked '; 
    $theoutput = $theoutput .'> Pounds <input type="radio" name="wunit" value="Kilos"';
    if($wtracker_info["_UnitsforWeightMeasurement"] == 'Kilos')
            $theoutput = $theoutput .' checked '; 
    $theoutput = $theoutput . '> Kilograms</center>';   
    $theoutput = $theoutput . '</td></tr>';    
    
    $theoutput = $theoutput .'<tr><td colspan="2"><div class="button-medium" id="butcanwei"><a href="javascript:;" id="cancel-weight">Cancel</a></div>';
    $theoutput = $theoutput .'<div class="button-medium" id="butupwei"><a href="javascript:;" id="update-weight">Update</a></div></td></tr></table>';
    $theoutput = $theoutput .'</div>';
    return $theoutput; 
}
add_shortcode('get_weight_form', 'get_the_weight_form_popup');	



function fix_logout() {
    session_start();
    $_SESSION = array();
    session_destroy();
}
add_action('wp_logout', 'fix_logout');


/********************************
 **** Ajax Function to add points ****
 *******************************/

function process_points_ajax()
{
    global $wpdb;
    $user_id = get_current_user_id();
   
    if(isset($_POST["ref"]) && isset($_POST['entry']))
    {
        
        $results = $wpdb->get_row("SELECT points, window, repetition FROM `bww-points` WHERE description = '".  $_POST['ref']  . "';", ARRAY_N);  
        $wait = $results[1] * 86400 ;
        $points = $results[0];
        $rep = $results[2];
        $last = $wpdb->get_var("SELECT time FROM wp_myCRED_log
        WHERE ref = '". $_POST["ref"] ."' AND user_id = ". $user_id ." AND entry  like '%". $_POST['entry'] ."%' ORDER BY id DESC LIMIT 1;");
        $TimeDiff = time() - $last;
        $counting = $wpdb->get_var("SELECT count(time) FROM wp_myCRED_log
        WHERE ref = '". $_POST["ref"] ."' AND user_id = ". $user_id ." AND entry  like '%". $_POST['entry'] ."%';");
        
        if((is_null($last) ||  $TimeDiff > $wait) && $counting < $rep+1)
        {
            mycred_add( $_POST["ref"], get_current_user_id(), $points,$_POST["entry"] , date(''));
            echo "Points Added!";
        }
        else
        {
            echo "NOP! To soon";
        }
    }
    else
    { 
        echo "NOP!";
    }
    die();
}
add_action('wp_ajax_process_points_ajax', 'process_points_ajax');
add_action('wp_ajax_nopriv_process_points_ajax', 'process_points_ajax');



function unlockLessonsByRole($roles){
	$user_id = get_current_user_id();
	$user = new WP_User( $user_id );
	$access=false;
	$wtgwl_admin_ifs=false;
        $wtgwl4_admin_ifs=false;
	$wtgm_admin_ifs=false;
	$wtgm31_admin_ifs=false;
	$wtgwl31_admin_ifs=false;
	$wtgf_admin_ifs=false;
        $wtgf32_admin_ifs=false;
	$wtgb31_admin_ifs=false;
	if((strpos($_SERVER["REQUEST_URI"], 'courses') || strpos($_SERVER["REQUEST_URI"], 'lessons')) && strpos($_SERVER["REQUEST_URI"], 'wtgwl31')){
		$wtgwl31_admin_ifs=memb_hasAnyTags(8236);
	}elseif((strpos($_SERVER["REQUEST_URI"], 'courses') || strpos($_SERVER["REQUEST_URI"], 'lessons')) && strpos($_SERVER["REQUEST_URI"], 'wtgwl4')){
		$wtgwl4_admin_ifs=memb_hasAnyTags(6442);
        }elseif((strpos($_SERVER["REQUEST_URI"], 'courses') || strpos($_SERVER["REQUEST_URI"], 'lessons')) && strpos($_SERVER["REQUEST_URI"], 'wtgwl')){
            $wtgwl_admin_ifs=memb_hasAnyTags(6442);
        }
        
	if((strpos($_SERVER["REQUEST_URI"], 'courses') || strpos($_SERVER["REQUEST_URI"], 'lessons')) && strpos($_SERVER["REQUEST_URI"], 'wtgm31')){
		$wtgm31_admin_ifs=memb_hasAnyTags(6968);
	}elseif((strpos($_SERVER["REQUEST_URI"], 'courses') || strpos($_SERVER["REQUEST_URI"], 'lessons')) && strpos($_SERVER["REQUEST_URI"], 'wtgm')){
		$wtgm_admin_ifs=memb_hasAnyTags(6493);
	}
	if((strpos($_SERVER["REQUEST_URI"], 'courses') || strpos($_SERVER["REQUEST_URI"], 'lessons')) && strpos($_SERVER["REQUEST_URI"], 'wtgf32')){
		$wtgf32_admin_ifs=memb_hasAnyTags(9689);
        }elseif ((strpos($_SERVER["REQUEST_URI"], 'courses') || strpos($_SERVER["REQUEST_URI"], 'lessons')) && strpos($_SERVER["REQUEST_URI"], 'wtgf')){
		$wtgf_admin_ifs=memb_hasAnyTags(6684);
        }
	if((strpos($_SERVER["REQUEST_URI"], 'courses') || strpos($_SERVER["REQUEST_URI"], 'lessons')) && strpos($_SERVER["REQUEST_URI"], 'wtgb31'))
		$wtgb31_admin_ifs=memb_hasAnyTags(6684);

	foreach($roles as $role){
		if(in_array($role, $user->roles) || $wtgm_admin_ifs || $wtgf_admin_ifs || $wtgwl_admin_ifs || $wtgm31_admin_ifs || $wtgwl31_admin_ifs || $wtgb31_admin_ifs) $access=true;
	}
	return $access;
}

add_filter('single_template', create_function('$t', 'foreach( (array) get_the_category() as $cat ) { if ( file_exists(TEMPLATEPATH . "/single-{$cat->slug}.php") ) return TEMPLATEPATH . "/single-{$cat->slug}.php"; } return $t;' ));


add_action( 'add_meta_boxes', 'add_lessons_metaboxes' );

// Add the Lessons Meta Boxes

function add_lessons_metaboxes() {
	add_meta_box('wpt_lessons_journal_questions', 'Journal Questions', 'wpt_lessons_journal_questions', 'sfwd-lessons', 'normal', 'default');
}

// The Lesson Journal Questions Metabox

function wpt_lessons_journal_questions() {
	global $post;
	
	// Noncename needed to verify where the data originated
	echo '<input type="hidden" name="lessonsmeta_noncename" id="lessonsmeta_noncename" value="' . 
	wp_create_nonce( plugin_basename(__FILE__) ) . '" />';
	
	// Get the journal questions data if its already been entered
	$lnk_questions = get_post_meta($post->ID, '_journal_questions_link', true);
	// Get the journal questions data if its already been entered
	$questions = get_post_meta($post->ID, '_journal_questions', true);
	
	// Echo out the field
	echo '<p><label for="_journal_questions_link"><b>Text Link</b></label></p><input name="_journal_questions_link" class="widefat" type="text" value="' . $lnk_questions  . '" />';
	// Echo out the field
	echo '<p><label for="_journal_questions"><b>Questions to pre-populate</b></label></p><textarea name="_journal_questions" class="widefat">' . $questions  . '</textarea>';

}

// Save the Metabox Data

function wpt_save_lessons_meta($post_id, $post) {
	
	// verify this came from the our screen and with proper authorization,
	// because save_post can be triggered at other times
	if ( !wp_verify_nonce( $_POST['lessonsmeta_noncename'], plugin_basename(__FILE__) )) {
	return $post->ID;
	}

	// Is the user allowed to edit the post or page?
	if ( !current_user_can( 'edit_post', $post->ID ))
		return $post->ID;

	// OK, we're authenticated: we need to find and save the data
	// We'll put it into an array to make it easier to loop though.
	
	$lessons_meta['_journal_questions'] = $_POST['_journal_questions'];
	$lessons_meta['_journal_questions_link'] = $_POST['_journal_questions_link'];
	
	// Add values of $lessons_meta as custom fields
	
	foreach ($lessons_meta as $key => $value) { // Cycle through the $lessons_meta array!
		if( $post->post_type == 'revision' ) return; // Don't store custom data twice
		$value = implode(',', (array)$value); // If $value is an array, make it a CSV (unlikely)
		if(get_post_meta($post->ID, $key, FALSE)) { // If the custom field already has a value
			update_post_meta($post->ID, $key, $value);
		} else { // If the custom field doesn't have a value
			add_post_meta($post->ID, $key, $value);
		}
		if(!$value) delete_post_meta($post->ID, $key); // Delete if blank
	}

}

add_action('save_post', 'wpt_save_lessons_meta', 1, 2); // save the custom fields

function set_facebook_name_sc($atts, $content = null){
$facebook_name=get_current_IFS_contact_info(array(  '_FacebookUserName'));
$facebook_name=$facebook_name[0]['_FacebookUserName'];
$content='     <!--My Facebook Name-->
     <div id="myfacebookname" class="popup">
         <center><h3>Join Our Community!</h3>
         <strong>Step 1</strong> - Please enter the name you use on Facebook<br>
         <input type="text" value="" name="fb_name_set" class="fb_name_set" /><br><br>
         <strong>Step 2</strong> - Click the button below to go to the community page. You must then click the "<strong>Join Now</strong>" button on that page to request access. The approval process can take up to 24 hours.<br />
Once approved, youÃ‚Â´re all set!<br />
		 <div class="button-medium update_fb_name">
           <a href="https://www.facebook.com/groups/344601295735066/" target="_blank">NeuroGym Achievers Community</a>
         </div><br />
         <a href="javascript:;" onclick="jQuery(\'#myfacebookname\').hide();jQuery(\'#myfacebookname-tool\').fadeIn();">Why do we need this?</a>
		 <a href="javascript:;" onclick="document.getElementById(\'myfacebookname\').style.display = \'none\';" class="link-bottom-popup">Close</a>
         </center>
     </div>
     <!--My Facebook Name tooltip-->
     <div id="myfacebookname-tool" class="popup" style="display: none; background: rgb(255, 178, 0);">
<center>
         <h3>Join Our Community!</h3><br />

         In order to be approved to join our community, we need to know if your name on Facebook differs from your name in our database.  If you use the same name for both, you may leave this blank.<br><br><br>
           <a href="javascript:;" onclick="jQuery(\'#myfacebookname\').fadeIn();jQuery(\'#myfacebookname-tool\').hide();">Got it!</a>
		 <a href="javascript:;" onclick="document.getElementById(\'myfacebookname-tool\').style.display = \'none\';" class="link-bottom-popup">Close</a>
		   
     </div><div class="popup" id="facebook_name_updated">
<center>Facebook Name Updated!</center>
<a href="#" onclick="document.getElementById(\'facebook_name_updated\').style.display = \'none\';">Close</a>
</div>';
	 return $content;
}
add_shortcode('set_facebook_name_sc', 'set_facebook_name_sc');	

function set_facebook_name(){
    
    if(isset($_POST['fb_name_set']))
    {
        $app = new iSDK;
        $conId=get_current_IFS_contact_info(array(  'Id'));
        $conId=$conId[0]['Id'];

        $app = new iSDK;
        if(!$app->cfgCon("connectionName"))
        {
            echo "Did not connect.";
            exit();
        }
        echo "connected!";
        $conDat = array(
                        '_FacebookUserName' => $_POST['fb_name_set']
                );
        $conID = $app->updateCon($conId, $conDat);
		$tagIdWTGWL='6588';
		$tagIdACHIEVERS='6590';
		
		$app->grpAssign($conId, $tagIdWTGWL);
		$app->grpAssign($conId, $tagIdACHIEVERS);
	    $sessid = session_id();
		setcookie( 'fb_updated', $sessid, time() + 3600, COOKIEPATH, COOKIE_DOMAIN );
		echo "Updated!";
    }
    else
    {
        echo "notset!";
    }
    die();
}
add_action('wp_ajax_set_facebook_name', 'set_facebook_name');
add_action('wp_ajax_nopriv_set_facebook_name', 'set_facebook_name');

function get_current_IFS_contact_info($info=array()){
    $current_user = wp_get_current_user(); 
    $email = $current_user->user_email;
    $app = new iSDK;
    if(!$app->cfgCon("connectionName"))
    {
        echo "Did not connect.";
        exit();
    }
    $returnFields = $info;
    return $app->findByEmail($email, $returnFields);
}


    remove_action( 'show_user_profile', 'learndash_show_enrolled_courses',1 );
    remove_action( 'edit_user_profile', 'learndash_show_enrolled_courses',1 );
    remove_action( 'personal_options_update', 'learndash_save_enrolled_courses',1 );
    remove_action( 'edit_user_profile_update', 'learndash_save_enrolled_courses',1 );
	
	
	
   function bww_learndash_show_enrolled_courses( $user ) {
		$courses = get_pages("post_type=sfwd-courses");
    ?>
        <table class="form-table">
            <tr>
                <th> <h3 style="margin-top:0;"><?php _e('Edit Enrolled Courses Since', 'learndash'); ?></h3></th>
                <td>
					<ol>
					<?php 
						foreach($courses as $course) { 
								if(sfwd_lms_has_access($course->ID,  $user->ID)) { 

									echo "<li><a href='".get_permalink($course->ID)."'>".$course->post_title."</a></li>";
									$datesM=array('Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec');
									$sinceData = ld_course_access_from($course->ID,  $user->ID);
									$sinceM = empty($sinceData)? "":date("m", $sinceData);
									$sinceD = empty($sinceData)? "":date("d", $sinceData);
									$sinceY = empty($sinceData)? "":date("Y", $sinceData);
									$sinceH = empty($sinceData)? "":date("H", $sinceData);
									$sinceI = empty($sinceData)? "":date("i", $sinceData);
									echo '<div class="timestamp-wrap" id="timestampdiv">Edit Since: <label><span class="screen-reader-text">Month</span><select id="mm" name="'.$course->ID.'_mm">';
									$n=1;
									foreach($datesM as $dateM){
										$key=sprintf("%02d",$n);
										$selected='';
										if($key == $sinceM){
											$selected = 'selected="selected"';
										}
										echo '<option value="'.$key.'" data-text="'.$dateM.'" '.$selected.'>'.$key.'-'.$dateM.'</option>';
										$n++;
									}
									echo '</select></label> <label><span class="screen-reader-text">Day</span><input type="text" id="jj" name="'.$course->ID.'_jj" value="'.$sinceD.'" size="2" maxlength="2" autocomplete="off"></label>, <label><span class="screen-reader-text">Year</span><input type="text" id="aa" name="'.$course->ID.'_aa" value="'.$sinceY.'" size="4" maxlength="4" autocomplete="off"></label> @ <label><span class="screen-reader-text">Hour</span><input type="text" id="hh" name="'.$course->ID.'_hh" value="'.$sinceH.'" size="2" maxlength="2" autocomplete="off"></label>:<label><span class="screen-reader-text">Minute</span><input type="text" id="mn" name="'.$course->ID.'_mn" value="'.$sinceI.'" size="2" maxlength="2" autocomplete="off"></label></div><input name="course_id[]" value="'.$course->ID.'" type="hidden" />';
								}
						}
					?>
					</ol>
				</td>
			</tr>
        </table>
    <?php }
     
    function bww_learndash_save_enrolled_courses( $user_id ) {
        if ( !current_user_can('manage_options'))
            return FALSE;
		
		//bww_insrt_log($course_id, 'En la funciÃƒÂ³n bww_learndash_save_enrolled_courses pasando current_user_can("manage_options")');
		foreach($_POST['course_id'] as $course_id){
			if(!empty($_POST[$course_id.'_jj'])){
				$mm = $_POST[$course_id.'_mm'];
				$jj = $_POST[$course_id.'_jj'];
				$aa = $_POST[$course_id.'_aa'];
				$hh = "00";
				$mn = "00";
				$dateAccess=strtotime($mm."/".$jj."/".$aa." ".$hh.":".$mn.":00");
				update_user_meta($user_id, "course_".$course_id."_access_from", $dateAccess);
			}else{
				$dateAccess=strtotime(date("m")."/".date("d")."/".date("Y")." 00:00:00");
				update_user_meta($user_id, "course_".$course_id."_access_from", $dateAccess);
			}
		}
    }
	
    add_action( 'show_user_profile', 'bww_learndash_show_enrolled_courses' );
    add_action( 'edit_user_profile', 'bww_learndash_show_enrolled_courses' );
     
    add_action( 'personal_options_update', 'bww_learndash_save_enrolled_courses' );
    add_action( 'edit_user_profile_update', 'bww_learndash_save_enrolled_courses' );




function deletefbcookie() {
   setcookie( 'fb_updated', '', time() - 3600, COOKIEPATH, COOKIE_DOMAIN );
}
add_action('wp_logout', 'deletefbcookie');

function check_if_logged_in(){
	if (
            !in_array($GLOBALS['pagenow'], array('wp-login.php', 'wp-register.php', 'index.php'))
            && !is_admin()
            && !is_user_logged_in()
	) {
	  wp_redirect('/', 301);
	  exit;
	}
}
add_action( 'init', 'check_if_logged_in' ); 


function load_cat_parent_template($template) {

    $cat_ID = absint( get_query_var('cat') );
    $category = get_category( $cat_ID );

    $templates = array();

    if ( !is_wp_error($category) )
        $templates[] = "category-{$category->slug}.php";

    $templates[] = "category-$cat_ID.php";

    // trace back the parent hierarchy and locate a template
    if ( !is_wp_error($category) ) {
        $category = $category->parent ? get_category($category->parent) : '';

        if( !empty($category) ) {
            if ( !is_wp_error($category) )
                $templates[] = "category-{$category->slug}.php";

            $templates[] = "category-{$category->term_id}.php";
        }
    }

    $templates[] = "category.php";
    $template = locate_template($templates);

    return $template;
}
add_action('category_template', 'load_cat_parent_template');


function hide_welcome_section(){
	global  $wpdb;
	/*$users=get_users(array('fields'=> array("ID")));
	foreach($users as $user){
		delete_user_meta( $user->ID, '_welcome_section' );
	}*/
	$type="";
	if(isset($_POST['type'])){
		$type="-".$_POST['type'];
	}
	$user_id = get_current_user_id();
	if(get_user_meta( $user_id, '_welcome_section'.$type ))
		update_user_meta( $user_id, '_welcome_section'.$type, '1' );
	else
		add_user_meta( $user_id, '_welcome_section'.$type, '1' );
	
	die();
}
add_action('wp_ajax_hide_welcome_section', 'hide_welcome_section');
add_action('wp_ajax_nopriv_hide_welcome_section', 'hide_welcome_section');
function show_welcome_section(){
	global  $wpdb;
	/*$users=get_users(array('fields'=> array("ID")));
	foreach($users as $user){
		delete_user_meta( $user->ID, '_welcome_section' );
	}*/
	$type="";
	if(isset($_POST['type'])){
		$type="-".$_POST['type'];
	}
	$user_id = get_current_user_id();
	if(get_user_meta( $user_id, '_welcome_section'.$type ))
		echo 1;
	else
		echo 0;
	
	die();
}
add_action('wp_ajax_show_welcome_section', 'show_welcome_section');
add_action('wp_ajax_nopriv_show_welcome_section', 'show_welcome_section');

function show_welcome_section_bonus(){
	global  $wpdb;
	/*$users=get_users(array('fields'=> array("ID")));
	foreach($users as $user){
		delete_user_meta( $user->ID, '_welcome_section' );
	}*/
	$type="";
	if(isset($_POST['type'])){
		$type="-".$_POST['type'];
	}
	$user_id = get_current_user_id();
	if(get_user_meta( $user_id, '_welcome_section__'.$type ))
		update_user_meta( $user_id, '_welcome_section__'.$type, '1' );
	else
		add_user_meta( $user_id, '_welcome_section__'.$type, '1' );
	
	die();
}
add_action('wp_ajax_show_welcome_section_bonus', 'show_welcome_section_bonus');
add_action('wp_ajax_nopriv_show_welcome_section_bonus', 'show_welcome_section_bonus');
function hide_welcome_section_bonus(){
	global  $wpdb;
	/*$users=get_users(array('fields'=> array("ID")));
	foreach($users as $user){
		delete_user_meta( $user->ID, '_welcome_section' );
	}*/
	$type="";
	if(isset($_POST['type'])){
		$type="-".$_POST['type'];
	}
	$user_id = get_current_user_id();
	if(get_user_meta( $user_id, '_welcome_section__'.$type ))
		update_user_meta( $user_id, '_welcome_section__'.$type, '0' );
	else
		add_user_meta( $user_id, '_welcome_section__'.$type, '0' );
	
	die();
}
add_action('wp_ajax_hide_welcome_section_bonus', 'hide_welcome_section_bonus');
add_action('wp_ajax_nopriv_hide_welcome_section_bonus', 'hide_welcome_section_bonus');
function check_status_welcome_section_bonus(){
	global  $wpdb;
	/*$users=get_users(array('fields'=> array("ID")));
	foreach($users as $user){
		delete_user_meta( $user->ID, '_welcome_section' );
	}*/
	$type="";
	if(isset($_POST['type'])){
		$type="-".$_POST['type'];
	}
	$user_id = get_current_user_id();
	if(get_user_meta( $user_id, '_welcome_section__'.$type )){
		echo get_user_meta( $user_id, '_welcome_section__'.$type, true );
	}
	else
		echo 0;
	
	die();
}
add_action('wp_ajax_check_status_welcome_section_bonus', 'check_status_welcome_section_bonus');
add_action('wp_ajax_nopriv_check_status_welcome_section_bonus', 'check_status_welcome_section_bonus');
function bww_insrt_log($course_id='', $extra='', $user_id='', $request_url=''){
	global $wpdb, $post;
	if(empty($user_id))
		$user_id = get_current_user_id();
	if(empty($course_id))
		$course_id = $post->ID;
	if(empty($request_url))
		$request_url = $_SERVER["REQUEST_URI"];
	$wpdb->insert( 
		'bww_log', 
		array( 
			'user_id' => $user_id, 
			'course_id' => $course_id, 
			'request_url' => $request_url,
			'extra' => $extra
		)
	);
	//echo "<pre style='background:red; color:#FFF;'>";
	//	print_r (array( 'user_id' => $user_id, 'course_id' => $course_id, 'request_url' => $request_url,	'extra' => $extra));
	//echo "</pre>";
}

function wistia_thumbnail($args){
 $wistia_id=$args[0];
 $ch = curl_init();
 curl_setopt($ch, CURLOPT_URL, 'http://fast.wistia.com/oembed?url=http%3A%2F%2Fhome.wistia.com%2Fmedias%2F'.$wistia_id);
 curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
 $response = json_decode(curl_exec($ch), true);
 curl_close($ch);
 $parts_url=explode("?",$response['thumbnail_url']);
 $thumbnail_resized=$parts_url[0]."?image_crop_resized=176x99";
 return "<img src='".$thumbnail_resized."' />";
}
add_shortcode('wistia_thumbnail', 'wistia_thumbnail');


function wistia_player( $atts ) {
    return '<script src="//fast.wistia.com/embed/medias/'.$atts['id'].'.jsonp" async></script>
<script src="//fast.wistia.com/assets/external/E-v1.js" async></script>
<div class="wistia_responsive_padding" style="padding:56.25% 0 0 0;position:relative;">
<div class="wistia_responsive_wrapper" style="height:100%;left:0;position:absolute;top:0;width:100%;">
<div class="wistia_embed wistia_async_'.$atts['id'].' videoFoam=true" style="height:100%;width:100%">&nbsp;</div></div></div>';    
}
add_shortcode( 'wistia_player', 'wistia_player' );

function mark_completed_13(){
	if(strpos($_SERVER["REQUEST_URI"], 'wtgm31')){
		 global $wpdb;
		 //Get level 12 id
		 $wtgm3112_id=$wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE post_name = 'wtgm3112'");
		 //Get level 13 id 
		 $wtgm3113_id=$wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE post_name = 'wtgm3113'");
		 
		 if(isset($_POST['sfwd_mark_complete'])){
		  $user_id = get_current_user_id();
		 
		  if($_POST['post']==$wtgm3112_id);
		  learndash_process_mark_complete($user_id, $wtgm3113_id);
		 }
		 if(strpos($_SERVER["REQUEST_URI"], 'wtgm3113')){
		  $previus_completed=check_previous_complete();
		  learndash_process_mark_complete($user_id, $wtgm3113_id);
		 }
	}
}
add_action( 'init', 'mark_completed_13' );

/*
function bww_check_access(){
	if (!in_array($GLOBALS['pagenow'], array('wp-login.php', 'wp-register.php'))
  && !is_admin() && !is_admin() && !is_page_template( 'members.php' )) {
		//if(isset($_REQUEST["debug"])){
			global $post;
			if(is_user_logged_in() && !memb_hasPostAccess($post->ID) && strpos($_SERVER["REQUEST_URI"], 'msj') === false){ //Scenario 3
				wp_redirect(home_url()."/?msj=2"); exit;
			}elseif(is_user_logged_in()){
				if((
				(strpos($_SERVER["REQUEST_URI"], '/evadv2015/') !== false && !memb_hasAnyTags(5790)) ||
				(strpos($_SERVER["REQUEST_URI"], '/courses/wtgm31/') !== false && !memb_hasAnyTags(array(6962,6968,6112))) ||
				(strpos($_SERVER["REQUEST_URI"], '/courses/wtgm/') !== false && !memb_hasAnyTags(array(6493,6487))) ||
				(strpos($_SERVER["REQUEST_URI"], '/courses/wtgf/') !== false && !memb_hasAnyTags(array(6680,6684))) ||
(strpos($_SERVER["REQUEST_URI"], '/courses/wtgf32/') !== false && !memb_hasAnyTags(array(9663,6688))) ||
				(strpos($_SERVER["REQUEST_URI"], '/wtgmsuccess/') !== false && !memb_hasAnyTags(5824)) ||
				(strpos($_SERVER["REQUEST_URI"], 'com/wtgm/') !== false && !memb_hasAnyTags(array(1192,4294,5439,5459))) ||
				(strpos($_SERVER["REQUEST_URI"], '/wtgbsuccess/') !== false && !memb_hasAnyTags(5964)) ||
				(strpos($_SERVER["REQUEST_URI"], '/wtgb/') !== false && !memb_hasAnyTags(1190)) ||
				(strpos($_SERVER["REQUEST_URI"], '/wtgfsuccess/') !== false && !memb_hasAnyTags(5944)) ||
				(strpos($_SERVER["REQUEST_URI"], 'com/wtgf/') !== false && !memb_hasAnyTags(2430)) ||
				(strpos($_SERVER["REQUEST_URI"], '/wtgp/') !== false && !memb_hasAnyTags(array(1940,6680,6684))) ||
				(strpos($_SERVER["REQUEST_URI"], '/courses/wtgwl/') !== false && !memb_hasAnyTags(array(6072,6442,7248))) ||
				(strpos($_SERVER["REQUEST_URI"], '/newscience/') !== false && !memb_hasAnyTags(6226)) ||
				(strpos($_SERVER["REQUEST_URI"], '/4-pillars/') !== false && !memb_hasAnyTags(array(1184,1192,4294,5261,5449,5439,6493,6487))) ||
				(strpos($_SERVER["REQUEST_URI"], '/category/milliondollarvids/') !== false && !memb_hasAnyTags(array(1192,5443,5439,5824,5964,5740))) ||
				(strpos($_SERVER["REQUEST_URI"], '/money2-2014/') !== false && !memb_hasAnyTags(array(1192,4294,5459,3969,5117,5261,5439,6493,6487,6962,6968,6112))) ||
				(strpos($_SERVER["REQUEST_URI"], '/success-manifestors-2/') !== false && !memb_hasAnyTags(array(1278,5439,5824,6493,6487))) ||
				(strpos($_SERVER["REQUEST_URI"], '/having-it-all-3/') !== false && !memb_hasAnyTags(array(7380,7338))) ||
				(strpos($_SERVER["REQUEST_URI"], '/having-it-all/') !== false && !memb_hasAnyTags(array(283,5439,5824,6493,6487))) ||
				(strpos($_SERVER["REQUEST_URI"], '/values-based-living/') !== false && !memb_hasAnyTags(array(3298,5439))) ||
				(strpos($_SERVER["REQUEST_URI"], '/how-to-get-more-done-in-less-time-sa/') !== false && !memb_hasAnyTags(7448)) ||
				(strpos($_SERVER["REQUEST_URI"], '/how-to-get-more-done-in-less-time-3-2/') !== false && !memb_hasAnyTags(7340)) ||
				(strpos($_SERVER["REQUEST_URI"], '/how-to-get-more-done-in-less-time/') !== false && !memb_hasAnyTags(array(1830,5261,5439,6493,6487))) ||
				(strpos($_SERVER["REQUEST_URI"], '/powerhabits/') !== false && !memb_hasAnyTags(5648)) ||
				(strpos($_SERVER["REQUEST_URI"], '/category/wtglife/') !== false && !memb_hasAnyTags(5261)) ||
				(strpos($_SERVER["REQUEST_URI"], '/mastering-your-mindset/welcome/') !== false && !memb_hasAnyTags(array(3983,6784))) ||
				(strpos($_SERVER["REQUEST_URI"], '/category/masteringyourmindset/') !== false && !memb_hasAnyTags(0000)) ||
				(strpos($_SERVER["REQUEST_URI"], '/cloningvs/') !== false && !memb_hasAnyTags(5972)) ||
				(strpos($_SERVER["REQUEST_URI"], '/assessment/') !== false && !memb_hasAnyTags(array(5261,5439,5453,5459,5824,5964,6976,6072,6442,7248))) ||
				(strpos($_SERVER["REQUEST_URI"], '/cloning12-14/') !== false && !memb_hasAnyTags(array(5584,4985,4450,5604))) ||
				(strpos($_SERVER["REQUEST_URI"], '/bonus-content') !== false && !memb_hasAnyTags(array(6493,6487,6072,6442,6680,6684,6962,6968)))) && strpos($_SERVER["REQUEST_URI"], 'msj') === false){
					wp_redirect(home_url()."/?msj=2"); exit;
				}

			}elseif(!is_user_logged_in() && has_shortcode( $post->post_content, 'praxis_login' ) && strpos($_SERVER["REQUEST_URI"], 'msj') === false){ //Scenario 2
				wp_redirect(home_url()."/?msj=1"); exit;
			}elseif(((!is_user_logged_in() && !has_shortcode( $post->post_content, 'praxis_login' )) || !is_user_logged_in()) && strpos($_SERVER["REQUEST_URI"], 'msj') === false){ //Scenario 1
				wp_redirect(home_url()."/?msj=1&redirect_to='http://".$_SERVER[HTTP_HOST].$_SERVER["REQUEST_URI"]."'"); exit;
			}
			//exit;
		//}
	}
	if(strpos($_SERVER["REQUEST_URI"], 'masteringyourmindset') !== false && strpos($_SERVER["REQUEST_URI"], 'msj') === false && !is_user_logged_in()){
		echo '<script type="text/javascript">window.location = "'.home_url()."/?msj=1&redirect_to=http://".$_SERVER[HTTP_HOST].$_SERVER["REQUEST_URI"].'"</script>'; exit;
	}
}
add_action( 'wp_enqueue_scripts', 'bww_check_access' ); 
add_action( 'template_redirect', 'bww_check_access', 100 );

function praxis_login($args){
	if(strpos($_SERVER["REQUEST_URI"], 'course') !== false || strpos($_SERVER["REQUEST_URI"], 'lessons') !== false){
		return do_shortcode("[memb_redirect url='/?msj=1&redirect_to=\"http://" .$_SERVER[HTTP_HOST].$_SERVER["REQUEST_URI"]. "\"]");exit;
	}
	else{
		return do_shortcode("[memb_redirect url='/?msj=2&redirect_to=\"http://" .$_SERVER[HTTP_HOST].$_SERVER["REQUEST_URI"]. "\"]");exit;
	}
}
add_shortcode('redirect_new', 'praxis_login');	
*/

function sc_url_not_contains( $atts, $content = null ) {
	if(strpos($_SERVER["REQUEST_URI"], $atts[0]) !== false){
		return '<script type="text/javascript">window.location = "'.home_url()."/?msj=1&redirect_to=http://".$_SERVER[HTTP_HOST].$_SERVER["REQUEST_URI"].'"</script>';
	}
	return '<script type="text/javascript">window.location = "'.home_url()."/?msj=1&redirect_to=http://".$_SERVER[HTTP_HOST].$_SERVER["REQUEST_URI"].'"</script>';

}
add_shortcode( 'url_not_contains', 'sc_url_not_contains' );


function diff_days($date_i,$date_f){
	//$secs=$date_i - $date_f;
	//$days=intval($secs/60/60/24);
	$days	= ($date_i-$date_f)/86400;
	$days 	= abs($days); $days = floor($days);		
	return $days;
}
/* NEW EXPLORE EXPERIENCE WITH POP UP */
function get_explore_experience2()
{
	date_default_timezone_set('America/Los_Angeles');
	if(isset($_REQUEST['t'])){
		$now       = strtotime("now");
		$InitCountDown = strtotime($_REQUEST['t1']);
		$CountDown = strtotime($_REQUEST['t2']);
		//$CountDown2 = strtotime("+15 seconds");
	}else{
		$now       = strtotime("now");
		$InitCountDown = strtotime("12:01am September 28 2016");
		$CountDown = strtotime("11:59pm October 2 2016");
		//$CountDown2 = strtotime("03:00am July 1 2016");
	}
	
	$InitCountTo = $InitCountDown;
	$countTo = $CountDown;
	$countTo2 = $CountDown2;
	
	$InitSeconds =  $InitCountTo - $now;
	$seconds =  $countTo - $now;
	$seconds2 =  $countTo2 - $now;
	
	if($InitSeconds < 1){
		$InitSeconds = 0;
	}
	if($seconds < 1){
		$seconds = 0;
	}
	if($seconds2 < 1){
		$seconds2 = 0;
	}
	$user_id = get_current_user_id();
	$class_upgrade_WTGM='';
	$onclick_upgrade_wtgm='onclick="tag(8751);"';
	$link_program_wtgm='';
	$class_upgrade_WTGM ='show_upgrade_wtgm show_upgrade';
	$onclick_upgrade_wtgm='onclick="_preventDefault(event);jQuery(\'.popup_upgrade_wtgm_explore\').show();tag(8751);"';
	if(memb_hasAnyTags(array(5439,5824)) && !memb_hasAnyTags(array(6493,6487,6112)) && !get_user_meta( $user_id, '_dont_show_again_WTGM' )){
	}
	if(memb_hasAnyTags(array(5439)) && !memb_hasAnyTags(array(5824))){
		$link_program_wtgm ='/wtgm/';
	}elseif((!memb_hasAnyTags(array(5439)) && memb_hasAnyTags(array(5824))) || (memb_hasAnyTags(array(5439)) && memb_hasAnyTags(array(5824)))){
		$link_program_wtgm ='/wtgmsuccess/';
	}
	/* check to Grey Explore icons based on enrollment dates - BWW Diego 10/4/2016 */
	$grayExplore=false;
	$now = strtotime("now");
	if(memb_hasAnyTags(array(6962,6112,9663,9669,7994,8474,7950,8204,10331,10335,11126,11130))){
		$user_id = get_current_user_id();
		$courses = get_pages("post_type=sfwd-courses");
		$prog=0;
		foreach($courses as $course) { 
			if(sfwd_lms_has_access($course->ID,  $user_id)) {
				$sinceData = ld_course_access_from($course->ID,  $user_id);
				if(diff_days($sinceData,$now)>=0 && diff_days($sinceData,$now)<=35){
					$firstEnroll=$sinceData;
					if($firstEnroll>strtotime("now")) $firstEnroll = strtotime("now");
					$prog++;
				}
			}
		}
		$xxDays=35-diff_days($firstEnroll,$now);
		//var_dump($firstEnroll);
		//var_dump(diff_days($firstEnroll,$now));
		//var_dump($xxDays);
		//var_dump($prog);
		//var_dump($xxDays);
		if($prog>=1 && $xxDays>=1){
			$grayExplore=true;
		}
		if($xxDays==1){
			$xxDays=$xxDays. " day";
		}else{
			$xxDays=$xxDays. " days";
		}
                if($sinceData==""){
                    if(memb_hasAnyTags(array(11126,11130))){
                        global $wpdb;
                        $query = "select date from vault2time where user_id=".get_current_user_id()."";
                        $dateVault=$wpdb->get_var($query);
                        if($dateVault==NULL){
                            $grayExplore=true;
                            $xxDays= "35 days";
                        }else{
                            $xxDays=35-diff_days(strtotime($dateVault),$now);
                            if($xxDays>=1){
                                    $grayExplore=true;
                            }
                            if($xxDays==1){
                                    $xxDays=$xxDays. " day";
                            }else{
                                    $xxDays=$xxDays. " days";
                            }
                        }
                    }else{
                        $grayExplore=true;
                        $xxDays= "35 days";
                    }
                }

                
                
                
                
                
                
	}
	
        $no_tags = "5449,5972,7950,8803,283,7338,8895,8907,7340,5740,6216,4348,5117,5648,8969,7934,1278,3298,7342,1190,2430,5261,1192,1940,5964,8591,5944,6680,6684,5750,5824,5439,6487,6493,6962,6968,6072,7248,6442,7994,8236";
        
    $output = '<link href="'.plugins_url( 'bww-myn-plugin/css/countdown.css' ).'" rel="stylesheet" /><script src="'.plugins_url( 'bww-myn-plugin/js/jquery.countdown.min.js' ).'"></script>
<script type="text/javascript">
 $(function() {
   $("#InitcountdownContainer").countdown({
     date: +(new Date) + '.$InitSeconds.' * 1000, // convert seconds to miliseconds seconds
	 render: function(data) {
		//$(this.el).html(this.leadingZeros(data.days, 2) + "d, " + this.leadingZeros(data.hours, 2) + "h, " + this.leadingZeros(data.min, 2) + "m, " + this.leadingZeros(data.sec, 2) + "s");
		$("#InitcountdownContainer").html("<!--"+this.leadingZeros(data.days, 2)+":"+this.leadingZeros(data.hours, 2)+":"+this.leadingZeros(data.min, 2)+":"+this.leadingZeros(data.sec, 2)+"-->")
	 },
	 onEnd: function(data) {
	   $(".timer_container").show("fast");
	   $("#countdownContainer1").show("fast");
	   $("#countdownContainer1").countdown({
		 date: +(new Date) + '.$seconds.' * 1000, // convert seconds to miliseconds seconds
		 render: function(data) {
			//$(this.el).html(this.leadingZeros(data.days, 2) + "d, " + this.leadingZeros(data.hours, 2) + "h, " + this.leadingZeros(data.min, 2) + "m, " + this.leadingZeros(data.sec, 2) + "s");
			$(".days").html(this.leadingZeros(data.days, 2));
			$(".hours").html(this.leadingZeros(data.hours, 2));
			$(".minutes").html(this.leadingZeros(data.min, 2));
			$(".seconds").html(this.leadingZeros(data.sec, 2));
		 },
		 onEnd: function(data) {
			 $("#countdownContainer1").remove();
			 $(".timer_container").remove()
			 /*$("#countdownContainer2").show();
			   $("#countdownContainer2").countdown({
				 date: +(new Date) + '.$seconds2.' * 1000, // convert seconds to miliseconds seconds
				 render: function(data) {
					//$(this.el).html(this.leadingZeros(data.days, 2) + "d, " + this.leadingZeros(data.hours, 2) + "h, " + this.leadingZeros(data.min, 2) + "m, " + this.leadingZeros(data.sec, 2) + "s");
					$(".days").html(this.leadingZeros(data.days, 2));
					$(".hours").html(this.leadingZeros(data.hours, 2));
					$(".minutes").html(this.leadingZeros(data.min, 2));
					$(".seconds").html(this.leadingZeros(data.sec, 2));
				 },
				 onEnd: function(data) {
					 $(".timer_container").remove()
				},
			   });*/
		},
	   });
    },
   });
});
</script> 
<div id="InitcountdownContainer" style="display:none"></div>
<div id="countdownContainer1" class="countdown-header" style="display:none"><div class="countdown-container">
          <div class="countdown-header-col">
            <div class="countdown-header-countdown-box"><span class="days"></span>
              <div class="countdown-header-time-unit">days</div>
            </div>
          </div>
          <div class="countdown-header-col">
            <div class="countdown-header-countdown-box"><span class="hours"></span>
              <div class="countdown-header-time-unit">hours</div>
            </div>
          </div>
          <div class="countdown-header-col">
            <div class="countdown-header-countdown-box"><span class="minutes"></span>
              <div class="countdown-header-time-unit">minutes</div>
            </div>
          </div>
          <div class="countdown-header-col">
            <div class="countdown-header-countdown-box"><span class="seconds"></span>
              <div class="countdown-header-time-unit">seconds</div>
            </div>
          </div>
        </div></div>
<div id="countdownContainer2" class="countdown-header" style="display:none"><div class="countdown-container">
          <div class="countdown-header-col">
            <div class="countdown-header-countdown-box"><span class="days"></span>
              <div class="countdown-header-time-unit">days</div>
            </div>
          </div>
          <div class="countdown-header-col">
            <div class="countdown-header-countdown-box"><span class="hours"></span>
              <div class="countdown-header-time-unit">hours</div>
            </div>
          </div>
          <div class="countdown-header-col">
            <div class="countdown-header-countdown-box"><span class="minutes"></span>
              <div class="countdown-header-time-unit">minutes</div>
            </div>
          </div>
          <div class="countdown-header-col">
            <div class="countdown-header-countdown-box"><span class="seconds"></span>
              <div class="countdown-header-time-unit">seconds</div>
            </div>
          </div>
        </div></div></div>';
		$output ='';
		if($seconds>0 && !$grayExplore){
			$output .= '<div class="explore-list">'
				. '[memb_hide_from tagid="10331,6968,6493,5824,1192,4294,5439,5459,6962"]'
				. '<div class="product-rectangle2" style="height: 300px;">'
				. '<a href="https://www.myneurogym.com/WTGM/members/discount/index.php" target="_blank" class="program-wtgm31"><img src="'.get_template_directory_uri().'/images/newprograms/asides/icon-wtgm32-a.png" /><br />Winning The Game <br class="will-hide" /> Of Money</a>'
				. '</div>'
				. '[/memb_hide_from]'
				
				. '[memb_has_any_tag tagid="5439,5824,6962" except_tagid="10331"]'
				. '<div class="product-rectangle2 '.$class_upgrade_WTGM.'" style="height: 300px;">'
				. '<a href="https://www.myneurogym.com/WTGM/members/discount/index.php" target="_blank" class="program-wtgm31"><img src="'.get_template_directory_uri().'/images/newprograms/asides/icon-wtgm-upgrade-a.png" /><br />Winning The Game <br class="will-hide" /> Of Money</a>'
				. '</div>'
				. '[/memb_has_any_tag]'
				
				
				. '[memb_hide_from tagid="9663,9689"]'
				. '<div class="product-rectangle2" style="height: 300px;">'
				. '<a href="https://www.myneurogym.com/winning-the-game-of-fear/order/members/discount/index.php" target="_blank"><img src="'.get_template_directory_uri().'/images/newprograms/asides/icon-wtgf-3_2.png" /><br />Winning The Game<br class="will-hide" /> Of Fear</a>'
				. '</div>'
				. '[/memb_hide_from]'
				
				. '[memb_hide_from tagid="5964,1190,10990,10992"]'
				. '<div class="product-rectangle2" style="height: 300px;">'
				. '<a href="https://www.myneurogym.com/WTGB/order/members/discount/" target="_blank"><img src="'.get_template_directory_uri().'/images/newprograms/asides/icon-wtgb25-a.png" /><br />Winning The Game <br class="will-hide" /> Of Business</a></div>'
				. '[/memb_hide_from]'
				
				. '[memb_hide_from tagid="6072,6442,7994"]'
				. '<div class="product-rectangle2" style="height: 300px;">'
				. '<a href="https://www.myneurogym.com/winning-the-game-of-weight-loss/order/members/discount/index.php" target="_blank"><img src="'.get_template_directory_uri().'/images/newprograms/asides/icon-wtgwl31-a.png" /><br />Winning The Game <br class="will-hide" />Of Weight Loss</a></div>'
				. '[/memb_hide_from]'
				
				. '[memb_hide_from tagid="7338,283,5439,5824" except_tagid="7380"]'
				. '<div class="product-rectangle2" style="height: 300px;">'
				. '<a href="https://www.myneurogym.com/having-it-all/members/discount/index.php" target="_blank"><img src="'.get_template_directory_uri().'/images/newprograms/asides/icon-hia30-a.png" /><br />Having It All</a>'
				. '</div>'
				. '[/memb_hide_from]'
				
				. '[memb_hide_from tagid="7448,5261,5439,7340"]'
				. '<div class="product-rectangle2" style="height: 300px;">'
				. '<a href="https://www.myneurogym.com/how-to-get-more-done-in-less-time/members/discount/index.php" target="_blank"><img src="'.get_template_directory_uri().'/images/newprograms/asides/icon-moredonelesstime30-a.png" /><br />How To Get More Done In Less Time</a></div>'
				. '[/memb_hide_from]'
				
				. '[memb_hide_from tagid="5972,10990,10992"]'
				. '<div class="product-rectangle2" style="height: 300px;">'
				. '<a href="https://www.myneurogym.com/cloning-of-business-success/members/discount/index.php" target="_blank"><img src="'.get_template_directory_uri().'/images/newprograms/asides/icon-cobs-a.png" /><br />The Cloning Of<br class="will-hide" /> Business Success</a></div>'
				. '[/memb_hide_from]'
				
				. '[memb_hide_from tagid="3983,6784,0000,9083" except_tagid="'.$no_tags .'"]'
				. '<div class="product-rectangle2" style="height: 300px">'
				. '<a href="#" onclick="jQuery(\'.popup-explore\').hide();jQuery(\'#popup-mym\').fadeIn(600);tag(8609);" target="_blank"><img src="'.get_template_directory_uri().'/images/newprograms/asides/icon-mym-a.png" /><br />Mastering Your Mindset</a></div>          '
				. '[/memb_hide_from]' 
				. '<br></div>';
		}elseif($grayExplore){
			$output .= 'We have another exciting bonus for you! In just <b>'.$xxDays.'</b> we will unlock the below explore section which will allow you to test drive our other programs. For now, make sure to focus on the program you just started and let us know if you have any questions.<br /><br />'
				. '<div class="explore-list">'
				. '[memb_hide_from tagid="10331,6968,6493,5824,1192,4294,5439,5459,6962"]'
				. '<div class="product-rectangle2" style="height: 300px;">'
				. '<a href="#" class="program-wtgm31"><img src="'.get_template_directory_uri().'/images/newprograms/asides/icon-wtgm32-a-greyscale.png" /><br />Winning The Game <br class="will-hide" /> Of Money</a>'
				. '</div>'
				. '[/memb_hide_from]'
				
				. '[memb_has_any_tag tagid="5439,5824" except_tagid="10331"]'
				. '<div class="product-rectangle2 '.$class_upgrade_WTGM.'" style="height: 300px;">'
				. '<a href="#" class="program-wtgm31"><img src="'.get_template_directory_uri().'/images/newprograms/asides/icon-wtgm-upgrade-a-greyscale.png" /><br />Winning The Game <br class="will-hide" /> Of Money</a>'
				. '</div>'
				. '[/memb_has_any_tag]'
				
				
				. '[memb_hide_from tagid="9663,9689"]'
				. '<div class="product-rectangle2" style="height: 300px;">'
				. '<a href="#"><img src="'.get_template_directory_uri().'/images/newprograms/asides/icon-wtgf-3_2-greyscale.png" /><br />Winning The Game<br class="will-hide" /> Of Fear</a>'
				. '</div>'
				. '[/memb_hide_from]'
				
				. '[memb_hide_from tagid="5964,1190,10990,10992"]'
				. '<div class="product-rectangle2" style="height: 300px;">'
				. '<a href="#"><img src="'.get_template_directory_uri().'/images/newprograms/asides/icon-wtgb25-a-greyscale.png" /><br />Winning The Game <br class="will-hide" /> Of Business</a></div>'
				. '[/memb_hide_from]'
				
				. '[memb_hide_from tagid="6072,6442,7994"]'
				. '<div class="product-rectangle2" style="height: 300px;">'
				. '<a href="#"><img src="'.get_template_directory_uri().'/images/newprograms/asides/icon-wtgwl31-a-greyscale.png" /><br />Winning The Game <br class="will-hide" />Of Weight Loss</a></div>'
				. '[/memb_hide_from]'
				
				. '[memb_hide_from tagid="7338,283,5439,5824" except_tagid="7380"]'
				. '<div class="product-rectangle2" style="height: 300px;">'
				. '<a href="#"><img src="'.get_template_directory_uri().'/images/newprograms/asides/icon-hia30-a-greyscale.png" /><br />Having It All</a>'
				. '</div>'
				. '[/memb_hide_from]'
				
				. '[memb_hide_from tagid="7448,5261,5439,7340"]'
				. '<div class="product-rectangle2" style="height: 300px;">'
				. '<a href="#"><img src="'.get_template_directory_uri().'/images/newprograms/asides/icon-moredonelesstime30-a-greyscale.png" /><br />How To Get More Done In Less Time</a></div>'
				. '[/memb_hide_from]'
				
				. '[memb_hide_from tagid="5972,10990,10992"]'
				. '<div class="product-rectangle2" style="height: 300px;">'
				. '<a href="#"><img src="'.get_template_directory_uri().'/images/newprograms/asides/icon-cobs-a-greyscale.png" /><br />The Cloning Of<br class="will-hide" /> Business Success</a></div>'
				. '[/memb_hide_from]'
				
				. '[memb_hide_from tagid="3983,6784,0000,9083" except_tagid="'.$no_tags .'"]'
				. '<div class="product-rectangle2" style="height: 300px">'
				. '<a href="#"><img src="'.get_template_directory_uri().'/images/newprograms/asides/icon-mym-a-greyscale.png" /><br />Mastering Your Mindset</a></div>          '
				. '[/memb_hide_from]' 
				. '<br></div>';
		}else{
			$output .= '<div class="explore-list">'
				. '[memb_hide_from tagid="10331,6968,6493,5824,1192,4294,5439,5459,6962"]'
				. '<div class="product-rectangle2" style="height: 300px;">'
				. '<a href="#" onclick="jQuery(\'.popup-explore\').hide();jQuery(\'#popup-wtgm-exp\').fadeIn(600);tag(8595);" class="program-wtgm31"><img src="'.get_template_directory_uri().'/images/newprograms/asides/icon-wtgm32-a.png" /><br />Winning The Game <br class="will-hide" /> Of Money</a>'
				. '</div>'
				. '[/memb_hide_from]'
				
				. '[memb_has_any_tag tagid="5439,5824" except_tagid="6962"]'
				. '<div class="product-rectangle2 '.$class_upgrade_WTGM.'" style="height: 300px;">'
				. '<a href="'.$link_program_wtgm.'" '.$onclick_upgrade_wtgm.' class="program-wtgm31"><img src="'.get_template_directory_uri().'/images/newprograms/asides/icon-wtgm-upgrade-a.png" /><br />Winning The Game <br class="will-hide" /> Of Money</a>'
				. '</div>'
				. '[/memb_has_any_tag]'
				
				
				. '[memb_hide_from tagid="9663,9689"]'
				. '<div class="product-rectangle2" style="height: 300px;">'
				. '<a href="#" onclick="jQuery(\'.popup-explore\').hide();jQuery(\'#popup-wtgf\').fadeIn(600);tag(8597);"><img src="'.get_template_directory_uri().'/images/newprograms/asides/icon-wtgf-3_2.png" /><br />Winning The Game<br class="will-hide" /> Of Fear</a>'
				. '</div>'
				. '[/memb_hide_from]'
				
				. '[memb_hide_from tagid="5964,1190,10990,10992"]'
				. '<div class="product-rectangle2" style="height: 300px;">'
				. '<a href="#" onclick="jQuery(\'.popup-explore\').hide();jQuery(\'#popup-wtgb\').fadeIn(600);tag(8599);"><img src="'.get_template_directory_uri().'/images/newprograms/asides/icon-wtgb25-a.png" /><br />Winning The Game <br class="will-hide" /> Of Business</a></div>'
				. '[/memb_hide_from]'
				
				. '[memb_hide_from tagid="6072,6442,7994"]'
				. '<div class="product-rectangle2" style="height: 300px;">'
				. '<a href="#" onclick="jQuery(\'.popup-explore\').hide();jQuery(\'#popup-wtgwl\').fadeIn(600);tag(8601);"><img src="'.get_template_directory_uri().'/images/newprograms/asides/icon-wtgwl31-a.png" /><br />Winning The Game <br class="will-hide" />Of Weight Loss</a></div>'
				. '[/memb_hide_from]'
				
				. '[memb_hide_from tagid="7338,283,5439,5824" except_tagid="7380"]'
				. '<div class="product-rectangle2" style="height: 300px;">'
				. '<a href="#" onclick="jQuery(\'.popup-explore\').hide();jQuery(\'#popup-hia\').fadeIn(600);tag(8603);"><img src="'.get_template_directory_uri().'/images/newprograms/asides/icon-hia30-a.png" /><br />Having It All</a>'
				. '</div>'
				. '[/memb_hide_from]'
				
				. '[memb_hide_from tagid="7448,5261,5439,7340"]'
				. '<div class="product-rectangle2" style="height: 300px;">'
				. '<a href="#" onclick="jQuery(\'.popup-explore\').hide();jQuery(\'#popup-htgmdilt\').fadeIn(600);tag(8605);"><img src="'.get_template_directory_uri().'/images/newprograms/asides/icon-moredonelesstime30-a.png" /><br />How To Get More Done In Less Time</a></div>'
				. '[/memb_hide_from]'
				
				. '[memb_hide_from tagid="5972,10990,10992"]'
				. '<div class="product-rectangle2" style="height: 300px;">'
				. '<a href="#" onclick="jQuery(\'.popup-explore\').hide();jQuery(\'#popup-cobs\').fadeIn(600);tag(8607);"><img src="'.get_template_directory_uri().'/images/newprograms/asides/icon-cobs-a.png" /><br />The Cloning Of<br class="will-hide" /> Business Success</a></div>'
				. '[/memb_hide_from]'
				
				. '[memb_hide_from tagid="3983,6784,0000,9083" except_tagid="'.$no_tags .'"]'
				. '<div class="product-rectangle2" style="height: 300px">'
				. '<a href="#" onclick="jQuery(\'.popup-explore\').hide();jQuery(\'#popup-mym\').fadeIn(600);tag(8609);"><img src="'.get_template_directory_uri().'/images/newprograms/asides/icon-mym-a.png" /><br />Mastering Your Mindset</a></div>          '
				. '[/memb_hide_from]' 
				. '<br></div>';
		}
    return do_shortcode($output);
}
add_shortcode('explore_experience2', 'get_explore_experience2');

/*  End new eplore experience   */




/* APPLY TAG function for download links */
function apply_tag(){
    
    if(isset($_POST['apply_tag']))
    {
		$app = new iSDK;
		$conId=get_current_IFS_contact_info(array(  'Id'));
		$conId=$conId[0]['Id'];

        $app = new iSDK;
        if(!$app->cfgCon("connectionName"))
        {
            echo "Did not connect.";
            exit();
        }
		$tagId=$_POST['tag_id'];
		
		$app->grpAssign($conId, $tagId);
		echo "Updated!";
                do_shortcode('[memb_sync_contact]');
		$user_id = get_current_user_id();
		if($_POST['programEnroll']=='wtgwl'){
			$course_id=169058;
		}
		if($_POST['programEnroll']=='wtgm'){
			$course_id=135900;
		}
		ld_update_course_access($user_id, $course_id, $remove = false);
		$sinceData = ld_course_access_from($course_id,  $user_id);
		$sinceM = empty($sinceData)? "":date("m", $sinceData);
		$sinceD = empty($sinceData)? "":date("d", $sinceData);
		$sinceY = empty($sinceData)? "":date("Y", $sinceData);
		$dateAccess=strtotime($sinceM."/".$sinceD."/".$sinceY." 00:00:00");
		update_user_meta($user_id, "course_".$course_id."_access_from", $dateAccess);
    }
	elseif(isset($_POST['apply_tag_button']))
    {
		$app = new iSDK;
		$conId=get_current_IFS_contact_info(array(  'Id'));
		$conId=$conId[0]['Id'];

        $app = new iSDK;
        if(!$app->cfgCon("connectionName"))
        {
            echo "Did not connect.";
            exit();
        }
		$tagId=$_POST['tag'];
		
		$app->grpAssign($conId, $tagId);
		echo "Updated!";
		do_shortcode('[memb_sync_contact]');
    }
    else
    {
        echo "notset!";
    }
    die();
}
add_action('wp_ajax_apply_tag', 'apply_tag');
add_action('wp_ajax_nopriv_apply_tag', 'apply_tag');

function afteremailchanged(){
 if(isset($POST['change_email_1'])){
  wp_redirect('/my-profile'); exit;
 }
 if(isset($_POST["memb_form_type"]) && $_POST["memb_form_type"] == 'memb_change_email'){?>
  <style>body{display:none;}</style>
  <script>
         window.location = "<?php echo get_home_url()?>/my-profile";
        </script>
 <?php 
 }
}
add_action( 'plugins_loaded', 'afteremailchanged' );

function hide_referrer(){
 //print_r($_SERVER['HTTP_REFERER']);
 //print_r(get_home_url());
 //exit;
 $home_link = get_home_url();?>
  <style>body{display:none;}</style>
   <script>
                window.location = "<?php echo get_home_url()?>";

            </script>

<?php exit;}
add_action( 'wp_logout', 'hide_referrer' ); 

function change_referrer(){
 if(strpos($_SERVER['HTTP_REFERER'], 'logout') !== false){?>
   <script>
                window.location = "<?php echo get_home_url()?>";
            </script>

 <?php exit;}

}
add_action( 'init', 'change_referrer' );


function show_server_name(){
    if(isset($_GET['showsrvname'])){
        $hostname = gethostname();
        switch($hostname) {
               case "ip-172-30-1-124.ec2.internal":
                   $thehost = "PRD-SRV-B02";
                   break;
               case "ip-172-30-1-199.ec2.internal":
                   $thehost = "PRD-SRV-B02-CLONE";
                   break;
               case "ip-172-30-6-193.ec2.internal":
                   $thehost = "PRD-SRV-D02";
                   break;               
               case "ip-172-30-6-196.ec2.internal":
                   $thehost = "PRD-SRV-D02-CLONE";
                   break;
               case "ip-10-182-68-112":
                   $thehost = "BETA_SRV";
                   break;
               case "ip-172-30-4-66.ec2.internal":
                   $thehost = "PRD_SRV";
                   break;               
               default:
                   $thehost = $hostname;
        }
        echo '<div style="position: absolute; top: 0; left: 0; color: red;">'. $thehost  .'</div>';
    }
}
add_action( 'init', 'show_server_name' );


function runPointsProcess(){
    if(isset($_GET['runpointprocess'])){
        echo 'Run Process Points';
        $argus = array (
            'role'      => 'subscriber',
            'fields'    => array('ID','user_email')  
        );
        $blogusers = get_users($argus);
        foreach ( $blogusers as $user ) {
            echo 'Points For User: '. $user->user_email;
            $MYM_ID = 3983; 
            $NO_MYM_ID = 3985;
            if(userHasAnyTag($user->user_email,$MYM_ID) && !userHasAnyTag($user->user_email,$NO_MYM_ID))
            {
                mycred_add( "ProgramMYM", $user->ID, 25 ,"Points for Monthly MYM Subscription" , date(''));                    
                echo 'Points For User: '. $user->user_email . ' Added!';
            }
        }
        echo 'Points Process End ';
    }
}
add_action( 'init', 'runPointsProcess' );

function community_page_set_fb_name(){
    
    if(isset($_POST['fb_name']))
    {
		$app = new iSDK;
		$conId=get_current_IFS_contact_info(array(  'Id'));
		$conId=$conId[0]['Id'];

        $app = new iSDK;
        if(!$app->cfgCon("connectionName"))
        {
            echo "Did not connect.";
            exit();
        }
        echo "connected!";
        $conDat = array(
                        '_FacebookUserName' => $_POST['fb_name']
                );
        $conID = $app->updateCon($conId, $conDat);
        //alex added facebook tagging
        $app->grpAssign($conId, "10419");
	    //$sessid = session_id();
		//setcookie( 'fb_updated', $sessid, time() + 3600, COOKIEPATH, COOKIE_DOMAIN );
		echo "Updated!";
    }
    else
    {
        echo "notset!";
    }
    die();
}
add_action('wp_ajax_community_page_set_fb_name', 'community_page_set_fb_name');
add_action('wp_ajax_nopriv_community_page_set_fb_name', 'community_page_set_fb_name');

function fb_community_field($atts, $content = null){
$facebook_name=get_current_IFS_contact_info(array(  '_FacebookUserName'));
$facebook_name=$facebook_name[0]['_FacebookUserName'];
if(!is_user_logged_in() || $facebook_name=='E')
$facebook_name='';
$content="<input type='text' name='fb_name' id='fb_name' placeholder='Facebook Name...' class='community_input' value='".$facebook_name."' />";
	 return $content;
}
add_shortcode('fb_community_input', 'fb_community_field');	
function get_fb_name_community_page($atts, $content = null){
$facebook_name=get_current_IFS_contact_info(array(  '_FacebookUserName'));
$facebook_name=$facebook_name[0]['_FacebookUserName'];
$content=$facebook_name;
	 return $content;
}
add_shortcode('fbname_ifs', 'get_fb_name_community_page');	

function my_password_form() {
    global $post;
    wp_enqueue_style( 'bww-css_members', get_template_directory_uri(). '/style-members.css','',false,'all');
    $label = 'pwbox-'.( empty( $post->ID ) ? rand() : $post->ID );
    $o = '<style>#homelink { display:none !important;}</style><div class="white-box-620"><form action="' . esc_url( site_url( 'wp-login.php?action=postpass', 'login_post' ) ) . '" method="post" id="loginform">
    ' . __( "Enter the password to view this page:" ) . '
    <p class="login-password"><input type="password" style="-webkit-box-shadow: 0 0 0px 1000px #8953A8 inset; -webkit-text-fill-color:#fff;" name="post_password" id="' . $label . '" class="input" value="" size="20" placeholder="Password:"></p><p class="login-submit"><input type="submit" name="Submit" value="' . esc_attr__( "Submit" ) . '" id="wp-submit" class="button-primary" style="top:60px"/></p>
    </form></div>
    ';
    return $o;
}
add_filter( 'the_password_form', 'my_password_form' );
function get_timesrv(){
    echo time();
    die();
}
add_action('wp_ajax_get_timesrv', 'get_timesrv');
add_action('wp_ajax_nopriv_get_timesrv', 'get_timesrv');
function dont_show_again(){
	$user_id = get_current_user_id();
	$meta_popup=$_POST['dont_show_again_popup'];
	if(get_user_meta( $user_id, $meta_popup ))
		update_user_meta( $user_id, $meta_popup, true );
	else
		add_user_meta( $user_id, $meta_popup, true );
    die();
}
add_action('wp_ajax_dont_show_again', 'dont_show_again');
add_action('wp_ajax_nopriv_dont_show_again', 'dont_show_again');

function setTimeZoneName(){
	$tz=$_POST['tz'];
		if ($tz <= -11){$timezone = 'Pacific/Midway';}
		if ($tz >= -11 && $tz <= -10){$timezone = 'US/Hawaii';}
		if ($tz >= -10 && $tz <= -9){$timezone = 'US/Alaska';}
		if ($tz >= -9 && $tz <= -8){$timezone = 'US/Pacific';}
		if ($tz >= -8 && $tz <= -7){$timezone = 'America/Los_Angeles';}
		if ($tz > -7 && $tz <= -6){$timezone = 'US/Arizona';}
		if ($tz >= -6 && $tz <= -5){$timezone = 'US/Eastern';}
		if ($tz >= -5 && $tz <= -4){$timezone = 'Canada/Atlantic';}
		if ($tz >= -4 && $tz <= -3){$timezone = 'America/Buenos_Aires';}
		if ($tz > -3 && $tz <= -2){$timezone = 'Atlantic/Stanley';}
		if ($tz >= -2 && $tz <= 1){$timezone = 'Atlantic/Azores';}
		if ($tz >= -1 && $tz <= 0){$timezone = 'Europe/Dublin';}
		if ($tz >= 0 && $tz <= 1){$timezone = 'Europe/Amsterdam';}
		if ($tz >= 1 && $tz <= 2){$timezone = 'Europe/Athens';}
		if ($tz >= 2 && $tz <= 3){$timezone = 'Asia/Baghdad';}
		if ($tz >= 3 && $tz <= 4){$timezone = 'Asia/Baku';}
		if ($tz >= 4 && $tz <= 5){$timezone = 'Asia/Karachi';}
		if ($tz >= 5 && $tz <= 6){$timezone = 'Asia/Yekaterinburg';}
		if ($tz >= 6 && $tz <= 7){$timezone = 'Asia/Novosibirsk';}
		if ($tz >= 7 && $tz <= 8){$timezone = 'Asia/Krasnoyarsk';}
		if ($tz >= 8 && $tz <= 9){$timezone = 'Asia/Irkutsk';}
		if ($tz >= 9 && $tz <= 10){$timezone = 'Australia/Sydney';}
		if ($tz >= 10 && $tz <= 11){$timezone = 'Asia/Vladivostok';}
		if ($tz >= 11){$timezone = 'Pacific/Fiji';}
		$app = new iSDK;
		$conId=get_current_IFS_contact_info(array(  'Id'));
		$conId=$conId[0]['Id'];
		$conUTZ=get_current_IFS_contact_info(array(  '_UserTimeZone'));
		$conUTZ=$conUTZ[0]['_UserTimeZone'];
		echo $conUTZ;
        $app = new iSDK;
        if(!$app->cfgCon("connectionName"))
        {
            echo "Did not connect.";
            exit();
        }
		if(!empty($conUTZ)){
			exit;
		}		
		$conDat = array('_UserTimeZone' => $timezone);
		$conID = $app->updateCon($conId, $conDat);
    die();
}
add_action('wp_ajax_setTimeZoneName', 'setTimeZoneName');
add_action('wp_ajax_nopriv_setTimeZoneName', 'setTimeZoneName');


function sess_test(){
	if(isset($_REQUEST['show_sessions_db'])){
		global $wpdb;
		$myrows = $wpdb->get_results( "SELECT * FROM $wpdb->pantheon_sessions" );
		print_r($myrows);
	}
}
add_action('init', 'sess_test');


/**********
 * Run Once a Month the Points addition
 */

function add_points_for_programs(){ 
    $date = date('d');
    if ('01' == $date) {
        $argus = array (
            'role'      => 'subscriber',
            'fields'    => array('ID','user_email')  
        );
        $blogusers = get_users($argus);
        foreach ( $blogusers as $user ) {
                $MYM_ID = 3983; 
                $NO_MYM_ID = 3985;
                if(userHasAnyTag($user->user_email,$MYM_ID) && !userHasAnyTag($user->user_email,$NO_MYM_ID))
                {
                    mycred_add( "ProgramMYM", $user->ID, 25 ,"Points for Monthly MYM Subscription" , date(''));                    
                }
        }
    }    
}
add_action('add_points_action','add_points_for_programs');
wp_schedule_event(1467378000, 'daily', 'add_points_action');


function program_4_points_menu() {
	add_options_page( 'Program 4 Points', 'Program 4 Points', 'manage_options', 'bww-prog-4-points', 'program_4_points_options' );
}

function program_4_points_options() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
        global $wpdb;
	$results = $wpdb->get_results("SELECT mt.id,mt.name, IFNULL(p4p.points,0) as points
            FROM memberium_tags mt
            LEFT join program_4_points p4p ON mt.id = p4p.id
            WHERE mt.name NOT LIKE '%PAYF%'  
            AND mt.name NOT LIKE '%ADMIN%'
            AND mt.name NOT LIKE '%Sampler%'
            AND mt.name NOT LIKE '%Free%'
            AND category = 17
            ORDER BY NAME;", ARRAY_N);  
        echo '<div class="wrap">';
        echo '<h1>Programs For Points Administration Page</h1>';
        echo '<form method="post" action="">';
        echo '<table><tr><td>Program Tag</td><td>Program Name</td><td>Points To Get IT</td><td>Link Example</td></tr>';        
        foreach ($results as $program) {
            echo '<tr><td><input type="text" value="'. $program[0] .'" name="p4ptag[]" /></td>'
                . '<td><input type="text" value="'. $program[1] .'" name="p4pname[]" style="width: 467px;" /></td>'
                . '<td><input type="text" value="'. $program[2] .'" name="p4ppoints[]" /></td>'
                . '<td>&lt;a class="p4pclass" tag="'. $program[0] .'" >This Link&lt;/a></td></tr>';
        }
	
	echo '</table><input type="submit" value="submit" /></form><hr />';
        echo '<h1>Points Awarded for Actions</h1>';
        
        
        $results = $wpdb->get_results("SELECT description, points, window, repetition FROM `bww-points`  ORDER BY description", ARRAY_N);
        echo '<form method="post" action="">';
        echo '<table><tr><td>Description</td><td>Points To award</td><td>Time to Wait (days)</td><td>Repetition</td></tr>';        
        foreach ($results as $program) {
            echo '<tr><td><input type="text" value="'. $program[0] .'" name="pawdesc[]" style="width: 467px;" /></td>'
                . '<td><input type="text" value="'. $program[1] .'" name="pawval[]"  /></td>'
                . '<td><input type="text" value="'. $program[2] .'" name="pawwind[]" /></td>'
                . '<td><input type="text" value="'. $program[3] .'" name="pawrep[]" /></td></tr>';
        }
        echo '<tr><td><input type="text" value="" name="pawdesc[]" style="width: 467px;" /></td>'
                . '<td><input type="text" value="" name="pawval[]"  /></td>'
                . '<td><input type="text" value="" name="pawwind[]" /></td>'
                . '<td><input type="text" value="" name="pawrep[]" /></td></tr>';
	
	echo '</table><input type="submit" value="submit" /></form></div>';
        
        
        
}

add_action( 'admin_menu', 'program_4_points_menu' );

function program_4_points_save() {
    if(isset($_POST['p4ppoints']))
    {
        global $wpdb;
        $result = $wpdb->get_var("truncate table program_4_points;");
        $tags = $_POST['p4ptag'];
        $name = $_POST['p4pname'];
        $points = $_POST['p4ppoints'];
        foreach( $name as $key => $n ) {
            $result = $wpdb->get_var("insert into program_4_points (id,name,points) VALUES(". $tags[$key].",'". $n."',". $points[$key].");");
        }
    } else if(isset($_POST['pawdesc']))
    {
        global $wpdb;
        $result = $wpdb->get_var("truncate table `bww-points`;");
        $desc = $_POST['pawdesc'];
        $val = $_POST['pawval'];
        $wind = $_POST['pawwind'];
        $rep = $_POST['pawrep'];
        foreach( $desc as $key => $n ) {
            $query = "insert into `bww-points` (description, points, window, repetition) VALUES('". $n ."', " . $val[$key].", " . $wind[$key].", " . $rep[$key].");";
            $result = $wpdb->get_var($query);
        }
    }
}
add_action('init', 'program_4_points_save');

function add_program_4_points()
{
    global $wpdb;
    $user_id = get_current_user_id();
    if(isset($_POST["ref"]) && isset($_POST['entry']))
    {
        $results = $wpdb->get_row("SELECT mt.id,mt.name, IFNULL(p4p.points,0) as points
            FROM memberium_tags mt
            LEFT join program_4_points p4p ON mt.id = p4p.id
            WHERE mt.id = ". $_POST['entry'] . ";", ARRAY_N);  
        $user_points = mycred_get_users_cred($user_id);
        $deduct = $results[2] * -1;
        if($user_points >= $results[2])
        {
            mycred_add( $_POST["ref"], get_current_user_id(), $deduct,$results[1] . ' Program Added' , date(''));
            $app = new iSDK;
            $conId=get_current_IFS_contact_info(array('Id'));
            $conId=$conId[0]['Id'];
            $app = new iSDK;
            if(!$app->cfgCon("connectionName"))
            {
                echo "Did not connect.";
                exit();
            }
            $app->grpAssign($conId, $_POST["entry"]);
            do_shortcode('[memb_sync_contact]');
        } else { echo "Not enough points in balance "; }
    }
    die();
}
add_action('wp_ajax_add_program_4_points', 'add_program_4_points');
add_action('wp_ajax_nopriv_add_program_4_points', 'add_program_4_points');



function fwistia_locked($args) {
    $wistia_id=$args[0];
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'http://fast.wistia.com/oembed?url=http%3A%2F%2Fhome.wistia.com%2Fmedias%2F'.$wistia_id);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $response = json_decode(curl_exec($ch), true);
    curl_close($ch);
    $parts_url=explode("?",$response['thumbnail_url']);
    $thumbnail_resized=$parts_url[0]."?image_crop_resized=176x99";
    $output = '<a class="video-library-box boxlock">
        <span class="video-thumbnail">
            <img src="https://d3atjl2sg13ne.cloudfront.net/members/wp-content/uploads/2016/05/lock.png" style="position:absolute;top:15px;left:60px">
            <img src="'. $thumbnail_resized .'" data-pin-nopin="true">
        </span>
        <span class="video-title">'. str_replace('"', '', $args[1]) .'</span>
    </a>';
    return $output;

}
add_shortcode( 'wistia_locked', 'fwistia_locked' );


function sucuri_login_fix() {
    
   $user_agent = $_SERVER['HTTP_USER_AGENT'];    
    if (!is_user_logged_in() && (stripos( $user_agent, 'Chrome') === false) && (stripos( $user_agent, 'Safari') !== false) && $_SERVER['REQUEST_URI'] == '/') 
    {
        header('Location: /wp-admin');
        die();
    }
}
add_action( 'init', 'sucuri_login_fix' ); 

/*LEARNDASH HOOKS Flush rewrite rules*/
add_filter( 'learndash_flush_rewrite_rules', '__return_false' );
/*AJAX Solution*/
/*add_action( 'init', 'turnOff_heartbeat', 1 );
function turnOff_heartbeat() {
	global $pagenow;
	if ( 'post.php' != $pagenow && 'post-new.php' != $pagenow )
		wp_deregister_script('heartbeat');
}*/

function mark_completed_GS_wtgm32(){
	global $wpdb;
	//Get GS id
	$wtgm32gettingstarted_id=$wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE post_name = 'wtgm32gettingstarted'");
	$user_id = get_current_user_id();
	learndash_process_mark_complete($user_id, $wtgm32gettingstarted_id);
}

?>

