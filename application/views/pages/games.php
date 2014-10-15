

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

		<div id=\"left\">

		<h2 id=\"title\">Todays Games:</h2>

		<table class=\"gameday\"><tr><td>

	");

} else {

	echo ("

		<div id=\"left\">

		<h2 id=\"title\">Games from ".$date->date.":</h2>

		<table class=\"gameday\"><tr><td>

	");

}

$query = $CI->db->query("SELECT team_one,team_two,game_time,game_id FROM games WHERE date='".$date->date."';");

	//echo("SELECT team_one,team_two,game_time,game_id FROM games WHERE date='".date("m.d.y")."';");


	$count = 0;

	foreach ($query->result_array() as $row){

		if(($count%2)==0 && $count!=0)
			echo ("</td></tr><tr><td>");
		echo ("
			
				<div class=\"border\" name=\"{$row['game_id']}\">
				<img id=\"select_team\" src=\"{$teams[$row['team_one']]}\">
				vs.
				<img id=\"select_team\" src=\"{$teams[$row['team_two']]}\">
				</div>

		");

		$count++;
	}

?>

</td></tr></table>

</div><!--end div left-->
