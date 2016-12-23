<?php

function imageresize($outfile,$infile,$filetype) {
	
	$neww = 320;
	$newh = 240;
	$quality = 85;
	$quality_png = 2;
	
	if ($filetype == "jpg"){
		$im=imagecreatefromjpeg($infile);
	}
	else if ($filetype == "gif"){
		$im=imagecreatefromgif($infile);
	}
	else if ($filetype == "png"){
		$im=imagecreatefrompng($infile);
	}
	
	$im1=imagecreatetruecolor($neww,$newh);
	imagecopyresampled($im1,$im,0,0,0,0,$neww,$newh,imagesx($im),imagesy($im));
	
	if ($filetype == "jpg"){
		imagejpeg($im1,$outfile,$quality);
	}
	else if ($filetype == "gif"){
		imagegif($im1,$outfile,$quality);
	}
	else if ($filetype == "png"){
		imagepng($im1,$outfile,$quality_png);
	}
	
	
	imagedestroy($im);
	imagedestroy($im1);
}

function getUID(){
	$uid = ''.base_convert(microtime(),10,36).base_convert(rand(0,2000000000),10,36);
	//e	cho $uid;
	return $uid;
}
