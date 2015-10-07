<?php

class toCode{
	private $code = array();
	public $path;

	function setImg(){
		if (isset($_FILES['src_img']['error']) && is_int($_FILES['src_img']['error'])) {

			try {
				switch ($_FILES['src_img']['error']) {
				    case UPLOAD_ERR_OK: // OK
				        break;
				    case UPLOAD_ERR_NO_FILE:
				        throw new RuntimeException('No File selected');
				    case UPLOAD_ERR_INI_SIZE:
				    case UPLOAD_ERR_FORM_SIZE:
				        throw new RuntimeException('Too Large File');
				    default:
				        throw new RuntimeException('Unidentified err.');
				}

				$type = @exif_imagetype($_FILES['src_img']['tmp_name']);

				if (!in_array($type, [IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG], true)) {
				    throw new RuntimeException("We can't know this file");
				}

				$path = sprintf('./img/tmp/%s', $_FILES['src_img']['name']);
				if (!move_uploaded_file($_FILES['src_img']['tmp_name'], $path)) {
				    throw new RuntimeException('File saving error');
				}
			       chmod($path, 0644);

			        $msg = ['green', 'Uploaded ok'];

			} catch (RuntimeException $e) {

			        $msg = ['red', $e->getMessage()];

			}
			$this -> path = $path;
		}
	}

	function test($which){
		$code = array();
		$path = $this -> path;
		$im = imagecreatefrompng($path);
		list($width, $height) = getimagesize($path);
		//echo $width. $height;
		for($y = 0; $y < $width; ++$y){
			for($x = 0; $x < $height; ++$x){
				$rgb = imagecolorat($im, $x, $y);
				$colors = imagecolorsforindex($im, $rgb);

				/*echo "<pre>";
				echo $y * $width + $x + 1;
				echo "<br>";
				var_dump($colors);
				echo "</pre>";*/

				array_push($code, 255 - $colors["red"], 255 - $colors["green"], 255 - $colors["blue"]);
			}
		}
		/*echo "<pre>";
		var_dump($code);
		echo "</pre>";*/
		$new_code = "";
		echo count($code)."<br>";

		for($i = 0; $i < count($code); ++$i){
			if($code[$i] == 3) break;
			$new_code .= chr($code[$i]);
		}

		if(preg_match("/\/tmp\//", $path))
			unlink($path);
		if($which){
			return nl2br(htmlspecialchars($new_code));
		}
		return $new_code;
	}
}