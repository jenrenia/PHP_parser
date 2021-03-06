function start_handler(){
	if (Number.isInteger(parseInt(document.getElementById('input_field').value)) || document.getElementById('input_field').value == 0){
	    $.ajax({
		    data: 'number=' + document.getElementById('input_field').value,
		    url: 'handler.php',
		    method: 'POST',
		    success: function(msg) {
		        document.getElementById('main_content').innerHTML = msg;
		    }
		});
	}
	else{
	    document.getElementById('input_field').value = "Enter integer only";
	}
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
	        document.getElementById('link').innerHTML = '<a href = "' + msg + '">Download &nbsp </a><input id = "mail_form"></input><button onClick = "mail_handler()">Mail</button>';
	    }
	});
}

function mail_handler(){
	$.ajax({
	    data: {file_link: file_link, reciever: document.getElementById('mail_form').value},
	    url: 'handler.php',
	    method: 'POST',
	    success: function(msg) {
	    	file_link = msg;
	        document.getElementById('link').innerHTML = 'Sent';
	    }
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
	get_saved();
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