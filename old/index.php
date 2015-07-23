<?php

if(isset($_POST["code"]) && ! empty($_POST["code"])){
		$code = $_POST["code"];
		$newcode = array();
		$code_num = mb_strlen($code);
		echo $code_num;
		for($i = 0; $i < $code_num; ++$i){
			$newcode[$i] = ord($code[$i]);
		}
		//var_dump($newcode);

		$side = ceil(sqrt($code_num / 4));
		$size = $side * $side;
		echo "<br>".$side."-".$size."<br>";

		$im = @imagecreatetruecolor($side, $side)
			or die('Cannot Initialize new GD image stream');

		$end_file = false;

		for($i = 0; $i < $size; ++$i){
			if(! ($x = $i % $side)){
				$x = $side;
				$y = $i / $side;
			}else{
				$y = ($i - $x) / $side;
			}
			for($m = 0; $m < 4; ++$m){
				//echo $i * 4 + $m."<br>";
				if(($i * 4 + $m) >= $code_num){
					$newcode[$i * 4 + $m] = 127;
					$end_file = true;
				}
			}
			$rgba = array( $newcode[$i * 4],
				$newcode[$i * 4 + 1],
				$newcode[$i * 4 + 2],
				$newcode[$i * 4 + 3]);
			var_dump($rgba);

			$color = imagecolorallocatealpha(
				$im, $newcode[$i * 4],
				$newcode[$i * 4 + 1],
				$newcode[$i * 4 + 2],
				$newcode[$i * 4 + 3]);
			//echo "<br>".$x."-".$y."<br>";
			//var_dump($newcode);
			imagesetpixel($im, $x, $y, $color);
			echo imagecolorat($im, $x, $y)."<br>";
			if($end_file) break;
		}
		$title = "notitle";
		if(isset($_POST["title"])){
			$title  = $_POST["title"];
		}
		$path = "./img/".$title.".png";

		imagetruecolortopalette($im, true, 255);
		imagepng($im, $path);
}else if (isset($_FILES['src_img']['error']) && is_int($_FILES['src_img']['error'])) {

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
		$path = sprintf('./img/tmp/%s%s', sha1_file($_FILES['src_img']['tmp_name']), image_type_to_extension($type));
		if (!move_uploaded_file($_FILES['src_img']['tmp_name'], $path)) {
		    throw new RuntimeException('ファイル保存時にエラーが発生しました');
		}
	       chmod($path, 0644);

	        $msg = ['green', 'ファイルは正常にアップロードされました'];

	    } catch (RuntimeException $e) {

	        $msg = ['red', $e->getMessage()];

		}
		$im = imagecreatefrompng($path);
		list($width, $height, $type, $attr) = getimagesize($path);
		$size = $width * $height;
		$side = $width;
		echo $size."-".$height."-".$side;
		$rawcodes = array();
		$decodes = array();
		$colors = array("red", "green", "blue", "alpha");
		for($i = 0; $i < $size; ++$i){
			if(! ($x = $i % $side)){
				$x = $side - 1;
				$y = $i / $side;
			}else{
				$y = ($i - $x) / $side;
			}
			$index = imagecolorat($im, $x, $y);
			echo $index."<br>";
			$rgba = imagecolorsforindex($im, $index);
			//echo "<br>";
			var_dump($rgba);
			for($m = 0; $m < 4; ++$m){
				$key = $colors[$m];
				$rawcodes[$i * 4 + $m] = $rgba[$key];
				$decodes[$i * 4 + $m] = chr($rgba[$key]);
				if($rgba[$key] == 127){
					$decodes[$i * 4 + $m] = chr(00);
					break 2;
				}
			}

		}
		//var_dump($rawcodes);

}
?>

<html>
<head>
	<title>TO IMAGE</title>
</head>
<body>
	<?php
		if(isset($_POST["title"])):
			$title = $_POST["title"];
			if(file_exists($path))
				echo "<img src=".$path.">";
		endif;
	?>
	<form enctype="multipart/form-data" method="post" action="">
		<input type="file" name="src_img"><br>
		<input type="text" name="title"><br>
		<textarea name="code" style="width:500px; height:500px;" id="code"></textarea><br>
		<input type="submit" value='code'>
	</form><br>
	<?php
	if(isset($decodes)){
		echo nl2br($decodes);
	}
	?>
</body>
</html>