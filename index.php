<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
<?php
	include 'libraries/simple_html_dom.php';
	$html = file_get_html('http://www.pravda.com.ua/rus/news/');
	
	$html = $html->find('.block_news_all');//str_get_html($html->innertext);
	$html = str_get_html($html[0]);
	echo "<table>";
	echo "<tr><td>Номер</td><td>Время и дата парсинга</td><td>Время новости</td><td>Заголовок</td><td>Текст новости</td><td>Метки</td></tr>";
	if($html->innertext!='' and count($html->find('a'))){
	  	foreach($html->find('.article') as $key => $a){
	    	echo '<tr><td>'. $key . '</td><td>' . date('l jS \of F Y h:i:s A') 
	    	.'</td><td>' . $html->find('.article__time')[$key] . '</td><td><a href="'.$a->href.'">'
	    	.$a->plaintext.'</a></td><td>' . $html->find('.article__subtitle')[$key] .'</td></tr>';
	  	}  	
	}
	echo "</table>";
?>