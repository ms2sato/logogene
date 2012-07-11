<?php

require_once('private/config.php');

function toAlphabetOnly($text){
	$han = mb_convert_kana($text, 'a', 'UTF-8');
//	error_log('han:' . $han);

	$alpha = mb_ereg_replace('[^a-zA-Z]', '',  $han);
//	error_log('alpha:' . $alpha);
	
	return strtoupper($alpha);
}


$first = toAlphabetOnly($_REQUEST['first']);
$last = toAlphabetOnly($_REQUEST['last']);
error_log('first[' . $first . ']');
error_log('last[' . $last . ']');

$error;
if(strlen($first) == 0 || strlen($last) == 0){
	$error = INPUT_ERROR;
	include_once('template/index.tpl');
	return;
}



///////////////////////////////////////
// テンプレートに送信する変数はここで定義すること

include_once('template/result.tpl');
