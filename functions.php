<?php
//шаблонизатор
function renderTemplate($tpath, $tdata) {
	$content = '';

	if (file_exists($tpath)) { 
		ob_start();
		extract($tdata);
		require($tpath);
		$content = ob_get_clean();
	}
	return $content;
}

//функция вывода цены с разделением на тысячные
function rurNumberFormat(int $price) {
	return number_format($price, 0, '.', ' ') . '<b class="rub">р</b>';
}

//временная зона по умолчанию
date_default_timezone_set("Europe/Moscow");

//функция показывает, сколько осталось до назначенной даты и времени
function timeToFinish() {
	$time = strtotime('tomorrow');
	$today = time();
	$day = $time - $today;
	$time_hours = floor($day / 3600);
	$time_minutes = floor(($day % 3600) / 60);
	$get_time = $time_hours . ':' . $time_minutes;

	return date('H:i', strtotime($get_time));
}

?>