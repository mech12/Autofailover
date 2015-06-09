<?php

class Msetting extends CI_Model {

    public function __construct() {
        // Call the CI_Model constructor
        parent::__construct();
    }


    public function save_setting($args) {
        $this->load->helper('file');
        $json = json_encode($args);
        if ( ! write_file('./autofailover_save_setting.json',  $json))
        {
            return $args;
        }
        else
        {
            return $args;
        }
/*
        $db = $this->load->database("default", true);
        //$db = $this->db;
        $email = $_SERVER['SERVER_ADDR'];
        $q = $db->where('email' ,$email )->get('tbl_user');
        $tbl_user = null;;
        if( $q->num_rows() == 0)
        {
            $args['email'] = $email;
            $db->insert("tbl_user", $args);
            $q = $db->where('email' ,$email )->get('tbl_user');
            $tbl_user = $q->row();
        }
        else
        {
            $db->where('email' , $email)->update('tbl_user', $args);
        }
        $q = $db->where('email' ,$email )->get('tbl_user');

        return $q->row();
*/
    }

    public function get_save_setting()
    {
        $this->load->helper('file');
        $string = read_file('./autofailover_save_setting.json');
        if($string===false ||  strlen($string) ==0)
        {
            $ret =array(
                'type'=>'Shared',
                'server1'=>'localhost',
                'server2'=>'localhost',
                'disk1'=>'c:\\',
                'disk2'=>'d:\\',
                'app1'=>'notepad.exe',
                'app2'=>'mspaint.exe',
                'db_type1'=>'ORACLE',
                'db_type2'=>'ORACLE',
                'db1'=>'SYSTEM a localhost:1521',
                'db2'=>'SYSTEM a localhost:1521',
                'vip1'=>'google.co.kr',
                'vip2'=>'127.0.0.1',

            );
            return (Object)$ret;
        }
        return (Object)json_decode($string);

/*
        $db = $this->load->database("default", true);
        $email = $_SERVER['SERVER_ADDR'];
        $q = $db->where('email' ,$email )->get('tbl_user');
        if($q && $q->num_rows()!=0)
            return $q->row();
		
		$ret =array(
		'type'=>'Shared',
		'server1'=>'localhost',
		'server2'=>'localhost',
		'disk1'=>'c:\\',
		'disk2'=>'d:\\',
		'app1'=>'nodepad.exe',
        'app2'=>'nodepad.exe',
        'db_type1'=>'ORACLE',
        'db_type2'=>'ORACLE',
        'db1'=>'SYSTEM a localhost:1521',
		'db2'=>'SYSTEM a localhost:1521',
		'vip1'=>'google.co.kr',
		'vip2'=>'127.0.0.1',
		
		);
        return (Object)$ret;
*/
    }

}
