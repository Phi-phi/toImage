<?php

class toCode{
	private $code = array();
	public $path;

	function setImg(){
		if (isset($_FILES['src_img']['error']) && is_int($_FILES['src_img']['error'])) {

		try {
			// $_FILES['upfile']['error'] の値を確認
			switch ($_FILES['src_img']['error']) {
			    case UPLOAD_ERR_OK: // OK
			        break;
			    case UPLOAD_ERR_NO_FILE:   // ファイル未選択
			        throw new RuntimeException('ファイルが選択されていません');
			    case UPLOAD_ERR_INI_SIZE:  // php.ini定義の最大サイズ超過
			    case UPLOAD_ERR_FORM_SIZE: // フォーム定義の最大サイズ超過
			        throw new RuntimeException('ファイルサイズが大きすぎます');
			    default:
			        throw new RuntimeException('その他のエラーが発生しました');
			}	

			// $_FILES['upfile']['mime']の値はブラウザ側で偽装可能なので、MIMEタイプを自前でチェックする
			$type = @exif_imagetype($_FILES['src_img']['tmp_name']);	

			if (!in_array($type, [IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG], true)) {
			    throw new RuntimeException('画像形式が未対応です');
			}	

			// ファイルデータからSHA-1ハッシュを取ってファイル名を決定し、ファイルを保存する
			$path = sprintf('./img/tmp/%s', $_FILES['src_img']['name']);
			if (!move_uploaded_file($_FILES['src_img']['tmp_name'], $path)) {
			    throw new RuntimeException('ファイル保存時にエラーが発生しました');
			}
		       chmod($path, 0644);	

		        $msg = ['green', 'ファイルは正常にアップロードされました'];
		        $this -> path = $path;

		    } catch (RuntimeException $e) {	

		        $msg = ['red', $e->getMessage()];

			}
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

		//var_dump($code);
		$new_code = "";

		for($i = 0; $i < count($code); ++$i){
			if($code[$i] == 2) break;
			$new_code .= chr($code[$i]);
		}
		unlink($path);
		if($which){
			return htmlspecialchars($new_code);
		}
		return $new_code;
	}
}