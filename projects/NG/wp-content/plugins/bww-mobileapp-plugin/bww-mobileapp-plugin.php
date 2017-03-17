<?php
/*
Plugin Name: BWW Mobile App Integration Plugin
Description: Functions and Procedures for the Mobile Apps Integration
Version:     1.0
Author:      Best Worlds Web
Author URI:  http://www.bestworldsweb.com
*/
//require(plugin_dir_path( __FILE__ ) . 'includes/admin_logic.php');
//require(plugin_dir_path( __FILE__ ) . 'includes/mobileapp_class.php');

//require("/home/myneurogym/public_html/ajax-api/isdk.php");
add_action('wp_enqueue_scripts','bww_mobile_js_init');
add_action( 'admin_enqueue_scripts', 'bww_mobile_js_admin_init' );


function bww_mobile_js_init() {
    //wp_enqueue_style( 'bww-cssa', plugins_url( 'bww-myn-plugin/css/styles.css' ),'',false,'all');
    //wp_enqueue_script( 'bww-jsa', plugins_url( 'bww-mobileapp-plugin/js/scripts.js' ),'jquery',false,true);
}

/*add_action('wp_head','pluginname_ajaxurl');
function pluginname_ajaxurl() {
?>
<script type="text/javascript">
var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
</script>
<?php
}*/


function bww_mobile_js_admin_init() {
   //wp_enqueue_script( 'bww-jsadmin', plugins_url( 'bww-mobileapp-plugin/js/scripts.js' ),'jquery',false,true);
}

function bww_mobileapp_options() {
    //add_option( 'blueSky_uri', '');
    
}


function bwwmobileappplugin_install() {
 
    // Trigger all our function 
    //create_journal_post_type();
 
    // Clear the permalinks after the post type has been registered
    //flush_rewrite_rules();
 
}
register_activation_hook( __FILE__, 'bwwmobileappplugin_install' );



function bww_mobile_auto_login() {
    if(isset($_POST["user-to-login"]))
    {
        $username = $_POST["user-to-login"];
        $pass = $_POST["user-pass"];
        $app = new iSDK;
        if(!$app->cfgCon("connectionName"))
        {
            echo "Did not connect.";
            die();
        }
        

        $returnFields = array('Id', '_WTIGMembershipSitePassword');       
        $contacts  = $app->findByEmail($username, $returnFields);
        if($pass == $contacts[0]["_WTIGMembershipSitePassword"] && $pass != "")
        {
            $url = '/?memb_autologin=yes&Id='. $contacts[0]["Id"] .'&Email='. $username .'&auth_key=CHaRoFiXqdun&redir=/';
        }else
        {
            $url = '/';
        }
        header('Location: '.$url);
        die();
        
    }
    else
    {
        if(isset($_GET["mobileapp_user"]) && isset($_GET["mobileapp_pass"]) && isset($_GET["mobileapp_autologin"]) && !is_user_logged_in())
        {        
            $username = $_GET["mobileapp_user"];
            $pass = $_GET["mobileapp_pass"];
            $app = new iSDK;
            if(!$app->cfgCon("connectionName"))
            {
                echo "Did not connect.";
                die();
            }
            if($_GET["app"] == 'wtgwl')
            {
                $rApp = "wtgwl";

                if(userHasAnyTag($username,array(7994, 8236)))
                {
                    $rApp = "wtgwl31";
                }
            } else
            {
                if(userHasAnyTag($username,array(10331,10333)))
                    $rApp = "wtgm32";
                else
                    $rApp = "wtgm31";
            }

            $returnFields = array('Id');       
            $contacts  = $app->findByEmail($username, $returnFields);
            $url = '/?memb_autologin=yes&Id='. $contacts[0]["Id"] .'&Email='. $username .'&auth_key=CHaRoFiXqdun&redir=/'. $rApp .'-mobile-app-login/';
            header('Location: '.$url);
            die();
        }    
        else if (isset($_GET["mobileapp_autologin"]))
        {
             if($_GET["app"] == 'wtgwl')
            {
                $rApp = "wtgwl";
                    if(memb_hasAnyTags("7994", "8236"))
                    {
                        $rApp = "wtgwl31";
                    }
            } else
            {
                //'winning-the-game-of-money-3-2' 
            if(memb_hasAnyTags("10331","10333"))
                $rApp = "wtgm32";
            else
                $rApp = "wtgm31";
            }
            $url = '/'. $rApp.'-mobile-app-login/';
            header('Location: '.$url);
            die();
        }
    }
}
add_action('wp_loaded', 'bww_mobile_auto_login');







