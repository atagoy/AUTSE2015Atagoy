<?php

$basic_options = array('form-title'	=> array(	'label'		=> __('Signup form title', 'wp-registration'),
					 							'desc'		=> __('Provide signup form title', 'wp-registration'),
					 							'id'			=> 'nm_wpregistration'.'_form_title',
					 							'type'			=> 'text',
					 							'default'		=> '',
					 							'help'			=> ''
					 							),
					'form-description' => array(	'label'		=> __('Signup form description', 'wp-registration'),
					 							'desc'		=> __('Provide signup form description', 'wp-registration'),
					 							'id'			=> 'nm_wpregistration'.'_form_desc',
					 							'type'			=> 'textarea',
					 							'default'		=> '',
					 							'help'			=> ''
					 							),
					'terms-condition'=> array(	'label'		=> __('Terms and condition', 'wp-registration'),
					 							'desc'		=> __('Enter terms and condition', 'wp-registration'),
					 							'id'			=> 'nm_wpregistration'.'_terms_condition',
					 							'type'			=> 'textarea',
					 							'default'		=> '',
					 							'help'			=> __('It will put a term & condition checkout with your text at the bottom of registration page.', 'wp-registration'),
					 							),
					'success-message'=> array(	'label'		=> __('Successful registration message', 'wp-registration'),
					 							'desc'		=> __('Enter the message for successful registration', 'wp-registration'),
					 							'id'			=> 'nm_wpregistration'.'_success_message',
					 							'type'			=> 'text',
					 							'default'		=> '',
					 							'help'			=> __('It will be shown to user after successful registration.', 'wp-registration'),
					 							),
					'redirect-url'=> 	array(  'label'		=> __('Redirect after registration', 'wp-registration'),
					 							'desc'		=> __('Enter URL to redirect after submission button', 'wp-registration'),
					 							'id'			=> 'nm_wpregistration'.'_redirect_url',
					 							'type'			=> 'text',
					 							'default'		=> '',
					 							'help'			=> __('If provided user will be redirected to this url on successful registration.', 'wp-registration'),
					 							),
		
				'redirect-url-login'=> 	array(  'label'		=> __('Redirect after login', 'wp-registration'),
												'desc'		=> __('Enter URL to redirect after submission button', 'wp-registration'),
												'id'			=> 'nm_wpregistration'.'_redirect_url_login',
												'type'			=> 'text',
												'default'		=> '',
												'help'			=> __('If provided user will be redirected to this url on successful registration.', 'wp-registration'),
												),
					'button-title'=> 	array(  'label'		=> __('Button title', 'wp-registration'),
												'desc'		=> __('Provide signup form submit button label', 'wp-registration'),
												'id'			=> 'nm_wpregistration'.'_button_title',
												'type'			=> 'text',
												'default'		=> '',
												'help'			=> ''
										),
				'validation-message'=> 	array(  'label'		=> __('Signup form validation message', 'wp-registration'),
												'desc'		=> __('Provide signup form validation message', 'wp-registration'),
												'id'			=> 'nm_wpregistration'.'_validation_notification',
												'type'			=> 'text',
												'default'		=> '',
												'help'			=> __('It shows validation notification at bottom of the signup form, when one or more required fields are empty.', 'wp-registration'),
										),
					);
					

$meatEmailTemplates = array('new-user' => array(	'label'		=> __('New user email', 'wp-registration'),
					 							'desc'		=> __('Email template for new user registration', 'wp-registration'),
					 							'id'			=> 'nm_wpregistration'.'_new_user',
					 							'type'			=> 'textarea',
					 							'default'		=> '%USERNAME%, %USER_PASSWORD%, %SITE_NAME%',
					 							'help'			=> __('Provide template for new user. Put %USERNAME%, %USER_PASSWORD% and %SITE_NAME% at appropriate positions. These will be replaced with user name, user password and site name.', 'wp-registration'),
					 							),
					/*'change-password' => array(	'label'		=> __('Change password email', 'wp-registration'),
					 							'desc'		=> __('Email template on successfull change of user password', 'wp-registration'),
					 							'id'			=> 'nm_wpregistration'.'_change_password',
					 							'type'			=> 'textarea',
					 							'default'		=> '',
					 							'help'			=> ''
					 							)*/
					);

