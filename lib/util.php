<?php

class toImage{

	private $codes = array();
	private $new_codes = array();
	private $len;
	private $title;
	private $side;
	private $im;
	private $path;

	private function toCode(){
		$old = $this -> codes;
		$len = $this -> len;

		for($i = 0; $i < $len; ++$i){
			$this -> new_codes[$i] = ord($old[$i]);
		}
	}

	function setName($title){
		if(isset($title) && ! empty($title)){
			$this -> title = $title;
		}
	}

	private function setSize(){
		$len = $this -> len;
		$side = ceil(sqrt($len / 3));

		$this -> im = @imagecreatetruecolor($side, $side)
			or die('Cannot Initialize new GD image stream');

		$this -> side = $side;
	}

	private function createImage(){
		$side 	= $this -> side;
		$total 	= $side * $side;
		$len 	= $this -> len;
		$codes 	= $this -> new_codes;
		$title 	= $this -> title;
		$end	= false;
		$im 	= $this -> im;
		$rgb 	= array();

		echo $len."-".$total."<br>";

		for($i = 1; $i < $total + 1; ++$i){
			if(! ($x = $i % $side)){
				$x = $side;
				$y = $i / $side;
			}else{
				$y = floor(($i - $x) / $side) + 1;
			}

			$rgb[$i] = array();

			for($m = 0; $m < 3; ++$m){
				if(($n = ($i - 1) * 3 + $m) >= $len){
					$codes[$n] = 2;
					$end = true;
				}
				array_push($rgb[$i], $codes[$n]);
			}

			$x--;
			$y--;
			//echo $x."-".$y."<br>";

			$color = imagecolorallocate($im, 255 - $rgb[$i][0], 255 - $rgb[$i][1], 255 - $rgb[$i][2]);
			imagesetpixel($im, $x, $y, $color);
			if($end)
				break;

		}
		/*echo "<pre>";
		var_dump($codes);
		var_dump($rgb);
		echo "</pre>";*/
		if(! isset($title)){
			$title = "img";
		}

		$path = "./img/".$title.".png";
		imagepng($im, $path);
		imagedestroy($im);
		$this -> path = $path;
	}

	function start(){
		if(isset($this -> codes)){
			$this -> toCode();
			$this -> setSize();
			$this -> createImage();
		}
	}

	function path(){
		$path = $this -> path;
		return $path;
	}


	function setCode($code){
		if(is_string($code) && isset($code)){
			$this -> len = mb_strlen($code);
			$this -> codes = $code;
		}
	}
}