function searchForId($id, $array) {
   foreach ($array as $key => $val) {
       if ($val['ContactId'] === $id) {
           return $key;
       }
   }
   return null;
}
function bww_mobile_check_login() {
    if(isset($_GET["mobileapp_user"]) && isset($_GET["mobileapp_pass"]) && isset($_GET["mobileapp_checklogin"]) && !is_user_logged_in())
    {        
        $username = $_GET["mobileapp_user"];
        $pass = $_GET["mobileapp_pass"];
        $app = new iSDK;
        if(!$app->cfgCon("connectionName"))
        {
            echo "Did not connect.";
            exit();
        }
        $returnFields = array('Id',
                              'FirstName',
                              '_WTIGMembershipSitePassword');       
        $contacts  = $app->findByEmail($username, $returnFields);
        //$tags = $app->dsFind('ContactGroupAssign',2000,0,'GroupId',10331,array('ContactId'));
        if($pass == $contacts[0]["_WTIGMembershipSitePassword"] && $pass != "")
        { 
            /*if(searchForId($contacts[0]["Id"], $tags)!==NULL){
		echo "ERROR-";
                die();
            }*/
            echo 'OK-'.$contacts[0]["FirstName"];}
        else
            echo "ERROR-";
        
        die();
    }    
}
add_action('wp_loaded', 'bww_mobile_check_login');


function userHasAnyTag($email,$tag){
    $app = new iSDK;
    if(!$app->cfgCon("connectionName"))
    {
        echo "Did not connect.";
        exit();
    }
    $fieldName='Groups';
    $returnFields = array($fieldName);
    $groups=$app->findByEmail($email, $returnFields);
    $tags=explode(",",$groups[0][$fieldName]);


    if(!is_array($tag)) 
            $tag=array($tag);
    if(count(array_intersect($tag, $tags)) > 0) {
        return true;
    }
    else
    {
        return false;
    }
}



function check_mobileapp_lesson($number, $slug, $user){
                
                if($slug == "wtgm"){
                    if(memb_hasAnyTags("10331","10333"))
                        $slug = "winning-the-game-of-money-3-2";
                    else
                        $slug = "wtgm31";
                }
                $args = array(
                  'name'        => $slug,
                  'post_type'   => 'sfwd-courses',
                  'post_status' => 'publish',
                  'numberposts' => 1
                );
                $post = get_posts($args);
                $post = $post[0];
                $course_id = $post->ID;
                if($slug == "winning-the-game-of-money-3-2")
                    $slug = "wtgm32";
                $slug = $slug . $number;
                $args = array(
                  'name'        => $slug,
                  'post_type'   => 'sfwd-lessons',
                  'post_status' => 'publish',
                  'numberposts' => 1
                );
                $post = get_posts($args);
                $post = $post[0];
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
		$lesson_access_from = ld_lesson_access_from($lesson_id, $user->ID);
		$access=false;//unlockLessonsByRole(array("administrator","member"));
		if(count($posts) == $number-1 || $access)
			return 1;
		if((empty($lesson_access_from) && !empty($lesson_id)))
			return 1;
		else
		{
			return 0;
		}
}


