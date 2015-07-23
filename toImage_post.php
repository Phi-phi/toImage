<?php

require_once (__DIR__."/util.php");

$toim = new toImage;

$toim -> setCode($_POST["code"]);
$toim -> setName($_POST["title"]);
$toim -> start();

if(file_exists(($path = $toim -> path()))){
	echo "<img src=".$path."><br><br>";
}
