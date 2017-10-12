
<?php
	class Handler{
		public function getNews($number){
			$_SESSION = array(array(array()));
			include 'libraries/simple_html_dom.php';
			$html = file_get_html('http://www.pravda.com.ua/rus/news/');
			$html = $html->find('.block_news_all');
			$html = str_get_html($html[0]);
			$parsed_string = "<table><tr><td>Номер</td><td>Время и дата парсинга</td>" . 
			"<td>Время новости</td><td>Заголовок</td><td>Текст новости</td><td>Метки</td></tr>";
			if($html->innertext!=''){
				if($number != 0){
				  	foreach($html->find('.article') as $key => $a){
				  		if($key + 1 <= $number){
				  			//write to session for possible further saving to file
				  			$_SESSION['key' . $key]['number'] = $key;
				  			$_SESSION['key' . $key]['parsing_time'] = date('l jS \of F Y h:i:s A');
				  			$_SESSION['key' . $key]['news_time'] = strval($html->find('.article__time')[$key]);
				  			$_SESSION['key' . $key]['title'] = strval($a->plaintext);
				  			$_SESSION['key' . $key]['href'] = strval($a->find('a')[0]->href);
				  			$_SESSION['key' . $key]['body'] = strval($html->find('.article__subtitle')[$key]);
				  			$_SESSION['key' . $key]['singns'] = strval($html->find('.article__subtitle')[$key]);
				  			//preparing string out for main div
					    	$parsed_string = $parsed_string . '<tr><td>'. $key . '</td><td>' . date('l jS \of F Y h:i:s A') .
					    	'</td><td>' . $html->find('.article__time')[$key] . '</td><td><a href="'. $a->find('a')[0]->href .'">' .
					    	$a->plaintext.'</a></td><td>' . $html->find('.article__subtitle')[$key] .'</td></tr>';
				    	}
				  	}
				}
			  	else{
			  		foreach($html->find('.article') as $key => $a){
			  				//write to session for possible further saving to file
				  			$_SESSION['key' . $key]['number'] = $key;
				  			$_SESSION['key' . $key]['parsing_time'] = date('l jS \of F Y h:i:s A');
				  			$_SESSION['key' . $key]['news_time'] = strval($html->find('.article__time')[$key]);
				  			$_SESSION['key' . $key]['title'] = strval($a->plaintext);
				  			$_SESSION['key' . $key]['href'] = strval($a->find('a')[0]->href);
				  			$_SESSION['key' . $key]['body'] = strval($html->find('.article__subtitle')[$key]);
				  			$_SESSION['key' . $key]['singns'] = strval($html->find('.article__subtitle')[$key]);
				  			//preparing string out for main div
					    	$parsed_string = $parsed_string . '<tr><td>'. $key . '</td><td>' . date('l jS \of F Y h:i:s A') 
					    	.'</td><td>' . $html->find('.article__time')[$key] . '</td><td><a href="'. $a->find('a')[0]->href .'">'
					    	.$a->plaintext.'</a></td><td>' . $html->find('.article__subtitle')[$key] .'</td></tr>';
				  	}
			  	}  	
			}
			return $parsed_string . "</table>";
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
			return $file_link + $reciever;
		}

		public function save_to_db(){
			include 'configs/config.php';
			$conn = new mysqli($servername, $username, $password, $dbname);
			if($conn->connect_error){
			    die("Connection failed: " . $conn->connect_error);
			}	 
			$sql = "SELECT id FROM news WHERE id = 1 ";
			$result = $conn->query($sql);
			if($result->num_rows > 0){
				foreach ($_SESSION as $key => $value) {
					$news_time = strval($value['news_time']);
					$parsing_time = strval($value['parsing_time']);
					$title = strval($value['title']);
					$body = strval($value['body']);
					$href = strval($value['href']);
					$sql = "INSERT INTO news (number, parsing_time, news_time, title, href, body, singns)
					VALUES ({$value['number']}, '{$parsing_time}', '{$news_time}', '{$title}', '{$href}', '{$body}', '1')";
					$conn->query($sql);
					/*if($conn->query($sql) === TRUE){
					    echo "record created successfully";
					}
					else{
					    echo "Error: " . $sql . "<br>" . $conn->error;
					}*/
				}
			}
			else{
				$sql = "CREATE TABLE news (
					  id int(10) NOT NULL,
					  number int(10) NOT NULL,
					  parsing_time varchar(30) NOT NULL,
					  news_time varchar(30) NOT NULL,
					  title varchar(255) NOT NULL,
					  body varchar(255) NOT NULL,
					  singns varchar(255) NOT NULL
					) ENGINE=InnoDB DEFAULT CHARSET=latin1;";

				if($conn->query($sql) === TRUE){
				    echo "It's a first query.Table created.";
				}else{
				    echo "Error creating table: " . $conn->error;
				}

				$conn->close();
			}
		}

		public function get_saved(){
			include 'configs/config.php';
			$conn = new mysqli($servername, $username, $password, $dbname);
			if($conn->connect_error){
			    die("Connection failed: " . $conn->connect_error);
			}	 
			$sql = "SELECT * FROM news GROUP BY parsing_time";
			$result = $conn->query($sql);
			$output_string = "<select>";
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
	}

	$handler = new Handler;

	session_start();

	if(isset($_POST['number'])){
		$handler->writer($handler->getNews($_POST['number']));
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
?>
