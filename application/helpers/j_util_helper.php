<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

define('jMAX_CHARACTER', 3); // 최대 생성가능 캐릭


function jRequest_Init($file,$func)
{
    $mode = $_SERVER['TALKLISH_SERVICE_MODE'];
    $name = $_SERVER['TALKLISH_SERVER_NAME'];
    if(is_null($mode))
    {
        $mode = 'TALKLISH_SERVICE_MODE is not setup';
    }

    //$count = count(get_object_vars((object)$arg));
    jL( json_encode(jARG()) ,'T_RQ');
    return array( 'env'=>$_ENV , 'server'=> $mode.':'.$name, 'eCmd' => basename($file, '.php')  .':'. $func);
}

function rank_to_int($rank)
{
    if($rank=='S') return 1;
    if($rank=='A') return 2;
    if($rank=='B') return 3;
    return 4;
}

// 챌린지 등급에 대한 번호로 스트링 구분자를 리턴.
function rank_to_string($rank)
{
    jCHECK($rank > 0 && $rank <=4 , ' error $rank > 0 && $rank <=4 : rank = ' . $rank);

    if($rank==1) return 'S';
    if($rank==2) return 'A';
    if($rank==3) return 'B';
    return 'C';
}

function j_find_csv_by_func($csv ,$arg1 , $func )
{
    foreach($csv as $row)
    {
        if( $func($row,$arg1) == true) return $row;
    }
    return null;
}

function j_content_load($name)
{
    get_instance()->load->helper("ContentDB");
    $csv = content_load($name);
    jCHECK( ! is_null($csv) , 'ContentsDB is not found : ' . $name);
    return $csv;
}

// j_content_load 와 j_find_csv_by_func를 합친 함수.
function j_content_find($name , $arg1 , $func)
{
    get_instance()->load->helper("ContentDB");
    $csv = content_load($name);
    jCHECK( ! is_null($csv) , 'ContentsDB is not found : ' . $name);

    foreach($csv as $row)
    {
        if( $func($row,$arg1) == true )
            return $row;
    }
    return null;
}
// j_content_load 와 j_find_csv_by_func를 합친 함수.
function j_content_find2($name ,  $func)
{
    get_instance()->load->helper("ContentDB");
    $csv = content_load($name);
    jCHECK( ! is_null($csv) , 'ContentsDB is not found : ' . $name);

    foreach($csv as $row)
    {
        if( $func($row) == true )
            return $row;
    }
    return null;
}


function jSESSION_CHECK($session_data_name)
{
    $data = SESSION($session_data_name);
    //jCHECK(is_null($data)==false, $session_data_name . ' => session not found.  try [Cuser/login]');
    return $data;
}



function _find_by_cid($tbl_character_list, $cid)
{
    foreach($tbl_character_list as $tbl)
    {
        if($tbl->cid != $cid) continue;
        return $tbl;
    }
}

function _order_to_cid($tbl_character_list , $order)
{
    jCHECK( ! is_null($tbl_character_list) , __FUNCTION__ . ' $tbl_character_list is null');
    if($order < 0)
        $order = $order * -1 - 1;

    $max = count($tbl_character_list);
    jCHECK($max > $order ,'tbl_character_list.length error : max =' . $max . ' order = '. $order);
    $tbl = $tbl_character_list [ $order ];
    //jL($tbl);
    return $tbl->cid;
}


function jUnset($arr , $what_list)
{
    foreach($what_list as $key ) {
        unset($arr[$key]);
    }
}

function jCHECK($ret , $err)
{
    if($ret == null || $ret == false) throw new Exception($err);
}


function jARG()
{
    $ci =& get_instance();
    $arg = $ci->input->post();
    if($arg==null)$arg = $ci->input->get();
    return $arg;
}

function jARG_b($name )
{
    $ci =& get_instance();
    $var = $ci->input->post_get($name);
    if(isset($var) ) return $var;
    return false;
}


function jARG_i($arg ,$min = null ,$max = null)
{
    $ci =& get_instance();
    $var = $ci->input->post_get($arg);
    jCHECK(is_null($var)==false, $arg . " is null jARG_i error ");
    jCHECK(is_numeric($var) ,$arg .  ' is not number jARG_i error');

    if( is_null($min)==false && $var < $min )
    {
        throw new Exception($arg . ' jARG_i check error : min value >=' . $min );
    }
    if(is_null($max)==false && $var > $max )
    {
        throw new Exception($arg . ' jARG_i check error : max value <=' . $max );
    }
    return $var;
}

