<?php

require realpath(__DIR__ . "/") . '/vendor/autoload.php';
require realpath(__DIR__ . "/") . '/vendor/yiisoft/yii2/Yii.php';

$secrets = require realpath(__DIR__ . "/") . "/config/secrets.php";
//$config  = require realpath(__DIR__ . "/") . "/7__include.php";

function writeToLog($text) {
    $log = '[' . date('Y-m-d H:i:s') . '] ' . $text;
    file_put_contents(__DIR__ . '/moveDataFromDBRetail.log', $log . PHP_EOL, FILE_APPEND);
}

// =======================================================================
// =======================================================================
$db_database = 'torg_cd80210_prod';
// Создаём SSH-соединение
$ssh = ssh2_connect('92.53.96.209', 22);
if($ssh){echo "Connection Successful!\n";}
else{echo 'Connection Failed... ' . error_get_last()['message'] . "\n";die();}

$ssh_auth = ssh2_auth_password($ssh, 'cd80210', 'Portretny013');
if($ssh_auth){echo "Authentication Successful!\n";}
else{echo 'Authentication Failed... ' . error_get_last()['message'] . "\n";die();}

//////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////
//if (0) {
//////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////

$backup_file = 'backup_' . date('Y-m-d_H-i-s') . '.sql';
// Создание дампа базы данных
$dump_cmd = "mysqldump -u cd80210_prod -p'MgBcVd3H' cd80210_prod > /tmp/$backup_file 2>&1";
$stream = ssh2_exec($ssh, $dump_cmd);
stream_set_blocking($stream, true);
$output = stream_get_contents($stream);
fclose($stream);

if (!empty($output)) {
    die("Dump creation failed: $output\n");
}
echo "Database dump created: /tmp/$backup_file\n";

// Скачивание файла через SCP
if (ssh2_scp_recv($ssh, "/tmp/$backup_file", $backup_file)) {
    echo "Backup file downloaded: $backup_file\n";
} else {
    die("Failed to download backup file\n");
}

// Удаление временного файла на сервере
ssh2_exec($ssh, "rm -f /tmp/$backup_file");
echo "Temporary file removed from server\n";

echo "Starting database import...\n";

