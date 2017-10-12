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

var file_link;

function get_download(){
	$.ajax({
	    data: 'download=1',
	    url: 'handler.php',
	    method: 'POST',
	    success: function(msg) {
	    	file_link = msg;
	        document.getElementById('link').innerHTML = '<a href = "' + msg + '">Download</a>';
	        document.getElementById('mail').innerHTML = '<input id = "mail_form"></input><button onClick = "mail_handler()">Mail</button>';
	    }
	});
}

function mail_handler(){
	$.post( "handler.php", { file_link: "John", reciever: "2pm" },function(data){//cheack if sended
        document.getElementById('mail').innerHTML = "Sent";
    });
}

function save_to_db(){
	$.ajax({
	    data: 'save=1',
	    url: 'handler.php',
	    method: 'POST',
	    success: function(msg) {
	    	file_link = msg;
	        document.getElementById('link').innerHTML = msg;
	    }
	});
}

function get_saved(){
	$.ajax({
	    data: 'saved=1',
	    url: 'handler.php',
	    method: 'POST',
	    success: function(msg) {
	    	file_link = msg;
	        document.getElementById('link').innerHTML = msg;
	    }
	});
}
function select_handler(){
	var e = document.getElementById("select");
	var strUser = e.options[e.selectedIndex].value;
	$.ajax({
	    data: 'show=' + strUser,
	    url: 'handler.php',
	    method: 'POST',
	    success: function(msg) {
	    	file_link = msg;
	        document.getElementById('main_content').innerHTML = msg;
	    }
	});
}