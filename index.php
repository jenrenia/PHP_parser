<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
<?php
	$html = file_get_contents('http://www.pravda.com.ua/rus/news/');
	$pieces = explode('class="news news_all"', $html);
	$html = explode('class="archive-navigation"', $pieces[1]);
	echo $html[0];
?>