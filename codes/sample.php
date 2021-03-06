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
			$other_side = ceil(sqrt($len));
			if($side < $other_side){
				$side = $other_side;
			}
		}else{
			$side = ceil(sqrt($len));
		}

		$this -> im = @imagecreatetruecolor($side, $side)
			or die('Cannot Initialize new GD image stream');

		//ブレンドモードを無効にする
		imagealphablending($this -> im, false);
		//完全なアルファチャネル情報を保存するフラグをonにする
		imagesavealpha($this -> im, true);

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

		for($i = 1; $i < $total + 1; ++$i){
			if(! ($x = $i % $side)){
				$x = $side;
				$y = $i / $side;
			}else{
				$y = floor(($i - $x) / $side) + 1;
			}

			$x--;
			$y--;
			//echo $x."-".$y."<br>";

			if($len <= $i)
				$color = imagecolorallocatealpha($im, 159, 253, 242, rand(100,255));
			else
				$color = imagecolorallocatealpha($im, 159, 253, 242, $codes[$i]);
			imagesetpixel($im, $x, $y, $color);

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