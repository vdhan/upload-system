var bytesUploaded = 0;
var bytesTotal = 0;
var previousBytesLoaded = 0;
var intervalTimer = 0;
var t = 0;
var fname = "";

function _id(id) {
	return document.getElementById(id);
}

function Output(msg) {
	var m = _id("messages");
	m.innerHTML = msg;
}

$(function() {			
	$( "#dialog-message" ).dialog({
		modal: true,
		autoOpen: false,
		show: 'clip',
		hide: 'explode',
		width: 450,
		buttons: {
			Close: function() {
				$( this ).dialog( "close" );
			}
		}
	});
});

function ParseFile(file) {
	Output("");
		
	// display an image
	if (file.type.indexOf("image") == 0) {
		var reader = new FileReader();
		reader.onload = function(e) {
			Output("<p><strong>" + file.name + ":</strong><br />" +
				'<img src="' + e.target.result + '" /></p>');
		}
		reader.readAsDataURL(file);
	}

	// display text
	if (file.type.indexOf("text") == 0) {
		var reader = new FileReader();
		reader.onload = function(e) {
			Output("<p><strong>" + file.name + ":</strong></p><pre>" +
				e.target.result.replace(/</g, "&lt;").replace(/>/g, "&gt;") +
				"</pre>");
		}
		reader.readAsText(file);
	}
}

function fileSelected() {
	var file = _id('fileToUpload').files[0];
	var fileSize = 0;
	
	if (file.size >= 1000000) {
		fileSize = (Math.round(file.size * 100 / 1000000) / 100).toString() + ' MB';
	}
	else if(file.size >= 1000) {
		fileSize = (Math.round(file.size * 100 / 1000) / 100).toString() + ' KB';
	}
	else
	{
		fileSize = (file.size).toString() + ' B';
	}
	
	_id('fileInfo').style.display = 'block';
	_id('fileName').innerHTML = 'Tên: ' + file.name;
	_id('fileSize').innerHTML = 'Kích thước: ' + fileSize;
	_id('fileType').innerHTML = 'Kiểu tập tin: ' + file.type;
	
	ParseFile(file);
	fname = file.name;
	
	if(file.size >= 209715200)
	{
		t = 0;
	}
	else
	{
		t = 1;
	}
}

function uploadFile() {
	previousBytesLoaded = 0;
	_id('uploadResponse').style.display = 'none';
	_id('progressNumber').innerHTML = '';
	
	var progressBar = _id('progressBar');			
	progressBar.style.width = '0px';
	
	var fd = new FormData();
	fd.append("author", "Hoang An");
	fd.append("name", "Hoang An 's uploader");
	fd.append("fileToUpload", _id('fileToUpload').files[0]);
	
	if(t == 1)
	{
		progressBar.style.display = 'block';
		
		var xhr = new XMLHttpRequest();
		xhr.upload.addEventListener("progress", uploadProgress, false);
		xhr.addEventListener("load", uploadComplete, false);
		xhr.addEventListener("error", uploadFailed, false);
		xhr.addEventListener("abort", uploadCanceled, false);
		xhr.open("POST", "upload.php");
		xhr.send(fd);
		intervalTimer = setInterval(updateTransferSpeed, 500);
	}
	else
	{
		alert("File not found or File Size must < 200 MB");
	}
}

function updateTransferSpeed() {
	var currentBytes = bytesUploaded;
	var bytesDiff = currentBytes - previousBytesLoaded;
	
	if (bytesDiff == 0) {
		return;
	}
	
	previousBytesLoaded = currentBytes;
	bytesDiff = bytesDiff * 2;
	var bytesRemaining = bytesTotal - previousBytesLoaded;
	var secondsRemaining = bytesRemaining / bytesDiff;
	var speed = "";
	
	if (bytesDiff > 1024 * 1024) {
		speed = (Math.round(bytesDiff * 100/(1024*1024))/100).toString() + 'MBps';
	}
	else if (bytesDiff > 1024) {
		speed =  (Math.round(bytesDiff * 100/1024)/100).toString() + 'KBps';
	}
	else {
		speed = bytesDiff.toString() + 'Bps';
	}
	
	_id('transferSpeedInfo').innerHTML = speed;
	_id('timeRemainingInfo').innerHTML = '| ' + secondsToString(secondsRemaining);
}

function secondsToString(seconds) {
	var h = Math.floor(seconds / 3600);
	var m = Math.floor(seconds % 3600 / 60);
	var s = Math.floor(seconds % 3600 % 60);
	return ((h > 0 ? h + ":" : "") + (m > 0 ? (h > 0 && m < 10 ? "0" : "") + m + ":" : "0:")
		+ (s < 10 ? "0" : "") + s);
}

function uploadProgress(evt) {
	if (evt.lengthComputable) {
		bytesUploaded = evt.loaded;
		bytesTotal = evt.total;
		var percentComplete = Math.round(evt.loaded * 100 / evt.total);
		var bytesTransfered = '';
		
		if (bytesUploaded > 1024*1024) {
			bytesTransfered = (Math.round(bytesUploaded * 100/(1024*1024))/100).toString() + 'MB';
		}
		else if (bytesUploaded > 1024) {
			bytesTransfered = (Math.round(bytesUploaded * 100/1024)/100).toString() + 'KB';
		}
		else {
			bytesTransfered = (Math.round(bytesUploaded * 100)/100).toString() + 'Bytes';
		}
		
		_id('progressNumber').innerHTML = percentComplete.toString() + '%';
		_id('progressBar').style.width = (percentComplete * 5).toString() + 'px';
		_id('transferBytesInfo').innerHTML = bytesTransfered;
	}
	else {
		_id('progressBar').innerHTML = 'Không xác định được';
	}
}

function uploadComplete(evt) {
	clearInterval(intervalTimer);
	_id('uploadForm').style.display = 'none';
	
	var r = _id('uploadResponse');
	r.innerHTML = "<p><strong>Tập tin tải lên thành công: " + fname + "</strong></p>" + r.innerHTML;
	r.style.display = 'block';
	
	var dialog = _id('dialog-message');
	dialog.innerHTML = evt.target.responseText;
	fname = "Chia sẻ " + fname;
	$( "#dialog-message" ).dialog( "option", "title", fname );
}

function uploadFailed(evt) {
	clearInterval(intervalTimer);
	alert("Lỗi xảy ra khi đang tải lên.");
}

function uploadCanceled(evt) {
	clearInterval(intervalTimer);
	alert("Tải lên bị dừng bởi người dùng hoặc trình duyệt ngắt kết nối.");
}

function Bclick() {
	$( "#dialog-message" ).dialog("open");
}