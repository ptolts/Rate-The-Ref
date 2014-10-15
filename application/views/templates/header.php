<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>Rate The Ref</title>
<meta name="keywords" content="" />
<meta name="description" content="" />
<link href="/styles.css" rel="stylesheet" type="text/css" />
<link rel="shortcut icon" href="images/favicon.ico" mce_href="images/favicon.ico"/>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
<script src="/jquery.corner.js" type="text/javascript"></script>
<script type="text/javascript" src="/rtf.js"></script>
</head>
<body>

<div id="top">


	<h1 id="logo">RateTheRef</h1>

	<ul id="navigation">
		<li class="home"><a href="/index.php"><span>Home</span></a></li>
		<li class="games"><a href="/index.php/pages/view/games"><span>Games</span></a></li>
		<li class="about"><a href=""><span>About</span></a></li>
	</ul>


</div><!--end div top-->

<div id="content">

<div id="login">

<h2 class="title">User Info:</h2>

<table class="login_table">

<?php 
include("teams.php");
// this is the login box.

$session_data = $this->session->all_userdata();

if(isset($session_data['twitter_name'])){

	echo("

		<tr>

			<td><img id=\"twitterlogo\" src=\"{$session_data['twitter_pic']}\">
			<div><h4 id=\"green\"></h4><span>@{$session_data['twitter_name']}</span></div></td>

		

	");

	if(strcmp($session_data['team'],"NONE")==0){
	//the following is ugly, but if theres no team for this user
	//we have to put in the dialog box code and the "select team image.
		echo("

	

				<td><img id=\"select_team\" name=\"modal\" src=\"images/teamlogo/none.gif\">
				<div><span>Team</span></div></td>
			</tr>

			<script type=\"text/javascript\" src=\"team.js\"></script>

			");

	}

	else {

		echo("

	

				<td><img id=\"team_logo\" src=\"{$teams[$session_data['team']]}\">
				<div><span>Team</span></div></td>
			</tr>

		");

	}


}

else {

	require_once APPPATH . 'third_party/EpiCurl.php';
	require_once APPPATH . 'third_party/EpiOAuth.php';
	require_once APPPATH . 'third_party/EpiTwitter.php';
	include APPPATH . 'third_party/key.php';

	$Twitter = new EpiTwitter($consumerKey, $consumerSecret);

	echo ("
		<tr>		

			<td id=\"twit\"><a href=\"{$Twitter->getAuthenticateUrl()}\"><img id=\"twit\" src=\"\images/twitter.png\"></a>
			<div><h4 id=\"red\"></h4><span>Login</span></div></td>

		</tr>
	");

}

?>

</table>

</div><!--end div login-->

<div class="list_games">

	<?php 

	include("teams.php");

	// Get current CodeIgniter instance
	$CI =& get_instance();
	// We need to use $CI->session instead of $this->session
	$session_details = $CI->session->all_userdata();

	$date = $CI->db->query("SELECT * FROM `dates` ORDER BY date DESC LIMIT 1");
	$date = $date->row();

	if (strcmp(date("m.d.y"),$date->date)==0) {

		echo ("

			<h2 id=\"title\">Todays Games:</h2>

			<table class=\"gameday\">

		");

	} else {

		echo ("

			<h2 id=\"title\">Games ".$date->date.":</h2>

			<table class=\"gameday\">

		");

	}

	$query = $CI->db->query("SELECT team_one,team_two,game_time,game_id FROM games WHERE date='".$date->date."';");

		//echo("SELECT team_one,team_two,game_time,game_id FROM games WHERE date='".date("m.d.y")."';");



		foreach ($query->result_array() as $row){


			echo ("
			
					<tr><td>
					<div class=\"list_border\" name=\"{$row['game_id']}\">
					<img id=\"select_team\" src=\"{$teams[$row['team_one']]}\">
					vs.
					<img id=\"select_team\" src=\"{$teams[$row['team_two']]}\">
					</div>
					</td></tr>

			");

		}

	?>

	</table>	
	
</div><!--end div login-->