function check_last_wtgm_lesson()
{
    if(isset($_GET["email"]) && isset($_GET["slug"]) && ($_GET["slug"]=="wtgm31" || $_GET["slug"]=="wtgm32"))
    {
        $user = get_user_by_email($_GET["email"]);
        if(isset($user) && $user->ID > 0)
        {
            for($a=1;$a<13;$a++)
            {
               if(check_mobileapp_lesson($a, $_GET["slug"], $user) == 0)
                        break;
                //echo '<!-- Level: '. $a . ' Result: ' . check_mobileapp_lesson($a, "wtgm", $user) .' -->';
            }
            $a = $a-1;
            echo "$a";
            die();
        }
        else {
            echo '-ERROR';
            die();
        }
    }
}
add_action('wp_loaded', 'check_last_wtgm_lesson');


function check_last_wtgwl_lesson()
{
    if(isset($_GET["email"]) && isset($_GET["slug"]) && $_GET["slug"]=="wtgwl" )
    {
        $user = get_user_by_email($_GET["email"]);
        $slug = 'wtgwl';
        if(userHasAnyTag($_GET["email"],array(7994, 8236))) {
            $slug = 'wtgwl31';
        }
        if(isset($user) && $user->ID > 0)
        {
            for($a=1;$a<14;$a++)
            {
                //echo 'Level: '. $a . ' Result: ' . check_mobileapp_lesson($a, $slug, $user) .'<br />';
                if(check_mobileapp_lesson($a, $slug, $user) == 0)
                    break;
            }
            $a = $a-2;
            echo "$a";
            die();
        }
        else {
            echo '-ERROR';
            die();
        }
    }
}
add_action('wp_loaded', 'check_last_wtgwl_lesson');


function redirect_app_mobile(){
    /*$isMobileApp = strpos($_SERVER[REQUEST_URI], 'mobile-app') > 0 || 
                    strpos($_SERVER['HTTP_REFERER'], 'mobile-app') > 0 || 
                    $_SERVER['HTTP_X_REQUESTED_WITH'] == "com.myneurogym.wtgwl" || 
                    $_SERVER['HTTP_X_REQUESTED_WITH'] == "com.myneurogym.wtgm"  || 
                    (strpos($_SERVER['HTTP_USER_AGENT'], 'Mobile/') !== false) && (strpos($_SERVER['HTTP_USER_AGENT'], 'Safari/') == false);
    if($isMobileApp)
    {
		$mobileApp="wtgwl";
        switch($_SERVER['HTTP_X_REQUESTED_WITH']) {
            case "com.myneurogym.wtgm" : $mobileApp = "wtgm31";break;
            case "com.myneurogym.wtgwl" : $mobileApp = "wtgwl";break;
        }
		if(is_user_logged_in() && (strpos($_SERVER[REQUEST_URI],"wtgm31")===false && strpos($_SERVER[REQUEST_URI],"wtgwl")===false && strpos($_SERVER[REQUEST_URI],"bonus-content")===false)){
			wp_redirect("/courses/".$mobileApp); exit;
		}
    }
	$app_name='no-set';
	if(!is_user_logged_in()){
	   setcookie( 'app_name', '', time() - 3600, COOKIEPATH, COOKIE_DOMAIN );
	}else{
		if(!isset($_COOKIE['app_name'])){
			$app_url_login = $_SERVER['HTTP_REFERER'];
			setcookie( 'app_name', $app_url_login, time() + 3600, COOKIEPATH, COOKIE_DOMAIN );
		}
		$app_name=$_COOKIE['app_name'];
	}
    $isMobileApp = strpos($_SERVER[REQUEST_URI], 'mobile-app') > 0 || 
                    strpos($_SERVER['HTTP_REFERER'], 'mobile-app') > 0 || 
                    $_SERVER['HTTP_X_REQUESTED_WITH'] == "com.myneurogym.wtgwl" || 
                    $_SERVER['HTTP_X_REQUESTED_WITH'] == "com.myneurogym.wtgm"  || 
                    (strpos($_SERVER['HTTP_USER_AGENT'], 'Mobile/') !== false) && (strpos($_SERVER['HTTP_USER_AGENT'], 'Safari/') == false);
        if(strpos($app_name, 'wtgm')!==false){
			$mobileApp = "wtgm31";
			wp_redirect("/courses/".$mobileApp); exit;
		}
        if(strpos($app_name, 'wtgwl')!==false){
			$mobileApp = "wtgwl";
			wp_redirect("/courses/".$mobileApp); exit;
		}
*/

}

