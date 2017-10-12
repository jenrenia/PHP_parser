function start_handler(){
	$.ajax({
	    data: 'number=' + document.getElementById('input_field').value,
	    url: 'handler.php',
	    method: 'POST',
	    success: function(msg) {
	        document.getElementById('main_content').innerHTML = msg;
	    }
	});
}

function clear_handler(){
	document.getElementById('main_content').innerHTML = '';
}

function getDownload(){
	$.ajax({
	    data: 'download=1',
	    url: 'handler.php',
	    method: 'POST',
	    success: function(msg) {
	        document.getElementById('link').innerHTML = '<a href = "' + msg + '">Download</a>';
	    }
	});
}