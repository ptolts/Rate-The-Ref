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

	$('img[name=sel_team]').click(function(e) {

		var team = $(this).attr('id');

		var answer = confirm("You can't undo this, are you sure "+ team + " is your team?");
		if (answer){
			// Ajax request sent to the CodeIgniter controller "ajax" method "username_taken"
			// post the username field's value
			$.post('index.php/pages/update_team',
			{ 'team':team },

			// when the Web server responds to the request
			function(result) {

				// if the result is TRUE write a message to the page
				if (result) {
					$('#login').html(result);
				}

			});

			$('#mask').hide();
			$('.window').hide();
		}
		else {

			return;

		}

	});

	$('#login').corner("keep");

	$('#left').corner("keep");

	$('.list_games').corner("keep");

	$('.list_border').corner("keep");

	$('#logo').corner("keep");

	$('.twitter').corner("keep");

	$('.team').corner("keep");

	$('.signin').corner("keep");

	$('.team').corner("keep");

	$('#team_logo').corner("keep");

	$('#twitterlogo').corner("keep");

	$('#select_team').corner("keep");

	$('#game_info').corner("keep");
	$('.game_info').corner("keep");

	$('.team_logo').corner("keep");

	$('#twit').corner("keep");

	$('#title').corner("keep");

	$('h2').corner("keep");

	$('.title').corner("keep");

	$('.border').corner("keep");

	$('.pen').corner("keep");

	$('.intro').corner("keep bottom");

	$('.sig_title').corner("keep top");

	$('.pen_title').corner("keep");

	$('.border').click(function(){
		window.location="/index.php/pages/game/"+$(this).attr("name"); 
		return false;
	});

	$('.border').hover(over, out);
		function over(event) {
		$(this).css("cursor", "pointer");
	}

		function out(event) {
		$(this).css("cursor", "default");
	}

	$('.list_border').click(function(){
		window.location="/index.php/pages/game/"+$(this).attr("name"); 
		return false;
	});

	$('.list_border').hover(over, out);
		function over(event) {
		$(this).css("cursor", "pointer");
	}

		function out(event) {
		$(this).css("cursor", "default");
	}

});

