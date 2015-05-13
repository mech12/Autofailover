<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Index extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		//$this->load->view('welcome_message');
		//$this->load->view('main.html');
		
		echo '<!DOCTYPE html>';
		echo '<html>';
		echo 	'<head>';
		$this->load->view('main_00_head.html');
		echo 	'</head>';
		echo 	'<body class="skin-blue">';
		echo 		'<div class="wrapper">';
		$this->load->view('main_10_body_main-header.html');
		$this->load->view('main_20_body_main-sidebar.html');
		$this->load->view('main_30_body_main-content.html');
		$this->load->view('main_80_body_main-footer.html');
		$this->load->view('main_90_end_js.html');
		echo		'</div><!-- ./wrapper -->';
		echo	'</body>';
		echo '</html>';
	}

    public function oci_test()
    {
        /*
        $conn = oci_connect('scott', 'tiger', "(DESCRIPTION =
                                                                  (ADDRESS = (PROTOCOL = tcp) (HOST = 127.0.0.1) (PORT = 1521))
                                                                  (CONNECT_DATA = (SID = orcl))
                                                              )");
        */
        echo 'ok';
    }
}
