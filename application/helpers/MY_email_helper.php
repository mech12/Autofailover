<?php
// 시스템 메일 발송 하기
function send_system_email($to, $subject, $message)
{
    $ci =& get_instance();
    $ci->config->load('email');

    $ci->load->library('email', $ci->config->item('EMAIL'));

    $ci->email->from('noreply@campingbears.com', "CampingBears");
    $ci->email->to($to);

    $ci->email->subject($subject);

    $ci->email->message($message);
    $ci->email->send();
}