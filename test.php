<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Simple JQuery Modal Window from Queness</title>

<script type="text/javascript" src="http://code.jquery.com/jquery-latest.pack.js"></script>
<link href="styles.css" rel="stylesheet" type="text/css" />
<script>

$(document).ready(function() {	

	//select all the a tag with name equal to modal
	$('a[name=modal]').click(function(e) {
		//Cancel the link behavior
		e.preventDefault();
		
		//Get the A tag
		var id = $(this).attr('href');

		alert(id);
	
		//Get the screen height and width
		var maskHeight = $(document).height();
		var maskWidth = $(window).width();
	
		//Set heigth and width to mask to fill up the whole screen
		$('#mask').css({'width':maskWidth,'height':maskHeight});
		
		//transition effect		
		$('#mask').fadeIn(1000);	
		$('#mask').fadeTo("slow",0.8);	
	
		//Get the window height and width
		var winH = $(window).height();
		var winW = $(window).width();
              
		//Set the popup window to center
		$(id).css('top',  winH/2-$(id).height()/2);
		$(id).css('left', winW/2-$(id).width()/2);
	
		//transition effect
		$(id).fadeIn(2000); 
	
	});
	
	//if close button is clicked
	$('.window .close').click(function (e) {
		//Cancel the link behavior
		e.preventDefault();
		
		$('#mask').hide();
		$('.window').hide();
	});		
	
	//if mask is clicked
	$('#mask').click(function () {
		$(this).hide();
		$('.window').hide();
	});			
	
});

</script>

</head>
<body>

<ul>

<li><a href="#teams" name="modal">Login Dialog Box</a></li>

</ul>


<div id="boxes">

<?php

include("teams.php");

	echo (" 
			<!-- Start of Login Dialog -->  
			<div id=\"teams\" class=\"window\">
			<div class=\"d-header\"><h1 id=\"logo2\">Choose a team:</h1></div>
			<div class=\"d-content\"><div class=\"team_logos\"> "
		);

	$count = 0;	

	foreach ($teams as $key => $value) {

		$count++;

		echo (" <img src=\"{$value}\" class=\"team_logo\" name=\"{$key}\">" );
		if(($count%6)==0) {
			echo ("</div><div class=\"team_logos\">" );
		}
	}


	echo ("
			</div><!-- close last block -->	</div>
			</div>
			<!-- End of Login Dialog -->  
		");


?>


<!-- Mask to cover the whole screen -->
<div id="mask"></div>

</div> <!-- End Boxes -->




</body>
</html>


