<?php

class Pages extends CI_Controller {

	public function view($page = 'home')
	{
			
		if ( ! file_exists('application/views/pages/'.$page.'.php'))
		{
			// Whoops, we don't have a page for that!
			show_404();
		}
	
		$this->load->library('session');
		$this->load->database();

		$this->load->helper('login');

		$this->load->view('templates/header');
		$this->load->view('pages/'.$page);
		$this->load->view('templates/footer');

	}

	public function game($game)
	{
			
		$data['game_id'] = intval($game);	

		$this->load->library('session');
		$this->load->database();

		$this->load->helper('login');
		
		$this->load->view('templates/header');
		$this->load->view('pages/game',$data);
		$this->load->view('templates/footer');

	}


	public function update_team()
	{
			
		include('teams.php');
		$this->load->library('session');
		$this->load->database();

		$this->db->query("UPDATE users SET team='{$_POST['team']}' WHERE user={$this->session->userdata('twitter_id')};");

		$this->session->set_userdata('team', $_POST['team']);

		$session_data = $this->session->all_userdata();

		if(!isset($_POST['team'])){
			echo "opps";
		}
		else {

			echo ("
				<h2 class=\"title\">User Info:</h2>
				<table class=\"login_table\">
					<tr>
						<td><img id=\"twitterlogo\" src=\"{$session_data['twitter_pic']}\">
						<div><h4 id=\"green\"></h4><span>@{$session_data['twitter_name']}</span></div></td>
						<td><img id=\"select_team\" src=\"{$teams[$session_data['team']]}\">
						<div><span>Team</span></div></td>
					</tr>
				</table>


			");



		} // end else

	}

}

?>