$output_file = 'cleaned_backup.sql';
// Читаем все строки файла
$lines = file($backup_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
if ($lines === false) {
    die("Cannot read input file\n");
}
// Заменяем первую строку
$lines[0]  = '';
$lines[25] = '';
// Записываем обратно в файл
file_put_contents($output_file, implode(PHP_EOL, $lines));
if (is_file($backup_file)) unlink($backup_file);

$username = $secrets['db']['username'];
$password = $secrets['db']['password']; // q12we34r!
//$mysql_path = 'C:\\Users\\Программист ПК\\Downloads\\qwerty\\nginx-1.28.1\\mysql-9.6.0-winx64\\bin\\mysql.exe';
$mysql_path = 'mysql';

$mysqli = new mysqli($secrets['db']['host'], $secrets['db']['username'], $secrets['db']['password']);
// Удаляем базу данных, если существует
/*if (!$mysqli->query("DROP DATABASE IF EXISTS `$db_database`")) {
    echo "Ошибка при удалении базы данных: " . $mysqli->error;
	die();
}
// Создаём новую базу данных
if ($mysqli->query("CREATE DATABASE `$db_database` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci")) {
    echo "База данных '$db_database' успешно создана!\n";
} else {
    echo "Ошибка при создании базы данных: " . $mysqli->error;
}*/

// Создаём команду импорта
$import_cmd = "\"$mysql_path\" -u $username -p\"$password\" $db_database < \"$output_file\" 2>&1";
// Выполняем импорт
$output = shell_exec($import_cmd);

// Проверяем результат
if ($output === null) {
    echo "Import completed successfully!\n";
} elseif (strpos($output, 'ERROR') !== false) {
    die("Import failed with errors:\n$output\n");
} else {
    echo "Import completed with warnings:\n$output\n";
}

if (is_file('cleaned_backup.sql')) unlink('cleaned_backup.sql');

//////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////
//}
//////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////

// =======================================================================
// =======================================================================

$mysqliOld = new mysqli($secrets['db']['host'], $secrets['db']['username'], $secrets['db']['password'], $db_database);
// $mysqliNew = new mysqli(db_host, db_username, db_password, 'user_prod_backend');
$mysqliNew = new mysqli($secrets['db']['host'], $secrets['db']['username'], $secrets['db']['password'], $secrets['db']['dbname']);


if (!$mysqliOld || !$mysqliOld ) {
    die("Ошибка подключения: " . mysqli_connect_error());
}


$companyMaxID = mysqli_query($mysqliNew, "SELECT MAX(id) as company_max_id FROM company");
$companyMaxID = mysqli_fetch_array($companyMaxID);
$contactMaxID = mysqli_query($mysqliNew, "SELECT MAX(id) as contact_max_id FROM contact");
$contactMaxID = mysqli_fetch_array($contactMaxID);
$buildingMaxID = mysqli_query($mysqliNew, "SELECT MAX(id) as building_max_id FROM building");
$buildingMaxID = mysqli_fetch_array($buildingMaxID);
//$landPlotCnt = mysqli_query($mysqliNew, "SELECT COUNT(id) as land_plot_max_id FROM land_plot");
//$landPlotCnt = mysqli_fetch_array($landPlotCnt);
$metroListOld = mysqli_query($mysqliOld, "SELECT * FROM `b_iblock_element` WHERE `IBLOCK_ID` = 11");
//$metroListOld = $mysqliOld->query('SELECT * FROM `b_iblock_element` WHERE `IBLOCK_ID` = 11');
$metroListNew = mysqli_query($mysqliNew, "SELECT * FROM metro");
$contactPositionList = mysqli_query($mysqliNew, "SELECT id, name FROM contact_position WHERE is_active = 1");

$districtListOld = mysqli_query($mysqliOld, "SELECT * FROM `b_iblock_element` WHERE `IBLOCK_ID` = 10");
$realEstateTypeOld = mysqli_query($mysqliOld, "SELECT * FROM `b_iblock_element` WHERE `IBLOCK_ID` = 12");

/*
DELETE FROM `phone` WHERE `id` > 99703;
DELETE FROM `email` WHERE `id` > 53928;
DELETE FROM `website` WHERE `id` > 5793;
DELETE FROM `contact` WHERE `id` > 46283;
DELETE FROM `company` WHERE `id` > 11503;
DELETE FROM `user` WHERE `id` > 83;
DELETE FROM `user_profile` WHERE `id` > 64;
DELETE FROM `user_profile_email` WHERE `id` > 124;
DELETE FROM `user_profile_phone` WHERE `id` > 135;
DELETE FROM `request` WHERE `id` > 10096;
DELETE FROM `building` WHERE `id` > 12275;
DELETE FROM `communications` WHERE `id` > 4725;
DELETE FROM `security` WHERE `id` > 31374;
DELETE FROM `land_plot` WHERE `id` > 12266;
DELETE FROM `parking` WHERE `id` > 31369;
DELETE FROM `lifting_mechanisms` WHERE `id` > 983;
DELETE FROM `building_object` WHERE `id` > 21479;
DELETE FROM `offer` WHERE `id` > 13820;
*/

$metroListOldArr = [];
$realEstateTypeOldArr = [];
$districtListOldArr = [];
$metroListNewArr = [];
$contactPositionListArr = [];

foreach ($metroListOld as $data) {
	$metroListOldArr[$data['ID']] = $data['NAME'];
}
foreach ($realEstateTypeOld as $data) {
	$realEstateTypeOldArr[$data['ID']] = $data['NAME'];
}
foreach ($districtListOld as $data) {
	$districtListOldArr[$data['ID']] = $data['NAME'];
}
foreach ($metroListNew as $data) {
	$metroListNewArr[$data['id']] = $data['metro_title'];
}
foreach ($contactPositionList as $data) {
	$contactPositionListArr[$data['id']] = mb_strtolower($data['name']);
}

// b_iblock_element -
// Перенос звонков //b_iblock_element
$fieldsContact = [
	//'id' => 'ID',
	'nameRu' => 'NAME',
	//'noName' => 'no_name',
	//'status' => 'result',
	'officeAdress' => 'address',
	'updated_at' => 'TIMESTAMP_X',
	'created_at' => 'DATE_CREATE',
	'active' => 'ACTIVE',
	'description' => 'PREVIEW_TEXT',
	'consultant_id' => '41',
	'morph' => 'morph',
	'all_moved_data' => 'all_moved_data',

	'company_department' => '3',
	'company_department_id' => 'depID',
];

$companyListData = ['NAME' => [], 'address' => []];

$lastCompanyInsId = 0;
// $result = $mysqliOld->query('SET NAMES \'utf8\';');
$resultSql = mysqli_query($mysqliOld, 'SELECT bbe.*, GROUP_CONCAT(bbep.VALUE) as phones, GROUP_CONCAT(bbep2.VALUE) as address, GROUP_CONCAT(bbep3.VALUE) as email, GROUP_CONCAT(bbep4.VALUE) as director_name
FROM `b_iblock_element` as bbe
LEFT JOIN `b_iblock_element_property` as bbep ON bbe.`ID` = bbep.`IBLOCK_ELEMENT_ID` AND bbep.`IBLOCK_PROPERTY_ID` IN (565, 566, 573)
LEFT JOIN `b_iblock_element_property` as bbep2 ON bbe.`ID` = bbep2.`IBLOCK_ELEMENT_ID` AND bbep2.`IBLOCK_PROPERTY_ID` = 563
LEFT JOIN `b_iblock_element_property` as bbep3 ON bbe.`ID` = bbep3.`IBLOCK_ELEMENT_ID` AND bbep3.`IBLOCK_PROPERTY_ID` = 570
LEFT JOIN `b_iblock_element_property` as bbep4 ON bbe.`ID` = bbep4.`IBLOCK_ELEMENT_ID` AND bbep4.`IBLOCK_PROPERTY_ID` = 568
WHERE bbe.`IBLOCK_ID` = 40 GROUP BY bbe.`ID`');

$subResult = mysqli_query($mysqliOld, "SELECT bbe.`ID`, GROUP_CONCAT(bbep.VALUE) as tags
	FROM `b_iblock_element_property` as bbep
	JOIN `b_iblock_element` as bbe ON bbe.`ID` = bbep.`IBLOCK_ELEMENT_ID`
	WHERE bbep.`IBLOCK_PROPERTY_ID` = 575
	GROUP BY bbe.`ID`");
$joinedTags = [];

while ($row = $subResult->fetch_assoc()) {
    $joinedTags[$row['ID']] = $row['tags'];
}
/* (код - значение)
// 577 - creation_datetime
576 - type
+ 575 - tags
+ 570 - email
+ 568 - director_name
+ 573 - other_phone
+ 566 - mobile_phone
+ 565 - city_phone
+ 563 - address
SET SESSION sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));
*/

/*
DELETE FROM `phone` WHERE `id` > 100117;
DELETE FROM `email` WHERE `id` > 54029;
# DELETE FROM `website` WHERE `id` > 5929;
DELETE FROM `contact` WHERE `id` > 46881;
DELETE FROM `company` WHERE `id` > 11737;
*/

$loppIndex = 0;

foreach ($resultSql as $data) {
// while ($data = $resultSql->fetch_assoc()) {
//break;
    // var_dump('======================================================================', $data, $joinedTags[$data['ID']]); //var_dump($data['id']); die();
    $sql = "INSERT INTO company (" . implode(", ", array_keys($fieldsContact)) . ") VALUES (";
    $data['0'] = 0;
    $data['1'] = '';
    $data['2'] = '2';
	$data['3'] = '3';
    $data['41'] = '41';
    $data['morph'] = 'company';
	$dataID = $data['ID'];

    foreach ($fieldsContact as $field) {
		if ($field == 'id') {
			$data[$field] = $dataID + $companyMaxID["company_max_id"];
		}
		if ($field == 'depID') {
			$data[$field] = $dataID;
		}
		if ($field == 'ACTIVE') {
            $data['ACTIVE'] = !empty($data['ACTIVE']) && ($data['ACTIVE'] == 'Y') ? 1 : 0;
        }

		if ($field == 'PREVIEW_TEXT' && (strlen($data['PREVIEW_TEXT']) > 65535)) {
            $data['PREVIEW_TEXT'] = trim( str_replace("'", "\'", substr($data['PREVIEW_TEXT'], 0, 65534)), '\\');
        } elseif ($field == 'PREVIEW_TEXT') {
			$data['PREVIEW_TEXT'] = trim( str_replace("'", "\'", $data['PREVIEW_TEXT']), '\\');;
		}
		if ($field == 'NAME') {
            $data['NAME'] = addslashes(!empty($data['NAME']) ? $data['NAME'] : 'Мусор');
        }
		if ($field == 'address') {
            $data['address'] = addslashes(!empty($data['address']) ? $data['address'] : '');
        }
		if ($field == 'NAME' || $field == 'address') {
			if (!empty($data[$field]) && in_array($data[$field], $companyListData[$field])) {
				$duplKey = array_search($data[$field], $companyListData[$field]);
				$data['all_moved_data']['id_duplicate'] = 1;
				if (!empty($duplKey)) $data['all_moved_data']['duplicated_id'] = $duplKey;
			}
		}

        if ($field == 'result') {
            $data['result'] = empty($data['result']) ? 1 : 0;
            $sql .= "'" . $data['result'] .  "',";
        } elseif ($field == 'all_moved_data') {
			if (!empty($joinedTags[$data['ID']])) $data['all_moved_data']['tags'] = $joinedTags[$data['ID']];
            $sql .= "'" . json_encode($data['all_moved_data'] ?? '') . "',";
        } elseif ($field == 'dt_insert') {
            $sql .= !empty($data['dt_insert']) ? "'" . $data['dt_insert'] . "'," : 'null,';
        } elseif ($field == 'dt_update_full') {
            $sql .= !empty($data['dt_update_full']) ? "'" . $data['dt_update_full'] . "',"  : (!empty($data['dt_insert']) ? "'" . $data['dt_insert'] . "'," : 'null,');
        } else {
            $sql .= "'" . $data[$field] .  "',";
        }
    }
	if (!empty($data['address']) && !in_array($data[$field], $companyListData['address'])) {
		$companyListData['address'][$data['ID']] = $data['address'];
	}
	if (!empty($data['NAME']) && !in_array($data[$field], $companyListData['NAME'])) {
		$companyListData['NAME'][$data['ID']] = $data['NAME'];
	}

    $sql = substr($sql, 0, -1) . ")";
    echo '.';// . $sql . "\n\n"; //break; //die();
    $result = mysqli_query($mysqliNew, $sql);
	if (!$result) {
		echo $sql; die();
	}

	$lastCompanyInsId = $mysqliNew->insert_id;
	$lastContactInsId = 0;

	// ADDind FIO, dolzhnost, phones, email
	//for ($i = 1; $i <= 3; $i++) {
		$keyDolzhnost = 0;// array_search(mb_strtolower($data['c_dolzhnost']), $contactPositionListArr);
		$dataToInsert = [
			'company_id' => $lastCompanyInsId,
			'created_at' => $data['DATE_CREATE'] ?? 'null',
			'updated_at' => $data['TIMESTAMP_X'] ?? $data['DATE_CREATE'] ?? 'null',
			'position_id' => !empty($keyDolzhnost) ? $keyDolzhnost : 0,
			'status' => !empty($data['ACTIVE']) && ($data['ACTIVE'] == 'Y') ? 1 : 0,
			'morph' => 'contact',
			'temp_name' => addslashes(!empty($data['director_name']) ? $data['director_name'] : 'Мусор'),
		];

		$sql = "INSERT INTO contact (" . implode(", ", array_keys($dataToInsert)) . ") VALUES (";
		foreach ($dataToInsert as $data2) {
			$sql .= $data2 == 'null' ? "null," : "'" . $data2 .  "',";
		}
		$sql = substr($sql, 0, -1) . ")";
		echo '.';// . $sql . "\n\n"; //break; //die();
		$result = mysqli_query($mysqliNew, $sql);
		if (!$result) {
			echo $sql; die();
		}

		$lastContactInsId = $mysqliNew->insert_id;

		if (!empty($data['phones'])) {
			$dataPhone = explode(",", $data['phones']);
			foreach ($dataPhone as $phone) {
				$dataToInsert = [
					'contact_id' => $lastContactInsId,
					'phone' => preg_replace('/[-()+]/', '', $phone),
					'country_code' => 'RU',
					'created_at' => $data['dt_insert'] ?? null,
					'updated_at' => $data['dt_update_full'] ?? $data['dt_insert'] ?? null,
				];
				if (empty($dataToInsert['created_at'])) unset($dataToInsert['created_at']);
				if (empty($dataToInsert['updated_at'])) unset($dataToInsert['updated_at']);
				$sqlPhone = "INSERT INTO phone (" . implode(", ", array_keys($dataToInsert)) . ") VALUES (";
				foreach ($dataToInsert as $data2) {
					$sqlPhone .= $data2 == 'null' ? "null," : "'" . $data2 .  "',";
				}
				$sqlPhone = substr($sqlPhone, 0, -1) . ")";
				echo '.';// . $sqlPhone . "\n\n"; //break; //die();
				$result = mysqli_query($mysqliNew, $sqlPhone);
				if (!$result) {
					echo $sqlPhone; die();
				}
			}
		}
		if (!empty($data['email'])) {
			$dataEmails = explode(",", $data['email']);
			foreach ($dataEmails as $email) {
				$sqlEmail = "INSERT INTO email (contact_id, email) VALUES (" . $lastContactInsId . ",'" . trim($email, "'") . "')";
				echo '.';// . $sqlEmail . "\n\n"; //break; //die();
				$result = mysqli_query($mysqliNew, $sqlEmail); //die();
				if (!$result) {
					echo $sqlEmail; die();
				}
			}
		}
	//}

	if ($loppIndex % 1000 == 0) { echo '__'; sleep(2); }
	$loppIndex++;
	//break;
}
//die();
echo 'unset';
unset($companyListData); // $6$jOD2gbOh6JaOCYG3$Kf2MEfFsI9KNjtEBBPFUoHvz/Eg8i5fn8iMSLVvjEub/EloHNPlJZvQp6cPtWT4JDWjdZ0YQiTYGDYMFzulnd/
// e10adc3949ba59abbe56e057f20f883e - 123456
sleep(2);

// Перенос агентов //b_iblock_element
$contactListData = ['email' => ['ena@raysarma.ru', 'rent@realtor.ru', 'borzov@realtor.ru', 'atanov@realtor.ru']];
$fieldsUsers = [
	'username' => 'CODE',
	'email' => 'email',
	'status' => 'ACTIVE',
	'created_at' => 'DATE_CREATE',
	'updated_at' => 'TIMESTAMP_X',
	'email_username' => 'email',
	'password_hash' => 'password_hash',
	//'role' => 'role',
	//'phones' => 'phones',
	'restrict_ip_login' => '1_1',

	'user_department' => '3',
	'user_department_id' => 'depID',
];

//$resultSql = $mysqliOld->query('SELECT * FROM `c_users`');
$resultSql = mysqli_query($mysqliOld, 'SELECT bbe.*, GROUP_CONCAT(bbep.VALUE) as phones, GROUP_CONCAT(bbep2.VALUE) as email, GROUP_CONCAT(bbep3.VALUE) as photo
FROM `b_iblock_element` as bbe
LEFT JOIN `b_iblock_element_property` as bbep ON bbe.`ID` = bbep.`IBLOCK_ELEMENT_ID` AND bbep.`IBLOCK_PROPERTY_ID` = 105
LEFT JOIN `b_iblock_element_property` as bbep2 ON bbe.`ID` = bbep2.`IBLOCK_ELEMENT_ID` AND bbep2.`IBLOCK_PROPERTY_ID` = 102
LEFT JOIN `b_iblock_element_property` as bbep3 ON bbe.`ID` = bbep3.`IBLOCK_ELEMENT_ID` AND bbep3.`IBLOCK_PROPERTY_ID` = 613
WHERE bbe.`IBLOCK_ID` = 14 GROUP BY bbe.`ID`');


/*
DELETE FROM `user_profile` WHERE `id` > 64;
DELETE FROM `user_profile_email` WHERE `id` > 124;
DELETE FROM `user_profile_phone` WHERE `id` > 13;
DELETE FROM `user` WHERE `id` > 84;

(код - значение)
102 - Email
105 - Phone
613 - Photo
*/

foreach ($resultSql as $data) {
//while ($data = $resultSql->fetch_assoc()) {
//break;
	// if ($data['user_type'] == 'none') continue;
    //var_dump('======================================================================', $data); //var_dump($data['id']); die();
    $sql = "INSERT INTO user (" . implode(", ", array_keys($fieldsUsers)) . ") VALUES (";
    $data['0'] = 0;
    $data['1_1'] = '1';
    $data['1'] = '';
    $data['2'] = '2';
	$data['3'] = '3';
	$data['NowTimeVar'] = time();
//var_dump(1111111111, class_exists(Yii), Yii::$app);
	foreach ($fieldsUsers as $field) {

        //if (in_array($field, ['clyent_id','area_max'])) {
        //    $data[$field] = intval($data[$field] ?? 0);
        //}
		if ($field == 'password_hash') {
			if (!empty(Yii::$app) && $security = Yii::$app->getSecurity()) {
				$data[$field] = $security->generatePasswordHash($data['user_password']);
			} else {
				//echo "Security component is null"; // die();
			}
			$data[$field] = ''; // TO DO !!!!!!!!!!!!!!!!
			// $data[$field] = Yii::$app->getSecurity()->generatePasswordHash($data['user_password']);
		}
		if ($field == 'ACTIVE') {
            $data['ACTIVE'] = !empty($data['ACTIVE']) && ($data['ACTIVE'] == 'Y') ? 1 : 0;
        }
		if ($field == 'DATE_CREATE' || $field == 'TIMESTAMP_X') {
            $data[$field] = !empty($data[$field]) ? strtotime($data[$field]) : 0;
        }
		if ($field == 'depID') {
			$data[$field] = $data['ID'];
		}

		$sql .= "'" . $data[$field] .  "',";
	}

	$sql = substr($sql, 0, -1) . ")";
    echo '.';// . $sql . "\n\n"; //break; //die();
	if (in_array($data['email'], $contactListData['email'])) continue;

	$result = mysqli_query($mysqliNew, $sql);
	if (!$result) {
		echo $sql; die();
	}

	$lastUserInsId = $mysqliNew->insert_id;

	$sql = "INSERT INTO user_profile (user_id, temp_name) VALUES (" . $lastUserInsId . ",'" . addslashes(!empty($data['NAME']) ? $data['NAME'] : 'Мусор') . "')";
	echo '.';// . $sql . "\n\n"; //break; //die();
	$result = mysqli_query($mysqliNew, $sql);
	if (!$result) {
		echo $sql; die();
	}
	$lastProfileInsId = $mysqliNew->insert_id;

	// Добавляем телефоны юзеру если заполнены
	if (!empty($data['phones'])) {
		$dataPhones = explode(",", $data['phones']);
		foreach ($dataPhones as $phone) {
			if (strlen($phone) < 7) continue;
			$sqlPhone = "INSERT INTO user_profile_phone (user_profile_id, phone) VALUES (" . $lastProfileInsId . ",'" . preg_replace('/[-()+ ]/', '', $phone) . "')";
			echo '.';// . $sqlPhone . "\n\n"; //break; //die();
			$result = mysqli_query($mysqliNew, $sqlPhone); //die();
			if (!$result) {
				echo $sqlPhone; die();
			}
		}
	}
	if (!in_array($data['email'], $contactListData['email'])) {
		$contactListData['email'][$data['ID']] = $data['email'];
	}
	if ($loppIndex % 1000 == 0) { echo '---'; sleep(2); }
	$loppIndex++;
	//break;
}
//die();
echo 'unset';
unset($contactListData);
sleep(2);


// Перенос запроссов (Возможно не нужно) //b_iblock_element
$fieldsRequests = [
	//'id' => 'id',
	'company_id' => 'clyent_id',
	'dealType' => 'deal_type',
	'minArea' => 'area_min',
	'maxArea' => 'area_max',
	'consultant_id' => '41',
	'description' => 'comments', // + c_comments
	'unknownMovingDate' => '1_1',
	'created_at' => 'dt_insert',
	'updated_at' => 'dt_update_full',
	'status' => 'result',
	'name' => '1',
	//'contact_id' => 'clyent_id',
	'all_moved_data' => 'all_moved_data',

	'request_department' => '3',
	'request_department_id' => 'depID',
];

$resultSql = [];// $mysqliOld->query('SELECT * FROM `c_offices_requests`');
// TO DO !!!! TO DO !!!! TO DO !!!! TO DO !!!! TO DO !!!! TO DO !!!! TO DO !!!! TO DO !!!! TO DO !!!!
// TO DO !!!! TO DO !!!! TO DO !!!! TO DO !!!! TO DO !!!! TO DO !!!! TO DO !!!! TO DO !!!! TO DO !!!!
// TO DO !!!! TO DO !!!! TO DO !!!! TO DO !!!! TO DO !!!! TO DO !!!! TO DO !!!! TO DO !!!! TO DO !!!!

/*
DELETE FROM `request` WHERE `id` > 10096;
*/

/* foreach ($resultSql as $data) {
//while ($data = $resultSql->fetch_assoc()) {
//break;
    var_dump('======================================================================', $data); var_dump($data['id']); die();
    $sql = "INSERT INTO request (" . implode(", ", array_keys($fieldsRequests)) . ") VALUES (";
    $data['0'] = 0;
    $data['1_1'] = '1';
    $data['1'] = '';
    $data['2'] = '2';
	$data['3'] = '3';
	$data['41'] = '41';

	foreach ($fieldsRequests as $field) {
        if (in_array($field, ['clyent_id', 'area_min', 'area_max'])) {
            $data[$field] = intval($data[$field] ?? 0);
        }
        if ($field == 'clyent_id') {
            $data[$field] = $data[$field] ?: 2; // $companyMaxID["company_max_id"]
        }
		if ($field == 'depID') {
			$data[$field] = $data['id'];
		}
		if ($field == 'dt_update_full') {
			$data[$field] = $data[$field] ?? $data['dt_insert'] ?? null;
		}
		if ($field == 'comments' && !empty($data['c_comments'])) {
			$data[$field] = $data[$field] . "\n\n\n" . $data['c_comments'];
		}

        if ($field == 'result') {
            $data['status'] = empty($data['result']) ? 1 : 0;
            $sql .= "'" . $data['status'] .  "',";
        } elseif ($field == 'dt_insert' || $field == 'dt_update_full') {
            $sql .= !empty($data[$field]) ? "'" . $data[$field] .  "'," : "null,";
        } else {
            $sql .= "'" . $data[$field] .  "',";
        }
	}
    $sql = substr($sql, 0, -1) . ")";
    echo '.';// . $sql . "\n\n"; //break; //die();
	$result = mysqli_query($mysqliNew, $sql);
	//if (!$result) {
		//echo $sql . "\n\n"; die();
	//}
	if ($loppIndex % 500 == 0) { echo '_-_'; sleep(2); }
	$loppIndex++;
	//break;
} */
//die();


/*
DELETE FROM `building` WHERE `id` > 12275;
DELETE FROM `communications` WHERE `id` > 4725;
DELETE FROM `security` WHERE `id` > 31374;
DELETE FROM `land_plot` WHERE `id` > 12266;
DELETE FROM `parking` WHERE `id` > 31369;
DELETE FROM `lifting_mechanisms` WHERE `id` > 983;
*/

// Перенос объектов (строений) //b_iblock_element
$fieldsBuilding = [
	//'id' => 'id',
		'building_ready_status' => '3',
    'real_estate_type' => 'real_estate_type',
	'building_square' => 'total_area',
	'building_floors_counts' => 'floors_count',
	'building_allowed_electro_power' => 'electro_power',
		//'building_external_decor' => 'finishing',
	'building_in_main_sections' => 'in_main_sections',
		'building_restrictions' => '0', // 'land_use_restrictions',
	'building_attributes' => '1',

	'building_title' => 'NAME',
	'building_location' => 'location_id',
	'building_last_update' => 'TIMESTAMP_X',
		'building_management_company' => '0', //'company_id',
		'building_management_company_type' => '0', // 'company_id_type',
	'building_owner' => 'owner_id',
		'building_owner_type' => '0',
		//'building_line' => 'first_line',
	'building_owner_contact' => 'owner_id',
	'building_type' => 'building_type',
	'building_address' => 'address',
	'building_kadastr' => 'kadastr',
	'building_year_construction' => 'year_build',
		//'building_year_repairs' => 'year_repair',
	'building_description' => 'DETAIL_TEXT',
		'building_infrastructure' => '1',
	// Тип перекрытий
	'building_class' => 'building_class',
	'building_complex_id' => '0',
	'building_in_complex' => '0',
	'building_latitude' => 'latitude',
	'building_longitude' => 'longitude',
	'building_author_id' => 'agent_id',
	'building_from_mkad' => '0',
	'building_photo' => 'photos', //+ photo_plans
		//'building_test_only' => 'test_only',
		//'building_property_documents' => 'building_property_documents',
		//'building_layouts' => 'building_layouts',
	'building_department' => '3',
	'building_department_id' => 'depID',
	'building_all_moved_data' => 'all_moved_data',

	// location_text
];

/*
DELETE FROM `building_object` WHERE `id` > 21479;
DELETE FROM `offer` WHERE `id` > 13820;
*/

// Перенос блоков объектов (строений и участков) //b_iblock_element
$fieldsObjBuilding = [
    //'id' => 'id',
    //'b_obj_photo' => 'photo_block',
    'b_obj_building_id' => 'parent_id',
	'b_obj_offer_id' => '0',
	'b_obj_full_square_min' => 'total_area',
	'b_obj_full_square_max' => 'total_area',
		'b_obj_storage_square_min' => '0',
		'b_obj_storage_square_max' => '0',
	'b_obj_office_square_min' => 'total_area',
	'b_obj_office_square_max' => 'total_area',
		'b_obj_retail_square_min' => '0',
		'b_obj_retail_square_max' => '0',
		'b_obj_technical_square_min' => '0',
		'b_obj_technical_square_max' => '0',
		'b_obj_public_square_min' => '0',
		'b_obj_public_square_max' => '0',
		//'b_obj_floor_type' => 'floor_types',
	'b_obj_floor' => 'floor',
	'b_obj_special_floor' => '0',
	'b_obj_ceiling_height_min' => '0',
	'b_obj_ceiling_height_max' => '0',
		//'b_obj_floor_load_min' => 'load_floor_min',
		//'b_obj_floor_load_max' => 'load_floor_max',
	'b_obj_columns_grid' => '0',
	'b_obj_finishing_renovation' => 'finishing',
		//b_obj_gate
		//'b_obj_cross_docking' => 'cross_docking',
		//'b_obj_layout_features' => 'column_grids',
		//'b_obj_charging_room' => 'charging_room',
		//'b_obj_storage_methods' => 'safe_type',

	'b_obj_department' => '3',
	'b_obj_department_id' => 'depID',
	//'b_obj_all_moved_data' => 'all_moved_data',
];

// Перенос объектов (частков) //b_iblock_element
$fieldsLand = [
	'id' => 'ID',
    'land_plot_square' => 'land_area',
    'land_plot_type' => '0',
    'land_plot_permition' => 'inv_vri',
		//'land_plot_permition_text' => 'field_allow_usage',
	'land_plot_in_main_sections' => 'in_main_sections',
		'land_plot_restrictions' => '0',
	'land_plot_attributes' => '1',
		//land_plot_infrastructure -------- ?????
	'land_plot_title' => 'NAME',
	'land_plot_location' => 'location_id',
	'land_plot_category' => '0',
	'land_plot_last_update' => 'TIMESTAMP_X',
		//'land_plot_management_company' => 'company_id',
		//'land_plot_management_company_type' => 'company_id_type',
	'land_plot_owner' => 'owner_id',
		//'land_plot_owner_type' => 'own_type_land',
	'land_plot_owner_contact' => 'owner_id',
	'land_plot_address' => 'address',
	'land_plot_kadastr' => 'kadastr',
		'land_plot_description' => '1',
	'land_plot_in_complex' => '0',
	'land_plot_complex_id' => '0',
	'land_plot_latitude' => 'latitude',
	'land_plot_longitude' => 'longitude',
	'land_plot_author_id' => 'agent_id',
    'land_plot_department' => '3',
	'land_plot_department_id' => 'depID',
];
$buildingListData = ['address' => []];
$buildingInsIdData = [];

//$result = mysqli_query($mysqliOld, "SELECT * FROM `c_offices` WHERE id = 5");

//////////////////////////////// Локация (код - значение)
// 63  - ID Город
// 65  - ID Района
// 74  - ID Метро
// 131 - Ближайшее метро, метров
// 128 - Ближайшее метро, минут пешком (83 - Расстояние до метро (пешком))
// 129 - Ближайшее метро, название
// 33  - Район
// 489 - Район региона

//$realEstateTypeOldArr
//$districtListOldArr

// Получаем все локации
$resultSqlLocations = mysqli_query($mysqliOld, 'SELECT bbe.ID, GROUP_CONCAT(bbep.VALUE) as city_id, GROUP_CONCAT(bbep2.VALUE) as district_id,
GROUP_CONCAT(DISTINCT bbep3.VALUE) as metro_id, GROUP_CONCAT(DISTINCT bbep4.VALUE) as distance_to_metro, GROUP_CONCAT(DISTINCT bbep5.VALUE) as distance_to_metro_min,
GROUP_CONCAT(DISTINCT bbep6.VALUE) as closest_metro
FROM `b_iblock_element` as bbe
LEFT JOIN `b_iblock_element_property` as bbep  ON bbe.`ID` = bbep.`IBLOCK_ELEMENT_ID`  AND bbep.`IBLOCK_PROPERTY_ID`  = 63
LEFT JOIN `b_iblock_element_property` as bbep2 ON bbe.`ID` = bbep2.`IBLOCK_ELEMENT_ID` AND bbep2.`IBLOCK_PROPERTY_ID` = 65
LEFT JOIN `b_iblock_element_property` as bbep3 ON bbe.`ID` = bbep3.`IBLOCK_ELEMENT_ID` AND bbep3.`IBLOCK_PROPERTY_ID` = 74
LEFT JOIN `b_iblock_element_property` as bbep4 ON bbe.`ID` = bbep4.`IBLOCK_ELEMENT_ID` AND bbep4.`IBLOCK_PROPERTY_ID` = 131
LEFT JOIN `b_iblock_element_property` as bbep5 ON bbe.`ID` = bbep5.`IBLOCK_ELEMENT_ID` AND bbep5.`IBLOCK_PROPERTY_ID` = 128
LEFT JOIN `b_iblock_element_property` as bbep6 ON bbe.`ID` = bbep6.`IBLOCK_ELEMENT_ID` AND bbep6.`IBLOCK_PROPERTY_ID` = 129
WHERE bbe.`IBLOCK_ID` IN (5,24) GROUP BY bbe.`ID`');

$resultSqlLocationsArray = [];
foreach ($resultSqlLocations as $keyL =>  $location) {
	$resultSqlLocationsArray[$location['ID']] = $location;
}

//////////////////////////////// Здание, кусок (код - значение)
// 610,611 - картинки
// 540 - cadastral_number
// 34  - Адрес
// 141 - ID Агента
// 48  - ID Тип недвижемости
// 630 - is_apartments
// 527 - owner_id
// 54  - URL
// 70  - Географическая долгота
// 69  - Географическая широта
// 137, 405, 467 - Год постройки с, Год постройки с (числами), Год постройки точный
// 126 - Категория земель
// 121 - Класс здания
// 91  - Материал стен
// 42, 132, 133, 135 - Назначение
// 51  - Новостройка
// 123 - Обременения
// 37  - Общая площадь
// 93  - Окружение
// 78  - Фотографии планировок
// 87  - Электрическая мощность
// 38  - Этаж
// 39  - Этажность
// 124 - Рельеф
// 80  - Высота потолка (в кусок)
// 615 - Планировка (в кусок)
// 36  - Отделка
// 551 - (Инв Пр) Кадастровый номер участка:
// 550 - (Инв Пр) Наличие строений:
// 553 - (Инв Пр) Площадь участка: (// 136 - Площадь земельного участка)
// 554 - (Инв Пр) ВРИ: - inv_vri
/*
"магазины"
"Для строительства торгового центра"
"для размещения объектов делового назначения, в том числе офисных центров."
"для жилищного строительства и иных объектов культурно-социального назначения"
"4.1 Деловое управление, 4.1 - 4.6; 4.8-4.8.2 Объекты торговли, общепит, рынки"
"эксплуатация земельных участков АТС." <<<===>>> "эксплуатация земельных участков АТС"
"эксплуатация существующего административного здания"
"для строительства здания многофункционального делового комплекса с размещением Центра развития предпринимательства Юго-Западного административного округа города Москвы"
"для размещения дома отдыха"
"земли населенных пунктов для объектов общественно-делового значения; земельные участки, предназначенные для размещения административных зданий (1.2.7)"
"Объекты торговли (4.2)"
"размещение гостиничного комплекса с точкой общественного питания"
"деловое управление"
"Развлекательные мероприятия, Обеспечение занятий спортом в помещениях, Гостиничное обслуживание, Общественное питание, Магазины"
"Магазины (4.4)"
"Для комплексного освоения в целях жилищного строительства"
"Для промышленного строительства складской терминал для оптовой торговли и производственную базу"
"для размещения многофункционального торгово-делового центра"
"блокированная жилая застройка, коммунальное обслуживание"
"4.1 Деловое управление; 6.9 Склады"
"коммунальное обслуживание (3.1);  гостиничное обслуживание (4.7)"
"предоставление коммунальных услуг (3.1.1)"
"производственная деятельность (6.0); склады (6.9); коммунальное обслуживание (3.1)"
"Предоставление коммунальных услуг (3.1.1), Деловое управление (4.1), Обеспечение занятий спортом в помещениях (5.1.2)" <<<====>>> "Предоставление коммунальных услуг (3.1.1); Деловое управление (4.1); Обеспечение занятий спортом в помещениях (5.1.2)"
"эксплуатации здания АТС, складских строений и размещения служебного и аварийного автотранспорта"
"Бытовое обслуживание (3.3); Деловое управление (4.1); Магазины (4.4); Обеспечение занятий спортом в помещениях (5.1.2)"
"связь (6.8)"
"многоэтажная жилая застройка (высотная застройка) (2.6), деловое управление"
"коммунальное обслуживание (3.1), деловое управление (4.1)"
"Эксплуатации части здания под административные цели"
"1.2.7"
*/
// 423 - Тип здания  -- жилое, нежилое


// Получаем все объекты
$resultSql = mysqli_query($mysqliOld, 'SELECT bbe.*, GROUP_CONCAT(bf.FILE_NAME) as photos, GROUP_CONCAT(bf.SUBDIR) as photos_dir,
GROUP_CONCAT(DISTINCT bbep2.VALUE) as kadastr, GROUP_CONCAT(DISTINCT bbep3.VALUE) as address, GROUP_CONCAT(DISTINCT bbep4.VALUE) as agent_id,
GROUP_CONCAT(DISTINCT bbep5.VALUE) as building_type, GROUP_CONCAT(DISTINCT bbep6.VALUE) as is_apartments, GROUP_CONCAT(DISTINCT bbep7.VALUE) as owner_id,
GROUP_CONCAT(DISTINCT bbep8.VALUE) as url, GROUP_CONCAT(DISTINCT bbep9.VALUE) as longitude, GROUP_CONCAT(DISTINCT bbep10.VALUE) as latitude,
GROUP_CONCAT(DISTINCT bbep11.VALUE) as year_build, GROUP_CONCAT(DISTINCT bbep12.VALUE) as land_category, GROUP_CONCAT(DISTINCT bbep13.VALUE) as building_class,
GROUP_CONCAT(DISTINCT bbep14.VALUE) as wall_material, GROUP_CONCAT(DISTINCT bbep15.VALUE) as purpose, GROUP_CONCAT(DISTINCT bbep16.VALUE) as new_building,
GROUP_CONCAT(DISTINCT bbep17.VALUE) as encumbrances, GROUP_CONCAT(DISTINCT bbep18.VALUE) as total_area, GROUP_CONCAT(DISTINCT bbep19.VALUE) as environment,
GROUP_CONCAT(DISTINCT bbep20.VALUE) as photo_plans, GROUP_CONCAT(DISTINCT bbep21.VALUE) as electro_power, GROUP_CONCAT(DISTINCT bbep22.VALUE) as floor,
GROUP_CONCAT(DISTINCT bbep23.VALUE) as floors_count, GROUP_CONCAT(DISTINCT bbep24.VALUE) as relief, GROUP_CONCAT(DISTINCT bbep25.VALUE) as height,
GROUP_CONCAT(DISTINCT bbep26.VALUE) as layout, GROUP_CONCAT(DISTINCT bbep27.VALUE) as finishing, GROUP_CONCAT(DISTINCT bbep28.VALUE) as land_kadastr,
GROUP_CONCAT(DISTINCT bbep29.VALUE) as building_in_plot, GROUP_CONCAT(DISTINCT bbep30.VALUE) as land_area, GROUP_CONCAT(DISTINCT bbep31.VALUE) as real_estate_type,
GROUP_CONCAT(bbep32.VALUE) as inv_vri
FROM `b_iblock_element` as bbe
LEFT JOIN `b_iblock_element_property` as bbep ON bbe.`ID` = bbep.`IBLOCK_ELEMENT_ID` AND bbep.`IBLOCK_PROPERTY_ID` IN (610,611)
LEFT JOIN `b_file` as bf ON bf.`ID` = bbep.`VALUE`
LEFT JOIN `b_iblock_element_property` as bbep2 ON bbe.`ID` = bbep2.`IBLOCK_ELEMENT_ID` AND bbep2.`IBLOCK_PROPERTY_ID` = 540
LEFT JOIN `b_iblock_element_property` as bbep3 ON bbe.`ID` = bbep3.`IBLOCK_ELEMENT_ID` AND bbep3.`IBLOCK_PROPERTY_ID` = 34
LEFT JOIN `b_iblock_element_property` as bbep4 ON bbe.`ID` = bbep4.`IBLOCK_ELEMENT_ID` AND bbep4.`IBLOCK_PROPERTY_ID` = 141
LEFT JOIN `b_iblock_element_property` as bbep5 ON bbe.`ID` = bbep5.`IBLOCK_ELEMENT_ID` AND bbep5.`IBLOCK_PROPERTY_ID` = 48
LEFT JOIN `b_iblock_element_property` as bbep6 ON bbe.`ID` = bbep6.`IBLOCK_ELEMENT_ID` AND bbep6.`IBLOCK_PROPERTY_ID` = 630
LEFT JOIN `b_iblock_element_property` as bbep7 ON bbe.`ID` = bbep7.`IBLOCK_ELEMENT_ID` AND bbep7.`IBLOCK_PROPERTY_ID` = 527
LEFT JOIN `b_iblock_element_property` as bbep8 ON bbe.`ID` = bbep8.`IBLOCK_ELEMENT_ID` AND bbep8.`IBLOCK_PROPERTY_ID` = 54
LEFT JOIN `b_iblock_element_property` as bbep9 ON bbe.`ID` = bbep9.`IBLOCK_ELEMENT_ID` AND bbep9.`IBLOCK_PROPERTY_ID` = 70
LEFT JOIN `b_iblock_element_property` as bbep10 ON bbe.`ID` = bbep10.`IBLOCK_ELEMENT_ID` AND bbep10.`IBLOCK_PROPERTY_ID` = 69
LEFT JOIN `b_iblock_element_property` as bbep11 ON bbe.`ID` = bbep11.`IBLOCK_ELEMENT_ID` AND bbep11.`IBLOCK_PROPERTY_ID` IN (137,405,467)
LEFT JOIN `b_iblock_element_property` as bbep12 ON bbe.`ID` = bbep12.`IBLOCK_ELEMENT_ID` AND bbep12.`IBLOCK_PROPERTY_ID` = 126
LEFT JOIN `b_iblock_element_property` as bbep13 ON bbe.`ID` = bbep13.`IBLOCK_ELEMENT_ID` AND bbep13.`IBLOCK_PROPERTY_ID` = 121
LEFT JOIN `b_iblock_element_property` as bbep14 ON bbe.`ID` = bbep14.`IBLOCK_ELEMENT_ID` AND bbep14.`IBLOCK_PROPERTY_ID` = 91
LEFT JOIN `b_iblock_element_property` as bbep15 ON bbe.`ID` = bbep15.`IBLOCK_ELEMENT_ID` AND bbep15.`IBLOCK_PROPERTY_ID` IN (42,132,133,135)
LEFT JOIN `b_iblock_element_property` as bbep16 ON bbe.`ID` = bbep16.`IBLOCK_ELEMENT_ID` AND bbep16.`IBLOCK_PROPERTY_ID` = 51
LEFT JOIN `b_iblock_element_property` as bbep17 ON bbe.`ID` = bbep17.`IBLOCK_ELEMENT_ID` AND bbep17.`IBLOCK_PROPERTY_ID` = 123
LEFT JOIN `b_iblock_element_property` as bbep18 ON bbe.`ID` = bbep18.`IBLOCK_ELEMENT_ID` AND bbep18.`IBLOCK_PROPERTY_ID` = 37
LEFT JOIN `b_iblock_element_property` as bbep19 ON bbe.`ID` = bbep19.`IBLOCK_ELEMENT_ID` AND bbep19.`IBLOCK_PROPERTY_ID` = 93
LEFT JOIN `b_iblock_element_property` as bbep20 ON bbe.`ID` = bbep20.`IBLOCK_ELEMENT_ID` AND bbep20.`IBLOCK_PROPERTY_ID` = 78
LEFT JOIN `b_iblock_element_property` as bbep21 ON bbe.`ID` = bbep21.`IBLOCK_ELEMENT_ID` AND bbep21.`IBLOCK_PROPERTY_ID` = 87
LEFT JOIN `b_iblock_element_property` as bbep22 ON bbe.`ID` = bbep22.`IBLOCK_ELEMENT_ID` AND bbep22.`IBLOCK_PROPERTY_ID` = 38
LEFT JOIN `b_iblock_element_property` as bbep23 ON bbe.`ID` = bbep23.`IBLOCK_ELEMENT_ID` AND bbep23.`IBLOCK_PROPERTY_ID` = 39
LEFT JOIN `b_iblock_element_property` as bbep24 ON bbe.`ID` = bbep24.`IBLOCK_ELEMENT_ID` AND bbep24.`IBLOCK_PROPERTY_ID` = 124
LEFT JOIN `b_iblock_element_property` as bbep25 ON bbe.`ID` = bbep25.`IBLOCK_ELEMENT_ID` AND bbep25.`IBLOCK_PROPERTY_ID` = 80
LEFT JOIN `b_iblock_element_property` as bbep26 ON bbe.`ID` = bbep26.`IBLOCK_ELEMENT_ID` AND bbep26.`IBLOCK_PROPERTY_ID` = 615
LEFT JOIN `b_iblock_element_property` as bbep27 ON bbe.`ID` = bbep27.`IBLOCK_ELEMENT_ID` AND bbep27.`IBLOCK_PROPERTY_ID` = 36
LEFT JOIN `b_iblock_element_property` as bbep28 ON bbe.`ID` = bbep28.`IBLOCK_ELEMENT_ID` AND bbep28.`IBLOCK_PROPERTY_ID` = 551
LEFT JOIN `b_iblock_element_property` as bbep29 ON bbe.`ID` = bbep29.`IBLOCK_ELEMENT_ID` AND bbep29.`IBLOCK_PROPERTY_ID` = 550
LEFT JOIN `b_iblock_element_property` as bbep30 ON bbe.`ID` = bbep30.`IBLOCK_ELEMENT_ID` AND bbep30.`IBLOCK_PROPERTY_ID` IN (553,136)
LEFT JOIN `b_iblock_element_property` as bbep31 ON bbe.`ID` = bbep31.`IBLOCK_ELEMENT_ID` AND bbep31.`IBLOCK_PROPERTY_ID` = 423
LEFT JOIN `b_iblock_element_property` as bbep32 ON bbe.`ID` = bbep32.`IBLOCK_ELEMENT_ID` AND bbep32.`IBLOCK_PROPERTY_ID` = 554
WHERE bbe.`IBLOCK_ID` IN (5,24) GROUP BY bbe.`ID`');

foreach ($resultSql as $data) {
//while ($data = $resultSql->fetch_assoc()) {
//break;
    //var_dump('======================================================================', $data); //var_dump($data['ID']); die();
    // $fields = !empty($data['is_land']) ? $fieldsLand : $fieldsBuilding;
    $sql = "INSERT INTO building (" . implode(", ", array_keys($fieldsBuilding)) . ") VALUES (";
    $data['0'] = 0;
    $data['1'] = '';
    $data['2'] = '2';
    $data['3'] = '3';
    $data['in_main_sections'] = 3;
	$data['location_id'] = 0;
	$dataID = $data['ID'];
	$data['photos'] = explode(',', $data['photos'] ?? '');
	$data['photo_plans'] = explode(',', $data['photo_plans'] ?? '');
	$data['photos_dir']  = explode(',', $data['photos_dir'] ?? '');

    foreach ($fieldsBuilding as $field) {
        if (in_array($field, ['electro_power', 'total_area', 'floors_count', 'building_type', 'longitude', 'latitude'])) {
            $data[$field] = intval($data[$field] ?? 0);
        }
        if ($field == 'owner_id') {
			$data[$field] = $dataID + ($contactMaxID['contact_max_id'] ?? 0);
        }
        if ($field == 'year_build') {
			// EXPLODE AND GET FIRST NOT EMPTY VALUE FROM 3 
			$data[$field] = explode(',', $data[$field]);
			foreach ($data[$field] as $date) {
				if (!empty($data[$field])) {
					$data[$field] = intval($data[$field]); break;
				}
			}
        }
        if ($field == 'agent_id') {
			$data[$field] = 0;//$dataID + ($contactMaxID['contact_max_id'] ?? 0);
        }
        if ($field == 'inv_vri') {
			$data[$field] = '';
			if ($data[$field] == 'магазины') {
				$data[$field] = ['72'];
			} elseif ($data[$field] == '4.1 Деловое управление, 4.1 - 4.6; 4.8-4.8.2 Объекты торговли, общепит, рынки') {
				$data[$field] = ['69','70','71','72','73','74','76','77','78','79'];
			} elseif ($data[$field] == 'Объекты торговли') {
				$data[$field] = ['70'];
			} elseif ($data[$field] == 'деловое управление') {
				$data[$field] = ['69'];
			} elseif ($data[$field] == 'Развлекательные мероприятия, Обеспечение занятий спортом в помещениях, Гостиничное обслуживание, Общественное питание, Магазины') {
				$data[$field] = ['77','91','35','74','72'];
			} elseif ($data[$field] == 'Магазины (4.4)') {
				$data[$field] = ['72'];
			} elseif ($data[$field] == 'блокированная жилая застройка, коммунальное обслуживание') {
				$data[$field] = ['27','35'];
			} elseif ($data[$field] == '4.1 Деловое управление; 6.9 Склады') {
				$data[$field] = ['69','119'];
			} elseif ($data[$field] == 'коммунальное обслуживание (3.1);  гостиничное обслуживание (4.7)') {
				$data[$field] = ['35','75'];
			} elseif ($data[$field] == 'предоставление коммунальных услуг (3.1.1)') {
				$data[$field] = ['36'];
			} elseif ($data[$field] == 'производственная деятельность (6.0); склады (6.9); коммунальное обслуживание (3.1)') {
				$data[$field] = ['103','119','35'];
			} elseif ($data[$field] == 'Предоставление коммунальных услуг (3.1.1), Деловое управление (4.1), Обеспечение занятий спортом в помещениях (5.1.2)') {
				$data[$field] = ['36','69','91'];
			} elseif ($data[$field] == 'Бытовое обслуживание (3.3); Деловое управление (4.1); Магазины (4.4); Обеспечение занятий спортом в помещениях (5.1.2)') {
				$data[$field] = ['43','69','72','91'];
			} elseif ($data[$field] == 'связь (6.8)') {
				$data[$field] = ['118'];
			} elseif ($data[$field] == 'многоэтажная жилая застройка (высотная застройка) (2.6), деловое управление') {
				$data[$field] = ['30','69'];
			} elseif ($data[$field] == 'коммунальное обслуживание (3.1), деловое управление (4.1)') {
				$data[$field] = ['35','69'];
			} elseif ($data[$field] == '1.2.7') {
				$data[$field] = ['3'];
			} elseif ($data[$field] == 'Для строительства торгового центра') {
				$data[$field] = ['70'];
			} elseif ($data[$field] == 'для размещения объектов делового назначения, в том числе офисных центров.') {
				$data[$field] = ['69'];
			} elseif ($data[$field] == 'для жилищного строительства и иных объектов культурно-социального назначения') {
				$data[$field] = ['23'];
			} elseif ($data[$field] == 'эксплуатация земельных участков АТС' || $data[$field] == 'эксплуатация земельных участков АТС.') {
				$data[$field] = ['80'];
			} elseif ($data[$field] == 'эксплуатация существующего административного здания') {
				$data[$field] = ['58'];
			} elseif ($data[$field] == 'для строительства здания многофункционального делового комплекса с размещением Центра развития предпринимательства Юго-Западного административного округа города Москвы') {
				$data[$field] = ['69'];
			} elseif ($data[$field] == 'для размещения дома отдыха') {
				$data[$field] = ['97'];
			} elseif ($data[$field] == 'земли населенных пунктов для объектов общественно-делового значения; земельные участки, предназначенные для размещения административных зданий (1.2.7)') {
				$data[$field] = ['69'];
			} elseif ($data[$field] == 'размещение гостиничного комплекса с точкой общественного питания') {
				$data[$field] = ['75'];
			} elseif ($data[$field] == 'Для комплексного освоения в целях жилищного строительства') {
				$data[$field] = ['25', '29', '30'];
			} elseif ($data[$field] == 'Для промышленного строительства складской терминал для оптовой торговли и производственную базу') {
				$data[$field] = ['119'];
			} elseif ($data[$field] == 'для размещения многофункционального торгово-делового центра') {
				$data[$field] = ['69'];
			} elseif ($data[$field] == 'эксплуатации здания АТС, складских строений и размещения служебного и аварийного автотранспорта') {
				$data[$field] = ['80'];
			} elseif ($data[$field] == 'Эксплуатации части здания под административные цели') {
				$data[$field] = ['58'];
			}
			$data[$field] = json_encode($data[$field]);
        }
        if ($field == 'real_estate_type') {
			$data[$field] = $data[$field] == 'жилое' ? 2 : ($data[$field] == 'нежилое' ? 1 : 0);
        }
		if ($field == 'DATE_CREATE' || $field == 'TIMESTAMP_X') {
            $data[$field] = !empty($data[$field]) ? strtotime($data[$field]) : 0;
        }
		if ($field == 'ID') {
			$data[$field] = $dataID + ($buildingMaxID['building_max_id'] ?? 0);
		}
		if ($field == 'depID') {
			$data[$field] = $data['CODE'] ?? 0;
		}
		if ($field == 'address') {
			if (in_array($data[$field], $buildingListData[$field])) {
				$duplKey = array_search($data[$field], $buildingListData[$field]);
				$data['all_moved_data']['id_duplicate'] = 1;
				if (!empty($duplKey)) $data['all_moved_data']['duplicated_id'] = $duplKey;
			}
		}
		if ($field == 'NAME') {
            $data['NAME'] = addslashes(!empty($data['NAME']) ? $data['NAME'] : 'Мусор');
        }
        if ($field == 'building_class') {
			$data['building_class'] = 0;
			if ($data['building_class'] == 2) {
				$data['building_class'] = 5;
			} elseif ($data['building_class'] == 3) {
				$data['building_class'] = 2;
			} elseif ($data['building_class'] == 4) {
				$data['building_class'] = 6;
			} elseif ($data['building_class'] == 5) {
				$data['building_class'] = 3;
			}
        }
		if ($field == 'location_id') {
			//if city 215 - Piter
			//if city 214 - Moscow
			$keyMetro = array_search(mb_strtolower($metroListOldArr[$resultSqlLocationsArray[$dataID]['metro_id']] ?? 0), $metroListNewArr);

			$locationByMetro = mysqli_query($mysqliNew, "SELECT id FROM location WHERE location_metro = '[\"" . $keyMetro . "\"]' LIMIT 1");
			$locationByMetro = mysqli_fetch_array($locationByMetro);

			$data['location_id'] = $locationByMetro['id'] ?? 0;
		}

	///////////////////////////////////////
		//// MOVE PHOTOS AND FILES ////
	///////////////////////////////////////

		if ($field == 'photos') {
			$dataFolder = '/uploads/objects/' . $data['ID'] . '/';
			$dataPhotos = [];
			if (!file_exists( __DIR__ . '/public_html' . $dataFolder )) {
				mkdir(__DIR__ . '/public_html' . $dataFolder, 0755, true);
			}

			foreach ($data['photos'] as $phKey => $photo) {
				$fileUrl = $data['photos_dir'][$phKey] . "/" . $photo;
				//$file_headers = @get_headers('https://retail.realtor.ru/upload/' . str_replace(' ', '%20', $fileUrl));
				if ($fileContent = @file_get_contents( 'https://retail.realtor.ru/upload/' . str_replace(' ', '%20', $fileUrl) ) !== false ) {
				//if (str_contains($file_headers[0], "200 OK") || str_contains($file_headers[7], "200 OK")) {
					//$fileContent  = file_get_contents('https://retail.realtor.ru/upload/' . str_replace(' ', '%20', $fileUrl));
					$dataMoveFile = file_put_contents(__DIR__ . '/public_html' . $dataFolder . $photo , $fileContent);
					if (!empty($dataMoveFile)) {
						if (empty($dataMoveFile)) writeToLog('Photo ' . $fileUrl . ' was not transferred');
						else $dataPhotos[] = $dataFolder . $photo;
					} else {
						unlink( __DIR__ . '/public_html' . $dataFolder.$photo);
					}
				}
			}

			foreach ($data['photo_plans'] as $phKey => $photo) {
				$dataFileURL  = explode('/', $photo);
				$fileName = end($dataFileURL);
				$fileName = explode('?', $fileName);
				$fileName = $fileName[0];

				//$file_headers = @get_headers($photo);
				//if (str_contains($file_headers[0], "200 OK") || str_contains($file_headers[7], "200 OK")) {
				if ($fileContent = @file_get_contents( $photo ) !== false ) {
					//$fileContent  = file_get_contents($photo);
					$dataMoveFile = file_put_contents(__DIR__ . '/public_html' . $dataFolder . $fileName, $fileContent);
					if (!empty($dataMoveFile)) {
						if (empty($dataMoveFile)) writeToLog('Photo ' . $photo . ' was not transferred');
						else $dataPhotos[] = $dataFolder . $fileName;
					} else {
						unlink( __DIR__ . '/public_html' . $dataFolder . $fileName);
					}
				}
			}

			$data['photos'] = json_encode($dataPhotos, JSON_UNESCAPED_UNICODE);
		}
		if ($field == 'all_moved_data') {
            $sql .= "'" . json_encode($data['all_moved_data'] ?? '') . "',";
        } else {
			$sql .= "'" . $data[$field] .  "',";
		}

    }
    $sql = substr($sql, 0, -1) . ")";
    echo '.';// . $sql . "\n\n"; //break; //die();
    $result = mysqli_query($mysqliNew, $sql);
	if (!$result) {
		echo $sql; die();
	}

	$lastBuildingInsId = $mysqliNew->insert_id;
	$buildingInsIdData[$data['ID']] = $lastBuildingInsId;

	if (!in_array($data['address'], $buildingListData['address'])) {
		$buildingListData['address'][$data['ID']]  = $data['address'];
	}

    // Добавление участка, если есть
    // land_plot

	if (!empty($data['land_area'])) {
		$data['land_area'] = explode(',', $data['land_area']);
		foreach ($data['land_area'] as $date) {
			if (!empty($data['land_area'])) {
				$data['land_area'] = intval($data['land_area']); break;
			}
		}
		if (!empty($data['land_area'])) {

			$sqlLand = "INSERT INTO land_plot (" . implode(", ", array_keys($fieldsLand)) . ") VALUES (";
			foreach ($fieldsLand as $field) {
				if (in_array($field, ['land_area', 'own_type_land', 'location_id'])) {
					$data[$field] = intval($data[$field] ?? 0);
				}
				if ($field == 'clyent_id') {
					$data[$field] = $dataID + ($contactMaxID['contact_max_id'] ?? 0);
				}
				if ($field == 'ID') {
					if (!empty($buildingMaxID['building_max_id'])) $buildingMaxID['building_max_id']++;
					$data[$field] = $dataID + ($buildingMaxID['building_max_id'] ?? 0) + 1;
				}
				if ($field == 'depID') {
					$data[$field] = $dataID + ($buildingMaxID['building_max_id'] ?? 0) + 1;
				}
				if ($field == 'inv_vri') {
					$data[$field] = 0;
				}

				$sqlLand .= "'" . $data[$field] .  "',";
			}
			$sqlLand = substr($sqlLand, 0, -1) . ")";
			echo '.';// .  $sqlLand . "\n\n"; //break; // die();
			$result = mysqli_query($mysqliNew, $sqlLand);
			if (!$result) {
				echo $sqlLand; die();
			}
		}
	}

	// Перенос блоков объектов (строений и участков)
	$sqlBObj = "INSERT INTO building_object (" . implode(", ", array_keys($fieldsObjBuilding)) . ") VALUES (";
	$data['0'] = 0;
	$data['1'] = '';
	$data['2'] = '2';
	$data['3'] = '3';
	foreach ($fieldsObjBuilding as $field) {
		if ($field == 'parent_id') {
			$data[$field] = intval($lastBuildingInsId ?? 0);
		}
		if ($field == 'depID') {
			$data[$field] = $data['ID'];
		}
		if ($field == 'finishing') {
			$data[$field] = 0;
			if ($data[$field] == 'с ремонтом') {
				$data[$field] = 1;
			} elseif ($data[$field] == 'офисная отделка') {
				$data[$field] = 1;
			} elseif ($data[$field] == 'чистовая') {
				$data[$field] = 4;
			} elseif ($data[$field] == 'без отделки') {
				$data[$field] = 6;
			} elseif ($data[$field] == 'предчистовая') {
				$data[$field] = 2;
			} elseif ($data[$field] == 'хороший ремонт / офисная отделка') {
				$data[$field] = 1;
			} elseif ($data[$field] == 'требует косметического ремонта') {
				$data[$field] = 3;
			} elseif ($data[$field] == 'требует капитального ремонта') {
				$data[$field] = 5;
			}
        }
		if (in_array($field, ['block_area_min', 'block_area_max', 'floor'])) {
			$data[$field] = abs($data[$field] ?? 0);
		}

		$sqlBObj .= "'" . $data[$field] .  "',";
	}

	$sqlBObj = substr($sqlBObj, 0, -1) . ")";
	echo '.';// . $sqlBObj . "\n\n"; //break; //die();
	$result = mysqli_query($mysqliNew, $sqlBObj);
	if (!$result) {
		echo $sqlBObj; die();
	}

	if ($loppIndex % 500 == 0) { echo '__--__'; sleep(2); }
	$loppIndex++;
	//break;
}
//die();

echo 'unset';
unset($buildingListData);
sleep(2);

//////////////////////////////// Атрибуты (Коммуникаци, Безопастность, Парковка) (код - значение)
// 90  - Кондиционирование
// 84  - Наличие воды в помещении
// 89  - Охранная сигнализация
// 85  - Пожарная сигнализация
// 92  - Паркинг - parking
// 99  - Паркинг - вид - parking_type
// 96  - Паркинг - кол-во машиномест - parking_spaces
// 122 - Парковка - parking_space
// 41  - Помещение (Возможно вход) ??????????
// 87  - Электрическая мощность
// 81  - Вентиляция
// 100 - Интернет-провайдер

$resultSql = mysqli_query($mysqliOld, 'SELECT bbe.*, GROUP_CONCAT(bbep.VALUE) as conditioning, GROUP_CONCAT(DISTINCT bbep2.VALUE) as water,
GROUP_CONCAT(DISTINCT bbep3.VALUE) as security_alert, GROUP_CONCAT(DISTINCT bbep4.VALUE) as parking, GROUP_CONCAT(DISTINCT bbep5.VALUE) as parking_type,
GROUP_CONCAT(DISTINCT bbep6.VALUE) as parking_spaces, GROUP_CONCAT(DISTINCT bbep7.VALUE) as parking_space, GROUP_CONCAT(DISTINCT bbep8.VALUE) as fire_alert,
GROUP_CONCAT(DISTINCT bbep9.VALUE) as entrance, GROUP_CONCAT(DISTINCT bbep10.VALUE) as ventilation, GROUP_CONCAT(DISTINCT bbep11.VALUE) as internet,
GROUP_CONCAT(DISTINCT bbep12.VALUE) as electrical_power
FROM `b_iblock_element` as bbe
LEFT JOIN `b_iblock_element_property` as bbep ON bbe.`ID` = bbep.`IBLOCK_ELEMENT_ID` AND bbep.`IBLOCK_PROPERTY_ID` = 90
LEFT JOIN `b_iblock_element_property` as bbep2 ON bbe.`ID` = bbep2.`IBLOCK_ELEMENT_ID` AND bbep2.`IBLOCK_PROPERTY_ID` = 84
LEFT JOIN `b_iblock_element_property` as bbep3 ON bbe.`ID` = bbep3.`IBLOCK_ELEMENT_ID` AND bbep3.`IBLOCK_PROPERTY_ID` = 89
LEFT JOIN `b_iblock_element_property` as bbep4 ON bbe.`ID` = bbep4.`IBLOCK_ELEMENT_ID` AND bbep4.`IBLOCK_PROPERTY_ID` = 92
LEFT JOIN `b_iblock_element_property` as bbep5 ON bbe.`ID` = bbep5.`IBLOCK_ELEMENT_ID` AND bbep5.`IBLOCK_PROPERTY_ID` = 99
LEFT JOIN `b_iblock_element_property` as bbep6 ON bbe.`ID` = bbep6.`IBLOCK_ELEMENT_ID` AND bbep6.`IBLOCK_PROPERTY_ID` = 96
LEFT JOIN `b_iblock_element_property` as bbep7 ON bbe.`ID` = bbep7.`IBLOCK_ELEMENT_ID` AND bbep7.`IBLOCK_PROPERTY_ID` = 122
LEFT JOIN `b_iblock_element_property` as bbep8 ON bbe.`ID` = bbep8.`IBLOCK_ELEMENT_ID` AND bbep8.`IBLOCK_PROPERTY_ID` = 85
LEFT JOIN `b_iblock_element_property` as bbep9 ON bbe.`ID` = bbep9.`IBLOCK_ELEMENT_ID` AND bbep9.`IBLOCK_PROPERTY_ID` = 41
LEFT JOIN `b_iblock_element_property` as bbep10 ON bbe.`ID` = bbep10.`IBLOCK_ELEMENT_ID` AND bbep10.`IBLOCK_PROPERTY_ID` = 81
LEFT JOIN `b_iblock_element_property` as bbep11 ON bbe.`ID` = bbep11.`IBLOCK_ELEMENT_ID` AND bbep11.`IBLOCK_PROPERTY_ID` = 100
LEFT JOIN `b_iblock_element_property` as bbep12 ON bbe.`ID` = bbep12.`IBLOCK_ELEMENT_ID` AND bbep12.`IBLOCK_PROPERTY_ID` = 87
WHERE bbe.`IBLOCK_ID` IN (5,24) GROUP BY bbe.`ID`');

foreach ($resultSql as $data) {
//while ($data = $resultSql->fetch_assoc()) {
//break;
    //var_dump('======================================================================', $data); //var_dump($data['ID']); die();
    // Добавление безопасности
    // security

    $fieldsLifts2 = [
        'security_object_id' => 'building_id',
        'security_object_type' => 'security_type',
        'security_object_attributes' => 'dataAttr',
    ];
	$data['security_alert'] = 'есть';
	if ( !empty($data['fire_alert']) && (($data['fire_alert'] ?? '') == 'есть') || !empty($data['security_alert']) && (($data['security_alert'] ?? '') == 'есть') ) {

		$data['security_type'] = '2';
		$sqlSecurity = "INSERT INTO security (" . implode(", ", array_keys($fieldsLifts2)) . ") VALUES (";
		foreach ($fieldsLifts2 as $field) {
			if ($field == 'building_id') {
				$data[$field] = $buildingInsIdData[$data['ID']] ?? 0;
			}
			if ($field == 'dataAttr') {
				/*$data['dataAttr'] = json_encode([
					'boom_barrier' => ($data['barrier'] ?? 0) == 1 ? 1 : 0,
					'fence' => ($data['fence'] ?? 0) == 1 ? 1 : 0,
					'perimeter_security' => 0,
					'security_alert' => ($data['security_alert'] ?? 0) == 1 ? 1 : 0,
					'access_control' => ($data['access_control'] ?? 0) == 1 ? 1 : 0,
					'video_control' => ($data['video_control'] ?? 0) == 1 ? 1 : 0,
				]);*/
				$data['dataAttr'] = json_encode([
					'fire_alert' => ($data['fire_alert'] ?? '') == 'есть' ? 1 : 0,
					//'fire_exhaust' => 0,
					//'smoke_exhaust' => ($data['smoke_exhaust'] ?? 0) == 1 ? 1 : 0,
					'security_alert' => ($data['security_alert'] ?? '') == 'есть' ? 1 : 0,
					//'access_control' => ($data['access_control'] ?? 0) == 1 ? 1 : 0,
					//'video_control' => in_array(2, $dataIngening) ? 1 : 0,
				]);
			}
			$sqlSecurity .= "'" . $data[$field] .  "',";
		}

		$sqlSecurity = substr($sqlSecurity, 0, -1) . ")";
		echo '.';// . $sqlSecurity . "\n\n"; //break; //die();
		if (!empty($data['dataAttr'])) {
			$result = mysqli_query($mysqliNew, $sqlSecurity);
			if (!$result) {
				echo $sqlSecurity; die();
			}
		}
	}

    // Добавление коммуникаций
    // communications

    $fieldsLifts3 = [
        'communications_object_id' => 'building_id',
        'communications_object_type' => '1', // относиться к зданию
        'communications_electricity_power' => 'electrical_power',
        'communications_electricity' => 'electricity',
        'communications_water_supply' => 'water',
        'communications_water_supply_type' => 'water_type',
        'communications_ventilation' => 'ventilation',
        'communications_air_conditioning' => 'conditioning',
        'communications_internet' => 'internet',
    ];
    $data['1'] = '1';
    $sqlCommun = "INSERT INTO communications (" . implode(", ", array_keys($fieldsLifts3)) . ") VALUES (";
    foreach ($fieldsLifts3 as $field) {
		if ($field == 'building_id') {
			$data[$field] = $buildingInsIdData[$data['ID']] ?? 0;
		}
        if (in_array($field, ['electricity']) && !empty($data['power'])) {
            $data['electricity'] = 4;
        }
        if ($field == 'water') {
			$data[$field] = !empty($data[$field]) ? 1 : 0;
        }
        if ($field == 'water_type') {
			$data['water_type'] = 0;
			if ($data['water_type'] == 'холодное') {
				$data['communications_water_supply_type'] = 1;
			} elseif ($data['water_type'] == 'горячее и холодное') {
				$data['communications_water_supply_type'] = 2;
			} elseif ($data['water_type'] == 'холодное и бойлер') {
				$data['communications_water_supply_type'] = 3;
			}
        }
        if ($field == 'conditioning') {
			$data['conditioning'] = $data['conditioning'] == 'сплит-система' ? 2 : 4;
        }
        if ($field == 'internet') {
			$data['internet'] = $data['internet'] == 'Скоростной интернет' ? 1 : 2;
        }
        if ($field == 'ventilation') {
			$data['ventilation'] = 0;
			if ($data['ventilation'] == 'есть') {
				$data['ventilation'] = 3;
			} elseif ($data['ventilation'] == 'нет' || empty($data['ventilation'])) {
				$data['ventilation'] = 1;
			}
        }
        if ($field == 'communications_air_conditioning') {
			$data['communications_air_conditioning'] = 0;
			if (in_array($data['ventilation'], [5, 4])) {
				$data['communications_air_conditioning'] = 2;
			} elseif (in_array($data['ventilation'], [3, 6])) {
				$data['communications_air_conditioning'] = 1;
			} else {
				$data['communications_air_conditioning'] = 1;
			}
        }
		if (in_array($field, ['electrical_power', 'water_type', 'electricity'])) {
            $data[$field] = intval($data[$field] ?? 0);
        }
        $sqlCommun .= "'" . $data[$field] .  "',";
    }

    $sqlCommun = substr($sqlCommun, 0, -1) . ")";
    echo '.';// .  $sqlCommun . "\n\n"; //break;
    $result = mysqli_query($mysqliNew, $sqlCommun);
	if (!$result) {
		echo $sqlCommun; die();
	}
    //die();

    // Добавление парковок и въездов
    // parking_and_entrance
	
	if ( !empty($data['parking_type']) || !empty($data['parking_spaces']) ) {

		$fieldsLifts4 = [
			'parking_object_id' => 'building_id',
			'parking_object_type' => '1', // ?????????????????????
			'parking_object_attributes' => 'dataAttr',
		];
		$data['1'] = '1';
		$data['dataAttr'] = '';
		$sqlParking = "INSERT INTO parking (" . implode(", ", array_keys($fieldsLifts4)) . ") VALUES (";
		foreach ($fieldsLifts4 as $field) {
			if ($field == 'building_id') {
				$data[$field] = $buildingInsIdData[$data['ID']] ?? 0;
			}

			if ($field == 'dataAttr') {
				if ($data['parking_type'] == 'Стихийная' || $data['parking_type'] == 'открытая') {
					$data['dataAttr'] = json_encode([
						'spontaneous' => [
							'is_exist' => 1,
							'is_paid' => 0,
							'amount' => $data['parking_spaces'] ?? 0,
							'one_car_price' => '0',
						],
					]);
				} elseif ($data['parking_type'] == 'наземная (со шлагбаумом)' || $data['parking_type'] == 'Наземная') {
					$data['dataAttr'] = json_encode([
						'fenced' => [
							'is_exist' => 1,
							'is_paid' => 0,
							'amount' => $data['parking_spaces'] ?? 0,
							'one_car_price' => '0',
						],
					]);
				} elseif ($data['parking_type'] == 'многоуровневая') {
					$data['dataAttr'] = json_encode([
						'multi_level' => [
							'is_exist' => 1,
							'is_paid' => 0,
							'amount' => $data['parking_spaces'] ?? 0,
							'one_car_price' => '0',
						],
					]);
				} elseif ($data['parking_type'] == 'Подземная') {
					$data['dataAttr'] = json_encode([
						'underground' => [
							'is_exist' => 1,
							'is_paid' => 0,
							'amount' => $data['parking_spaces'] ?? 0,
							'one_car_price' => '0',
						],
					]);
				} elseif ($data['parking_type'] == 'Организованная') {
					$data['dataAttr'] = json_encode([
						'individual' => [
							'is_exist' => 1,
							'is_paid' => 0,
							'amount' => $data['parking_spaces'] ?? 0,
							'one_car_price' => '0',
						],
					]);
				}
			}
			$sqlParking .= "'" . $data[$field] .  "',";
		}

		$sqlParking = substr($sqlParking, 0, -1) . ")";
		echo '.';// . $sqlParking . "\n\n"; //break;
		if (!empty($data['dataAttr'])) {
			$result = mysqli_query($mysqliNew, $sqlParking);
			if (!$result) {
				echo $sqlParking; die();
			}
		}
	}
    //die();

	if ($loppIndex % 500 == 0) { echo '__|__'; sleep(2); }
	$loppIndex++;
	//break;
}
//die();


// Перенос предложений //b_iblock_element
$fieldsOffer = [
    //'id' => 'id',
	'offer_object_id' => 'parent_id',
	'offer_original_id' => '0',
'offer_company_id' => '0',
	'offer_contact_id' => 'clyent_id',
'offer_consultant_id' => '0',
	'offer_hide' => '0',
	'offer_personal_exam' => '0',
	'offer_signing_contract' => 'exclusive_agreement',
	'offer_type' => 'deal_type',
	'offer_gpzu' => 'inv_gpzu',

	'offer_owner_percent' => 'owner_commission',
		'offer_owner_percent_type' => '0',
		'offer_owner_percent_type2' => '0',
	'offer_client_percent' => 'commission',
	'offer_agent_percent' => 'realtor_commission',
	'offer_tax_system' => 'vat_type',
		'offer_sale_legal_entity' => '0',
	'offer_opex' => '0',
		'offer_ku_communal' => '0',
		//offer_ku_communal_type => public_services
	'offer_vacation' => '0',
		'offer_deposit' => 'deposit', // deposit
		'offer_built_to_rent' => '0',
		'offer_built_to_suit' => '0',
		'offer_construction_months' => '0',
		'offer_project_availability' => '0',

	'offer_with_rental_business' => 'ready_made_business',
		//offer_annual_rental_flow
	'offer_monthly_rental_flow' => 'map',
	'offer_net_operating_income' => 'profit_percent',
		'offer_net_profit' => '0',
		//offer_capitalization_rate
	'offer_payback_period_chod' => 'payback_period',
		//offer_payback_period_map
		'offer_long_contracts_percent' => '0',
		//offer_object_occupancy_percent

	'offer_investment_project' => 'real_estate_type',
	'offer_project_type' => 'project_type',
	'offer_need_change_tou' => 'vri_change',
	'offer_project_stage' => 'project_stage',
		//offer_gns_area
		//offer_unit_price_based_on_area
		//offer_unit_price_based_on_area_gns
		//offer_buildings_presence
		//offer_current_state
		//offer_teps_density
		//offer_teps_height
		//offer_teps_height_unit
		//offer_teps_buildings_percent
		//offer_crt
		//offer_szz_zone
		//offer_oopt_zone
		//offer_okn_zone
		//offer_has_exits_to_uds

		//offer_loading_operations
		//'offer_cross_docking' => 'cross_docking',
		//'offer_boxes_recalc'
		//offer_product_culling
		//offer_product_repack
		//offer_pallet_formation
		//offer_stretch_tape_wrapp
		//offer_batch_accounting
		//offer_serial_numbers_account
		//offer_in_fifi_lifi_fefo_lefo
		//offer_product_choice
		//'offer_provision_pallets' => 'pallet_place_min',
		//offer_complete_sets
		//offer_labeling
		//offer_product_packaging
		//offer_co_packing
		//offer_print_accomp_docks
		//offer_provision_reports
		//'offer_inventory' => 'price_safe_service_inventory',
		//offer_disposal_waste
		//offer_management_inventory
		//offer_acceptance_refunds
		//offer_pack_repair
		//offer_archive_storage
		//offer_zpl_services
		//offer_delivery_by_city
		//offer_delivery_by_region
		//offer_delivery_by_country

		'offer_room_purpose' => '0',
		//'offer_access_to_object' => 'access_control', // ???????
	'offer_electricity_power' => 'electro_power',
		//'offer_water_supply_power' => 'water_value',
		//'offer_gas_for_prod' => 'gas',
		//'offer_steam_for_prod' => 'steam',
	'offer_input_groups_cnt' => 'cnt_entrance_groups',
	'offer_type_of_right' => 'land_rights',
	'offer_contract_term' => 'rental_type',

		'offer_full_price_min' => '0',
		'offer_full_price_max' => '0',
		//'offer_warehouse_price_min'
		//offer_warehouse_price_max
		//offer_warehouse_price_type
		'offer_office_price_min' => '0',
		'offer_office_price_max' => '0',
		//offer_office_price_type
	'offer_retail_price_min' => 'retail_price',
	'offer_retail_price_max' => 'ready_retail_price',
	'offer_retail_price_type' => 'ready_retail_price_type',
		//'offer_technical_price_min' => 'price_tech_min',
		//'offer_technical_price_max' => 'price_tech_max',
		//offer_technical_price_type
		//offer_public_price_min
		//offer_public_price_max
		//offer_public_price_type
		//'offer_land_plot_price_min' => 'price_field_min',
		//'offer_land_plot_price_max' => 'price_field_max',
		//offer_land_plot_price_type
		//offer_entrance_attributes
		//offer_infrastructure_attributes
		//offer_infrastructure_railway = // railway + railway_value
		//offer_price_min
		//offer_price_max

		//responsible_storage_price_15
		//responsible_storage_price_18
		//responsible_storage_price_22
		//responsible_storage_price_type

	'offer_offer_description' => 'DETAIL_TEXT',
		//'offer_documents_layouts' => 'building_layouts_block', // Из таблицы c_industry_blocks !!!!!!!!!!!!!!!!!!!!!!!
		//offer_tenant_id
	'offer_rental_period' => 'term_agreement',
	'offer_status' => 'ACTIVE',
	'offer_status_reason' => '1',
	'offer_last_update' => 'TIMESTAMP_X',

	'offer_department' => '3',
	'offer_department_id' => 'depID',
	'offer_all_moved_data' => 'all_moved_data',
];

// Перенос сделок (код - значение)
// 547 - (Инв Пр) Права на земельный участок
// 555 - (Инв Пр) ГПЗУ:
//-- 561 - (Инв Пр) Доходность: ??????????????
// 546 - (Инв Пр) Проект
// 549 - (Инв Пр) Стадия проекта
// 60  - Готовый бизнес
// 8   - Доходность %
// 97  - Комиссия (возможно - от клиента)
// 539 - Комиссия для других риэлторов
// 537 - Комиссия от собственника
// 533 - Месячный арендный потоК (MAП)
// 548 - Смена ВРИ
// 9   - Срок окупаемости лет
// 134 - Сроки договора аренды
// 94  - Субаренда - да, нет
// 28  - Тип сделки - продажа, аренда
// 79  - Тип аренды
// 535 - Тип НДС
// 27  - Тип недвижимости
// 111 - Цена аренды в месяц - month_rent_price
// 10  - Цена аренды за м.кв./год - rental_price_square_meter_per_year
// 43  - цена за весь объект - продажа - price_for_full_object
// 44  - цена за метр - продажа - price_per_meter
// 88  - Эксклюзивный договор с собственником
// 87  - Электрическая мощность
// 86  - Кол-во входных групп
// 529  - Количество месяцев предоплаты

//$resultSql = mysqli_query($mysqliOld, "SELECT * FROM `c_offices_blocks`");
$resultSql = mysqli_query($mysqliOld, 'SELECT bbe.*, GROUP_CONCAT(DISTINCT bbep2.VALUE) as inv_gpzu,
GROUP_CONCAT(DISTINCT bbep3.VALUE) as inv_profit, GROUP_CONCAT(DISTINCT bbep4.VALUE) as land_rights, GROUP_CONCAT(DISTINCT bbep5.VALUE) as project_type,
GROUP_CONCAT(DISTINCT bbep6.VALUE) as project_stage, GROUP_CONCAT(DISTINCT bbep7.VALUE) as ready_made_business, GROUP_CONCAT(DISTINCT bbep8.VALUE) as profit_percent,
GROUP_CONCAT(DISTINCT bbep9.VALUE) as commission, GROUP_CONCAT(DISTINCT bbep10.VALUE) as realtor_commission, GROUP_CONCAT(DISTINCT bbep11.VALUE) as owner_commission,
GROUP_CONCAT(DISTINCT bbep12.VALUE) as map, GROUP_CONCAT(DISTINCT bbep13.VALUE) as vri_change, GROUP_CONCAT(DISTINCT bbep14.VALUE) as payback_period,
GROUP_CONCAT(DISTINCT bbep15.VALUE) as term_agreement, GROUP_CONCAT(DISTINCT bbep16.VALUE) as sub_rent, GROUP_CONCAT(DISTINCT bbep17.VALUE) as rental_type,
GROUP_CONCAT(DISTINCT bbep19.VALUE) as vat_type, GROUP_CONCAT(DISTINCT bbep20.VALUE) as real_estate_type,
GROUP_CONCAT(DISTINCT bbep21.VALUE) as deal_type, GROUP_CONCAT(DISTINCT bbep22.VALUE) as month_rent_price,
GROUP_CONCAT(DISTINCT bbep23.VALUE) as rental_price_square_meter_per_year, GROUP_CONCAT(DISTINCT bbep24.VALUE) as price_for_full_object,
GROUP_CONCAT(DISTINCT bbep25.VALUE) as price_per_meter, GROUP_CONCAT(DISTINCT bbep26.VALUE) as exclusive_agreement,
GROUP_CONCAT(DISTINCT bbep27.VALUE) as electro_power, GROUP_CONCAT(DISTINCT bbep28.VALUE) as cnt_entrance_groups,
GROUP_CONCAT(DISTINCT bbep29.VALUE) as deposit
FROM `b_iblock_element` as bbe
LEFT JOIN `b_iblock_element_property` as bbep2 ON bbe.`ID` = bbep2.`IBLOCK_ELEMENT_ID` AND bbep2.`IBLOCK_PROPERTY_ID` = 555
LEFT JOIN `b_iblock_element_property` as bbep3 ON bbe.`ID` = bbep3.`IBLOCK_ELEMENT_ID` AND bbep3.`IBLOCK_PROPERTY_ID` = 561
LEFT JOIN `b_iblock_element_property` as bbep4 ON bbe.`ID` = bbep4.`IBLOCK_ELEMENT_ID` AND bbep4.`IBLOCK_PROPERTY_ID` = 547
LEFT JOIN `b_iblock_element_property` as bbep5 ON bbe.`ID` = bbep5.`IBLOCK_ELEMENT_ID` AND bbep5.`IBLOCK_PROPERTY_ID` = 546
LEFT JOIN `b_iblock_element_property` as bbep6 ON bbe.`ID` = bbep6.`IBLOCK_ELEMENT_ID` AND bbep6.`IBLOCK_PROPERTY_ID` = 549
LEFT JOIN `b_iblock_element_property` as bbep7 ON bbe.`ID` = bbep7.`IBLOCK_ELEMENT_ID` AND bbep7.`IBLOCK_PROPERTY_ID` = 60
LEFT JOIN `b_iblock_element_property` as bbep8 ON bbe.`ID` = bbep8.`IBLOCK_ELEMENT_ID` AND bbep8.`IBLOCK_PROPERTY_ID` = 8
LEFT JOIN `b_iblock_element_property` as bbep9 ON bbe.`ID` = bbep9.`IBLOCK_ELEMENT_ID` AND bbep9.`IBLOCK_PROPERTY_ID` = 97
LEFT JOIN `b_iblock_element_property` as bbep10 ON bbe.`ID` = bbep10.`IBLOCK_ELEMENT_ID` AND bbep10.`IBLOCK_PROPERTY_ID` = 539
LEFT JOIN `b_iblock_element_property` as bbep11 ON bbe.`ID` = bbep11.`IBLOCK_ELEMENT_ID` AND bbep11.`IBLOCK_PROPERTY_ID` = 537
LEFT JOIN `b_iblock_element_property` as bbep12 ON bbe.`ID` = bbep12.`IBLOCK_ELEMENT_ID` AND bbep12.`IBLOCK_PROPERTY_ID` = 533
LEFT JOIN `b_iblock_element_property` as bbep13 ON bbe.`ID` = bbep13.`IBLOCK_ELEMENT_ID` AND bbep13.`IBLOCK_PROPERTY_ID` = 548
LEFT JOIN `b_iblock_element_property` as bbep14 ON bbe.`ID` = bbep14.`IBLOCK_ELEMENT_ID` AND bbep14.`IBLOCK_PROPERTY_ID` = 9
LEFT JOIN `b_iblock_element_property` as bbep15 ON bbe.`ID` = bbep15.`IBLOCK_ELEMENT_ID` AND bbep15.`IBLOCK_PROPERTY_ID` = 134
LEFT JOIN `b_iblock_element_property` as bbep16 ON bbe.`ID` = bbep16.`IBLOCK_ELEMENT_ID` AND bbep16.`IBLOCK_PROPERTY_ID` = 94
LEFT JOIN `b_iblock_element_property` as bbep17 ON bbe.`ID` = bbep17.`IBLOCK_ELEMENT_ID` AND bbep17.`IBLOCK_PROPERTY_ID` = 79
LEFT JOIN `b_iblock_element_property` as bbep19 ON bbe.`ID` = bbep19.`IBLOCK_ELEMENT_ID` AND bbep19.`IBLOCK_PROPERTY_ID` = 535
LEFT JOIN `b_iblock_element_property` as bbep20 ON bbe.`ID` = bbep20.`IBLOCK_ELEMENT_ID` AND bbep20.`IBLOCK_PROPERTY_ID` = 27
LEFT JOIN `b_iblock_element_property` as bbep21 ON bbe.`ID` = bbep21.`IBLOCK_ELEMENT_ID` AND bbep21.`IBLOCK_PROPERTY_ID` = 28
LEFT JOIN `b_iblock_element_property` as bbep22 ON bbe.`ID` = bbep22.`IBLOCK_ELEMENT_ID` AND bbep22.`IBLOCK_PROPERTY_ID` = 111
LEFT JOIN `b_iblock_element_property` as bbep23 ON bbe.`ID` = bbep23.`IBLOCK_ELEMENT_ID` AND bbep23.`IBLOCK_PROPERTY_ID` = 10
LEFT JOIN `b_iblock_element_property` as bbep24 ON bbe.`ID` = bbep24.`IBLOCK_ELEMENT_ID` AND bbep24.`IBLOCK_PROPERTY_ID` = 43
LEFT JOIN `b_iblock_element_property` as bbep25 ON bbe.`ID` = bbep25.`IBLOCK_ELEMENT_ID` AND bbep25.`IBLOCK_PROPERTY_ID` = 44
LEFT JOIN `b_iblock_element_property` as bbep26 ON bbe.`ID` = bbep26.`IBLOCK_ELEMENT_ID` AND bbep26.`IBLOCK_PROPERTY_ID` = 88
LEFT JOIN `b_iblock_element_property` as bbep27 ON bbe.`ID` = bbep27.`IBLOCK_ELEMENT_ID` AND bbep27.`IBLOCK_PROPERTY_ID` = 87
LEFT JOIN `b_iblock_element_property` as bbep28 ON bbe.`ID` = bbep28.`IBLOCK_ELEMENT_ID` AND bbep28.`IBLOCK_PROPERTY_ID` = 86
LEFT JOIN `b_iblock_element_property` as bbep29 ON bbe.`ID` = bbep29.`IBLOCK_ELEMENT_ID` AND bbep29.`IBLOCK_PROPERTY_ID` = 529
WHERE bbe.`IBLOCK_ID` IN (5,24) GROUP BY bbe.`ID`');

foreach ($resultSql as $data) {

	//var_dump('======================================================================', $data); // die();
	// Перенос предложений //c_offices_blocks
		$sql = "INSERT INTO offer (" . implode(", ", array_keys($fieldsOffer)) . ") VALUES (";
		$data['0'] = 0;
		$data['1'] = '';
		$data['2'] = '2';
		$data['3'] = '3';
		$dataID = $data['ID'];
		foreach ($fieldsOffer as $field) {

			if (in_array($field, ['price_opex_inc', 'cross_docking', 'pallet_place_min'])) {
				$data[$field] = $data[$field] == 1 ? 1 : 0;
			}
			if ($field == 'TIMESTAMP_X') {
				$data[$field] = !empty($data[$field]) ? strtotime($data[$field]) : 0;
			}
			if ($field == 'depID') {
				$data[$field] = $data['ID'];
			}
			if ($field == 'parent_id') {
				$data[$field] = $buildingInsIdData[$data['ID']] ?? 0;
			}
			if ($field == 'exclusive_agreement') {
				$data[$field] = $data['exclusive_agreement'] == 'да' ? 2 : 3;
			}
			if (in_array($field, ['realtor_commission', 'owner_commission', 'commission', 'profit_percent', 'payback_period', 'map', 'electro_power', 'cnt_entrance_groups', 'vri_change', 'deposit', 'term_agreement'])) {
				$data[$field] = intval($data[$field] ?? 0);
			}
			if ($field == 'term_agreement') {
				// Перевод строки в месяца !!!!
				//$data[$field] = $dataID + ($contactMaxID['contact_max_id'] ?? 0);
			}
			if ($field == 'clyent_id') {
				$data[$field] = 0;//$dataID + ($contactMaxID['contact_max_id'] ?? 0);
			}
			if ($field == 'rental_type') {
				$data[$field] = $data[$field] == 'долгосрочная аренда (от года)' ? 1 : 2;
			}
			if ($field == 'project_type') {
				$data[$field] = 0;
				if ($data[$field] == 'Строительство торговых площадей') {
					$data[$field] = 7;
				} elseif ($data[$field] == 'Строительство офисных площадей') {
					$data[$field] = 6;
				} elseif ($data[$field] == 'Строительство МФК') {
					$data[$field] = 12;
				} elseif ($data[$field] == 'Строительство гостиницы') {
					$data[$field] = 5;
				} elseif ($data[$field] == 'Многоэтажная жилая застройка') {
					$data[$field] = 1;
				} elseif ($data[$field] == 'Строительство складских /производственных площадей') {
					$data[$field] = 9;
				} elseif ($data[$field] == 'Малоэтажная жилая застройка') {
					$data[$field] = 2;
				}
			}
			if ($field == 'project_stage') {
				$data[$field] = 0;
				if ($data[$field] == 'разрешение на строительство') {
					$data[$field] = 6;
				} elseif ($data[$field] == 'ГПЗУ') {
					$data[$field] = 2;
				} elseif ($data[$field] == 'утверждение АГР') {
					$data[$field] = 4;
				} elseif ($data[$field] == 'начальная') {
					$data[$field] = 1;
				} elseif ($data[$field] == 'незавершенное строительство') {
					$data[$field] = 7;
				} elseif ($data[$field] == 'предпроект') {
					$data[$field] = 1;
				}
			}
			if ($field == 'land_rights') {
				$data[$field] = $data[$field] == 'собственность' ? 2 : ($data[$field] == 'долгосрочная аренда' ? 1 : 0);
			}
			if ($field == 'vat_type') {
				$data[$field] = $data[$field] == 0 ? 2 : ($data[$field] == 2 ? 3 : ($data[$field] == 1 ? 4 : 0));
				
			}
			if ($field == 'ready_made_business') {
				$data[$field] = $data[$field] == 'true' ? 1 : 0;
			}
			if ($field == 'real_estate_type') {
				$data[$field] = $data[$field] == 'инвестиционный проект' ? 1 : 0;
			}
			if ($field == 'deal_type') {
				$data[$field] = $data['deal_type'] == 'аренда' ? 1 : ($data['deal_type'] == 'продажа' ? 2 : 0);
				$data[$field] = $data['sub_rent'] == 'да' ? 4 : $data[$field];
			}
			if ($field == 'retail_price') {
				$data[$field] = 0;
				$data['ready_retail_price'] = 0;
				$data['ready_retail_price_type'] = 0;

				if ($data['deal_type'] == 2) {
					$data[$field] = intval($data['price_per_meter']);
					$data['ready_retail_price'] = intval($data['price_per_meter']);
					$data['ready_retail_price_type'] = 1;

					//$data['price_per_meter'] != $data['map']; // Maybe need TODO (Update $data['map']) !!!!!

				} elseif ($data['deal_type'] == 1) {
					$data[$field] = intval($data['rental_price_square_meter_per_year']);
					$data['ready_retail_price'] = intval($data['rental_price_square_meter_per_year']);
					$data['ready_retail_price_type'] = 2;
				}
			}

			if ($field == 'ACTIVE') {
				$sql .= "'" . (!empty($data['ACTIVE']) ? 1 : 0) .  "',";
			} elseif ($field == 'all_moved_data') {
				$sql .= "'" . json_encode($data['all_moved_data'] ?? '') . "',";
			} else {
				$sql .= "'" . $data[$field] .  "',";
			}

		}

		$sql = substr($sql, 0, -1) . ")";
		echo '.';// . $sql . "\n\n"; break; //die();
		$result = mysqli_query($mysqliNew, $sql);
		if (!$result) {
			echo $sql; die();
		}
		if ($loppIndex % 500 == 0) { echo '__/\__'; sleep(2); }
		$loppIndex++;
		//break;
}


// $mysqliOld->close();
$mysqliNew->close();

?>
