<?php

/*
Plugin Name: WP Redirect Manager
Plugin URI:  http://myplugins.shrikantyadav.com/
Description: This is a simple plugin to redirect user to a specific page or URL after login.
Version:     1.0.1
Author:      Shrikant Yadav   
Author URI:  http://shrikantyadav.com/ 
License:     GPL2 
*/

 
define( 'SYLR_PLUGIN_NAME', 'WP Redirect Manager' );
define( 'SYLR_DOMAIN_NAME', 'shrikantyadav' );
define( 'SYLR_PLUGIN_VERSION', '1.0.0' );
define( 'SYLR_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'SYLR_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

/*
* Plugin Activation Function
*/ 
function sylr_activation() { 
  
    // Plugin setting function
    sylr_settings();

    // Clear the permalinks
    flush_rewrite_rules();
 
}
register_activation_hook( __FILE__, 'sylr_activation' );


/*
 * Plugin Setting Function
 */
function sylr_settings() {
    // Plugin Setting Code
    add_option('SYLR_PLUGIN_VERSION',SYLR_PLUGIN_VERSION);  

}
add_action('init','sylr_settings'); 

/*
 * Plugin Deactivation Function 
 */
function sylr_deactivation() {
    
    // Deactivation rules here
//    sylr_remove_setting();
    
    // Clear the permalinks
    flush_rewrite_rules();

}
register_deactivation_hook( __FILE__, 'sylr_deactivation' );

/*
* Admin Script and Styles
*/
function sylr_admin_enqueue_scripts() { 

        global $pagenow, $typenow; 
 
            wp_enqueue_media();     
            wp_enqueue_style( 'sylr-admin-style', plugins_url( 'css/admin-style.css', __FILE__ ) );  
   
            wp_enqueue_script( 'sylr-admin-scripts', plugins_url( 'js/admin-scripts.js', __FILE__ ), array( 'jquery'), '20180112', true );
            
            wp_localize_script( 'sylr-admin-scripts', 'SYLROBJ', array( 
                'ajax_url' => admin_url( 'admin-ajax.php' ),
                'sylr_security' => wp_create_nonce( 'sylr_setting_nonce_action' ),  
            ));              
        
} 
add_action( 'admin_enqueue_scripts', 'sylr_admin_enqueue_scripts' );   




/*
* Including files 
*/
require_once ( SYLR_PLUGIN_PATH . 'includes/admin/class-settings.php' );
require_once ( SYLR_PLUGIN_PATH . 'includes/functions.php' );       

