<?php

require_once (__DIR__."/lib/util.php");

$toim = new toImage;

$toim -> setCode($_POST["code"]);
$toim -> setName($_POST["title"]);
$toim -> start();

$toim -> disp();
