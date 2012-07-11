<?php

	//画像変換
	
	require 'private/config.php';
	require 'private/char_geom.php';
	
	function readPng($path){
		error_log('read:' . $path);
		$img = ImageCreateFromPNG($path);
		if(!$img){
			error_log('画像が読めませんでした:' . $path);
			return null;
		}
		return $img;
	}

	function readJpeg($path){
		error_log('read:' . $path);
		$img = ImageCreateFromJPEG($path);
		if(!$img){
			error_log('画像が読めませんでした:' . $path);
			return null;
		}
		return $img;
	}
	
	function responsePng($img){
		header('Content-Type: image/png');
		//error_log('出力: ' . $image);
		ImagePNG($img);
	}

	function responseJpeg($img, $quality){
		header('Content-Type: image/jpeg');
		//error_log('出力: ' . $image);
		ImageJPEG($img, NULL, $quality);
	}
	
	/**
	 * 一文字画像
	 */
	class Char{
		
		var $char;
		var $geom;
		var $width;
		var $height;
		private $img;
		
		function Char($char, $img){
			$this->char = $char;
			
			global $geom;
			$this->geom = $geom[$char];
			
			$this->img = $img;
			
			$this->width = ImageSx($this->img);
			$this->height = ImageSy($this->img);

		}
		
		function putTo($canvas, $x, $y){
		
//			error_log($canvas);
//			error_log($this->img);
		
			return ImageCopy($canvas, $this->img,
			    $x, $y,
			    0, 0,
			    $this->width, $this->height);
			    
			/*
			return imagecopymerge($canvas, $this->img,
			    $x, $y,
			    0, 0,
			    $this->width, $this->height, 100);*/
			    
		}
		
		
		function destroy(){
			imagedestroy($this->img);
		}
	
	}
	
	/**
	 * 連なり文字画像
	 */
	class CharSequence{
	
		var $chars = array();

		
		public $x;
		public $y;
		public $string;
		
		private $padding;
		private $width;
		
		
		function CharSequence($str){
			$this->string = $str;
		}
		
		/**
		 * 読みこめない文字があった時にはエラー
		 */
		function init(){
			$chars = str_split($this->string);
			for($i = 0; $i < count($chars); ++$i){
				$char = $chars[$i];
				$charPath = IMAGE_ALPHABET_ROOT . '/' . $char . '.png';
				$img = readPng($charPath);
				if(!$img){
					error_log('read failed: ' + $charPath);
					return false;
				}
				
				$char = new Char($chars[$i], $img);
				$this->chars[] = $char;
			}
			
			return true;
		}
		
		function setPadding($padding){
			$this->padding = $padding;
			$this->calcGeom();
		}
		
		function getWidth(){
			return $this->width;
		}
		
		function calcGeom(){
			$width = 0;
			for($i = 0; $i < count($this->chars); ++$i){
				$char = $this->chars[$i];
				$width += $this->calcWidth($char);
			}
			
			$this->width = $width;
		}
		
		private function calcWidth($char){
			return $char->geom['width'] + $this->padding;
		}
		
		function putTo($img){
			$x = $this->x;
			$y = $this->y;
			for($i = 0; $i < count($this->chars); ++$i){
				$char = $this->chars[$i];
				
				$char->putTo($img, $x - $char->geom['left'], $y - $char->geom['top']);
				$x += $this->calcWidth($char);
			}
			
		}
		
		function destroy(){
			for($i = 0; $i < count($this->chars); ++$i){
				$char = $this->chars[$i];
				$char->destroy();
			}
		}
	
	}
	
	
	function drawBar($image, $left, $right, $top){
		$color = imagecolorallocate($image, 29, 32, 136);
		imagefilledrectangle($image, $left + 10, $top, $right - 10, $top + 23, $color);

		$leftImage = readPng(IMAGE_ALPHABET_ROOT . '/barleft.png');
		imagecopy($image, $leftImage, $left, $top, 0, 0, imageSx($leftImage), imageSy($leftImage));

		$rightImage = readPng(IMAGE_ALPHABET_ROOT . '/barright.png');
		$rwidth = imageSy($rightImage);
		imagecopy($image, $rightImage, $right - $rwidth, $top, 0, 0, imageSx($rightImage), $rwidth);
		
		$logoImage = readPng(IMAGE_ALPHABET_ROOT . '/barlogo.png');
		$logowidth = imageSx($logoImage);
		imagecopy($image, $logoImage, ($right - $left) * 0.5 - $logowidth * 0.5 + 10, $top, 0, 0, $logowidth, imageSy($logoImage));
	}
	
	
	if(empty($_GET['first'])){
		return;
	}
	if(empty($_GET['last'])){
		return;
	}
	
	$top = 10; //一番上の座標
	$left = 42; //一番左の座標
	$padding = 10; //一文字毎のパディング
	$upperCharHeight = $lowerCharHeight = 59; //文字の高さ
	
	$lineHeight = 30; //中央のラインの高さ（上下のパディング含む）
	$quality = 100; //JPEGのクオリティ
	
	$qoffset = 8; //Qの文字分のオフセット
	$firstString = $_GET['first'];
	if(strstr($firstString, 'Q')){
		$upperCharHeight += $qoffset;
	}
	
	$secondString = $_GET['last'];
	if(strstr($secondString, 'Q')){
		$lowerCharHeight += $qoffset;
	}


	$firstSeq = new CharSequence($firstString);
	if(!$firstSeq->init()){
		return;
	}
	$firstSeq->x = $left;
	$firstSeq->y = $top;
	$firstSeq->setPadding($padding);

	if(!$secondString){
		return;
	}
	
	$secondSeq = new CharSequence($secondString);
	if(!$secondSeq->init()){
		return;
	}
	$secondSeq->y = $top + $upperCharHeight + $lineHeight;
	$secondSeq->setPadding($padding);

	$markImage = readPng(IMAGE_ALPHABET_ROOT . '/mark.png');
	$markWidth = imageSx($markImage);

	$upperWidth = $firstSeq->getWidth() + $markWidth + 40;
	$lowerWidth = $secondSeq->getWidth() + 50;
	
	$imageWidth = max($upperWidth, $lowerWidth);
	$imageHeight = $secondSeq->y + $lowerCharHeight + 10;
	$image = imagecreatetruecolor($imageWidth, $imageHeight);
	$white = imagecolorallocate($image, 255, 255, 255);
	imagefill($image, 0, 0, $white);

	drawBar($image, 5, $imageWidth - 5, $secondSeq->y - 28);
	imagecopy($image, $markImage, $imageWidth - $markWidth - 5, $top + 1, 0, 0, $markWidth, imageSy($markImage));


	//下段は右寄せ
	$secondSeq->x = $imageWidth - $secondSeq->getWidth() - 24;

	$firstSeq->putTo($image);
	$secondSeq->putTo($image);

	$firstSeq->destroy();
	$secondSeq->destroy();

	if(empty($_GET['type'])){
		responseJpeg($image, $quality);
		return;
	}
	
	if('m' == $_GET['type']){
		//拡大縮小はこれ
		$resampleWidth = 240;
		$resampleHeight = $imageHeight * 240 / $imageWidth;
		$resampledImg = imagecreatetruecolor($resampleWidth, $resampleHeight);
		imagecopyresampled($resampledImg, $image, 0, 0, 0, 0, $resampleWidth, $resampleHeight, $imageWidth, $imageHeight);
		responseJpeg($resampledImg, $quality);
		return;
	}
	
	//通常はこれ
	responseJpeg($image, $quality);
