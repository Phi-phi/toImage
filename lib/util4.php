<?php

class toImage{

	private $codes = array();
	private $new_codes = array();
	private $other_exist;
	private $other_size;
	private $len;
	private $title;
	private $side;
	private $im;
	private $path;
	private $path2;

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

	function checkSame($title){
		$other_path = "./img/".$title.".png";
		if(file_exists($other_path)){
			list($width, $height) = getimagesize($other_path);
			$this -> other_size = $width;
			return true;
		}else{
			return false;
		}
	}

	private function setSize(){
		$len = $this -> len;
		$title = $this -> title;
		$which = $this -> checkSame($title);

		if($which){
			$side = $this -> other_size;
			$other_side = ceil(sqrt($len / 3));
			if($side < $other_side){
				$side = $other_side;
			}
		}else{
			$side = ceil(sqrt($len / 4));
		}

		$this -> im = @imagecreatetruecolor($side, $side)
			or die('Cannot Initialize new GD image stream');

		$this -> other_exist = $which;
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

		//ブレンドモードを無効にする
		imagealphablending($this -> im, false);
		//完全なアルファチャネル情報を保存するフラグをonにする
		imagesavealpha($this -> im, true);

		for($i = 1; $i < $total + 1; ++$i){
			if(! ($x = $i % $side)){
				$x = $side;
				$y = $i / $side;
			}else{
				$y = floor(($i - $x) / $side) + 1;
			}

			$rgb[$i] = array();

			for($m = 0; $m < 4; ++$m){
				if(($n = ($i - 1) * 4 + $m) >= $len){
					$codes[$n] = 3;
					$end = true;
				}
				array_push($rgb[$i], $codes[$n]);
			}

			$x--;
			$y--;
			//echo $x."-".$y."<br>";

			$color = imagecolorallocatealpha($im, 255 - $rgb[$i][0], 255 - $rgb[$i][1], 255 - $rgb[$i][2], 127 - $rgb[$i][3]);
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

		$path = "./img/".$title;

		if($this -> other_exist){
			$this -> path2 = $path.".png";
			$path .= "_other";
		}

		$path .= ".png";
		imagepng($im, $path);
		imagedestroy($im);
		$this -> path = $path;
	}

	function disp(){
		$width = " width='200' ";
		echo "<img src='".$this -> path."'><br>";
		if($this -> other_exist)
			echo "old one<br>";
			echo "<img src='".$this -> path2."'><br>";
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