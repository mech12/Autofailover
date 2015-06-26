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
	

	function ping($host, $port, $timeout) { 
		$tB = microtime(true); 
		$fP = fSockOpen($host, $port, $errno, $errstr, $timeout); 
		if (!$fP) { return "down"; } 
		$tA = microtime(true); 
		return round((($tA - $tB) * 1000), 0)." ms"; 
	}

    function php_ping($host, $timeout = 1) {
        /* ICMP ping packet with a pre-calculated checksum */
        $package = "\x08\x00\x7d\x4b\x00\x00\x00\x00PingHost";
        $socket  = socket_create(AF_INET, SOCK_RAW, 1);
        socket_set_option($socket, SOL_SOCKET, SO_RCVTIMEO, array('sec' => $timeout, 'usec' => 0));
        socket_connect($socket, $host, null);
        $ts = microtime(true);
        socket_send($socket, $package, strLen($package), 0);
        return 'ok ' . $host;
        $result = false;
        if (socket_read($socket, 255))
            $result = microtime(true) - $ts;

        socket_close($socket);

        return $result;
    }

    function is_windown()
    {
        return strpos(PHP_OS , 'WIN') !==false;
    }

    function _check_disk($disk)
    {
        if($disk=='NULL')
            return array('error'=>$disk);

        if(is_windown())
        {
            exec('dir '.$disk , $ret);
        }
        else
        {
            exec('ls '.$disk , $ret);
        }
        if(count($ret)==0 ) return array('error'=>$disk);
        return array('result'=>$disk);
    }

    function is_exist_app($proc_list , $_app)
    {
        foreach($proc_list as $p)
        {
            if(strpos($p , $_app) !== false)
                return true;
        }
        return false;
    }

    function _check_process($app)
    {
        if(is_windown()) {
            exec('tasklist', $proc_list);
        }
        else
        {
            exec('ps aux', $proc_list);
        }
        $app_arr = array();
        if(strpos($app,';')!==false)
        {
            $app_arr = explode(';' , $app , 1000);
        }
        else
        {
            array_push( $app_arr , $app);
        }

        foreach($app_arr as $_app)
        {
            if(is_exist_app($proc_list , $_app) ==false)
                return array('error'=>$_app);
        }
        return array('result'=>$app);

    }

	function _check_ping($server1)
	{
		if(! isset($server1) || strlen($server1)<=0 || $server1=='NULL') return array('error' => 'need setup');
        //$r = system('ping -n 1 2>&1' . $server1);

        $server = explode(':', $server1 , 10);
        $server1 = $server[0];
        if(is_windown()) {
            exec('ping -n 1 2>&1' . $server1 , $r);
            $r = implode($r);
            return (strpos($r, 'TTL') === false && strpos($r, 'ms') === false) ? array('error'=>$server1) : array('result'=>$server1);
        }
        else
        {
            exec('ping -c 1 2>&1' . $server1 , $r);
            $r = implode($r);
            return (strpos($r, 'transmitted') === false) ? array('error'=>$server1) : array('result'=>$server1);
        }
	}


function _check_virtual_ip($vip)
{
    $ret = _check_ping( $vip );
    if(isset($ret['error'])) return $ret;

    $r =null;
    if(is_windown()) {
        exec('ipconfig' , $r);
    }
    else
    {
        exec('ifconfig', $r);
    }
    $r = implode($r);
    return (strpos($r, $vip) === false ) ? array('error'=>$vip) : array('result'=>$vip);

}



function _check_ping1234($server1)
{
    if(! isset($server1) || strlen($server1)<=0) return array('error' => 'need setup');

    //$r = php_ping($server1);
    //$r = shell_exec('ping -n 1 2>&1' . $server1);
    $r = system('ping -n 1 2>&1' . $server1);
    //exec('ping -n 1 2>&1' . $server1 , $r);
    //print_r($r);
    //echo "=======================================================================================";
    //$r = implode($r);
    //$r = iconv( "euckr","utf8", $r);
    //$r = iconv("UTF-8", "euc-kr", $r);
    print_r($r);
    echo "=======================================================================================";

    //return array('error'=>$r);

    //$r = passthru('ping -n 1 2>&1' . $server1);
    /*
    while (@ ob_end_flush()); // end all output buffers if any
    $proc = popen('ping -n 1 2>&1' . $server1, 'r');
    $r ='';
    while (!feof($proc))
    {
        $a = fread($proc, 4096);
        $r = $r . $a;
        //echo fread($proc, 4096);
        @ flush();
    }
    */

    if( strpos($r , 'TTL') === false && strpos($r , 'ms') === false)
        return array('error'=>$r);
    else
        return array('result'=>$r);

}


