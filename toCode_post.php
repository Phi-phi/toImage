<?php

require_once(__DIR__."/lib/util2.php");

$toco = new toCode;

$toco -> setImg();

if(isset($_POST["escape"]) && $_POST["escape"]){
	$which = true;
}else{
	$which = false;
}

$code = $toco -> test($which);

echo $code;