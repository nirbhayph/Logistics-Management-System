//  ====================================================================
//	Theme Name: Spandow - Multi-purpose Bootstrap Template
//	Theme URI: http://themeforest.net/user/responsiveexperts
//	Description: This javascript file is using as a settings file. This file includes the sub scripts for the javascripts used in this template.
//	Version: 1.0
//	Author: Responsive Experts
//	Author URI: http://themeforest.net/user/responsiveexperts
//	Tags:
//  ====================================================================

//	TABLE OF CONTENTS
//	---------------------------
//	 01. Form Settings

//  ====================================================================


(function() {
	"use strict";
	var contact_error = $('#contact-error');
	var contact_success = $('#contact-success');
	var contact_failed = $('#contact-failed');
	var contact_loading = $('#contact-loading');
	function validate_email(email) 
	{
	   var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
	   return reg.test(email);
	}
	$(document).ready(function(e) {
		$('#contact-submit').on('click',function(e) {
			e.preventDefault();
			contact_submit();
		});
		$('input:text, textarea').on('keyup',function(e) {
			$(this).removeClass('error');
		});
		contact_error.hide();
		contact_loading.hide();
		contact_success.hide();
		contact_failed.hide();
	});
	// Contact form validation
	function contact_submit()
	{
		var contact_form = $('#contact-form');
		var submit_flag = 'yes';
		var contact_name = $('#contact-name');
		contact_error.hide();
		var name_val = $('#contact-name').val();
		var email_val = $('#contact-email').val();
		var website_val = $('#contact-web').val();
		var msg_val =  $('#contact-msg').val();
		if( name_val =='')
		{
				if(submit_flag == 'yes')
				{
					contact_name.focus();
				}
				contact_name.addClass('error');
				submit_flag = 'no';
		}
		var contact_email =$('#contact-email');
		if(email_val =='')
		{
				if(submit_flag == 'yes')
				{
					contact_email.focus();
				}
				contact_email.addClass('error');
				submit_flag = 'no';
		}
		if(!validate_email(email_val))
		{
				if(submit_flag == 'yes')
				{
					contact_email.focus();
				}
				contact_email.addClass('error');
				submit_flag = 'no';
		}
		var contact_website = $('#contact-web');
		if( website_val =='')
		{
				if(submit_flag == 'yes')
				{
					contact_website.focus();
				}
				contact_website.addClass('error');
				submit_flag = 'no';
		}
		var contact_msg = $('#contact-msg');
		if( msg_val =='' || msg_val == 'Your Message *')
		{
				if(submit_flag == 'yes')
				{
					contact_msg.focus();
				}
				contact_msg.addClass('error');
				submit_flag = 'no';
		}
		if(submit_flag != 'yes')
		{	
			contact_error.show().html('Please review the above details filled');
		}
		else
		{
			contact_error.hide();
			contact_success.hide();
			contact_failed.hide();
			contact_form.hide();
			contact_loading.show();
	
			$.ajax({
				url: 'php/reachus.php',
				type: 'post',
				cache: false,
				data: {'name' : name_val, 'email' : email_val , 'website' : website_val , 'msg' : msg_val},
				success: function(data) {
								if(data =='ok')
								{
									contact_error.hide();
									contact_failed.hide();
									contact_form.hide();
									contact_loading.hide();
									contact_success.show();
									
									contact_name.val('');
									contact_email.val('');
									contact_website.val('');
									contact_msg.val('');
									setTimeout("$('#contact-success').hide(); $('#contact-form').show();",5000);
								}
								else
								{
									contact_error.hide();
									contact_success.hide();
									contact_form.hide();
									contact_loading.hide();
									contact_failed.show();
									setTimeout("$('#contact_failed').hide(); $('#contact-form').show();",5000);
								}
						}
					});	
		}
	}
})(jQuery);
