<?php
/*Оригинальный скрипт для приложения поиска турников "Turnichok"*/

include_once('config.php');

//Подключаемся к базе
$mysqli = mysqli_connect($config['sqlhost'], $config['sqlusername'], $config['sqlpass'], $config['sqldb'])or DIE('Error: ' . mysqli_error($mysqli));
mysqli_set_charset($mysqli, "utf8");

if(isset($_REQUEST['add'])) {
	echo addHBar($_REQUEST['adminpass'], $_REQUEST['latitude'], $_REQUEST['longitude'], $_REQUEST['description'], @$_REQUEST['imgs']);
}

if(isset($_REQUEST['get'])) {
	echo getHBars(@$_REQUEST['latitude'], @$_REQUEST['longitude']);
}

if(isset($_REQUEST['del'])) {
	echo removeHBar($_REQUEST['id'], $_REQUEST['adminpass']);
}
mysqli_close($mysqli);

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
		$q = "SELECT * FROM `".$config['sqlprefix']."hBars` WHERE `latitude` LIKE ".$latitude." AND `longitude` LIKE ".$longitude;
		if(mysqli_num_rows(mysqli_query($mysqli, $q)) == 0) {
			$q = "INSERT INTO `".$config['sqlprefix']."hBars`(`latitude`, `longitude`, `description`, `imgs`) VALUES (".$latitude.",".$longitude.",'".$description."','".$imgs."')";
			mysqli_query($mysqli, $q)or DIE('OH NO! I did not want to kill him : '.mysqli_error($mysqli));
			return 'Success!';
		} else {
			return 'Data is duplicated!';
		}
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
		while($res = mysqli_fetch_assoc($resq)) {
			$result[] = $res;
		}
		return json_encode($result, JSON_UNESCAPED_UNICODE);
	}
}

function removeHBar($id, $adminpass) {
	global $mysqli, $config;
	if($adminpass != $config['adminPass']) {
		return 'Wrong pass!';
	} else {
		$id = mysqli_real_escape_string($mysqli, $id);
		$q = "SELECT * FROM `".$config['sqlprefix']."hBars` WHERE `id`=".$id;
		if(mysqli_num_rows(mysqli_query($mysqli, $q)) != 0) {
			$q = "DELETE FROM `".$config['sqlprefix']."hBars` WHERE `id` = ".$id;
			mysqli_query($mysqli, $q)or DIE('OH NO! I did not want to kill him : '.mysqli_error($mysqli));
			return 'Success!';
		} else {
			return 'Null';
		}
	}
}
?>