add_action('init', 'redirect_app_mobile');
function get_app_name(){
	session_start();
	$app_name='no-set';
	if(!is_user_logged_in()){
	   setcookie( 'app_name', '', time() - 3600, COOKIEPATH, COOKIE_DOMAIN );
	}else{
		if(!isset($_COOKIE['app_name'])){
			$app_url_login = $_SERVER['HTTP_REFERER'];
			setcookie( 'app_name', $app_url_login, time() + 3600, COOKIEPATH, COOKIE_DOMAIN );
			$_SESSION['app_name']=$app_url_login;
		}
		$app_name=$_COOKIE['app_name'];
	}
    $isMobileApp = strpos($_SERVER[REQUEST_URI], 'mobile-app') > 0 || 
                    strpos($_SERVER['HTTP_REFERER'], 'mobile-app') > 0 ||
		    strpos($_SERVER[REQUEST_URI], 'mobileapp') > 0 ||  
                    strpos($_SERVER['HTTP_REFERER'], 'mobileapp') > 0 || 
                    $_SERVER['HTTP_X_REQUESTED_WITH'] == "com.myneurogym.wtgwl" || 
                    $_SERVER['HTTP_X_REQUESTED_WITH'] == "com.myneurogym.wtgm"  || 
                    (strpos($_SERVER['HTTP_USER_AGENT'], 'Mobile/') !== false && (strpos($_SERVER['HTTP_USER_AGENT'], 'Safari/') == false)) || (strpos($_SERVER['HTTP_USER_AGENT'], 'Android') !== false && strpos($_SERVER['HTTP_USER_AGENT'], 'wv') !== false) || strpos($_SERVER['HTTP_USER_AGENT'], 'mobileapp-wtgm') !== false || strpos($_SERVER['HTTP_USER_AGENT'], 'wv') !== false;
   /* if($isMobileApp)
    {
    }*/
	if(empty($app_name) && strpos($_SERVER['HTTP_USER_AGENT'], 'mobileapp-wtgwl')){
		$app_name='wtgwl';
	}
	if(empty($app_name) && strpos($_SERVER['HTTP_USER_AGENT'], 'mobileapp-wtgm')){
		$app_name='wtgm';
	}
        if(strpos($app_name, 'wtgm')!==false){
                    //'winning-the-game-of-money-3-2' 
                    if(memb_hasAnyTags(array(10331,10333)))
                        $mobileApp = "winning-the-game-of-money-3-2";
                    else
                        $mobileApp = "wtgm31";
		}
	if(strpos($app_name, 'wtgwl')!==false){
		$mobileApp = "wtgwl";
                if(memb_hasAnyTags(array(7994, 8236)))
                {
                    $mobileApp = "wtgwl31";
                }                
            }
	session_write_close();
	return $mobileApp;
}

