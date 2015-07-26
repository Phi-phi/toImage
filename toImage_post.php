<?php

require_once (__DIR__."/lib/util.php");

$toim = new toImage;

$toim -> setCode($_POST["code"]);
$toim -> setName($_POST["title"]);
$toim -> start();

$toim -> disp();
echo $time = microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"];

?>
	<a href="./index.html">back</a>