<?php 
//Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Class for registering a new settings page under Settings.
 */
class SylrSettingsPage { 
 
    /**
    * Holds the values to be used in the fields callbacks
    */
    private $options; 
 
    /**
     * Constructor.
     */
    public function __construct() { 
        // WooCommerce Login Redirect
        add_filter( 'woocommerce_login_redirect', array( $this, 'sylr_wc_login_redirect' ), 10, 2 );
        
        // WooCommerce Registration Redirect 
        add_filter( 'woocommerce_registration_redirect', array( $this, 'sylr_wc_registration_redirect' ), 10, 1 );
        
        // Login Redirect 
        add_filter( 'login_redirect', array( $this, 'sylr_login_redirect' ), 10, 3 );
        
        // Logout URL
        add_filter( 'logout_url', array( $this, 'sylr_logout_url' ), 10, 2 );

        // Registrtion Redirect 
        add_filter( 'registration_redirect', array( $this, 'sylr_registration_redirect' ) );
        
        add_action( 'admin_menu', array( $this, 'add_pluing_settings_page' ) );
        add_action( 'wp_ajax_roles_repeated_fields_action', array( $this, 'sylr_get_repeated_roles_fields' ) );
        add_action( 'wp_ajax_nopriv_roles_repeated_fields_action', array( $this, 'sylr_get_repeated_roles_fields' ) );
    }
    
    /*
     * WooCommerce Registration Redirect
     */
    function sylr_wc_registration_redirect($redirect_to) {     
        
        $user = wp_get_current_user();
        
        $output_url = $redirect_to;  
         
        $reg_redirect_url = get_option('sy_default_registration_url');  
        if($reg_redirect_url!='') { 
            $output_url = $reg_redirect_url;
        }
             
        // Check User Roles
	if ( isset( $user->roles ) && is_array( $user->roles ) ) {
                 
            $user_roles = $user->roles;  
            $syrl_user_roles = get_option('sy_user_roles');   

            // Check User Role Admin 
            if ( in_array( 'administrator', $user_roles ) ) {

                // Redirect Admin To Default Place
                $output_url = $redirect_to;

            } else {
                if(is_array($syrl_user_roles) && !empty($syrl_user_roles)) {

                    foreach($syrl_user_roles as $syrl_user_role) {
                        if ( in_array( $syrl_user_role, $user_roles ) ) {
                            $sy_registration_url = get_option('sy_registration_url_'.$syrl_user_role);
                            if($sy_registration_url!='') { 
                                $output_url =  $sy_registration_url; 
                            }  
                        } 
                    }

                } 
            }  
	}  
        
    }
    
    /*
     * WooCommerce Login Redirect
     */
    function sylr_wc_login_redirect($redirect_to, $user) {
        
        $output_url = $redirect_to; 
        
        // Check if default URL given
        $login_redirect_url = get_option('sy_default_login_url');  
        if($login_redirect_url!='') { 
            $output_url = $login_redirect_url;
        }  
        
        // Check User Roles
	if ( isset( $user->roles ) && is_array( $user->roles ) ) {
                 
                $user_roles = $user->roles;  
                $syrl_user_roles = get_option('sy_user_roles');  
             
                // Check User Role Admin 
		if ( in_array( 'administrator', $user_roles ) ) {
                        // Redirect Admin To Default Place
                
			$output_url = $redirect_to;
                    
		} else {
                        if(is_array($syrl_user_roles) && !empty($syrl_user_roles)) {
                            
                            foreach($syrl_user_roles as $syrl_user_role) {
                                if ( in_array( $syrl_user_role, $user_roles ) ) {
                                    $sy_login_url = get_option('sy_login_url_'.$syrl_user_role);
                                    if($sy_login_url!='') { 
                                        $output_url = $sy_login_url; 
                                    } 
                                }
                            }
                            
                        }
		}
	}
        
        return $output_url; 
        
    }
    
    /*
     * Logout URL
     */
    public function sylr_logout_url($logout_url, $redirect) {
        
        $user = wp_get_current_user();
        $redirect_logout_url = $logout_url.'&redirect_to='; 
        $home_url = $redirect_logout_url.home_url( '/' );  
        
        $output_url = $home_url;
   
        // Check if default URL given
        $default_redirect_url = trim(get_option('sy_default_logout_url')); 
        if($default_redirect_url!='') {
            $output_url = $redirect_logout_url.$default_redirect_url;
        } 
        
        // Check User Roles
	if ( isset( $user->roles ) && is_array( $user->roles ) ) {
                 
                $user_roles = $user->roles;  
                $syrl_user_roles = get_option('sy_user_roles');  
               
                // Check User Role Admin 
		if ( in_array( 'administrator', $user_roles ) ) {
                  
                        // Redirect Admin To Default Place
			$output_url = $logout_url;
                        
		} else {
                        if(is_array($syrl_user_roles) && !empty($syrl_user_roles)) {
       
                            foreach($syrl_user_roles as $syrl_user_role) {
                                
                                if ( in_array( $syrl_user_role, $user_roles ) ) {
                                    
                                    $sy_logout_url = get_option('sy_logout_url_'.$syrl_user_role);
                                    
                                    if($sy_logout_url!='') {
                                        
                                        $output_url = $redirect_logout_url.$sy_logout_url; 
                                        
                                    }  
                                    
                                }  
                            }
                            
                        } 
		}
	} 
        
        return $output_url;  
   
    }
    
