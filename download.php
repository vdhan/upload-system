<?php
if(file_exists($_GET['file']))
{
	$file = $_GET['file'];
	$file = htmlspecialchars($file);
	$file = addslashes($file);
	
	$basename = basename($file);	
	$info = finfo_open(FILEINFO_MIME_TYPE);
	$type = finfo_file($info,$file);
	finfo_close($info);
	
	header("Content-Type: " . $type);
	header("Content-Disposition: attachment; filename=" . $basename);
	readfile($file);
}
?>