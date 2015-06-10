<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	class Welcome extends CI_Controller {


        function __construct() {
            parent::__construct();
            header('Access-Control-Allow-Origin:*');
            $this->load->model("Msetting");

        }

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
                //print_r($tbl_user); return;
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
        }

		public function Mirroed($tbl_user)
        {

            echo '<!DOCTYPE html>';
            echo '<html>';
            echo 	'<head>';
            $this->load->view('main_00_head.html');
            echo 	'</head>';
            echo 	'<body class="skin-blue">';
            echo 		'<div class="wrapper">';
            $this->load->view('main_10_body_main-header.html');
            $this->load->view('main_20_body_main-sidebar.html',$tbl_user);
            $this->load->view('main_30_body_main-content.html',$tbl_user);
            $this->load->view('main_80_body_main-footer.html');
            $this->load->view('main_90_end_js.html');
            echo		'</div><!-- ./wrapper -->';
            echo	'</body>';
            echo '</html>';
        }
		public function Shared($tbl_user)
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
			$this->load->view('main_20_body_main-sidebar.html' ,$tbl_user);
			$this->load->view('main_30_body_main-content.html',$tbl_user);
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
            //$arg = jARG();
            $arg = (array)$this->Msetting->get_save_setting();
            $ret['arg'] = $arg;

            if($arg['db_type1']=='ORACLE')
            {
                $db = $arg['db1'];
                if(isset($db) && strlen($db)>0)
                {

                    //$ret['db'] = $this->_db_check_oracle('SYSTEM a localhost:1521');
                    $ret['db1'] =  array('result'=>'ok');//$this->_db_check_oracle($db);
                }
                else
                    $ret['db1'] = array('error' => 'need setup');
            }
            if($arg['db_type2']=='ORACLE')
            {
                $db = $arg['db2'];
                if(isset($db) && strlen($db)>0)
                {

                    //$ret['db'] = $this->_db_check_oracle('SYSTEM a localhost:1521');
                    $ret['db2'] = array('result'=>'ok');//$this->_db_check_oracle($db);
                }
                else
                    $ret['db2'] = array('error' => 'need setup');
            }

            $ret['disk1'] = _check_disk($arg['disk1']);
            $ret['disk2'] = _check_disk($arg['disk2']);

            $ret['app1'] = _check_process($arg['app1']);
            $ret['app2'] = _check_process($arg['app2']);

			$ret['server1'] = _check_ping( $arg['server1'] );
			$ret['server2'] = _check_ping( $arg['server2'] );
			$ret['vip1'] = _check_ping( $arg['vip1'] );
			$ret['vip2'] = _check_ping( $arg['vip2'] );

            //{"type":"Mirroed","server1":"localhost","server2":"google.co.kr","vip1":"192.168.0.10","vip2":"192.168.0.11","db_type":"ORACLE","db":"SYSTEM a localhost:152","app":"notepad++.exe","disk1":"c:\\","disk2":"d:\\"
            JSON_OUTPUT($ret);

        }
	}
