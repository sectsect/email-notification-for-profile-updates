<?php
/*
Plugin Name: Email Notification for Profile Updates
Plugin URI: https://github.com/sectsect/email-notification-for-profile-updates
Description: Send Email Notification to All Administrator User on Profile Updates.
Author: SECT INTERACTIVE AGENCY
Version: 1.0.0
Author URI: https://www.ilovesect.com/
*/

// Get email address of type Administrator
class AdminClass
{
	public function get_admin_email(){
		$args = array(
			'role' => 'administrator'
		);
	    $blogusers = get_users($args);
		$emails = array();
	    foreach ($blogusers as $user) {
			array_push($emails, $user->user_email);
	    }

		return $emails;
	}
}
if(is_admin()){
	$class = new AdminClass();
	$mailto = $class->get_admin_email();
}

add_action( 'profile_update', function() use ($mailto) {
	global $current_user;
	get_currentuserinfo();
	$user_info = get_userdata( $current_user->ID );
	if(reset($user_info->roles) != "administrator"){
	    $site_url     = get_bloginfo('wpurl');
	    $site_title   = get_bloginfo('name');
		$profile_page = get_author_posts_url($user_info->ID);
	// $to            = $user_info->user_email;
	    $to_name      = $user_info->display_name;

		$subject = "User Profile Updated: " . $site_url;

	    // message body
	//    $message = "Hello " . $to_name . ",\n\n";
	    $message = $to_name . "'s profile has been updated.\n\n";
		$message .= "▼Author Page" . "\n";
	    $message .= $profile_page . "\n\n";
	    $message .= $site_title . "\n";
	    $message .= $site_url;

		$headers = 'From: Your Name <yourname@example.com>' . "\r\n";
	    wp_mail( $mailto, $subject, $message, $headers );
	}
}, 10, 2);

// @ http://wpcodesnippet.com/send-email-notification-user-profile-updates/
// @ http://wordpress.stackexchange.com/questions/174946/trying-to-use-add-action-and-do-action-with-parameters
// @ http://stackoverflow.com/questions/2843356/can-i-pass-arguments-to-my-function-through-add-action
?>
