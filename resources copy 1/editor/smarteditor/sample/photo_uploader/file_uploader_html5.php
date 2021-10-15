<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';

use util\CustomLog;

 	$sFileInfo = '';
	$headers = array();
	 
	foreach($_SERVER as $k => $v) {
		if(substr($k, 0, 9) == "HTTP_FILE") {
			$k = substr(strtolower($k), 5);
			$headers[$k] = $v;
		} 
	}

	$filename = rawurldecode($headers['file_name']);

	$filename_ext = explode('.',$filename);

	if(count($filename_ext) !== 1){
		$filename_ext = array_pop($filename_ext);
	}

	$filename_ext = strtolower($filename_ext);
	$allow_file = array("jpg", "png", "bmp", "gif"); 

	if(!in_array($filename_ext, $allow_file)) {
		echo "NOTALLOW_".$filename;
	} else {
		$file = new stdClass;
		$file->name = date("YmdHis").mt_rand().".".$filename_ext;
		$file->content = file_get_contents("php://input");

		$uploadDir = $GLOBALS['serverFileRoot'];
		if(!is_dir($uploadDir)){
			mkdir($uploadDir, 0777);
		}
		
		$file->name = '/' . $file->name;
		$newPath = $uploadDir.$file->name;
		
		if(file_put_contents($newPath, $file->content)) {
			$sFileInfo .= "&bNewLine=true";
			$sFileInfo .= "&sFileName=". $filename;
			$sFileInfo .= "&sFileURL=" . $GLOBALS['httpFilePrefix'] . $file->name;
		}
		
		echo $sFileInfo;
	}
?>