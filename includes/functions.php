<?php

function sylr_wp_dropdown_roles( $selected = '', $exclude ) {
    $r = '';
 
    $editable_roles =  get_editable_roles();
    $exclude[] = 'administrator'; 
    foreach ( $editable_roles as $role => $details ) {
        $name = translate_user_role($details['name'] );
        // preselect specified role
        if ( $selected == $role ) {
            $r .= "\n\t<option selected='selected' value='" . esc_attr( $role ) . "'>$name</option>";
        } else {
            if(!in_array($role, $exclude)) {
                $r .= "\n\t<option value='" . esc_attr( $role ) . "'>$name</option>";
            }
        }
    }
 
    echo $r;
}