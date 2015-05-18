<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	class Welcome extends CI_Controller {
		
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
            $this->load->model("Msetting");

            $ret = array();
            try {

                $id_disk1 = $this->input->post_get('disk1');
                $id_disk2 = $this->input->post_get('disk2');
                if (isset($id_disk1) && isset($id_disk2)) {
                    $this->save_setting(jARG());
                    return;
                }

                $cmd = $this->input->post_get('cmd');
                if (isset($cmd)) {
                    $this->$cmd(jARG());
                    return;
                }


                $tbl_user = $this->Msetting->get_save_setting();
                if ($tbl_user== null)
                {
                    $this->Shared(jARG());
                    return;
                }
                $type = $tbl_user->type;
                if($type=='Shared')
                    $this->Shared($tbl_user);
                else
                    $this->Mirroed($tbl_user);

            }
            catch(Exception $e)
            {
                $ret['error'] = $e->getMessage();
                JSON_OUTPUT($ret);
            }

        }

        public function save_setting($args)
        {
            $ret = $this->Msetting->save_setting($args);
            JSON_OUTPUT(array('result' => $ret   ));

/*
            $id_app= "";
            $id_db= "";
            $id_disk1= "";
            $id_disk2= "";
            $id_server1= "";
            $id_server2= "";
            $id_vip1= "192.168.1.1";
            $id_vip2= "192.168.1.2";
*/
        
        }

		public function Mirroed($args)
        {

            echo '<!DOCTYPE html>';
            echo '<html>';
            echo 	'<head>';
            $this->load->view('main_00_head.html');
            echo 	'</head>';
            echo 	'<body class="skin-blue">';
            echo 		'<div class="wrapper">';
            $this->load->view('main_10_body_main-header.html');
            $this->load->view('main_20_body_main-sidebar.html',$args);
            $this->load->view('main_30_body_main-content.html',$args);
            $this->load->view('main_80_body_main-footer.html');
            $this->load->view('main_90_end_js.html');
            echo		'</div><!-- ./wrapper -->';
            echo	'</body>';
            echo '</html>';
        }
		public function Shared($args)
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
			$this->load->view('main_20_body_main-sidebar.html' ,$args);
			$this->load->view('main_30_body_main-content.html',$args);
			$this->load->view('main_80_body_main-footer.html');
			$this->load->view('main_90_end_js.html');
			echo		'</div><!-- ./wrapper -->';
			echo	'</body>';
			echo '</html>';		
		}
		
		public function test_db_check()
		{
            $ret = $this->_db_check_oracle('SYSTEM a localhost:1521');
            JSON_OUTPUT($ret);
		}

        function _db_check_oracle($db_info)
        {
            error_reporting(E_ALL);
            try
            {
                $ret = shell_exec("db\\oracle\\check_server.bat " . $db_info);
                //$ret = shell_exec("chdir");
                if( strpos($ret , 'error') !== false
                    || strpos($ret , 'Error') !== false
                    || strpos($ret , 'ERROR') !== false
                )
                {
                    return (array('error'=> $ret) );
                }
                return (array('result'=> $ret) );
            }
            catch (Exception $e)
            {
                return (array('error'=> $e->getMessage()) );
            }
        }

        public function check_status()
        {
            $ret = array();
            $arg = jARG();
            $ret['arg'] = $arg;

            if($arg['db_type']=='ORACLE')
            {
                $db = $arg['db'];
                if(isset($db) && strlen($db)>0)
                {

                    //$ret['db'] = $this->_db_check_oracle('SYSTEM a localhost:1521');
                    $ret['db'] = $this->_db_check_oracle($db);
                    if($db !== 'SYSTEM a localhost:1521')
                    {
                        $ret['asdf'] =$db;
                    }
                }
                else
                    $ret['db'] = array('error' => 'need setup');
            }


            //{"type":"Mirroed","server1":"localhost","server2":"google.co.kr","vip1":"192.168.0.10","vip2":"192.168.0.11","db_type":"ORACLE","db":"SYSTEM a localhost:152","app":"notepad++.exe","disk1":"c:\\","disk2":"d:\\"
            JSON_OUTPUT($ret);




        }
	}
