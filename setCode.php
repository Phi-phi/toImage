<?php

require_once(__DIR__."/lib/util2.php");

function setCode($imgname){
	$toco = new toCode;

	$path = __DIR__."/img/".$imgname.".png";

	//echo $path;
	if(! file_exists($path)){
		//throw new RuntimeException("img data not found");7
		die("img data not found");
	}

	$toco -> path = $path;
	$code = $toco -> test(false);

	return "<script>".$code."</script>";
}

