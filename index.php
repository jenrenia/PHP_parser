<html>
	<head>
		<title>News parser</title>
		<meta http-equiv = "Content-Type" content = "text/html; charset=windows-1251">
		<link rel = "stylesheet" type = "text/css" href = "styles/css/style.css" >
		<script src="js/jquery-3.2.1.min.js"></script>
		<script src="js/script.js"></script>
		<div class = "header">
			<div class = "input_fields">
				<input id = "input_field"></input>
				<button onClick = "start_handler()" class = "start">Start</button>
				<button onClick = "clear_handler()" class = "clear">Clear</button>
				<button onClick = "get_download()">Save</button>
				<button onClick = "get_saved()">Show saved</button>
				<button onClick="save_to_db()">Save do db</button>
			</div>
			<div class = "link" id = "link">

			</div>
			<div class = "mail" id = "mail">

			</div>
		</div>
	</head>
	<body>
		<div id = "main_content" class = "main_content">
			
		</div>
	</body>
</html>