</div><!--end div content-->

<?php

//this is the code for the team select dialog boxs.

$session_data = $this->session->all_userdata();

if(isset($session_data['twitter_name']))
	if(strcmp($session_data['team'],"NONE")==0){
			
		echo ("

		<div id=\"boxes\">

		");

		include("teams.php");

		echo (" 
				<!-- Start of Login Dialog --> \n 
				<div id=\"teams\" class=\"window\">\n
				<div class=\"d-header\"><h1 id=\"logo2\">Choose a team:</h1></div>\n
				<div class=\"d-content\"><div class=\"team_logos\"> \n
			");

		$count = 0;	

		foreach ($teams as $key => $value) {

			$count++;

			echo (" <img src=\"{$value}\" class=\"team_logo\" name=\"sel_team\" id=\"{$key}\">\n" );
			if(($count%6)==0) {
				if($count ==30)
				echo ("</div>\n" );
				else
				echo ("</div>\n<div class=\"team_logos\">\n" );
			}
		}


		echo ("
				</div><!-- close last block -->	</div>
				</div>
				<!-- End of Login Dialog -->  
			
				<!-- Mask to cover the whole screen -->
				<div id=\"mask\"></div>

				</div> <!-- End Boxes -->

		");

	}

?>


</body>
</html>
