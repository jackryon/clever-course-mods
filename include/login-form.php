<?php
	/*	
	*	Goodlayers Login Form File
	*/	
	
	// redirect to login page
	add_filter('home_template', 'gdlr_lms_register_login_template');
	add_filter('page_template', 'gdlr_lms_register_login_template');
	add_filter('paged_template', 'gdlr_lms_register_login_template');
	function gdlr_lms_register_login_template($template){
		if( !is_user_logged_in() ){
			if( !empty($_GET['login']) ){
				$template = dirname(dirname( __FILE__ )) . '/login.php';
			}else if( !empty($_GET['register']) ){
				$template = dirname(dirname( __FILE__ )) . '/register.php';
			}
		}
		return $template;
	}
	
	// redirect to login page
	add_action('login_form_login', 'gdlr_lms_login_redirect');
	function gdlr_lms_login_redirect(){
		$args = array('login'=>home_url());
		wp_redirect(add_query_arg($args, home_url()));
	}	
	
	// redirect to login failed page
	add_action('wp_login_failed', 'gdlr_lms_login_failed_redirect');
	function gdlr_lms_login_failed_redirect( $username = '' ){
		global $pagenow;
		if( 'wp-login.php' == $pagenow ){
			$args = array('login'=>home_url());
			
			// check the post data
			if(!empty($username)){ 
				$args['status'] = 'login_incorrect';
			}
			
			wp_redirect(add_query_arg($args, home_url()));
			exit();
		}
	}
	
	// redirect to lost password page
	add_action('login_form_lostpassword', 'gdlr_lms_login_lost_redirect');
	add_action('login_form_retrievepassword', 'gdlr_lms_login_lost_redirect');
	function gdlr_lms_login_lost_redirect( ){
		$args = array('login'=>home_url(), 'action'=>'lost_password');
		
		// check the post data
		if( !empty($_POST['user_login']) ){
			$errors = retrieve_password();
			
			if( !is_wp_error($errors) ){
				$args['status'] = 'forgot_password_confimation';
				unset($args['action']);
			}else{
				$args['status'] = $errors->get_error_code();
			} 
		}		
		
		wp_redirect(add_query_arg($args, home_url()));	
		exit();
	}
	
	// redirect to retrieve password page
	add_action('login_form_rp', 'gdlr_lms_login_resetpass_redirect');
	add_action('login_form_resetpass', 'gdlr_lms_login_resetpass_redirect');
	function gdlr_lms_login_resetpass_redirect( ){	
		$args = array('login'=>home_url(), 'action'=>'reset_pass');
		
		if( !empty($_GET['key']) ){
			$args['key'] = $_GET['key'];
		}
		if( !empty($_GET['login']) ){
			$args['login'] = $_GET['login'];
		}
		
		wp_redirect(add_query_arg($args, home_url()));	
		exit();
	}	
?>