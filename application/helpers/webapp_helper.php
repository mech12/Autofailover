<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

define("HERE",dirname( __FILE__).":".__LINE__);

/**
 * 디버그용
 */
function D($value, $trace = false)
{
	$msg = '';
	if( $trace)
	{
		$debugTrace = debug_backtrace();
        if(isset($debugTrace[1])) {
            $fname = ( isset($debugTrace[0]['file'] ) ) ? str_replace(APPPATH, '', $debugTrace[0]['file']) : '';
            $line = ( isset($debugTrace[0]['line'] ) ) ? ':' . $debugTrace[0]['line'] : '';
            $func = ( isset($debugTrace[1]['function'] ) ) ? '::' . $debugTrace[1]['function']:'';
            $msg = '[' . $fname . $line . $func . ']';
        }
	}
	echo "<pre style='color: #222;background: none repeat scroll 0 0 #FBE6F2;padding: 10px; border: 1px solid #D893A1;text-align:left; display:block;font-size: 12px;border-radius: 8px;-moz-border-radius: 8px;' >"
		.$msg.print_r($value, true)."</pre>";
	log_message('debug', print_r($value, true));
}

function L($value, $level = "error")
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
        $ft = chr(27)."[31m".$ft.chr(27)."[0m\n";
    }

	log_message($level, $ft.print_r($value, true));
}



/**
 * 마지막 쿼리 남기기
 */
function LAST_QUERY()
{
    $ft = "";
    $debugTrace = debug_backtrace();
    if(isset($debugTrace[1])) {
        $fname = ( isset($debugTrace[0]['file'] ) ) ? str_replace(APPPATH, '', $debugTrace[0]['file']) : '';
        $line = ( isset($debugTrace[0]['line'] ) ) ? ':' . $debugTrace[0]['line'] : '';
        $func = ( isset($debugTrace[1]['function'] ) ) ? '::' . $debugTrace[1]['function'].'()':'';
        $ft .= '[' . $fname . $line . $func . ']';
    }
    if($ft) {
        $ft = chr(27)."[31m".$ft.chr(27)."[0m\n";
    }

	$ci =& get_instance();
    log_message("error", $ft.$ci->db->last_query());
}

/**
 * 디버그 툴바 로드
 */
function DEBUG_TOOLBAR()
{
	$ci =& get_instance();
	$ci->load->library(array('Profiler', 'Console'));
	$ci->output->enable_profiler(true);
}

/**
 * 디버그 툴바 콘솔로 출력
 */
function CONSOLE($v)
{
	Console::log($v);
}

function DISPLAY_DATE($str)
{
	$d = strtotime($str);
	return date("y-m-d H:i", $d);
}

function ALERT_AND_GO($message, $url)
{
	echo "<html lang='kr'><meta charset='utf-8' /><script type='text/javascript'>
		alert('$message');
		location.replace('$url');
	</script></html>";
	exit;
}

function ERROR_AND_BACK($msg)
{
	echo '
	<html lang="kr">
    <meta charset="utf-8" />
	<script type="text/javascript">
	alert("오류 : '.$msg.'");
	javascript:history.back();
	</script>
	</html>
	';
	exit;
}

/**
 * 자바스크립트로 Back으로 보낸다.
 */
function HISTORY_BACK()
{
	echo "<script type=\"text/javascript\">window.history.back();</script>";
}

function REDIS_PUBLISH($channel, $data, $target = "all")
{
	$ci =& get_instance();
	$redis = new Redis();
	$redis->pconnect($ci->config->item('redis_host'), $ci->config->item('redis_port'));
    if($channel == "COMMENT") {
        $redis->publish($channel, json_encode($data));
    }else{
        $redis->publish($channel, json_encode(array("target" => $target,
            "message" => $data,
            "stf_name" => $ci->session->userdata('stf_name'),
            "stf_id" => $ci->session->userdata('stf_id'))));
    }

	$redis->close();
}

