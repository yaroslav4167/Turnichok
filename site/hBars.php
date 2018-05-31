<?php
/*Оригинальный скрипт для приложения поиска турников "Turnichok"*/

$config = array(
		'sqlhost' => 'localhost',
		'sqlusername' => '',
		'sqlpass' => '',
		'sqldb' => '',
		'sqlprefix' => 'HB_',

		'adminPass' => '12345'
);

//Подключаемся к базе
$mysqli = mysqli_connect($config['sqlhost'], $config['sqlusername'], $config['sqlpass'], $config['sqldb'])or DIE('Error: ' . mysqli_error($mysqli));

if(isset($_REQUEST['add'])) {
	echo addHBar($_REQUEST['adminpass'], $_REQUEST['latitude'], $_REQUEST['longitude'], $_REQUEST['description'], @$_REQUEST['imgs']);
}

if(isset($_REQUEST['get'])) {
	echo getHBars(@$_REQUEST['latitude'], @$_REQUEST['longitude']);
}

//Добавление турников
function addHBar($adminpass, $latitude, $longitude, $description, $imgs = '') {
	global $mysqli, $config;
	if($adminpass != $config['adminPass']) {
		return 'Wrong pass!';
	} else {
		$latitude = mysqli_real_escape_string($mysqli, $latitude);
		$longitude = mysqli_real_escape_string($mysqli, $longitude);
		$description = mysqli_real_escape_string($mysqli, $description);
		$imgs = mysqli_real_escape_string($mysqli, $imgs);
		$q = "INSERT INTO `".$config['sqlprefix']."hBars`(`latitude`, `longitude`, `description`, `imgs`) VALUES (".$latitude.",".$longitude.",'".$description."','".$imgs."'')";
		mysqli_query($mysqli, $q);
		return 'Success!';
	}

}

//Вывод турников в диапазоне
function getHBars($latitude = 0, $longitude = 0) {
	global $mysqli, $config;
	if($latitude != 0 or $longitude != 0) {
		//Тут вывод в диапазоне
	} else {
		//В ином случае выводим всё
		$q = "SELECT * FROM `".$config['sqlprefix']."hBars`";
		$resq = mysqli_query($mysqli, $q)or DIE('Error: ' . mysqli_error($mysqli));
		$result = array();
		while($res = mysqli_fetch_array($resq)) {
			$result[] = $res;
		}
		return json_encode($result);
	}
}
?>