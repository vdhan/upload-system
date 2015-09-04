<?php
session_start();
$salt = uniqid(rand(),true);

if($_FILES["fileToUpload"]["size"] < 200000000)
{
	if ($_FILES["fileToUpload"]["error"] == 1)
	{
		echo "Tập tin tải lên vượt quy định cho phép.</br>";
	}
	else if($_FILES["fileToUpload"]["error"] == 2)
	{
		echo "Tập tin tải lên vượt quá thông số MAX_FILE_SIZE.</br>";
	}
	else if($_FILES["fileToUpload"]["error"] == 3)
	{
		echo "Tập tin chỉ tải lên được 1 phần.</br>";
	}
	else if($_FILES["fileToUpload"]["error"] == 4)
	{
		echo "Chưa chọn tập tin tải lên.</br>";
	}
	else if($_FILES["fileToUpload"]["error"] == 6)
	{
		echo "Không tìm thấy thư mục tạm.</br>";
	}
	else if($_FILES["fileToUpload"]["error"] == 7)
	{
		echo "Không thể ghi lên đĩa.</br>";
	}
	else if($_FILES["fileToUpload"]["error"] == 8)
	{
		echo "Tập tin tải lên bị dừng bởi phần mở rộng.</br>";
	}
	else if ($_FILES["fileToUpload"]["error"] == 0)
	{
		$s = $_FILES["fileToUpload"]["name"];
		$t = explode(" ", $s);
		$p = implode("_", $t);
		
		$fdir = hash('tiger192,4',$salt . $s);
		$dir = "files/" . $fdir;
		mkdir($dir,0777);		
		
		$dir .= "/" . $p;
		move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $dir);
		$md5 = md5_file($dir);
		$sha1 = sha1_file($dir);
		
		if($_FILES["fileToUpload"]["size"] >= 1000000)
		{
			$size = round($_FILES["fileToUpload"]["size"] / 1000000) . " MB";
		}
		else if($_FILES["fileToUpload"]["size"] >= 1000)
		{
			$size = round($_FILES["fileToUpload"]["size"] / 1000) . " KB";
		}
		else
		{
			$size = $_FILES["fileToUpload"]["size"] . " B";
		}
		
		$content =
"<!DOCTYPE html>
<html>
	<head>
		<title>{$s}</title>
		<meta name='author' content='Hoàng Ân' />
		
		<style>
			body {
				font-family: sans-serif;
				font-size: 100%;
				text-align: center;
			}
			
			#wrap {
				border: 1px solid #088;
				border-radius: 10px;
				margin: auto;
				width: 580px;
				background-color: #0ff;
			}
			
			a {
				text-decoration: none;
				color: #fff;
				padding: 5px;
				display: block;
				background-color:#080;
				border: 1px solid #bbb;
				border-radius: 5px;
			}
			
			#download {
				font-size: 1.25em;
				font-weight: bold;
				padding: 10px;
				background-color: #ddd;
				border-radius: 10px;
			}
			
			#wrap p {
				color: #fff;
				font-size: 1.5em;
				margin: 10px;
				background-color: #222;
				padding: 5px;
				overflow: hidden;
			}
			
			span {
				font-weight: normal;
			}
			
			.copy {
				color: #00f;
			}
		</style>
		
		<script>
			function Dclick()
			{
				document.getElementById('download').innerHTML = 'Đang tải xuống...';
			}
		</script>
	</head>
	
	<body>
		<div id='wrap'>
			<p><strong>{$s}</strong></p>
			<div id='download'><a href='download.php?file={$dir}' onclick='Dclick();'>Tải xuống {$size}</a></div>
		</div>
		
		<p class='copy'><strong>Copyright &copy; 2012 Hoàng Ân</strong></p>
	</body>
</html>";
		
		file_put_contents($fdir . ".html", $content);
		$fdir = 'http://localhost/upload-system/?download=' . $fdir;
		
		echo 
"<p><label for='url'>Địa chỉ liên kết: </label><input id='url' type='text' size='30' readonly value='$fdir' /></p>
<p>Mã QR: <img src='qrcode.php?text=$fdir' width='150' height='150' style='vertical-align: middle' /></p>

<p style='margin-top:50px'><label for='htl'>Mã nhúng HTML: </label><input id='htl' readonly size='40' type='text' value='<a href=\"$fdir\"; target=\"_blank\">$fdir</a>' /></p>
<p><label for='forum'>Mã nhúng diễn đàn: </label><input id='forum' size='40' readonly type='text' value='[URL=$fdir]" . $fdir . "[/URL]' /></p>
		
<p style='margin-top:50px'>MD5: $md5</p>
<p>SHA-1: $sha1</p>";
	}
	else
	{
		echo "Lỗi tải lên.</br>";
	}
}