function MYSQL_NOW($time = 0)
{
	if($time) {
		return date('Y-m-d H:i:s', time() + $time);
	}
	return date('Y-m-d H:i:s');
}


function COOKIE_TOAST($title, $text = '', $class_name = 'info')
{
	$ci =& get_instance();
	$ci->load->helper('cookie');
	delete_cookie("toasts");

	$cookie = array(
		'name'   => 'toasts',
		'value'  => json_encode(array('title' => $title, 'text' => $text, 'class_name' => $class_name)),
		'expire' => '600',
		'path'   => '/'
	);
	set_cookie($cookie);
}

function JSON_OUTPUT($data)
{
	$ci =& get_instance();
	$ci->output
		->set_content_type('application/json')
		->set_output(json_encode($data));
    if(isset($data['error'])) jE($data);
    else if( !isset($data['eCmd'])) jE($data);
    else{
        jL($data['eCmd'],'T_RQ');
    }
}
function JSON_OUTPUT_($data)
{
    $ci =& get_instance();
    $ci->output
        ->set_content_type('application/json')
        ->set_output(json_encode($data));
}

function IS_MOBILE()
{
	if(preg_match('/(alcatel|amoi|android|avantgo|blackberry|benq|cell|cricket|docomo|elaine|htc|iemobile|iphone|ipad|ipaq|ipod|j2me|java|midp|mini|mmp|mobi|motorola|nec-|nokia|palm|panasonic|philips|phone|playbook|sagem|sharp|sie-|silk|smartphone|sony|symbian|t-mobile|telus|up\.browser|up\.link|vodafone|wap|webos|wireless|xda|xoom|zte)/i', $_SERVER['HTTP_USER_AGENT']))
		return true;
	else
		return false;
}

function IS_LOGIN()
{
    $ci =& get_instance();
    return $ci->session->userdata("usr_uid")?true:false;
}

function SESSION($key)
{
    $ci =& get_instance();
    return $ci->session->userdata($key);
}


function IS_ADMIN()
{
	$ci =& get_instance();
	// session permision check
	// return strpos($ci->session->userdata('stf_permission'), "A_LOGIN")>-1?true:false;
}

function IS_VALID_URL($url)
{
    return preg_match('|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $url);
}

function REDIRECT_URL($url)
{
	header("Location: $url");
	exit;
}

function IS_IMAGE_FILE($file)
{
	return preg_match('/(\.jpg|\.png|\.bmp|\.gif)$/', strtolower($file));
}

function THUMBNAIL_PATH($path)
{
    return preg_replace('/(\.jpg|\.png|\.bmp|\.gif)$/', '_t$1' , $path);
//    return preg_replace('/(\.jpg|\.png|\.bmp|\.gif)$/', '_t.jpg' , $path);
}

function MYSQL_WHERE_IN_STRING($codes)
{
	$cnt = count($codes);
	for($i=0;$i<$cnt;$i++) {
		$codes[$i] = "'".$codes[$i]."'";
	}
	return implode(",", $codes);
}

function UNIQUE_ID($long=false) {
    $e = md5(uniqid(time(), true));
    if($long)
        return $e;

    return substr($e, 0, 16);
//    $s = uniqid('', $more_entropy);
//    if (!$more_entropy)
//        return base_convert($s, 16, 36);
//
//    $hex = substr($s, 0, 13);
//    $dec = $s[13] . substr($s, 15); // skip the dot
//    return base_convert($hex, 16, 36) . base_convert($dec, 10, 36);
}

// 나이 계산
function CAL_AGE($date)
{
    $date = new DateTime($date);
    $now = new DateTime();
    $interval = $now->diff($date);
    return $interval->y;
}


function SUBSTRING_DOT($str, $length)
{
    if(strlen($str) < $length) {
        return $str;
    }
    return substr($str, 0, $length). "...";
}

function POST_GET($input)
{
    $data = array();
    $ci =& get_instance();
    foreach($input as $key => $value) {
        $data[$key] = trim($ci->input->post_get($value, true));
    }
    return $data;
}
