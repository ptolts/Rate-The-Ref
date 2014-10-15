<?php

	// include Epi
	require_once APPPATH . 'third_party/EpiCurl.php';
	require_once APPPATH . 'third_party/EpiOAuth.php';
	require_once APPPATH . 'third_party/EpiTwitter.php';
	include APPPATH . 'third_party/key.php';


	// Get current CodeIgniter instance
	$CI =& get_instance();
	// We need to use $CI->session instead of $this->session
	$session_details = $CI->session->all_userdata();


	$Twitter = new EpiTwitter($consumerKey, $consumerSecret);

	if(isset($_GET['oauth_token']) || (isset($session_details['oauth_token']) && isset($session_details['oauth_token_secret'])))
	{
		// user accepted access
		if( !isset($session_details['oauth_token']) || !isset($session_details['oauth_token_secret']) )
		{
			// user comes from twitter
		   	$Twitter->setToken($_GET['oauth_token']);
			$token = $Twitter->getAccessToken();
			$CI->session->set_userdata('oauth_token', $token->oauth_token);
			$CI->session->set_userdata('oauth_token_secret', $token->oauth_token_secret);
			$Twitter->setToken($token->oauth_token, $token->oauth_token_secret);

		}
		else
		{
		 // user switched pages and came back or got here directly, stilled logged in
		 $Twitter->setToken($session_details['oauth_token'],$session_details['oauth_token_secret']);
		}

	 	$user= $Twitter->get_accountVerify_credentials();
		$CI->session->set_userdata('twitter_pic',$user->profile_image_url);
		$CI->session->set_userdata('twitter_name',$user->screen_name);
		$CI->session->set_userdata('twitter_id',$user->id);

		// check if this is a new user.
		$query = $CI->db->query("SELECT user FROM users WHERE user={$user->id}");

		if ($query->num_rows() < 1)
		{
			$CI->db->query("INSERT INTO users (user, join_date, team) VALUES ({$user->id},'".date("m.d.y")."','NONE');");
		}

		// grab their team.
		$query = $CI->db->query("SELECT team FROM users WHERE user={$user->id}");
		$row = $query->row();
		$CI->session->set_userdata('team', $row->team);


	}

	elseif(isset($_GET['denied'])) {
		// user denied access

	}

	else {
		// user not logged in

	}