function mobile_os_detect(){
	$device = '';
	if(strpos($_SERVER["HTTP_USER_AGENT"],"iPad") !== false)	$device = 'ipad';
	elseif(strpos($_SERVER["HTTP_USER_AGENT"],"iPhone") !== false) $device = 'iphone';
	elseif(strpos($_SERVER["HTTP_USER_AGENT"],"Blackberry") !== false) $device = 'blackberry';
	elseif(strpos($_SERVER["HTTP_USER_AGENT"],"Android") !== false) $device = 'android';
	if($device) return $device;
	 else return false;
}
function is_iphone($atts, $content = null ){
	$device = false;
	if(strpos($_SERVER["HTTP_USER_AGENT"],"iPhone") !== false) $device = true;
	if($device) return $content;
}
add_shortcode('isIphone', 'is_iphone');
function is_not_iphone($atts, $content = null ){
	$device = false;
	if(strpos($_SERVER["HTTP_USER_AGENT"],"iPhone") !== false) $device = true;
	if(!$device) return $content;
}
add_shortcode('isNotIphone', 'is_not_iphone');
function is_android($atts, $content = null ){
	$device = false;
	if(strpos($_SERVER["HTTP_USER_AGENT"],"Android") !== false) $device = true;
	if($device) return $content;
}
add_shortcode('isAndroid', 'is_android');
function is_not_android($atts, $content = null ){
	$device = false;
	if(strpos($_SERVER["HTTP_USER_AGENT"],"Android") !== false) $device = true;
	if(!$device) return $content;
}
add_shortcode('isNotAndroid', 'is_not_android');
function non_iOSapp_plugins() {
  return array(

    // an array of all the plugins you want to exclude for iOS

    'lucky-orange/lucky_wordpress.php'

  );
}
add_filter( 'option_active_plugins', 'disable_plugins_for_mobiles' );

function fn_is_iphone(){
	$device = false;
	if(strpos($_SERVER["HTTP_USER_AGENT"],"iPhone") !== false) $device = true;
	return $device;
}
function disable_plugins_for_mobiles( $plugins ) {

  if ( ! fn_is_iphone() ) {
    return $plugins; // for non-mobile device do nothing
  }

  $not_allowed = non_iOSapp_plugins(); // get non allowed plugins

  return array_values( array_diff( $plugins, $not_allowed ) );

}
function is_iphone_app(){
	return strpos($_SERVER['HTTP_USER_AGENT'], 'Mobile/') !== false && strpos($_SERVER['HTTP_USER_AGENT'], 'Safari/') == false;
}
function is_android_app(){
	return strpos($_SERVER['HTTP_USER_AGENT'], 'Android') !== false && strpos($_SERVER['HTTP_USER_AGENT'], 'wv') !== false;
}
function apply_app_tag(){
	//8793 WTGM iOS - 8795 WTGM Android - 8797 WTGWL iOS - 8799 WTGWL Android

    if(is_user_logged_in() && (is_iphone_app() || is_android_app())){
		echo '<!-- THIS IS THE MOBILE APP TAG FUNCTION INSIDE IF --> ';
                $app = new iSDK;
		$conId=get_current_IFS_contact_info(array(  'Id'));
		$conId=$conId[0]['Id'];

        $app = new iSDK;
        if(!$app->cfgCon("connectionName"))
        {
            echo "Did not connect.";
            exit();
        }
		if(is_iphone_app() && strpos(get_app_name(),'wtgwl') !== false )
			$tagId=8797;
		if(is_iphone_app() && strpos(get_app_name(),'wtgm') !== false )
			$tagId=8793;
		if(is_android_app() && strpos(get_app_name(),'wtgwl') !== false )
			$tagId=8799;
		if(is_android_app() && strpos(get_app_name(),'wtgm') !== false )
			$tagId=8795;
		echo '<!-- '. $conId .' '. $tagId .'  --> ';		
		$app->grpAssign($conId, $tagId);
	}
}
add_filter( 'init', 'apply_app_tag' );




