<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>Rate The Ref</title>
<meta name="keywords" content="" />
<meta name="description" content="" />
<link href="styles.css" rel="stylesheet" type="text/css" />
<link rel="shortcut icon" href="images/favicon.ico" mce_href="images/favicon.ico"/>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
<script src="jquery.corner.js" type="text/javascript"></script>
</head>
<body>

<script type="text/javascript">

    $(function() {
        var d=300;
        $('#navigation a').each(function(){
            $(this).stop().animate({
                'marginTop':'-80px'
            },d+=150);
        });

        $('#navigation > li').hover(
        function () {
            $('a',$(this)).stop().animate({
                'marginTop':'-2px'
            },200);
        },
        function () {
            $('a',$(this)).stop().animate({
                'marginTop':'-80px'
            },200);
        }
   	);

	$('#login').corner("keep");

	$('#left').corner("keep");

	$('#title').corner("keep");

	$('#logo').corner("keep");

	$('.twitter').corner("keep");

	$('.team').corner("keep");

	$('#teamlogo').corner("keep");

	$('#twitterlogo').corner("keep");

	$('.signin').corner("keep");


    });

</script>

<div id="top">


	<h1 id="logo" style="border-top-left-radius: 10px; border-top-right-radius: 10px; border-bottom-left-radius: 10px; border-bottom-right-radius: 10px; ">RateTheRef</h1>

	<ul id="navigation">
		<li class="home"><a href="" style="margin-top: -80px; "><span>Home</span></a></li>
		<li class="games"><a href="" style="margin-top: -80px; "><span>Games</span></a></li>
		<li class="about"><a href="" style="margin-top: -80px; "><span>About</span></a></li>
	</ul>


</div><!--end div top-->

<div id="content">


<div id="login" style="border-top-left-radius: 10px; border-top-right-radius: 10px; border-bottom-left-radius: 10px; border-bottom-right-radius: 10px; ">


		<div class="user">

			<img id="twitterlogo" src="images/cig_normal.jpg">
			<span><h4 id="green"></h4>@ptolts</span>

		</div>

		<div class="team">		

			<img id="teamlogo" src="images/teamlogo/leafs.gif">
			<p></p><span>Team</span>
		</div>


</div><!--end div login-->


<div id="left" style="border-top-left-radius: 10px; border-top-right-radius: 10px; border-bottom-left-radius: 10px; border-bottom-right-radius: 10px; ">

<h2 id="title">Todays Games:</h2>

<table>

<?php

// Get current CodeIgniter instance
$CI =& get_instance();
// We need to use $CI->session instead of $this->session
$session_details = $CI->session->all_userdata();

$query = $CI->db->query("SELECT team_one,team_two,game_time,game_id FROM games WHERE data='{$date("m.d.y")}");

	$count = 0;

	foreach ($query->result_array() as $row)
	{

		echo ("
			<tr>
				<td><img id=\"select_team\" src=\"{$teams[$row['team_one']]}\"></td>
				<td>vs.</td>
				<td><img id=\"select_team\" src=\"{$teams[$row['team_one']]}\"></td>
			</tr>
		");
	}

?>

</table>

</div><!--end div left-->

</div><!--end div content-->




</body></html>