$style_settings = array(
		'button-txt-color'=> array(	'label'		=> __('Signup button text color', 'wp-registration'),
				'desc'		=> __('Select signup form submit button text color', 'wp-registration'),
				'id'			=> 'nm_wpregistration'.'_button_txt_color',
				'type'			=> 'color',
				'default'		=> '#fff',
				'help'			=> 'in Hex code like: #ffccc'
		),
		'button-txt-font'=> array(  'label'		=> __('Button font size', 'wp-registration'),
				'desc'		=> __('Select signup form submit button font size', 'wp-registration'),
				'id'			=> 'nm_wpregistration'.'_button_fontsize',
				'type'			=> 'select',
				'default'		=> '12px',
				'options'		=> array('12px'=>'12px', '14px'=>'14px', '16px'=>'16px', '18px'=>'18px', '20px'=>'20px', '22px'=>'22px'),
				'help'			=> ''
		),
		'button-bg-color'=> array('label'		=> __('Signup form button BG color', 'wp-registration'),
				'desc'		=> __('Select signup form submit button BG color', 'wp-registration'),
				'id'			=> 'nm_wpregistration'.'_button_bg_color',
				'type'			=> 'color',
				'default'		=> '#999',
				'help'			=> 'in Hex code like: #ffccc'
		),
		'button-class'	=> array(	'label'		=> __('Button class', 'wp-registration'),
				'desc'		=> __('Enter button class', 'wp-registration'),
				'id'			=> 'nm_wpregistration'.'_button_class',
				'type'			=> 'text',
				'default'		=> '',
				'help'			=> __('if you need add more styles you can add class to signup button', 'wp-registration'),
		),
		'form-css'		=> array(	'label'		=> __('Form custom CSS', 'wp-registration'),
				'desc'		=> __('Enter custom CSS', 'wp-registration'),
				'id'			=> 'nm_wpregistration'.'_form_css',
				'type'			=> 'textarea',
				'default'		=> '',
				'help'			=> ''
		),
);


$meat_file_meta = array('file-meta'	=> array(	
									'desc'		=> '',
									'type'		=> 'file',
									'id'		=> 'file-meta.php',
									),
								);
$meat_pro_features = array('file-meta'	=> array(	
									'desc'		=> '',
									'type'		=> 'file',
									'id'		=> 'pro-features.php',
									),
								);

$this -> the_options = array(
					'basic-settings'	=> array(	
							'name'		=> __('Basic settings', 'wp-registration'),
							'type'	=> 'tab',
							'desc'	=> __('Set options as per your need', 'wp-registration'),
							'meat'	=> $basic_options,
							),
		
					'style-settings'	=> array(	
							'name'		=> __('Style & Layout settings', 'wp-registration'),
							'type'	=> 'tab',
							'desc'	=> __('Set form design', 'wp-registration'),
							'meat'	=> $style_settings,
					
					),
					'email-templates'	=> array(	
							'name'		=> __('Email Templates', 'wp-registration'),
							'type'	=> 'tab',
							'desc'	=> __('Set email templates as per your need', 'wp-registration'),
							'meat'	=> $meatEmailTemplates,
						),
		
	
					'file-meta'	=> array(	'name'		=> __('Registration Meta', 'wp-registration'),
							'type'	=> 'tab',
							'desc'	=> __('More field can be attached to User registration', 'wp-registration'),
							'meat'	=> $meat_file_meta,
							'class'	=> 'pro',
					
					),
					
						'pro-features'	=> array(	'name'		=> __('PRO Features', 'nm-filemanager'),
						'type'	=> 'tab',
						'desc'	=> '',
						'meat'	=> $meat_pro_features,
						'class'	=> 'pro',
							
				),
	
					);

//print_r($repo_options);