<?php
// example of how to use basic selector to retrieve HTML contents
include('simple_html_dom.php');
include("connect.php");
include("nhl_tsn.php");

$useragent = "Mozilla/5.0 (iPhone; CPU iPhone OS 5_0_1 like Mac OS X) AppleWebKit/534.46 (KHTML, like Gecko) Version/5.1 Mobile/9A405";
$curl = curl_init(); 
curl_setopt($curl, CURLOPT_USERAGENT, $useragent);
curl_setopt($curl, CURLOPT_URL, 'http://www.nhl.com/ice/m_scores.htm');  
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);  
curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);  
curl_setopt($curl, CURLOPT_HTTPPROXYTUNNEL, 1);
curl_setopt($curl, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
curl_setopt($curl, CURLOPT_PROXY, '127.0.0.1:9050'); 
$str = curl_exec($curl);  
curl_close($curl);  

$html = str_get_html(utf8_decode($str)); 

//echo "<meta http-equiv=\"content-type\" content=\"text/html;charset=UTF-8\" />";

$game_counter = 0;

if(strpos($html,date("D, M j"))){

	$query = "INSERT IGNORE INTO dates (date) VALUES ('".date("m.d.y")."');";
	mysql_query( $query );

}

else {

	mysql_close();	
	exit();

}

//this grabs the two boxscores within the scores page.
foreach($html->find('table.gmDisplay') as $e) {

		
		$game_counter++;	
		//echo "Game: " . $game_counter . "<br>\n";

		// find each link within each score square.

		// team_count tracks team counter.
		$team_count = 0;
		$team_one = "";
		$team_two = "";
		$game_time = "";

		foreach($e->find('a') as $l) {

			////echo $element . "<br>\n";

			if(strpos($l->href,"team")){
				$team_count++;


				if($team_count == 1){
					$tmp = trimmer($l -> plaintext);
					$team_one = $nhl_tsn["$tmp"];
					//echo "Team: \"" . $tmp . "\"<br>\n";
				}
				else{
					$tmp = trimmer($l -> plaintext);
					$team_two = $nhl_tsn["$tmp"];
					//echo "Team: \"" . $tmp . "\"<br>\n";
				}
			}

			if(strpos($l->href,"preview")){

				preg_match("/(?<=id=)[0-9]*/",$l->href,$matches);
				$link = trimmer($matches[0]);
				//echo "Link: " . $link . "<br>\n";


			}

			if(strpos($l,"AM") || strpos($l,"PM")){

				$time = utf8_decode($l -> plaintext);
				$time = preg_replace("/\sEST.*/", "", $time);

				//echo "Time: " . $time . "<br>\n";
				$game_time = trimmer($time);

			}




		}



		$query = "SELECT game_id FROM games WHERE (team_one='" . $team_one . "' OR team_one = '" . $team_two . "') AND date='".date("m.d.y")."';";
		$result = mysql_query($query);

		// we check to see if this game exists yet.
		// if we've setup the scripts right, it shouldnt
		// but probably better safe and sorry.
		if(mysql_num_rows($result)>0){

			if(isset($link)){
				$query = "UPDATE games SET link=\"{$link}\" WHERE (team_one='" . $team_one . "' OR team_one = '" . $team_two . "') AND date='".date("m.d.y")."';";
				//echo "This game has already been inserted.<br>";
				mysql_query($query);
			}

		}

		// Entry doesn't yet exist, insert.
		else {

			$query = "INSERT INTO games (team_one,team_two,game_time,date) VALUES ('". $team_one. "','" . $team_two . "','" . $game_time . "','".date("m.d.y")."');";
			mysql_query( $query );
			//echo "Game successfully inserted.<br>";

		}

} 


mysql_close();

?>



