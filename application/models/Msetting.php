<?php

class Msetting extends CI_Model {

    public function __construct() {
        // Call the CI_Model constructor
        parent::__construct();
    }


    public function save_setting($args) {

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
    }

    public function get_save_setting()
    {
        $db = $this->load->database("default", true);
        $email = $_SERVER['SERVER_ADDR'];
        $q = $db->where('email' ,$email )->get('tbl_user');
        if($q && $q->num_rows()!=0)
            return $q->row();
        return null;
    }

}
