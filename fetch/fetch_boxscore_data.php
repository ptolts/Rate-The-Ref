<?php
// example of how to use basic selector to retrieve HTML contents
include('simple_html_dom.php');
include("connect.php");
include("nhl_tsn.php");

$game_id = $argv[1];
$query = "SELECT * FROM games WHERE game_id=".$game_id;
$result = mysql_query($query);
$row = mysql_fetch_array($result);

$useragent = "Mozilla/5.0 (iPhone; CPU iPhone OS 5_0_1 like Mac OS X) AppleWebKit/534.46 (KHTML, like Gecko) Version/5.1 Mobile/9A405";
$curl = curl_init(); 
curl_setopt($curl, CURLOPT_USERAGENT, $useragent);
curl_setopt($curl, CURLOPT_URL, "http://www.nhl.com/ice/m_boxscore.htm?id=".$row['link']);  
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);  
curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);  
curl_setopt($curl, CURLOPT_HTTPPROXYTUNNEL, 1);
curl_setopt($curl, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
curl_setopt($curl, CURLOPT_PROXY, '127.0.0.1:9050'); 
$str = curl_exec($curl);  
curl_close($curl);  

$html = str_get_html(utf8_decode($str)); 

//echo "<meta http-equiv=\"content-type\" content=\"text/html;charset=UTF-8\" />";

$period = 0;
$p_counter = 0;
$g_counter = 0;
$penalty = 0;
$scores = 0;
$skip = 0;

$penalty_section = 0;
$goal_section = 0;
$ref_section = 0;

// this is for checking if the games done.
$check_done = $html->find('div.gcScoreboard',0);

foreach($html->find('table, li') as $e) {

		if($penalty_section==1){

			$penalty_section = 0;

			foreach($e->find('tr') as $g) {

				if(strpos($g,"Period")){
					$period++;
				}

				if(strpos($g,"Shootout")){
					$period++;
				}

				//echo "<br>\n". $period . " Period:\n<br>";
				$pens = explode("/ice/m_teamdashboard.htm",$g->innertext);
				//print_r($goals);
				foreach($pens as $pen){

					if(preg_match("/[0-9][0-9]:[0-9][0-9]/",$pen)){

						//echo $pen;
	
						$p_counter++;

						$pa = array();

						$pa['period']=$period;

						//echo "Penalty: ";

						// here we setup the pa array.

						preg_match("/[0-9][0-9]:[0-9][0-9]/",$pen,$matches);

						$pa['time'] = $matches[0];

						$pa['player'] = str_get_html($pen)->find('a',0)->plaintext;

						preg_match("/(?<=team=)[A-Z]*/",$pen,$matches);

						$pa['team'] = $matches[0];
						$pa['team'] = $nhl_teams[$pa['team']];
			

						preg_match("/(?<=\().*?min(?=\))/i",$pen,$matches);

						$pa['length'] = preg_replace ("/[()]/", "",$matches[0]);

						preg_match("/(?<=--\> - ).*?(?= \()/",$pen,$matches);

						$pa['offense'] = $matches[0];

						//echo "Player: " . $pa['player'] . "\t";

						//echo "Time: " . $pa['time'] . "\t";

						//echo "Length: " . $pa['length'] . "\t";

						//echo "Offense: " . $pa['offense'] . "\t";

						//echo "Team: " . $pa['team'] . "<br>\n";

						//echo "Game id:" . $game_id;

						array_trimmer($pa);
						//print_r($pa);

						insert_penalty($p_counter,$pa,$game_id);

					}
	
				}

			}

		}

		if($ref_section==1){

			$ref_section = 0;

			$g = array();

			$g['ref_one'] = "Pending...";
			$g['ref_two'] = "Pending...";
			$g['line_one'] = "Pending...";
			$g['line_two'] = "Pending...";
			$g['attendance'] = 0;
			$g['first'] = "";
			$g['second'] = "";
			$g['third'] = "";

			foreach($e->find('tr') as $o) {

				if(strpos($o,"Referees")){

					preg_match("/(?<=Referees:).*/i",$o->plaintext,$match);
					$refs = $match[0];
					$refs = explode(",",$refs);
					$g['ref_one'] = trimmer($refs[0]);
					$g['ref_two'] = trimmer($refs[1]);

					//echo "\nRef:\n" . $g['ref_one'] . "\n" . $g['ref_two'] ;
				}

				if(strpos($o,"Linesmen")){

					preg_match("/(?<=Linesmen:).*/i",$o->plaintext,$match);
					$refs = $match[0];
					$refs = explode(",",$refs);
					$g['line_one'] = trimmer($refs[0]);
					$g['line_two'] = trimmer($refs[1]);

					//echo "\nLines:\n" . $g['line_one'] . "\n" . $g['line_two'] ;
				}

				if(strpos($o,"Attendance")){

					preg_match("/(?<=Attendance:).*/i",$o->plaintext,$match);
					$refs = trimmer($match[0]);
					$refs = str_replace(",","",$refs);
					$g['attendance'] = intval($refs);

					//echo "\nAttendeance:\n" . $g['attendance'] ;
				}

				if(strpos($o,"1st")){

					$star = str_get_html($o)->find("a",0);

					$g['first'] = trimmer($star->plaintext);

					//echo "\n1st:\n" . $g['first'] . "\n" ;
				}

				if(strpos($o,"2nd")){

					$star = str_get_html($o)->find("a",0);

					$g['second'] = trimmer($star->plaintext);

					//echo "\n2nd:\n" . $g['second'] . "\n" ;
				}

				if(strpos($o,"3rd")){

					$star = str_get_html($o)->find("a",0);

					$g['third'] = trimmer($star->plaintext);

					//echo "\n3rd:\n" . $g['third'] . "\n" ;
				}

			}

			insert_other($g,$game_id);			

		}

		if($goal_section==1){

			$goal_section = 0;

			foreach($e->find('tr') as $g) {

				if(strpos($g,"Period")){
					$period++;
				}

				if(strpos($g,"Shootout")){
					$period++;
				}

				//echo "<br>\n". $period . " Period:\n<br>";
				$goals = explode("/ice/m_teamdashboard.htm",$g->innertext);
				//print_r($goals);
				foreach($goals as $goal){

					if(preg_match("/[0-9][0-9]:[0-9][0-9]/",$goal)){
	
						$g_counter++;

						$pa = array();

						$pa['period']=$period;


						//echo "Score: <br>\n";

						// here we setup the pa array.

						preg_match("/[0-9][0-9]:[0-9][0-9]/",$goal,$matches);

						$pa['time'] = $matches[0];

						//echo "Time: " . $pa['time'] . "<br>";

						$counter = 0;

						$pa['goal'] = "NONE";
						$pa['assist'] = "NONE";
						$pa['assist2'] = "NONE";

						foreach(str_get_html($goal)->find('a') as $pts) {
		
							$counter++;
								
							if($counter == 1){

			
								$pa['goal'] = $pts->plaintext;
								//echo "Scored by: " . $pa['goal'] . "<br>";

							}

							if($counter == 2){

								$pa['assist'] = $pts->plaintext;
								//echo "Primary Assist by: " . $pa['assist'] . "<br>";

							}

							if($counter == 3){

								$pa['assist2'] = $pts->plaintext;
								//echo "Secondary Assist by: " . $pa['assist2'] . "<br>";

							}

						}
		
						$counter = 0;


						preg_match("/(?<=team=)[A-Z]*/",$goal,$matches);
						$pa['team'] = $matches[0];
						$pa['team'] = $nhl_teams[$pa['team']];

						//echo "Team: " . $pa['team'] . "<br>\n";  

						array_trimmer($pa);
						//print_r($pa);

						insert_goal($g_counter,$pa,$game_id);

					}
	
				}
			}

			$period = 0;


		}


		if(strpos($e,"SCORING SUMMARY")){

			$goal_section = 1;

		}

		if(strpos($e,"PENALTY SUMMARY")){

			$penalty_section = 1;

		}

		if(strpos($e,"OTHER")){

			$ref_section = 1;

		}



}

// check if the game is done, if it is update database so we dont keep updating.
if(strpos($check_done,"FINAL")){

	$query = "UPDATE games SET done='TRUE' WHERE game_id=".$game_id.";";
	//mysql_query( $query );
	echo "\nGame ".$game_id." is done.\n";
}

mysql_close();

//end of "main"


function insert_penalty($p_count,$pa,$game_id){

		// check if penalty exists
		$query = "SELECT penalty_id FROM penalties WHERE game_id=" . $game_id . " AND p_count = ".$p_count.";";
		//echo "Check if it exists: " . $query . "<br>";
		$result = mysql_query($query);

		if(mysql_num_rows($result)==0){
			//penalty already exists, so we'll UPDATE the entry.
			$query = "INSERT INTO penalties (player, type, team, game_id, period, time, p_count, length) VALUES ('".$pa['player']."','".$pa['offense']."','".$pa['team']."',".$game_id.",".$pa['period'].",'".$pa['time']."',".$p_count.",'".$pa['length']."');";
			mysql_query( $query );
			echo $query." inserted.";
			//echo "Penalty successfully inserted.<br>";
		}	

		else{

			$query = "UPDATE penalties SET length='".$pa['length']."',player='".$pa['player']."',type='".$pa['offense']."',team='".$pa['team']."',game_id=".$game_id.",period=".$pa['period'].",time='".$pa['time']."',p_count=".$p_count." WHERE p_count=".$p_count." AND game_id=".$game_id.";";
			mysql_query( $query );
			echo $query." updated.";
			//echo "Penalty successfully updated.<br>";
		}

}

function insert_goal($g_count,$pa,$game_id){

		// check if goal exists
		$query = "SELECT goal_id FROM goals WHERE game_id=" . $game_id . " AND g_count = ".$g_count.";";
		//echo "Check if it exists: " . $query . "<br>";
		$result = mysql_query($query);

		if(mysql_num_rows($result)==0){

			$query = "INSERT INTO goals (goal, assist, assist2, team, game_id, period, time, g_count) VALUES ('".$pa['goal']."','".$pa['assist']."','".$pa['assist2']."','".$pa['team']."',".$game_id.",".$pa['period'].",'".$pa['time']."',".$g_count.");";
			mysql_query( $query );
			//echo $query." inserted.";
			//echo "Penalty successfully inserted.<br>";
		}	

		else{

			$query = "UPDATE goals SET assist2='".$pa['assist2']."',goal='".$pa['goal']."',assist='".$pa['assist']."',team='".$pa['team']."',game_id=".$game_id.",period=".$pa['period'].",time='".$pa['time']."',g_count=".$g_count." WHERE g_count=".$g_count." AND game_id=".$game_id.";";
			mysql_query( $query );
			//echo $query." updated.";
			//echo "Penalty successfully updated.<br>";
		}

}

function insert_other($g,$game_id){

		// check if goal exists
		$query = "SELECT game_id FROM other WHERE game_id=" . $game_id . ";";
		//echo "Check if it exists: " . $query . "<br>";
		$result = mysql_query($query);

		if(mysql_num_rows($result)==0){
			//goal doesnt exist so insert
			$query = "INSERT INTO other (ref_one, ref_two, line_one, line_two, game_id, attendance, first, second, third) VALUES (\"{$g['ref_one']}\",\"{$g['ref_two']}\",\"{$g['line_one']}\",\"{$g['line_two']}\",\"{$game_id}\",{$g['attendance']},\"{$g['first']}\",\"{$g['second']}\",\"{$g['third']}\");";
			mysql_query( $query );
			//echo $query." inserted.";
			//echo "Penalty successfully inserted.<br>";
		}	

		else{
			// update cuz its already there
			$query = "UPDATE other SET ref_one=\"{$g['ref_one']}\",ref_two=\"{$g['ref_two']}\",line_one=\"{$g['line_one']}\",line_two=\"{$g['line_two']}\",attendance=\"{$g['attendance']}\",first=\"{$g['first']}\",second=\"{$g['second']}\",third=\"{$g['third']}\" WHERE game_id={$game_id};";
			mysql_query( $query );
			//echo $query." updated.";
			//echo "Penalty successfully updated.<br>";
		}

}


?>

