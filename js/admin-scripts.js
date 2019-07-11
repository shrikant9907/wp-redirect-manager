jQuery('document').ready(function($){

    // Remove User Role Fields
    jQuery('.remove_roles_fields').click(function(){
        
        jQuery(this).parent().parent().prev().remove(); 
        jQuery(this).parent().parent().remove(); 
    });

    // Accordian Script
   jQuery('.repeated_table_row h3').click(function(e){  
       e.preventDefault();
       jQuery('.repeated_table_row .fields_container').not(jQuery(this).next()).slideUp();
       jQuery(this).next().slideToggle(); 
        if(jQuery(this).hasClass('open')) {
         jQuery(this).removeClass('open');    
        } else { 
         jQuery('.repeated_table_row h3').removeClass('open');   
         jQuery(this).addClass('open');
        }
   });

    // Add More Roles Fields
    jQuery('.add_more_roles_fields').click(function(){
        var jthis = jQuery(this); 
        var jthis_parent = jthis.parent();  
        jQuery.ajax({ 
            type: 'POST',   
            url: SYLROBJ.ajax_url, 
            data: { 
                "action": "roles_repeated_fields_action", 
            }, 
            success: function(data){  
                jthis_parent.before(data);
            }	 
        });
    });
   
});   