function get_wtgm_audio()
{
    if(isset($_GET["email"]) && isset($_GET["slug"]) && $_GET["slug"]=="wtgm")
    {
        $user = get_user_by_email($_GET["email"]);
        if(isset($user) && $user->ID > 0)
        {
            //'winning-the-game-of-money-3-2' 
            if(userHasAnyTag($_GET["email"],array(10331,10333)))
            {
                $version="32";
                $slug="winning-the-game-of-money-3-2";
                $folder="https://s3.amazonaws.com/cdn.praxisnow.com/products/wtgm/v3.1/";
                $titles="Finding Financial Opportunity,"
                        . "Attracting Wealth,"
                        . "Tenacity & Resolve,"
                        . "Releasing Your Stories and Excuses,"
                        . "Increasing Wealth Feelings,"
                        . "Being Creative,"
                        . "Accelerated Wealth Re-programming #1,"
                        . "Accelerated Wealth Re-programming #2,"
                        . "Accelerated Wealth Re-programming #3,"
                        . "Letting Go Of Your Money Fears,"
                        . "Beliefs & Habits Generator,"
                        . "Mastering Your Money Focus";
                $files="wtgm-level1-finding-financial-opportunity.mp3,"
                        . "wtgm-level2-attracting-wealth.mp3,"
                        . "wtgm-level3-tenacity-resolve.mp3,"
                        . "wtgm-level7-releasing-stories-excuses.mp3,"
                        . "wtgm-level4-increasing-wealth-feelings.mp3,"
                        . "wtgm-level6-being-creative.mp3,"
                        . "wtgm-level5-awr1.mp3,"
                        . "wtgm-level8-awr2.mp3,"
                        . "wtgm-level10-awr3.mp3,"
                        . "wtgm-level9-letting-go-of-your-money-fears.mp3,"
                        . "wtgm-level11-beliefs-habits-generator.mp3,"
                        . "wtgm-level12-mastering-money-focus.mp3";
            }
            //'wtgm31' 
            else //(memb_hasAnyTags(array(6962,6968,6112)))
            {
                $version="31";
                $slug="wtgm31";
                $folder="https://s3.amazonaws.com/cdn.praxisnow.com/products/wtgm/v3.1/";
                $titles="Finding Financial Opportunity,"
                        . "Attracting Wealth,"
                        . "Tenacity & Resolve,"
                        . "Increasing Wealth Feelings,"
                        . "Accelerated Wealth Re-programming #1,"
                        . "Being Creative,"                        
                        . "Releasing Your Stories and Excuses,"                        
                        . "Accelerated Wealth Re-programming #2,"
                        . "Letting Go Of Your Money Fears,"                        
                        . "Accelerated Wealth Re-programming #3,"
                        . "Beliefs & Habits Generator,"
                        . "Mastering Your Money Focus";
                $files="wtgm-level1-finding-financial-opportunity.mp3,"
                        . "wtgm-level2-attracting-wealth.mp3,"
                        . "wtgm-level3-tenacity-resolve.mp3,"
                        . "wtgm-level4-increasing-wealth-feelings.mp3,"
                        . "wtgm-level5-awr1.mp3,"
                        . "wtgm-level6-being-creative.mp3,"                        
                        . "wtgm-level7-releasing-stories-excuses.mp3,"                        
                        . "wtgm-level8-awr2.mp3,"
                        . "wtgm-level9-letting-go-of-your-money-fears.mp3,"                        
                        . "wtgm-level10-awr3.mp3,"
                        . "wtgm-level11-beliefs-habits-generator.mp3,"
                        . "wtgm-level12-mastering-money-focus.mp3";
            }
            /*echo 'slug: '. $slug . ' Version: '.$version. '<br />';
            global $wpdb;
            
            $course= $wpdb->get_var( "SELECT ID FROM wp_posts wp WHERE wp.post_type = 'sfwd-courses' AND wp.post_status = 'publish' AND wp.post_name = '".$slug ."';" );
            $lessons=learndash_get_course_lessons_list($course);
            foreach($lessons as $lesson){
                $lesson=$lesson['post'];
                
                $output .='<option value="'.$lesson->ID.'">'.$lesson->post_title.'</option>';
            }*/
            echo 'OK-'.$titles .'||'. $files;
            die();
        }
    }
}
add_action('wp_loaded', 'get_wtgm_audio');


?>