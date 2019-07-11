<?php 
if(isset($_POST['update_sy_url_settings'])) { 
    
    // Empty Values
    $user_roles = '';
    $login_redirect_urls = '';
    $registration_redirect_urls = '';
    $logout_urls = '';
 
    // Form Data
    $login_redirect_url = trim($_POST['sy_default_login_url']);
    $registration_redirect_url = trim($_POST['sy_default_registration_url']);
    $logout_redirect_url = trim($_POST['sy_default_logout_url']);

    if(isset($_POST['select_user_role'])) {
    $user_roles = $_POST['select_user_role'];
    
    $login_redirect_urls = $_POST['sy_login_url'];
    $registration_redirect_urls = $_POST['sy_registration_url'];
    $logout_urls = $_POST['sy_logout_url'];
    }
    
    // Update Option
    update_option('sy_default_login_url', $login_redirect_url); 
    update_option('sy_default_registration_url', $registration_redirect_url);
    update_option('sy_default_logout_url', $logout_redirect_url);
        
    $roles_loop_count = count($user_roles); 
        
        update_option('sy_roles_loop_count', $roles_loop_count);
        update_option('sy_user_roles', $user_roles);
   
    if($user_roles) { 
        foreach($user_roles as $user_key => $user_role) {   
            update_option('sy_login_url_'.$user_role, $login_redirect_urls[$user_key]);
            update_option('sy_registration_url_'.$user_role, $registration_redirect_urls[$user_key]);
            update_option('sy_logout_url_'.$user_role, $logout_urls[$user_key]);
        }
    }
    
  
}    
    // Get Options Data
    $login_redirect_url = get_option('sy_default_login_url'); 
    $registration_redirect_url = get_option('sy_default_registration_url');
    $logout_redirect_url = get_option('sy_default_logout_url');
    $roles_loop_count = get_option('sy_roles_loop_count'); 
    $user_roles = get_option('sy_user_roles');  
    
?>

<div id="sylr_settings_page" class="wrap">
    
<h1><?php echo esc_html( get_admin_page_title() ); ?> v<?php echo SYLR_PLUGIN_VERSION; ?> - Manage Redirect Rules</h1>

     <form name="login_redirect_form" action="" method="post" enctype="multipart/form-date"> 
        <div class="sylr-content">
            <h3>All Users: Default </h3>
            <table class="form-table">
            <tbody>
                <tr>
                    <th scope="row"><label for="sy_default_login_url">Login Redirect URL</label></th> 
                    <td><input type="text" value="<?php echo $login_redirect_url; ?>" name="sy_default_login_url" placeholder="Enter Login Redirect URL." class="large-text" /></td>
                </tr>
                <tr>
                    <th scope="row"><label for="sy_default_registration_url">Registration Redirect URL</label></th>
                    <td><input type="text" value="<?php echo $registration_redirect_url; ?>" name="sy_default_registration_url" placeholder="Enter Registration Redirect URL." class="large-text" /></td>
                </tr>
                <tr>
                    <th scope="row"><label for="sy_default_logout_url">Logout Redirect URL</label></th>
                    <td><input type="text" value="<?php echo $logout_redirect_url; ?>" name="sy_default_logout_url" placeholder="Enter Logout Redirect URL." class="large-text" /></td>
                </tr>
            </tbody>
            </table>
            <hr />
            <h3>Specific User Roles: (Repeated Fields)</h3>
            <div class="repeated_table_row ">
            <?php if($user_roles) { 
            foreach($user_roles as $user_key => $user_role) {  
            $sy_login_url = get_option('sy_login_url_'.$user_role);
            $sy_registration_url = get_option('sy_registration_url_'.$user_role);
            $sy_logout_url = get_option('sy_logout_url_'.$user_role);   
            ?>
            <h3>User Role: <?php echo $user_role; ?> - Edit</h3>    
            <div class="fields_container">
                <div class="repeated_row">
                    <label for="select_user_role">Select User Role</label>
                    <select name="select_user_role[]" id="select_user_role">
                        <option value="">--Select User Role--</option>
                        <?php sylr_wp_dropdown_roles( $user_role, $user_roles ); ?>
                    </select>
                </div>
                <div class="repeated_row">
                    <label for="sy_login_url">Login Redirect URL</label>
                    <input type="text" value="<?php echo $sy_login_url; ?>" name="sy_login_url[]" placeholder="Enter Login Redirect URL." class="large-text" />
                </div>
                <div class="repeated_row">
                    <label for="sy_registration_url">Registration Redirect URL</label>
                    <input type="text" value="<?php echo $sy_registration_url; ?>" name="sy_registration_url[]" placeholder="Enter Registration Redirect URL." class="large-text" />
                </div>
                <div class="repeated_row">
                    <label for="sy_logout_url">Logout Redirect URL</label>
                    <input type="text" value="<?php echo $sy_logout_url; ?>" name="sy_logout_url[]" placeholder="Enter Logout Redirect URL." class="large-text" />
                </div>
                <div class="repeated_row remove_repeated_row">
                    <input type="button" class="button button-default remove_roles_fields" value="Remove" name="remove_rules" />
                </div>
            </div>
            <?php }} else {
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
                    } ?>
                <div class="repeated_row add_more_repeated_rows">
                    <input type="button" class="button button-default add_more_roles_fields" value="Add More" name="add_more_rules" />
                </div>
              <hr />
            <div class="sylr-col-12">
                 <p><input type="submit" class="button button-primary" value="Update Settings" name="update_sy_url_settings" /></p> 
            </div>
           
        
    </form>
   

</div>