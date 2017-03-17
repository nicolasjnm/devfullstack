<?php
/*
Plugin Name: BWW Bluesky Integration Plugin
Description: Functions and Procedures for the Bluesky Integration
Version:     0.1
Author:      Best Worlds Web
Author URI:  http://www.bestworldsweb.com
*/
require(plugin_dir_path( __FILE__ ) . 'includes/admin_logic.php');
require(plugin_dir_path( __FILE__ ) . 'includes/bluesky_class.php');

add_action('wp_enqueue_scripts','bww_bs_js_init');
add_action( 'admin_enqueue_scripts', 'bww_bs_js_admin_init' );

function bww_bs_js_init() {
    //wp_enqueue_style( 'bww-cssa', plugins_url( 'bww-myn-plugin/css/styles.css' ),'',false,'all');
    wp_enqueue_script( 'bww-jsa', plugins_url( 'bww-bluesky-plugin/js/scripts.js' ),'jquery',false,true);
}

add_action('wp_head','pluginname_ajaxurl');
function pluginname_ajaxurl() {
?>
<script type="text/javascript">
var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
</script>
<?php
}


function bww_bs_js_admin_init() {
   wp_enqueue_script( 'bww-jsadmin', plugins_url( 'bww-bluesky-plugin/js/scripts.js' ),'jquery',false,true);
}

function bww_bluesky_options() {
    add_option( 'blueSky_uri', '');
    add_option( 'blueSky_cid', '');
    add_option( 'blueSky_ckey', '');    
    add_option( 'blueSky_stage', '');    
}


function bwwblueskyplugin_install() {
 
    // Trigger all our function 
    //create_journal_post_type();
 
    // Clear the permalinks after the post type has been registered
    //flush_rewrite_rules();
 
}
register_activation_hook( __FILE__, 'bwwblueskyplugin_install' );



function get_select_all_webinars() {
  
    $bso = new bluesky();
    $output = '<select style="background: none; border: 1px solid white; color: white; width: 150px; height: 30px; margin-bottom: 6px; padding: 0 10px;" id="bww_bs_event">';
    foreach($bso->getAllEvents() as $event)
    {
        if($event["product"]["type"] == 'webinar')
            //$output .= '<option value="'. $event["id"] .'"> '.$event["product"]["name"].' ('.$event["currency"].$event["price"].')</option>';
            $output .= '<option value="'. $event["id"] .'"> Test Webinar 1</option>';
    }

    $output .='</select><br /><button class="button-medium" style="border: none;" id="bww_bs_event_button">Watch Webinar</button><br/>';
    $output .='<iframe id="bww_bs_webinar_iframe" style="display: none; width: 100%; height: 600px;" src=""></iframe>';
    return $output;
}

add_shortcode('allWebinars', 'get_select_all_webinars');

function get_select_all_webinars_list() {
	$bso = new bluesky();
  	$output="";
	$user_info = get_userdata(get_current_user_id());
	$user["uid"] = get_current_user_id();
	$user["email"] = $user_info->user_email;
	$user["fname"] = $user_info->first_name;
	$user["lname"] = $user_info->last_name;
	$event=$_REQUEST['suscribe_id'];
	if(isset($_REQUEST['suscribe_id'])){
		$return_url = $bso->genToken($user);
		$user_id = get_current_user_id();
		$meta_url_event = get_user_meta( $user_id, '_bs_event_'.$event, false );
		if(isset($meta_url_event) && count($meta_url_event)>0 && !empty($meta_url_event[0])){
			$the_link_redirect=$meta_url_event[0];
			$the_link=$return_url['loginUrl'].'&login_redirect_url='.$the_link_redirect;
			//$output.='<iframe src="'.$return_url['loginUrl'].'" width="0" height="0" style="position:absolute; left:-9999px; visibility:hidden"></iframe><script>jQuery(document).ready(function(){jQuery.ajax({url: "'.$return_url['loginUrl'].'",complete: function(data){window.open("'.$the_link_redirect.'", "_blank");}});})</script>';
                        $output.='<iframe src="'.$return_url['loginUrl'].'" width="0" height="0" style="position:absolute; left:-9999px; visibility:hidden"></iframe><script>jQuery(document).ready(function(){jQuery.ajax({url: "'.$return_url['loginUrl'].'",complete: function(data){jQuery(\'#webinar_frame\').attr("src","'.$the_link_redirect.'"); jQuery(\'#webinar_frame\').show();}});})</script>';
		}else{
			$the_link = $bso->subscribeOrGoToEvent($user, $event);
			$the_link_redirect=$the_link['orderItems'][0]['path'];
			$the_link = $the_link['user']['loginUrl'].'&login_redirect_url='.$the_link_redirect;
			add_user_meta( $user_id, '_bs_event_'.$event, $the_link_redirect, true);
			update_user_meta( $user_id, '_bs_event_'.$event, $the_link_redirect );
			//$output.='<script>window.open("'.$the_link_redirect.'", "_blank");</script>';
                        $output.='<iframe id="webinar_frame" src="'.$the_link_redirect.'"></iframe>';
		}
	}
	$output .= '<div class="list_page">';
	foreach($bso->getAllEvents() as $event)
	{
		$the_link = $bso->subscribeOrGoToEvent($user, $event['id']);
		$output .= '<div class="item_list"> '.$event["product"]["name"].' ('.$event["currency"].$event["price"].')<a class="button-medium btn_right_to_item" href="?suscribe_id='. $event['id'] .'">Watch '.ucfirst($event["product"]['type']).'</a></div>';
	}
	$output .='</div>';
    return $output;
}

add_shortcode('allWebinarsList', 'get_select_all_webinars_list');

function get_the_webinar() {
  	$output="";
	$user_info = get_userdata(get_current_user_id());
	$user["email"] = $user_info->user_email;
	$user["fname"] = $user_info->first_name;
	$user["lname"] = $user_info->last_name;
	$output .= 'http://live.blueskybroadcast.com/bsb/launch_connect_3.asp?FName='. $user["fname"] .'&LName='. $user["lname"] .'&Email='.$user["email"].'&AdobeId=13773498';

    return $output;
}

add_shortcode('webinar', 'get_the_webinar');


function process_subscribe_post_ajax()
{
    $bso = new bluesky();
    $user_info = get_userdata(get_current_user_id());
    $user["email"] = $user_info->user_email;
    $user["fname"] = $user_info->first_name;
    $user["lname"] = $user_info->last_name;
    $event["id"] = $_POST["eid"];
    $result = $bso->subscribeToEvent($user, $event);
	if(isset($result["user"]["loginUrl"])) 
		echo $result["user"]["loginUrl"];
	elseif(count($result['errors'])>0 ) {
		$result = $bso->goToEvent($user, $event);
		echo $result["loginUrl"];

	}
    die();
}
add_action('wp_ajax_process_bwwbsky_subscribe_post_ajax', 'process_subscribe_post_ajax');
add_action('wp_ajax_nopriv_process_bwwbsky_subscribe_post_ajax', 'process_subscribe_post_ajax');

?>