<?php

$nhl_teams = array();

$nhl_teams['COL']="Colorado";
$nhl_teams['CHI']="Chicago";
$nhl_teams['STL']="St. Louis";
$nhl_teams['BOS']="Boston";
$nhl_teams['BUF']="Buffalo";
$nhl_teams['CAL']="Calgary";
$nhl_teams['WSH']="Washington";
$nhl_teams['CBJ']="Columbus";
$nhl_teams['PHO']="Phoenix";
$nhl_teams['DET']="Detroit";
$nhl_teams['ANA']="Anaheim";
$nhl_teams['MON']="Montreal";
$nhl_teams['CAR']="Carolina";
$nhl_teams['WPG']="Winnipeg";
$nhl_teams['LAK']="Los Angeles";
$nhl_teams['TOR']="Toronto";
$nhl_teams['NJD']="New Jersey";
$nhl_teams['VAN']="Vancouver";
$nhl_teams['NYI']="NY Islanders";
$nhl_teams['EDM']="Edmonton";
$nhl_teams['FLO']="Florida";
$nhl_teams['PIT']="Pittsburgh";
$nhl_teams['PHL']="Philadelphia";
$nhl_teams['NAS']="Nashville";
$nhl_teams['NYR']="NY Rangers";
$nhl_teams['OTT']="Ottawa";
$nhl_teams['SJS']="San Jose";
$nhl_teams['DAL']="Dallas";
$nhl_teams['TBL']="Tampa Bay";
$nhl_teams['MIN']="Minnesota";

$nhl_tsn = array();

$nhl_tsn['Avalanche']="Colorado";
$nhl_tsn['Blackhawks']="Chicago";
$nhl_tsn['Blues']="St. Louis";
$nhl_tsn['Bruins']="Boston";
$nhl_tsn['Sabres']="Buffalo";
$nhl_tsn['Flames']="Calgary";
$nhl_tsn['Capitals']="Washington";
$nhl_tsn['Blue Jackets']="Columbus";
$nhl_tsn['Coyotes']="Phoenix";
$nhl_tsn['Red Wings']="Detroit";
$nhl_tsn['Ducks']="Anaheim";
$nhl_tsn['Canadiens']="Montreal";
$nhl_tsn['Hurricanes']="Carolina";
$nhl_tsn['Jets']="Winnipeg";
$nhl_tsn['Kings']="Los Angeles";
$nhl_tsn['Maple Leafs']="Toronto";
$nhl_tsn['Devils']="New Jersey";
$nhl_tsn['Canucks']="Vancouver";
$nhl_tsn['Islanders']="NY Islanders";
$nhl_tsn['Oilers']="Edmonton";
$nhl_tsn['Panthers']="Florida";
$nhl_tsn['Penguins']="Pittsburgh";
$nhl_tsn['Flyers']="Philadelphia";
$nhl_tsn['Predators']="Nashville";
$nhl_tsn['Rangers']="NY Rangers";
$nhl_tsn['Senators']="Ottawa";
$nhl_tsn['Sharks']="San Jose";
$nhl_tsn['Stars']="Dallas";
$nhl_tsn['Lightning']="Tampa Bay";
$nhl_tsn['Wild']="Minnesota";

function trimmer($trim){

	$trim = preg_replace ("/^\s*/", "",$trim);
	$trim = preg_replace ("/\s*$/", "",$trim);
	return $trim;

}

function array_trimmer($trim){

	foreach($trim as $k => $v){
		$v = preg_replace ("/^\s*/", "",$v);
		$trim[$k] = preg_replace ("/\s*$/", "",$v);
	}

	return $trim;

}

?>
