<?php

if (strpos($_SERVER['HTTP_USER_AGENT'], 'Alipay') !== false) {
    $userid = $_GET['userid'];
    $amount = $_GET['amount'];
    $memo = $_GET['memo'];
	$html = file_get_contents(__DIR__ . "/template.php");
    $html = str_replace('itsuserid', $userid, $html);
    $html = str_replace('itsamount', $amount, $html);
    $html = str_replace('itsmemo', $memo,  $html);
    echo $html; }
    else {
	header("location:https://www.pornhub.com");
    }
?>