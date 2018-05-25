<?php
/*
Plugin Name: WPLMS VC Templates
Plugin URI: http://www.Vibethemes.com
Description: A WPLMS Addon to calculate the time spent by users inside the course.
Version: 1.0
Author: Vibethemes (H.K.)
Author URI: http://www.vibethemes.com
Text Domain: wplms-ut
*/
/*
Copyright 2017  VibeThemes  (email : vibethemes@gmail.com)
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_action( 'init', 'wplms_vc_templates_update' );
function wplms_vc_templates_update() {

    /* Load Plugin Updater */
    require_once( trailingslashit( plugin_dir_path( __FILE__ ) ) . '/class-plugin-update.php' );


    /* Updater Config */
    $config = array(
        'base'      => plugin_basename( __FILE__ ), //required
        'dashboard' => true,
        'repo_uri'  => 'http://wplms.io/',  //required
        'repo_slug' => 'wplms-vc-templates',  //required
    );

    /* Load Updater Class */
    new WPLMS_VC_Tempaltes_Updates_Auto_Update( $config );
}

//For Vibe custom types
add_filter('wplms_sync_settings',function($args){
	$args[]=array(
				'id'=>'wplms_vc_templates',
				'label'=>__('Visual Composer Templates','vibe-customtypes'),
				'description'=>__('Add Visual Composer templates.','vibe-customtypes'),
			);
	return $args;
});
//Change below to register activation hook when finalised
add_action('current_screen',function($current_screen){

	if($current_screen->base == 'lms_page_lms-settings' && is_admin()){

		if(isset($_GET['add_wplms_vc_templates']) && current_user_can('manage_options')){
			
			$myFile = plugin_dir_path( __FILE__ )."/export.txt";
		    $fh = fopen($myFile, 'r');
		   	$wplms_vc_templates = fread($fh,filesize($myFile ));

		    fclose($fh); 
		    $wplms_vc_templates = unserialize(base64_decode($wplms_vc_templates));
		    //$wplms_vc_templates = string_to_array($wplms_vc_templates);

		    update_option('wpb_js_templates',$wplms_vc_templates);
		}
	}
});

function string_to_array($string) {
    if(is_string($string)) $string = (array) $string;
    if(is_string($string)) {
        $new = array();
        foreach($string as $key => $val) {
            $new[$key] = string_to_array($val);
        }
    }
    else $new = $string;
    return $new;       
}

add_action('current_screen',function($current_screen){

	

	if($current_screen->base == 'lms_page_lms-settings' && is_admin()){

		if(isset($_GET['fetch_wplms_vc_templates']) && current_user_can('manage_options')){

			$wplms_vc_templates = get_option('wpb_js_templates');
			$wplms_vc_templates = base64_encode(serialize($wplms_vc_templates));
			
			$myFile = plugin_dir_path( __FILE__ )."/export.txt";
		    $fh = fopen($myFile, 'w+');
		    fwrite($fh, $wplms_vc_templates );
		    fclose($fh); 
		}
	}
});                                                                                                                                                                                                      