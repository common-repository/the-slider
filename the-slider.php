<?php
/*
 Plugin Name: The Slider
 Plugin URI: http://www.zingiri.com
 Description: The Slider is a catchy featured content slider ideal for showcasing your products and services.
 Author: Zingiri
 Version: 1.1.0
 Author URI: http://www.zingiri.com/
 */

define("THESLIDER_VERSION","1.1.0");

// Pre-2.6 compatibility for wp-content folder location
if (!defined("WP_CONTENT_URL")) {
	define("WP_CONTENT_URL", get_option("siteurl") . "/wp-content");
}
if (!defined("WP_CONTENT_DIR")) {
	define("WP_CONTENT_DIR", ABSPATH . "wp-content");
}

if (!defined("THESLIDER_PLUGIN")) {
	$the_slider_plugin=str_replace(realpath(dirname(__FILE__).'/..'),"",dirname(__FILE__));
	$the_slider_plugin=substr($the_slider_plugin,1);
	define("THESLIDER_PLUGIN", $the_slider_plugin);
}

if (!defined("BLOGUPLOADDIR")) {
	$upload=wp_upload_dir();
	define("BLOGUPLOADDIR",$upload['path']);
}

define("THESLIDER_URL", WP_CONTENT_URL . "/plugins/".THESLIDER_PLUGIN."/");

$theSlider=array('id' => 0);

$the_slider_version=get_option("the_slider_version");
add_action("init","the_slider_init");
add_action('admin_head','the_slider_admin_header');
add_action('wp_head','the_slider_header');
add_filter('the_content', 'the_slider_content', 10, 3);

require_once(dirname(__FILE__) . '/controlpanel.php');

function the_slider_admin_notices() {
	global $bookings;
	$errors=array();
	$warnings=array();
	$files=array();
	$dirs=array();

	if (isset($bookings['output']['warnings']) && is_array($bookings['output']['warnings']) && (count($bookings['output']['warnings']) > 0)) {
		$warnings=$bookings['output']['warnings'];
	}
	if (isset($bookings['output']['errors']) && is_array($bookings['output']['errors']) && (count($bookings['output']['errors']) > 0)) {
		$errors=$bookings['output']['errors'];
	}
	$upload=wp_upload_dir();
	//if (!is_writable(session_save_path())) $errors[]='PHP sessions are not properly configured on your server, the sessions save path '.session_save_path().' is not writable.';
	if ($upload['error']) $errors[]=$upload['error'];
	if (get_option('the_slider_debug')) $warnings[]="Debug is active, once you finished debugging, it's recommended to turn this off";
	if (phpversion() < '5') $warnings[]="You are running PHP version ".phpversion().". We recommend you upgrade to PHP 5.3 or higher.";
	if (ini_get("zend.ze1_compatibility_mode")) $warnings[]="You are running PHP in PHP 4 compatibility mode. We recommend you turn this option off.";
	if (!function_exists('curl_init')) $errors[]="You need to have cURL installed. Contact your hosting provider to do so.";

	if (count($warnings) > 0) {
		echo "<div id='zing-warning' style='background-color:greenyellow' class='updated fade'><p><strong>";
		foreach ($warnings as $message) echo 'Bookings: '.$message.'<br />';
		echo "</strong> "."</p></div>";
	}
	if (count($errors) > 0) {
		echo "<div id='zing-warning' style='background-color:pink' class='updated fade'><p><strong>";
		foreach ($errors as $message) echo 'Bookings:'.$message.'<br />';
		echo "</strong> "."</p></div>";
	}

	return array('errors'=> $errors, 'warnings' => $warnings);
}

function the_slider_activate() {
	update_option("the_slider_version",THESLIDER_VERSION);
}

function the_slider_deactivate() {
	$the_slider_options=the_slider_options();

	foreach ($the_slider_options as $value) {
		delete_option( $value['id'] );
	}
}

function the_slider_content($content) {
	if (preg_match_all('/\[the-slider\]/',$content,$matches)) {
		foreach ($matches[0] as $id => $match) {
			$output='<div style="clear:both">';
			$output.='<div class="the_slider">';
			$output.=the_slider_demo();
			$output.='</div>';
			$output.='<div style="clear:both">';
			$content=str_replace($match,$output,$content);
		}
	}
	
	if (preg_match_all('/\[the-slider (.*)\]/',$content,$matches)) {
		$vars=array();
		foreach ($matches[0] as $id => $match) {
			$postVars=array();
			$mode='normal';
			if ($matches[1][$id]) {
				$vars=explode(',',$matches[1][$id]);
				foreach ($vars as $var) {
					if (strstr($var,'=')) list($n,$v)=explode('=',$var);
					else echo '<br />Missing parameter for variable '.$n;
					$n=trim($n);
					$v=trim($v);
					if ($n=='cat') $postVars['cat']=$v;
					elseif ($n=='max') $postVars['posts_per_page']=$v;
					elseif ($n=='posts_per_page') $postVars['posts_per_page']=$v;
					else echo '<br />Unknown variable '.$n;
				}
			}

			$output='<div style="clear:both">';
			$output.='<div class="the_slider">';
			$output.=the_slider($postVars);
			$output.='</div>';
			$output.='<div style="clear:both">';
			$content=str_replace($match,$output,$content);
		}
		return $content;
	} else return $content;
}

