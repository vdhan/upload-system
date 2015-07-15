<?php
if(isset($_GET['download']) && trim($_GET['download']) != "")
{
	$t = $_GET['download'];
	$t = htmlspecialchars($t);
	$t = addslashes($t);
	header("location: download={$t}");
}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset='UTF-8' />
		<meta name="author" content="Hoàng Ân" />
		<title>Hoàng Ân 's Uploader</title>
		<script type="text/javascript" src="js/jquery.min.js"></script>
		<script type="text/javascript" src="js/jquery-ui.js"></script>
		<link rel="stylesheet" type="text/css" href="css/jquery-ui.css" />
		<link rel="stylesheet" type="text/css" href="css/Anstyle.css" />
		<script type="text/javascript" src="js/Anscript.js"></script>
	</head>
	
	<body onbeforeunload="return false;">
		<form id="form1" enctype="multipart/form-data" method="post" action="upload.php">
			<fieldset id="uploadForm">
				<legend>Chọn tập tin tải lên (tối đa: 200 MB)</legend>
				<div class="row">
					<input name="fileToUpload" id="fileToUpload" size="40" onchange="fileSelected();" type="file" />
				</div>
				
				<div class="row">
					<input class="button" onclick="uploadFile()" value="Đồng ý" title='Tải lên' type="button" />
				</div>
				
				<div id="messages"></div>
				
				<div id="fileInfo">
					<div id="fileName"></div>
					<div id="fileSize"></div>
					<div id="fileType"></div>
				</div>
				
				<div id="progressBar" class="floatLeft"></div>
				<div id="progressNumber" class="floatRight"></div>
				<div class="clear"></div>
				
				<div class="row">
					<div id="transferSpeedInfo" class="floatLeft"></div>
					<div id="timeRemainingInfo" class="floatLeft"></div>
					<div id="transferBytesInfo" class="floatRight"></div>
					<div class="clear"></div>
				</div>
			</fieldset>
			
			<div id="uploadResponse">
				<button type="button" autofocus onclick="Bclick();">Chia sẻ</button>
			</div>
			
			<div id="dialog-message" title="Chia sẻ"></div>
		</form>
		
		<p class="copy"><strong>Copyright &copy; 2012 Hoàng Ân</strong></p>
		<p class="copy"><img src="../img/HTML5.png" width="64" height="64" alt="HTML5" title="HTML5" />
		<img src="../img/CSS3.png" width="64" height="64" alt="CSS3" title="CSS3" /></p>
	</body>
</html>
