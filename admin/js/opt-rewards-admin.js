jQuery(document).ready(function($){

	jQuery('#optrewards_general_enable').on('change', function() {

		var setting_enable	=	this.value;
	    if( setting_enable ==  1){
            jQuery(".settings_enable").show();
	    }else{
            jQuery(".settings_enable").hide();
	    } 	
	});

	jQuery('#optrewards_general_security_authentication').on('change', function() {

		var security_authentication	=	this.value;
	    if( security_authentication ==  'otp_code'){
            jQuery(".exp_time").show();
	    }else{
            jQuery(".exp_time").hide();
	    } 	
	}); 

	var setting_enable	= jQuery('#optrewards_general_enable').val();

    if( setting_enable ==  1){
        jQuery(".settings_enable").show();
    }else{
        jQuery(".settings_enable").hide();
    } 

    var security_authentication	= jQuery('#optrewards_general_security_authentication').val();
    if( security_authentication ==  'otp_code'){
        jQuery(".exp_time").show();
    }else{
        jQuery(".exp_time").hide();
    }	   

	
});

