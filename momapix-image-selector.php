<?php   
    /* 
    Plugin Name: Momapix Image Selector 
    Plugin URI: http://www.momapix.com 
    Description: Plugin for displaying photos from Momapix album 
    Author: Cristiano Zanca
    Version: 1.3 
    Author URI: http://cristiano.zanca.it 
    License: GPLv2 - http://www.gnu.org/licenses/gpl-2.0.html
    */  



// wordpress version check and display warnings

add_action('admin_notices', 'my_plugin_admin_notices');
function my_plugin_admin_notices() {
    $version = get_bloginfo('version');
        if ($version < 3.3){
    if (is_plugin_active('momapix-image-selector/momapix.php')) {
        echo '<div id="message" class="updated"><p>Sorry, This Momapix plugin is available on Wordpress 3.3 and later version. Please deactivate it</p></div>';
    }
}

}

register_activation_hook( __FILE__, 'momapix_set_default_value' );

define( 'MOMAPIX_PATH', plugin_dir_path(__FILE__) );


// css style include 

function moma_css() {
    echo '<style type="text/css">';
    require_once(MOMAPIX_PATH ."/css/style_moma.css");
    echo '</style>';
    
}

add_action( 'admin_head', 'moma_css' );

// css style include  end

// js include 

function moma_js() {
    echo '<script type="text/javascript">';
    require_once(MOMAPIX_PATH ."/js/wp-momapix-gallery.js");
    echo '</script>';
    
}

add_action( 'admin_head', 'moma_js' );
// js include  end


add_action('media_buttons', 'add_momapix_button', 11);

function add_momapix_button() {
    global $post_ID, $temp_ID;
    $tabs = 'photo';
   // $page = 1;
    $uploading_iframe_ID = (int) (0 == $post_ID ? $temp_ID : $post_ID);
    $media_momapix_title = __('Add Momapix Picture', 'wp-momapix');
    $image_btn = WP_PLUGIN_URL . "/momapix-image-selector/images/moma_logo_wp.gif";
    $media_upload_iframe_src = "media-upload.php?post_id=$uploading_iframe_ID";
    
    $media_momapix_iframe_src = apply_filters('media_momapix_iframe_src', "$media_upload_iframe_src&amp;type=wp_momapix_photo&amp;tab=$tabs&amp;momapage=1");
   
    echo "<a href=\"{$media_momapix_iframe_src}&amp;TB_iframe=true&amp;height=500&amp;width=800\" class=\"thickbox\" title=\"$media_momapix_title\"><img src=\"$image_btn\" alt=\"$media_momapix_title\" /></a>";
}


function MomapixMediaTab() {
  $tabs = array (
        'photo' =>  'Momapix Image Selector',
        //'photo' =>  __('Momapix Last Photos'),
        'cerca' => '',
        'settings' => 'Settings'
  );
  
 return  $tabs;
}


function MomapixMediaUpload() {
	global $redir_tab;
	
	try {
		checkMomaPIXKey();
	}	catch (Exception $e) {
		$redir_tab = 'settings';
	}
	
	$gtab	=	$redir_tab	?: $_GET['tab'];
	
            if ((isset($gtab) && $gtab == 'settings'))
            {
            return  wp_iframe( 'media_upload_momapix_settings' );
            }
            else if (isset($gtab) && $gtab == 'cerca')
            {
                
            return wp_iframe( 'media_upload_momapix_iframe2' );
            }
            else if (isset($gtab) && $gtab == 'photo') 
            {
            
            return  wp_iframe( 'media_upload_momapix_iframe' );
            }
    
}


function checkMomaPIXKey($options	=	null) {
        
	$options 	= 	$options	?:	get_option( 'momapix_default_value');
	
	$postdata = 
			array(
					'request_array_cmd' 		=> 'check_wp_api_key',
					'request_array_wp_api_key' 	=> $options['momapix_api_key'],
			
	);
	
	
	$url	 	= 	$options['momapix_account']."/rest/json/cmd/check_wp_api_key"; 
	$result		=	json_decode(momapix_file_get_contents($url,$postdata),true);
	
	
	if ($result['Ack'] ==	"Success") 	return true;
	else  								throw new Exception($result['Errors'][0]['Message']);
}



function momapix_file_get_contents($url,$postdata = "") {
	
	$postdata	=	array_merge($postdata?:array(),array('api_key' 					=> 'oiyfghrn76540987uy67nbderhgturas'));
	
	$options 	= 	get_option('momapix_default_value')	?:	array();
	
	$opts = array(
			'http'=>array(
					'method'			=>	"POST",
					'user_agent' 		=> 	"MomaPIX Plugin for WordPress V ???",
					'follow_location' 	=> 	0,
					'timeout' 			=> 	3,
					'content' 			=> http_build_query($postdata)
			)
	);
	
	if (isset($options['momasessid']))	$opts['http']['header']	=	'Content-type: application/x-www-form-urlencoded;\nCookie: MOMASESSID='.$options['momasessid'];
	
	$context 		= stream_context_create($opts);
	
	if (!($sessionContent	=	@file_get_contents($url, false, $context))) {
		throw new Exception("Error connecting server. Check server again and try again.");
	}
	
	
	if (!empty($http_response_header) and !isset($options['momasessid']))
		
	{	if (preg_match("/Set-Cookie: MOMASESSID=(.*);/msU",implode(",",$http_response_header),$matched)) {
			$options['momasessid']	=	$matched[1];
			
			update_option('momapix_default_value', $options);
		}
	}
	
	return $sessionContent;
}

function media_upload_momapix_settings() {
	global $tabs, $checkMomaPIXKey;
	add_filter('media_upload_tabs', 'MomapixMediaTab');
	media_upload_header();
	include 'admin/momapix_settings.php';
}


function media_upload_momapix_iframe() {
  global $tabs, $page;
  add_filter('media_upload_tabs', 'MomapixMediaTab');
  media_upload_header();
  include 'admin/momapix_json_parse.php';
  
}


function media_upload_momapix_iframe2() {
  add_filter('media_upload_tabs', 'MomapixMediaTab');
  media_upload_header();
  include 'admin/momapix_json_parse2.php';
}


add_action('media_upload_wp_momapix_photo', 'MomapixMediaUpload');


function momapix_set_default_value() {
    
    if ( get_option( 'momapix_default_value' ) === false ) {
$new_options['momapix_account'] = ""; //for test http://demo.momapix.com/develop
$new_options['version'] = "0.6";
add_option( 'momapix_default_value', $new_options );
} else {
$existing_options = get_option( 'momapix_default_value' );
if ( $existing_options['version'] < 1.3 ) {
$existing_options['version'] = "1.3";
update_option( 'momapix_default_value', $existing_options );
}
}

}

add_action( 'admin_menu', 'register_momapix_menu_page' );

require_once(MOMAPIX_PATH ."/admin/momapix_admin.php");

function register_momapix_menu_page(){
    
    add_menu_page( 'momapix menu title', 'Momapix', 'administrator', 'momapixpage', 'momapix_menu_page', plugins_url( 'momapix-image-selector/images/moma_logo_wp.gif' ), 11 ); 

    
}