    /*
     * Login Redirect
     */
    public function sylr_login_redirect($redirect_to, $request, $user) {

        $output_url = $redirect_to; 
        
        // Check if default URL given
        $login_redirect_url = get_option('sy_default_login_url');  
        if($login_redirect_url!='') { 
            $output_url = $login_redirect_url;
        }  
        
        // Check User Roles
	if ( isset( $user->roles ) && is_array( $user->roles ) ) {
                 
                $user_roles = $user->roles;  
                $syrl_user_roles = get_option('sy_user_roles');  
             
                // Check User Role Admin 
		if ( in_array( 'administrator', $user_roles ) ) {
                        // Redirect Admin To Default Place
                
			$output_url = $redirect_to;
                    
		} else {
                        if(is_array($syrl_user_roles) && !empty($syrl_user_roles)) {
                            
                            foreach($syrl_user_roles as $syrl_user_role) {
                                if ( in_array( $syrl_user_role, $user_roles ) ) {
                                    $sy_login_url = get_option('sy_login_url_'.$syrl_user_role);
                                    if($sy_login_url!='') { 
                                        $output_url = $sy_login_url; 
                                    } 
                                }
                            }
                            
                        }
		}
	}
        
        return $output_url; 
        
    }
    
    /*
     * Registration Redirect
     */
    public function sylr_registration_redirect($redirect_to) {

        $user = wp_get_current_user();
        
        $output_url = $redirect_to;  
         
        $reg_redirect_url = get_option('sy_default_registration_url');  
        if($reg_redirect_url!='') { 
            $output_url = $reg_redirect_url;
        }
             
        // Check User Roles
	if ( isset( $user->roles ) && is_array( $user->roles ) ) {
                 
            $user_roles = $user->roles;  
            $syrl_user_roles = get_option('sy_user_roles');   

            // Check User Role Admin 
            if ( in_array( 'administrator', $user_roles ) ) {

                // Redirect Admin To Default Place
                $output_url = $redirect_to;

            } else {
                if(is_array($syrl_user_roles) && !empty($syrl_user_roles)) {

                    foreach($syrl_user_roles as $syrl_user_role) {
                        if ( in_array( $syrl_user_role, $user_roles ) ) {
                            $sy_registration_url = get_option('sy_registration_url_'.$syrl_user_role);
                            if($sy_registration_url!='') { 
                                $output_url =  $sy_registration_url; 
                            }  
                        } 
                    }

                } 
            }  
	}  
        
    }
    
    /**
    * Registers Settings.
    */
    public function add_pluing_settings_page() { 

        add_menu_page(
            __( SYLR_PLUGIN_NAME, SYLR_DOMAIN_NAME ), // Page Title
            __( SYLR_PLUGIN_NAME, SYLR_DOMAIN_NAME ), // Menu Title
            'manage_options', // Capablilties
            'sylr_settings', // Page slug
            array(
                $this,
                'sylr_settings_cb' // callback function
            ),
            'dashicons-admin-generic', 
             57
        ); 
        
    }
    
    /**
     * Settings page display callback.
     */ 
    public function sylr_settings_cb() {
        require_once ( SYLR_PLUGIN_PATH . 'includes/admin/redirect-options.php' ); 
    }
    
    /*
     * Repeated Roles Fields
     */
    public function sylr_get_repeated_roles_fields() {
        $user_roles = get_option('sy_user_roles'); 
        ob_start();
        if($user_roles=='') {
            $user_roles = array(); 
        }
        ?> 
<h3>New - Click Update Settings to save</h3>
<div class="fields_container_new">
        <div class="repeated_row">
            <label for="select_user_role">Select User Role</label>
            <select name="select_user_role[]" id="select_user_role">
                <option value="">--Select User Role--</option>
                <?php sylr_wp_dropdown_roles( '', $user_roles ); ?>
            </select>
        </div>
        <div class="repeated_row">
            <label for="sy_login_url">Login Redirect URL</label>
            <input type="text" value="" name="sy_login_url[]" placeholder="Enter Login Redirect URL." class="large-text" />
        </div>
        <div class="repeated_row">
            <label for="sy_registration_url">Registration Redirect URL</label>
            <input type="text" value="" name="sy_registration_url[]" placeholder="Enter Registration Redirect URL." class="large-text" />
        </div>
        <div class="repeated_row">
            <label for="sy_logout_url">Logout Redirect URL</label>
            <input type="text" value="" name="sy_logout_url[]" placeholder="Enter Logout Redirect URL." class="large-text" />
        </div>
</div>
        <?php
        $fields = ob_get_clean(); 
        echo $fields; 
        wp_die();
    }

}
 
$SylrSettingsPage = new SylrSettingsPage();