function jARG_s($arg ,$min=1 ,$max = 32)
{
    $ci =& get_instance();
    $var = $ci->input->post_get($arg);
    if(is_null($var))
    {
        throw new Exception($arg . " is null jARG_s error ");
    }
    $len = strlen($var);
    if( $len < $min || $len > $max) {
        throw new Exception($arg . " jARG_s length error : min =" . $min . " max=" . $max . " len = " . $len);
    }
    return $var;
}

function jARG_default($arg ,$defalut)
{
    $ci =& get_instance();
    $var = $ci->input->post_get($arg);
    if(is_null($var))
    {
        return $defalut;
    }
    return $var;
}
/*

function jW($value)
{
    $ft = "";
    $debugTrace = debug_backtrace();
    if(isset($debugTrace[1])) {
        $fname = ( isset($debugTrace[0]['file'] ) ) ? str_replace(APPPATH, '', $debugTrace[0]['file']) : '';
        $line = ( isset($debugTrace[0]['line'] ) ) ? ':' . $debugTrace[0]['line'] : '';
        $func = ( isset($debugTrace[1]['function'] ) ) ? '::' . $debugTrace[1]['function']:'';
        $ft .= '[' . $fname . $line . $func . ']';
    }
    if($ft) {
        $ft = chr(27)."[32m".$ft.chr(27)."[0m\n";
    }

    log_message("T_WARN", $ft.print_r($value, true));
}
*/

function jE($value)
{
    $ft = "";
    $debugTrace = debug_backtrace();
    if(isset($debugTrace[1])) {
        $fname = ( isset($debugTrace[0]['file'] ) ) ? str_replace(APPPATH, '', $debugTrace[0]['file']) : '';
        $line = ( isset($debugTrace[0]['line'] ) ) ? ':' . $debugTrace[0]['line'] : '';
        $func = ( isset($debugTrace[1]['function'] ) ) ? '::' . $debugTrace[1]['function']:'';
        $ft .= '[' . $fname . $line . $func . ']';
    }
    //if($ft) {$ft = chr(27)."[31m".$ft.chr(27)."[0m\n";}

    log_message("T_ERR", $ft.print_r($value, true));
}
function jL($value ,$level ="T_LOG")
{
    $ft = "";
    $debugTrace = debug_backtrace();
    if(isset($debugTrace[1])) {
        $fname = ( isset($debugTrace[0]['file'] ) ) ? str_replace(APPPATH, '', $debugTrace[0]['file']) : '';
        $line = ( isset($debugTrace[0]['line'] ) ) ? ':' . $debugTrace[0]['line'] : '';
        $func = ( isset($debugTrace[1]['function'] ) ) ? '::' . $debugTrace[1]['function']:'';
        $ft .= '[' . $fname . $line . $func . ']';
    }
    //if($ft) {$ft = chr(27)."[33m".$ft.chr(27)."[0m\n";}

    log_message($level , $ft.print_r($value, true));
}


function get_next_level_exp($level)
{
    jCHECK($level > 0 , '_get_next_level_exp level is invalid = ' . $level);
    ++$level;
    $csv = j_content_load('file_Sys_Level.ansi');
    foreach($csv as $row)
    {
        if( $row['level'] == $level) return $row['exp_total'];
    }
    return $csv[0]['exp_total'];
}



function CSV_NpcData($csv_npc_data)
{
    $row = j_content_find2('NpcData' , function($row) use($csv_npc_data) {
        if( $row['index'] <= $csv_npc_data) return true;
    });
    jCHECK(isset($row) , ' CSV_NpcData find fail : $csv_npc_data = ' . $csv_npc_data);
    return $row;
}


function CSV_Sys_NpcRelation($npc_group)
{
    $row = j_content_find2('file_Sys_NpcRelation.ansi' , function($row) use($npc_group) {
        if( $row['group'] == $npc_group) return true;
    });
    jCHECK(isset($row) , ' file_Sys_NpcRelation find fail : $npc_group = ' . $npc_group);
    return $row;
}


function CSV_Sys_NpcParts_EmotionGift($emotion_grade)
{
    $row = j_content_find2('file_Sys_NpcParts_EmotionGift.ansi' , function($row) use($emotion_grade) {
        if( $row['emotion_grade'] == $emotion_grade) return true;
    });
    jCHECK(isset($row) , ' file_Sys_NpcParts_EmotionGift find fail : $emotion_grade = ' . $emotion_grade);
    return $row;
}

