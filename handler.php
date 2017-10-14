
<?php
	error_reporting(0);

	class Handler{
		public function get_news($number){
			$_SESSION = array(array(array()));
			include 'libraries/simple_html_dom.php';
			$html = file_get_html('http://www.pravda.com.ua/rus/news/');
			$html = $html->find('.block_news_all');
			$html = str_get_html($html[0]);         
			if($html->innertext!=''){
				foreach($html->find('.article') as $key => $a){
					$keyinc = $key + 1;
					$array[$key]['number'] = $keyinc;
					$array[$key]['parsing_time'] = date('jS F Y h:i:s A');
					$array[$key]['news_time'] = $html->find('.article__time')[$key]->innertext;
					$array[$key]['title'] = $a->find('a')[0]->innertext;
					//$temp = str_get_html($a->outertext);
					//$temp1 = $temp->find('div[class=article article_bold]');
					//$array[$key]['bold'] = $temp1;
					$array[$key]['body'] = $html->find('.article__subtitle')[$key]->innertext;
					$array[$key]['href'] = $a->find('a')[0]->href;
					$array[$key]['singns'] = $a;
					//print_r($array[$key]['bold']);
				}
			  	$table_html = $this->table_former($array, $number);
			}
			return $table_html;
		}

		public function table_former($array, $number = 0){
			$output_string = "
			<table class='table table-bordered'>
			<thead>
		      <tr>
		        <th>Номер</th>
		        <th>Время и дата парсинга</th>
		        <th>Время новости</th>
		        <th>Заголовок</th>
		        <th>Текст новости</th>
		        <th>Метки</th>
		      </tr>
		    </thead><tbody>";
		    if($number != 0){
				foreach($array as $key => $a){
			  		if($key + 1 <= $number){
			  			$num = $key + 1;
			  			//preparing string out for main div
				    	$output_string = $output_string . '<tr><td>'. $a['number'] . '</td><td>' . $a['parsing_time'] .
				    	'</td><td>' . $a['news_time'] . '</td><td><a target="_blank" href="http://www.pravda.com.ua'. $a['href'] .'">' ;
				    	if (isset($a['bold'])) {
				    		$output_string = $output_string . '<b>' . $a['title'] . '</b>';
				    	}
				    	else{
				    		$output_string = $output_string . $a['title'];
				    	}
				    	$output_string = $output_string . '</a></td><td>' . $a['body'] .'</td><td>' . '</td></tr>';
				    	$this->write_session($a, $key);
			    	}
			    }
			}
		    else{
		    	//print_r($array);
		    	foreach($array as $key => $a){
					
		  			//preparing string out for main div
			    	$output_string = $output_string . '<tr><td>'. $a['number'] . '</td><td>' . $a['parsing_time'] .
				    	'</td><td>' . $a['news_time'] . '</td><td><a target="_blank" href="http://www.pravda.com.ua'. $a['href'] .'">' ;
				    	if (isset($a['bold'])) {
				    		$output_string = $output_string . '<b>' . $a['title'] . '</b>';
				    	}
				    	else{
				    		$output_string = $output_string . $a['title'];
				    	}
				    	$output_string = $output_string . '</a></td><td>' . $a['body'] .'</td><td></td></tr>';
				    	$this->write_session($a, $key);
			    }
		  	}
		  	return $output_string . "</tbody></table>";;
		}

		public function write_session($a, $key){	
			//write to session for possible further saving to file
  			$_SESSION['key' . $key]['number'] = $a['number'];
  			$_SESSION['key' . $key]['parsing_time'] = $a['parsing_time'];
  			$_SESSION['key' . $key]['news_time'] = strval($a['news_time']);
  			$_SESSION['key' . $key]['title'] = strval($a['title']);
  			$_SESSION['key' . $key]['href'] = strval($a['href']);
  			$_SESSION['key' . $key]['body'] = strval($a['body']);
  			$_SESSION['key' . $key]['singns'] = '';
			return ;
		}

		public function get_download($buffer){	
			$dir = 'download_temp/' . microtime(true) . '.csv';
			$fp = fopen($dir, 'w');
			foreach ($buffer as $fields) {
			    fputcsv($fp, $fields);
			}
			fclose($fp);
			return $dir;
		}

		public function writer($string){
			echo $string;
		}

		public function sendMail($file_link, $reciever){
			$url = 'domain.xx' . $file_link;
			mail($reciever, 'Your saved news from PHP parser.', $url);
			return 1;
		}

		public function save_to_db(){
			include 'configs/config.php';
			$conn = new mysqli($servername, $username, $password, $dbname);
			if($conn->connect_error){
			    die("Connection failed: " . $conn->connect_error);
			}	 
			$sql = "SELECT 1 FROM news LIMIT 1";
			$result = $conn->query($sql);
			if($result == TRUE){
				foreach ($_SESSION as $key => $value){
					$news_time = strval($value['news_time']);
					$parsing_time = strval($value['parsing_time']);
					$title = strval($value['title']);
					$body = strval($value['body']);
					$href = strval($value['href']);
					$sql = "INSERT INTO news (number, parsing_time, news_time, title, href, body, singns)
					VALUES ({$value['number']}, '{$parsing_time}', '{$news_time}', '{$title}', '{$href}', '{$body}', '1')";
					$conn->query($sql);
				}
			}
			else{
				$this->create_table();
				$sql = "SELECT 1 FROM news LIMIT 1";
				$result = $conn->query($sql);
				if($result == TRUE){
					foreach ($_SESSION as $key => $value){
						$news_time = strval($value['news_time']);
						$parsing_time = strval($value['parsing_time']);
						$title = strval($value['title']);
						$body = strval($value['body']);
						$href = strval($value['href']);
						$sql = "INSERT INTO news (number, parsing_time, news_time, title, href, body, singns)
						VALUES ({$value['number']}, '{$parsing_time}', '{$news_time}', '{$title}', '{$href}', '{$body}', '1')";
						$conn->query($sql);
					}
				}
				$conn->close();
			}
		}
		public function create_table(){
			include 'configs/config.php';
			$conn = new mysqli($servername, $username, $password, $dbname);
			if($conn->connect_error){
			    die("Connection failed: " . $conn->connect_error);
			}
			$sql = "CREATE TABLE news (
					  id int(10) NOT NULL,
					  number int(10) NOT NULL,
					  parsing_time varchar(30) NOT NULL,
					  news_time varchar(30) NOT NULL,
					  title varchar(255) NOT NULL,
					  body longtext NOT NULL,
					  singns varchar(255) NOT NULL,
					  href varchar(255) NOT NULL
					) ENGINE=InnoDB DEFAULT CHARSET=latin1";

			if($conn->query($sql) === TRUE){
			    echo "It's a first query.Table created.";
			}else{
			    echo "Error creating table: " . $conn->error;
			}

			$sql = "ALTER TABLE `news` ADD PRIMARY KEY (`id`)";

			if($conn->query($sql) === TRUE){
			    echo "It's a first query.Table created.";
			}else{
			    echo "Error creating table: " . $conn->error;
			}

			$sql = "ALTER TABLE `news` MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1";

			if($conn->query($sql) === TRUE){
			    echo "It's a first query.Table created.";
			}else{
			    echo "Error creating table: " . $conn->error;
			}
			return;
		}
		public function get_saved(){
			include 'configs/config.php';
			$conn = new mysqli($servername, $username, $password, $dbname);
			if($conn->connect_error){
			    die("Connection failed: " . $conn->connect_error);
			}	 
			$sql = "SELECT parsing_time FROM news GROUP BY parsing_time";
			$result = $conn->query($sql);
			$output_string = "<select id = 'select' onChange = 'select_handler()' ><option>Choose date:</option>";
			if ($result->num_rows > 0){
			    // output data of each row
			    while($row = $result->fetch_assoc()) {
			        $output_string = $output_string .  '<option value = "' . $row['parsing_time'] . '">' . $row["parsing_time"] . "</option>";
			    }
			} 
			else{
			    echo "0 results";
			}
			$conn->close();
			$output_string = $output_string . '</select>';
			return $output_string;
		}	

		public function show_saved($date){
			include 'configs/config.php';
			$conn = new mysqli($servername, $username, $password, $dbname);
			if($conn->connect_error){
			    die("Connection failed: " . $conn->connect_error);
			}	 
			$sql = "SELECT * FROM news WHERE parsing_time LIKE '$date'";
			$result = $conn->query($sql);
			while($row = $result->fetch_assoc()){
			    $array[] = $row; // Inside while loop
			}
			if (isset($array)) {
				$table_html = $this->table_former($array, 0);
			}
			else{
				$table_html = "No data";
			}
			return $table_html;
		}			
	}

	$handler = new Handler;

	session_start();

	if(isset($_POST['number'])){
		$handler->writer($handler->get_news($_POST['number']));
	}

	$buffer = $_SESSION;
	if (isset($_POST['download'])) {
		$handler->writer($handler->get_download($buffer));
	}

	if (isset($_POST['file_link'])&&isset($_POST['reciever'])) {
		$handler->writer($handler->sendMail($_POST['file_link'], $_POST['reciever']));
	}

	if (isset($_POST['save'])) {
		$handler->writer($handler->save_to_db());
	}

	if (isset($_POST['saved'])) {
		$handler->writer($handler->get_saved());
	}

	if (isset($_POST['show'])) {
		$handler->writer($handler->show_saved($_POST['show']));
	}
?>
