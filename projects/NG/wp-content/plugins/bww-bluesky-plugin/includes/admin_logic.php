<?php
add_action( 'admin_menu', 'bww_bluesky_plugin_menu' );

function bww_bluesky_plugin_menu() {
	add_options_page( 'BlueSky Plugin Options', 'BlueSky Plugin', 'manage_options', 'bww_bluesky_plugin_menu', 'bww_bluesky_plugin_options' );
}


function bww_bluesky_plugin_options() {
	if ( current_user_can( 'manage_options' ) )  { 
            $theoutput = '<div class="updated" id="bww_bs_update" style="display: none;"><p>Options Updated!</p></div>'
                    .'<h1>BlueSky Integration Settings</h1>'
                    . '<table class="form-table">'
                    . '<tr><th scope="row"><label for="bww_bs_cid">Client ID</label></th>'
                    . '<td><input type="text" name="bww_bs_cid" id="bww_bs_cid" / value="'.get_option('blueSky_cid') .'" class="regular-text" /></td></tr>'
                    . '<tr><th scope="row"><label for="bww_bs_ckey">Client Secret</label></th>'
                    . '<td><input type="text" name="bww_bs_ckey" id="bww_bs_ckey" / value="'.get_option('blueSky_ckey') .'" class="regular-text" /></td></tr>'
                    . '<tr><th scope="row"><label for="bww_bs_path">Client Url Path</label></th>'
                    . '<td>http://pathlms.com/<input type="text" name="bww_bs_path" id="bww_bs_path" / value="'.get_option('blueSky_uri') .'" class="regular-text" /></td></tr>'                    
                    . '<tr><th scope="row">Stage of Service</th>'
                    . '<td><fieldset><legend class="screen-reader-text"><span>Stage of Service</span></legend>'
                    . '<label title="Live"><input type="radio" name="bww_bs_stage" id="bww_bs_stage" value="live" ';
                    if(get_option('blueSky_stage')== 'live')  
                        {  $theoutput .= 'checked="checked"'; }
                    $theoutput .= '>Live</label><br>'
                    . '<label title="Test"><input type="radio" name="bww_bs_stage" id="bww_bs_stage" value="test" ';
                    if(get_option('blueSky_stage')== 'test')  
                        {  $theoutput .= 'checked="checked"'; }
                    $theoutput.= '>Test</label><br></fieldset></td>'
                    . '</tbody></table>'                    
                    .'<button id="bww_bluesky_admin_submit">Save</button>';
            echo $theoutput;
        }
}

function process_admin_post_ajax()
{

    update_option( 'blueSky_cid', $_POST["cid"]);
    update_option( 'blueSky_ckey', $_POST["ckey"]); 
    if($_POST["stage"] == 'test')
        $url = 'https://staging.pathlms.com/';
    else
        $url = 'https://www.pathlms.com/';
    update_option( 'blueSky_uri', $_POST['uri']);
    update_option( 'blueSky_stage', $_POST["stage"]);
    die();
}
add_action('wp_ajax_process_bwwbsky_post_ajax', 'process_admin_post_ajax');
add_action('wp_ajax_nopriv_process_bwwbsky_post_ajax', 'process_admin_post_ajax');


?>