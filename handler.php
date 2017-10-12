
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
					foreach ($html->find('.article') as $k => $v) {
						$d = $v->find('a');
						echo $d[0]->href;
					}
				  	foreach($html->find('.article') as $key => $a){
				  		if($key + 1 <= $number){
				  			//write to session for possible further saving to file
				  			$_SESSION['key' . $key]['number'] = $key;
				  			$_SESSION['key' . $key]['parsing_time'] = date('l jS \of F Y h:i:s A');
				  			$_SESSION['key' . $key]['news_time'] = strval($html->find('.article__time')[$key]);
				  			$_SESSION['key' . $key]['title'] = strval($a->find('a')[0]->href);
				  			$_SESSION['key' . $key]['body'] = $a->href;
				  			$_SESSION['key' . $key]['sings'] = strval($html->find('.article__subtitle')[$key]);
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
				  			$_SESSION['key' . $key]['title'] = $a->href;
				  			$_SESSION['key' . $key]['body'] = $a->plaintext;
				  			$_SESSION['key' . $key]['sings'] = strval($html->find('.article__subtitle')[$key]);
				  			//preparing string out for main div
					    	$parsed_string = $parsed_string . '<tr><td>'. $key . '</td><td>' . date('l jS \of F Y h:i:s A') 
					    	.'</td><td>' . $html->find('.article__time')[$key] . '</td><td><a href="'. $a->find('a')[0]->href .'">'
					    	.$a->plaintext.'</a></td><td>' . $html->find('.article__subtitle')[$key] .'</td></tr>';
				  	}
			  	}  	
			}
			return $parsed_string . "</table>";
		}
		public function getDownload($key, $buffer){	
			if(isset($key)){
				$fp = fopen('download_temp/' . $_SERVER['REQUEST_TIME'] . '.csv', 'w');
				print_r($buffer);
				foreach ($buffer as $fields) {
				    fputcsv($fp, $fields);
				}
				fclose($fp);
			}
		}

		public function writer($string){
			echo $string;
		}

	}

	$handler = new Handler;

	session_start();

	if(isset($_POST['number'])){
		$handler->writer($handler->getNews($_POST['number']));
	}

	$buffer = $_SESSION;
	if (isset($_POST['download'])) {
		$handler->writer($handler->getDownload($_POST['download'], $buffer));
	}
?>
