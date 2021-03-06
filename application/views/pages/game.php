<div id="left">

<h2 class="title">Game:</h2>

<table class="game_table">

<?php
include("teams.php");

// Get current CodeIgniter instance
$CI =& get_instance();
// We need to use $CI->session instead of $this->session
$session_details = $CI->session->all_userdata();

$query = $CI->db->query("SELECT game_id,team_one,team_two FROM games WHERE game_id='{$game_id}';");
$query = $query->row();

$refs = $CI->db->query("SELECT ref_one,ref_two FROM other WHERE game_id='{$game_id}';");

if ($refs->num_rows() > 0)
{
// if we've scraped the refs, display their names. 

	$refs = $refs->row();

	echo ("
		<tr class=\"game_info\" >
			<td>		
				<div id=\"game_info\">
				<img id=\"select_team\" src=\"{$teams[$query->team_one]}\">
				vs.
				<img id=\"select_team\" src=\"{$teams[$query->team_two]}\">
				</div>
				<a>Referee: {$refs->ref_one}.</a><br />
				<a>Referee: {$refs->ref_two}.</a>
			
			</td>
		</tr>

		");

}

else {

// we haven't scraped the refs yet

	echo ("
		<tr class=\"game_info\" >
			<td>		
				<div id=\"game_info\">
				<img id=\"select_team\" src=\"{$teams[$query->team_one]}\">
				vs.
				<img id=\"select_team\" src=\"{$teams[$query->team_two]}\">
				</div>
				<a>Referee: Pending.</a><br />
				<a>Referee: Pending.</a>
			
			</td>
		</tr>

		");

}
?>

<tr class="pen_title">
	<th>Player:</th>
	<th>Infraction:</th>
	<th>Time:</th>

</tr>

<?php


$query = $CI->db->query("SELECT player,type,period,time,penalty_id FROM penalties WHERE game_id='{$game_id}';");

	$count = 0;
	$period = 1;

	foreach ($query->result_array() as $row){

		if($period!=$row['period']){

			if($period!=4){

				echo("
					<tr class=\"pen_title\">
						<th>Period: {$period}</th>
					</tr>

				");		

			} else {

				echo("
					<tr class=\"pen_title\">
						<th>Period: Overtime</th>
					</tr>

				");	

			}

		$period++;

		}
		


		echo ("
			

				<tr class=\"pen\" name=\"{$row['penalty_id']}\">
					<td>{$row['player']}</td>
					<td>{$row['type']}</td>
					<td>{$row['time']}</td>
				</tr>

		");

		$count++;
	}

?>

</table>

</div><!--end div left-->
