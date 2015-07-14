<?php
if(isset($_GET['text']) && trim($_GET['text']) != "")
{
	require_once 'phpqrcode.php';
	$text = $_GET['text'];
	QRcode::png($text);
}
?>