function the_slider_header() {
	echo '<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.5.3/jquery-ui.min.js" ></script>';
	echo '<link rel="stylesheet" type="text/css" href="' . THESLIDER_URL . 'css/style.css" media="screen" />';
}

function the_slider_admin_header() {
	echo '<link rel="stylesheet" type="text/css" href="' . THESLIDER_URL . 'css/admin.css" media="screen" />';
}

function the_slider_home() {
	global $post,$page_id;

	$pageID = $page_id;

	if (get_option('permalink_structure')){
		$homePage = get_option('home');
		$wordpressPageName = get_permalink($pageID);
		$wordpressPageName = str_replace($homePage,"",$wordpressPageName);
		$home=$homePage.$wordpressPageName;
		if (substr($home,-1) != '/') $home.='/';
		$home.='?';
	}else{
		$home=get_option('home').'/?page_id='.$pageID.'&';
	}

	return $home;
}

function the_slider_init()
{
	//	ob_start();
	//	session_start();
	wp_enqueue_script('jquery');
}

function the_slider($vars=array()) {
	global $theSlider;
	
	$theSlider['id']++;
	
	$output=$tabs=$fragments='';

	$the_query = new WP_Query($vars);

	$first=true;
	while ( $the_query->have_posts() ) {

		$the_query->the_post();
		get_the_title();
		get_the_post_thumbnail();
		$i=get_the_ID();
		$tabs.='<li class="ui-tabs-nav-item'.($first ? " ui-tabs-selected" : "").'" id="nav-fragment-'.$i.'"><a href="#fragment-'.$i.'">';
		$tabs.=get_the_post_thumbnail(null,array(80,50));
		$tabs.='<span>'.get_the_title().'</span></a></li>';

		$fragments.='<div id="fragment-'.$i.'" class="ui-tabs-panel'.(!$first ? " ui-tabs-hide" : "").'" style="">';
		$fragments.=get_the_post_thumbnail();
		$fragments.='<div class="info" >';
		$fragments.='<h2><a href="#" >'.get_the_title().'</a></h2>';
		$fragments.='<p>'.get_the_content().'<br /><a href="#" >read more</a></p>';
		$fragments.='</div>';
		$fragments.='</div>';

		$first=false;
	}

	// Reset Post Data
	wp_reset_postdata();

	$output.='<div class="the-slider" id="the-slider-'.$theSlider['id'].'" >';
	$output.='<ul class="ui-tabs-nav">';
	$output.=$tabs;
	$output.='</ul>';
	$output.=$fragments;
	$output.='</div>';
	$output.=the_slider_js('the-slider-'.$theSlider['id']);
	return $output;

}

function the_slider_js($id='featured') {
	$output='<script type="text/javascript">';
	$output.='jQuery(document).ready(function(){';
	$output.='jQuery("#'.$id.' > ul").tabs({fx:{opacity: "toggle"}}).tabs("rotate", 5000, true);';
	$output.='});';
	$output.='</script>';
	return $output;
}

function the_slider_demo() {
	$output=$tabs=$fragments='';
	$a=array(1,2,3,4,5,6,7,8,9,10,11,12,13);
	$k=array_rand($a,4);

	foreach ($k as $i) {
		$tabs.=the_slider_demo_display_tab($a[$i]);
	}
	foreach ($k as $i) {
		$fragments.=the_slider_demo_display_fragment($a[$i]);
	}

	$output.='<div class="the-slider" id="featured" >';
	$output.='<ul class="ui-tabs-nav">';
	$output.=$tabs;
	$output.='</ul>';
	$output.=$fragments;
	$output.='</div>';
	$output.=the_slider_js();

	return $output;
}

function the_slider_demo_display_fragment($i) {
	if ($i===1) $hide=false; else $hide=true;
	$output='<div id="fragment-'.$i.'" class="ui-tabs-panel'.($hide ? " ui-tabs-hide" : "").'" style="">';
	$output.='<img src="'.THESLIDER_URL.'images/image'.$i.'.jpg" alt="" />';
	$output.='<div class="info" >';
	$output.='<h2><a href="#" >Lorem ipsum dolor sit amet</a></h2>';
	$output.='<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla tincidunt condimentum lacus. Pellentesque ut diam....<a href="#" >read more</a></p>';
	$output.='</div>';
	$output.='</div>';
	return $output;
}

function the_slider_demo_display_tab($i) {
	if ($i===1) $selected=true; else $selected=false;
	$output='<li class="ui-tabs-nav-item'.($selected ? " ui-tabs-selected" : "").'" id="nav-fragment-'.$i.'"><a href="#fragment-'.$i.'"><img width="80px" height="50px" src="'.THESLIDER_URL.'images/image'.$i.'.jpg" alt="" /><span>Lorem ipsum dolor sit amet.</span></a></li>';
	return $output;

}