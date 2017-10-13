<html>
	<head>
		<title>News parser</title>
		<meta http-equiv = "Content-Type" content = "text/html; charset=windows-1251">
		<link rel = "stylesheet" type = "text/css" href = "styles/css/style.css" >
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap-theme.min.css"> 
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script> 
		<script src="js/jquery-3.2.1.min.js"></script>
		<script src="js/script.js"></script>
		<div class = "header">
			<!--<nav class="navbar navbar-inverse">
		  		<div class="container-fluid">
			    	<div class="navbar-header">
			      		<a class="navbar-brand aling-middle" href="#">WebSiteName</a>
		    		</div>
			    	<div class="nav navbar-nav">
				      	<div class="btn-group align-meddle">
							<button style="height:100%" onClick = "start_handler()" class="btn btn-primary">Start</button>
							<button style="height:100%" onClick = "clear_handler()" class="btn btn-primary">Clear</button>
							<button style="height:100%" onClick = "get_download()" class="btn btn-primary">Save</button>
							<button style="height:100%" onClick = "get_saved()" class="btn btn-primary">Show saved</button>
							<button style="height:100%" onClick = "save_to_db()" class="btn btn-primary">Save do db</button>
							<input id = "input_field" type="text" class="form-control" name="msg" placeholder="Enter amount">
						</div>
			    	</div>
			  	</div>
			</nav>	-->
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
		<div class = "container main_content">
			<div class = "row">
				<div class = "col-lg-4"></div>
				<div id = "main_content" class = "col-lg-6"></div>
				<div class = "col-lg-3"></div>
			</div>
		</div>
	</body>
</html>