<?php

require_once(__DIR__."/lib/util2.php");

$toco = new toCode;

$toco -> setImg();

if(isset($_POST["escape"]) && $_POST["escape"] == "1"){
	$which = true;
}else{
	$which = false;
}

$code = $toco -> test($which);
//echo $time = microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"];

echo "<br>".nl2br($code);