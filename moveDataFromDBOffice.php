<?php

require realpath(__DIR__ . "/") . '/vendor/autoload.php';
require realpath(__DIR__ . "/") . '/vendor/yiisoft/yii2/Yii.php';
//$config = require __DIR__ . '/config/web.php';
//(new yii\web\Application($config))->run();

$secrets = require realpath(__DIR__ . "/") . "/config/secrets.php";
//$config  = require realpath(__DIR__ . "/") . "/7__include.php";

function writeToLog($text) {
    $log = '[' . date('Y-m-d H:i:s') . '] ' . $text;
    file_put_contents(__DIR__ . '/moveDataFromDBOfiice.log', $log . PHP_EOL, FILE_APPEND);
}

// =======================================================================
// =======================================================================
// Создаём SSH-соединение
$ssh = ssh2_connect('178.250.246.48', 2233);
if($ssh){echo "Connection Successful!\n";}
else{echo 'Connection Failed...';die();}

$ssh_auth = ssh2_auth_password($ssh, 'root', 'Vm0G_KLEVW');
if($ssh_auth){echo "Authentication Successful!\n";}
else{echo 'Authentication Failed...';die();}

// Создаём туннель переадресации порта
$stream = ssh2_tunnel($ssh, 'localhost', 3306);
if ($stream){echo "Tunnel created\n";}
else{echo "Tunnel creation failed!!";die();}

require realpath(__DIR__ . "/") . "/MysqlStreamDriver.php";

$mysqliOld = new MysqlStreamDriver($stream, 'root', 'rootpl$123$pass', 'penny_commerce');
// =======================================================================
// =======================================================================

//$mysqliOld = new mysqli(db_host, db_username, db_password, db_database);
//$mysqliNew = new mysqli(db_host, db_username, db_password, 'user_prod_backend');
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
// $metroListOld = mysqli_query($mysqliOld, "SELECT id, title FROM l_metros");
$result = $mysqliOld->query('SET NAMES \'utf8\';');
$metroListOld = $mysqliOld->query('SELECT id, title FROM l_metros');
$metroListNew = mysqli_query($mysqliNew, "SELECT * FROM metro");
$contactPositionList = mysqli_query($mysqliNew, "SELECT id, name FROM contact_position WHERE is_active = 1");

$metroListOldArr = [];
$metroListNewArr = [];
$contactPositionListArr = [];

while ($row = $metroListOld->fetch_assoc()) {
	$metroListOldArr[$row['id']] = $row['title'];
}
foreach ($metroListNew as $data) {
	$metroListNewArr[$data['id']] = $data['metro_title'];
}
foreach ($contactPositionList as $data) {
	$contactPositionListArr[$data['id']] = mb_strtolower($data['name']);
}


// c_offices_customers -
// Перенос звонков //c_offices_customers
$fieldsContact = [
	'id' => 'id',
	'nameRu' => 'c_company',
	'noName' => 'no_name',
	'status' => 'result',
	'officeAdress' => 'c_adress',
	'updated_at' => 'dt_update_full',
	'created_at' => 'dt_insert',
	//'active' => 'result',
	'description' => 'c_comments',
	'consultant_id' => '41',
	'morph' => 'morph',
	'all_moved_data' => 'all_moved_data',

	'company_department' => '2',
	'company_department_id' => 'depID',
];

$companyListData = ['c_company' => [], 'c_adress' => []];


$lastCompanyInsId = 0;
$resultSql = $mysqliOld->query('SELECT * FROM `c_offices_customers` ORDER BY id');

/*
DELETE FROM `phone` WHERE `id` > 99703;
DELETE FROM `email` WHERE `id` > 53928;
DELETE FROM `website` WHERE `id` > 5793;
DELETE FROM `contact` WHERE `id` > 46283;
DELETE FROM `company` WHERE `id` > 11503;
*/

// Доделать проверку на дубли !!!!!!!
$loppIndex = 0;

//foreach ($result as $data) {
while ($data = $resultSql->fetch_assoc()) {
//break;
    //var_dump('======================================================================', $data); //var_dump($data['id']); die();
    $sql = "INSERT INTO company (" . implode(", ", array_keys($fieldsContact)) . ") VALUES (";
    $data['0'] = 0;
    $data['1'] = '';
    $data['2'] = '2';
    $data['41'] = '41';
    $data['morph'] = 'company';
	$dataID = $data['id'];

    foreach ($fieldsContact as $field) {
		if ($field == 'id') {
			$data[$field] = $dataID + $companyMaxID["company_max_id"];
		}
		if ($field == 'depID') {
			$data[$field] = $dataID;
		}
		if ($field == 'no_name') {
            $data['no_name'] = empty($data['c_company']) ? 1 : 0;
        }
		if ($field == 'c_comments' && (strlen($data['c_comments']) > 65535)) {
            $data['c_comments'] = str_replace("'", "\'", substr($data['c_comments'], 0, 65534));
        } elseif ($field == 'c_comments') {
			$data['c_comments'] = str_replace("'", "\'", $data['c_comments']);
		}
		if ($field == 'c_company') {
            $data['c_company'] = addslashes($data['c_company'] ?? 'Мусор');
        }
		if ($field == 'c_company' || $field == 'c_adress') {
			if (in_array($data[$field], $companyListData[$field])) {
				$duplKey = array_search($data[$field], $companyListData[$field]);
				$data['all_moved_data']['id_duplicate'] = 1;
				if (!empty($duplKey)) $data['all_moved_data']['duplicated_id'] = $duplKey;
			}
		}

        if ($field == 'result') {
            $data['offer_status'] = empty($data['result']) ? 1 : 0;
            $sql .= "'" . $data['offer_status'] .  "',";
        } elseif ($field == 'all_moved_data') {
            $sql .= "'" . json_encode($data['all_moved_data'] ?? '') . "',";
        } elseif ($field == 'dt_insert') {
            $sql .= !empty($data['dt_insert']) ? "'" . $data['dt_insert'] . "'," : 'null,';
        } elseif ($field == 'dt_update_full') {
            $sql .= !empty($data['dt_update_full']) ? "'" . $data['dt_update_full'] . "',"  : (!empty($data['dt_insert']) ? "'" . $data['dt_insert'] . "'," : 'null,');
        } else {
            $sql .= "'" . $data[$field] .  "',";
        }
    }
	if (!in_array($data[$field], $companyListData['c_adress'])) {
		$companyListData['c_adress'][$data['id']] = $data['c_adress'];
	}
	if (!in_array($data[$field], $companyListData['c_company'])) {
		$companyListData['c_company'][$data['id']]  = $data['c_company'];
	}

    $sql = substr($sql, 0, -1) . ")";
    echo '.';// . $sql . "\n\n"; //break; //die();
    $result = mysqli_query($mysqliNew, $sql);
	//if (!$result) {
	//	echo $sql; die();
	//}

	$lastCompanyInsId = $mysqliNew->insert_id;
	$lastContactInsId = 0;

	// ADDind FIO, dolzhnost, phones, email
	for ($i = 1; $i <= 3; $i++) {
		if (!empty($data['c_fio' . $i]) || $i == 1) {
			$keyDolzhnost = array_search(mb_strtolower($data['c_dolzhnost' . $i]), $contactPositionListArr);
			$dataToInsert = [
				'company_id' => $lastCompanyInsId,
				'created_at' => $data['dt_insert'] ?? 'null',
				'updated_at' => $data['dt_update_full'] ?? $data['dt_insert'] ?? 'null',
				'position_id' => !empty($keyDolzhnost) ? $keyDolzhnost : 0,
				'status' => empty($data['result']) ? 1 : 0,
				'morph' => 'contact',
				'temp_name' => addslashes($data['c_fio' . $i] ?? 'Мусор'),
			];

			$sql = "INSERT INTO contact (" . implode(", ", array_keys($dataToInsert)) . ") VALUES (";
			foreach ($dataToInsert as $data2) {
				$sql .= $data2 == 'null' ? "null," : "'" . $data2 .  "',";
			}
			$sql = substr($sql, 0, -1) . ")";
			echo '.';// . $sql . "\n\n"; //break; //die();
			$result = mysqli_query($mysqliNew, $sql);
			//if (!$result) {
			//	echo $sql; die();
			//}

			$lastContactInsId = $mysqliNew->insert_id;
		}

		if (!empty($data['c_phones' . $i])) {
			$dataPhone = explode(", ", $data['c_phones' . $i]);
			foreach ($dataPhone as $phone) {
				$dataToInsert = [
					'contact_id' => $lastContactInsId,
					'phone' => preg_replace('/[-()+ ]/', '', $phone),
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
				//if (!$result) {
				//	echo $sqlPhone; die();
				//}
			}
		}
		if (!empty($data['c_emails' . $i])) {
			$pattern = "/\r
|
|\r|,/";
			$dataEmails = preg_split('/\n/', $data['c_emails' . $i]);
			foreach (preg_split($pattern, $data['c_emails' . $i]) as $email) {
				$sqlEmail = "INSERT INTO email (contact_id, email) VALUES (" . $lastContactInsId . ",'" . trim($email, "'") . "')";
				echo '.';// . $sqlEmail . "\n\n"; //break; //die();
				$result = mysqli_query($mysqliNew, $sqlEmail); //die();
				//if (!$result) {
				//	echo $sqlEmail; die();
				//}
			}
		}
	}

	// ADDind website
	if (!empty($data['c_url'])) {
		$sqlURL = "INSERT INTO website (contact_id, website) VALUES (" . $lastContactInsId . ",'" . $data['c_url'] . "')";
		echo '.';// . $sqlURL . "\n\n"; //break; //die();
		$result = mysqli_query($mysqliNew, $sqlURL);
		//if (!$result) {
		//	echo $sqlURL; die();
		//}
	}
	if ($loppIndex % 500 == 0) { echo '__'; sleep(2); }
	$loppIndex++;
	//break;
}
//die();
echo 'unset';
unset($companyListData);
sleep(2);

// Перенос агентов //c_users
$contactListData = ['user_email' => ['ena@raysarma.ru', 'rent@realtor.ru', 'borzov@realtor.ru', 'atanov@realtor.ru']];
$fieldsUsers = [
	'username' => 'user_email',
	//'password_hash' => 'user_password',
	'email' => 'user_email',
	'status' => 'user_active',
	//'created_at' => 'DATE_CREATE',
	//'updated_at' => 'TIMESTAMP_X',
	'email_username' => 'user_email',
	'password_hash' => 'password_hash',
	'role' => 'user_type',
	'restrict_ip_login' => '1_1',

	'user_department' => '2',
	'user_department_id' => 'depID',
];

$resultSql = $mysqliOld->query('SELECT * FROM `c_users`');

/*
DELETE FROM `user` WHERE `id` > 83;
DELETE FROM `user_profile` WHERE `id` > 64;
DELETE FROM `user_profile_email` WHERE `id` > 124;
DELETE FROM `user_profile_phone` WHERE `id` > 135;
*/

// foreach ($result as $data) {
while ($data = $resultSql->fetch_assoc()) {
//break;
	if ($data['user_type'] == 'none') continue;
    //var_dump('======================================================================', $data); //var_dump($data['id']); die();
    $sql = "INSERT INTO user (" . implode(", ", array_keys($fieldsUsers)) . ") VALUES (";
    $data['0'] = 0;
    $data['1_1'] = '1';
    $data['1'] = '';
    $data['2'] = '2';
	$data['NowTimeVar'] = time();
// var_dump(1111111111, class_exists(Yii), Yii::$app);
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
		if ($field == 'user_active') {
			$data[$field] = empty($data['user_active']) ? 9 : 10;
		}
		if ($field == 'user_type') {
			if ($data['user_type'] == 'agent') {
				$data['user_type'] = 2;
			} elseif ($data['user_type'] == 'dir') {
				$data['user_type'] = 4;
			} elseif ($data['user_type'] == 'admin') {
				$data['user_type'] = 3;
			} elseif ($data['user_type'] == 'rec' || $data['user_type'] == 'jur') {
				$data['user_type'] = 1;
			}
		}
		if ($field == 'depID') {
			$data[$field] = $data['id'];
		}

		$sql .= "'" . $data[$field] .  "',";
	}

	$sql = substr($sql, 0, -1) . ")";
    echo '.';// . $sql . "\n\n"; //break; //die();
	if (in_array($data['user_email'], $contactListData['user_email'])) continue;

	$result = mysqli_query($mysqliNew, $sql);
	//if (!$result) {
	//	echo $sql; die();
	//}

	$lastUserInsId = $mysqliNew->insert_id;

	$sql = "INSERT INTO user_profile (user_id, temp_name) VALUES (" . $lastUserInsId . ",'" . addslashes($data['user_name'] ?? 'Мусор') . "')";
	echo '.';// . $sql . "\n\n"; //break; //die();
	$result = mysqli_query($mysqliNew, $sql);
	//if (!$result) {
	//	echo $sql; die();
	//}
	$lastProfileInsId = $mysqliNew->insert_id;

	// Добавляем телефоны юзеру если заполнены
	if (!empty($data['user_phone']) && (strlen($data['user_phone']) < 7)) {
		$sqlPhone = "INSERT INTO user_profile_phone (user_profile_id, phone) VALUES (" . $lastProfileInsId . ",'" . preg_replace('/[-()+ ]/', '', $data['user_phone']) . "')";
		echo '.';// . $sqlPhone . "\n\n"; //break; //die();
		$result = mysqli_query($mysqliNew, $sqlPhone);
		//if (!$result) {
		//	echo $sqlPhone; die();
		//}
	}
	if (!empty($data['sms2']) && preg_replace('/[-()+ ]/', '', $data['user_phone']) != preg_replace('/[-()+ ]/', '', $data['sms2']) && (strlen($data['sms2']) < 7)) {
		$sqlPhone = "INSERT INTO user_profile_phone (user_profile_id, phone) VALUES (" . $lastProfileInsId . ",'" . preg_replace('/[-()+ ]/', '', $data['sms2']) . "')";
		echo '.';// . $sqlPhone . "\n\n"; //break; //die();
		$result = mysqli_query($mysqliNew, $sqlPhone);
		//if (!$result) {
		//	echo $sqlPhone; die();
		//}
	}
	if (!empty($data['call4'])) {
		$dataPhones = explode(",", $data['call4']);
		foreach ($dataPhones as $phone) {
			if (strlen($phone) < 7) continue;
			$sqlPhone = "INSERT INTO user_profile_phone (user_profile_id, phone) VALUES (" . $lastProfileInsId . ",'" . preg_replace('/[-()+ ]/', '', $phone) . "')";
			echo '.';// . $sqlPhone . "\n\n"; //break; //die();
			$result = mysqli_query($mysqliNew, $sqlPhone); //die();
			//if (!$result) {
			//	echo $sqlPhone; die();
			//}
		}
	}
	// die();
	if (!in_array($data['user_email'], $contactListData['user_email'])) {
		$contactListData['user_email'][$data['id']] = $data['user_email'];
	}

}
//die();
echo 'unset';
unset($contactListData);
sleep(2);


// Перенос запросов //c_offices_requests
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

	'request_department' => '2',
	'request_department_id' => 'depID',
];

$resultSql = $mysqliOld->query('SELECT * FROM `c_offices_requests`');

/*
DELETE FROM `request` WHERE `id` > 10096;
*/

// foreach ($result as $data) {
while ($data = $resultSql->fetch_assoc()) {
//break;
    //var_dump('======================================================================', $data); //var_dump($data['id']); die();
    $sql = "INSERT INTO request (" . implode(", ", array_keys($fieldsRequests)) . ") VALUES (";
    $data['0'] = 0;
    $data['1_1'] = '1';
    $data['1'] = '';
    $data['2'] = '2';
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
        } elseif ($field == 'all_moved_data') {
            $sql .= "'" . json_encode($data['all_moved_data'] ?? '') . "',";
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
}
//die();

//c_offices_calls ?????????????????????


/*
DELETE FROM `building` WHERE `id` > 12275;
DELETE FROM `communications` WHERE `id` > 4725;
DELETE FROM `security` WHERE `id` > 31374;
DELETE FROM `land_plot` WHERE `id` > 12266;
DELETE FROM `parking` WHERE `id` > 31369;
DELETE FROM `lifting_mechanisms` WHERE `id` > 983;
*/


// Перенос объектов (строений) //c_offices
$fieldsBuilding = [
	//'id' => 'id',
	'building_ready_status' => 'finishing',
	'building_square' => 'area',
	'building_floors_counts' => 'floors_int',
	'building_allowed_electro_power' => 'power',
		'building_external_decor' => '0', // TO DO !!!!!!!
	'building_in_main_sections' => 'in_main_sections',
		'building_restrictions' => 0, // 'land_use_restrictions',
	'building_attributes' => '1',

	'building_title' => 'object_name',
	'building_location' => 'location_id',
	'building_last_update' => 'dt_update_full',
		'building_management_company' => 0, //'company_id',
		'building_management_company_type' => 0, // 'company_id_type',
		'building_owner' => 'owners',
		'building_owner_type' => 'own_type_land',
		//'building_line' => 'first_line',
		'building_owner_contact' => 'clyent_id',
		'building_type' => 'type', // house_maintype + house_type ??????
	'building_address' => 'yandex_address_str',
	'building_kadastr' => '1',
		//'building_year_construction' => 'year_build',
		//'building_year_repairs' => 'year_repair',
		//'building_description' => 'description',
	'building_infrastructure' => 'infrastructure',
	// Тип перекрытий
	'building_class' => 'object_class',
	'building_complex_id' => '0',
	'building_in_complex' => '0',
	'building_latitude' => 'c_x',
	'building_longitude' => 'c_y',
		'building_author_id' => 'author_id',
	'building_from_mkad' => '0',
	'building_photo' => 'office_files2',
	//'building_test_only' => 'test_only',
		//'building_property_documents' => 'building_property_documents',
		//'building_layouts' => 'building_layouts',
	'building_department' => '2',
	'building_department_id' => 'depID',
	'building_all_moved_data' => 'all_moved_data',

	// location_text
];

$buildingListData = ['yandex_address_str' => []];

//$result = mysqli_query($mysqliOld, "SELECT * FROM `c_offices` WHERE id = 5");
//$resultSql = mysqli_query($mysqliOld, "SELECT co.*, GROUP_CONCAT(cf.file_name, ',,') as office_files FROM `c_offices` AS co LEFT JOIN `c_files` AS cf ON cf.tbl_id = co.id AND cf.deleted = 0 GROUP BY co.id LIMIT 1");
$resultSql = $mysqliOld->query("SELECT co.*, GROUP_CONCAT(cf.file_name, ',,') as office_files FROM `c_offices` AS co LEFT JOIN `c_files` AS cf ON cf.tbl_id = co.id AND cf.deleted = 0 GROUP BY co.id");

//foreach ($result as $data) {
while ($data = $resultSql->fetch_assoc()) {
//break;
    // var_dump('======================================================================', $data); //var_dump($data['id']); die();
    // $fields = !empty($data['is_land']) ? $fieldsLand : $fieldsBuilding;
    $sql = "INSERT INTO building (" . implode(", ", array_keys($fieldsBuilding)) . ") VALUES (";
    $data['0'] = 0;
    $data['1'] = '';
    $data['2'] = '2';
    $data['in_main_sections'] = 3;
	$data['location_id'] = 0;
	$dataID = $data['id'];
	$data['office_files'] = trim($data['office_files'], ',,');

    foreach ($fieldsBuilding as $field) {
        if (in_array($field, ['dt_update_full', 'author_id', 'power', 'own_type_land', 'area', 'location_id'])) {
            $data[$field] = intval($data[$field] ?? 0);
        }
        if ($field = 'clyent_id') {
			$data[$field] = $dataID + ($contactMaxID['contact_max_id'] ?? 0);
        }

		if ($field == 'type') {
			$data[$field] = 1; // house_maintype + house_type
		}
		if ($field == 'id') {
			$data[$field] = $dataID + ($buildingMaxID['building_max_id'] ?? 0);
		}
		if ($field == 'depID') {
			$data[$field] = $data[$field] == 2 ? 4 : $data[$field];
		}
		if ($field == 'yandex_address_str') {
			if (in_array($data[$field], $buildingListData[$field])) {
				$duplKey = array_search($data[$field], $buildingListData[$field]);
				$data['all_moved_data']['id_duplicate'] = 1;
				if (!empty($duplKey)) $data['all_moved_data']['duplicated_id'] = $duplKey;
			}
		}

		if ($field == 'finishing') {
			$data[$field] = $dataID;
		}
        if ($field == 'object_class' && !empty($data['object_class'])) {
			if ($data['object_class'] == 2) {
				$data['object_class'] = 5;
			} elseif ($data['object_class'] == 3) {
				$data['object_class'] = 2;
			} elseif ($data['object_class'] == 4) {
				$data['object_class'] = 6;
			} elseif ($data['object_class'] == 5) {
				$data['object_class'] = 3;
			}
        }
		if ($field == 'location_id') {
			$keyMetro = array_search(mb_strtolower($metroListOldArr[$data['metro']]), $metroListNewArr);

			$locationByMetro = mysqli_query($mysqliNew, "SELECT id FROM location WHERE location_metro = '[\"" . $keyMetro . "\"]' LIMIT 1");
			$locationByMetro = mysqli_fetch_array($locationByMetro);

			$data['location_id'] = $locationByMetro['id'] ?? 0;
		}

	/////////////////////////////////////
		 // MOVE PHOTOS AND FILES //
	/////////////////////////////////////

		if ($field == 'office_files2') {

			$dataFolder = '/uploads/objects/' . $data['id'] . '/';
			$dataPhotos2 = [];
			if (!file_exists( __DIR__ . '/public_html' . $dataFolder )) {
				mkdir(__DIR__ . '/public_html' . $dataFolder, 0755, true);
			}
			for ($i = 1; $i <= 10; $i++) {
				$fileUrl  = "/photo/offices/".$dataID."/0/" . $i . ".jpg";
				$fileUrl2 = "/plan/offices/".$dataID."/0/" . $i . ".jpg";

				$fileContent = file_get_contents( 'http://commerce.gorki.ru/' . str_replace(' ', '%20', $fileUrl) );
				$dataMoveFile = file_put_contents(__DIR__ . '/public_html' . $dataFolder.$i.".jpg" , $fileContent);
				if ($dataMoveFile != 33094) {
					if (empty($dataMoveFile)) writeToLog('Photo ' . $fileUrl . ' was not transferred');
					else $dataPhotos2[] = $dataFolder . '/' . $i . ".jpg";
				} else {
					unlink( __DIR__ . '/public_html' . $dataFolder.$i.".jpg" );
				}

				$fileContent = file_get_contents( 'http://commerce.gorki.ru/' . str_replace(' ', '%20', $fileUrl2) );
				$dataMoveFile = file_put_contents(__DIR__ . '/public_html' . $dataFolder.$i."_plan.jpg", $fileContent);
				if ($dataMoveFile != 33094) {
					if (empty($dataMoveFile)) writeToLog('Photo ' . $fileUrl2 . ' was not transferred');
					else $dataPhotos2[] = $dataFolder . '/' . $i . "_plan.jpg";
				} else {
					unlink( __DIR__ . '/public_html' . $dataFolder.$i."_plan.jpg" );
				}
			}

		}
        if ($field == 'office_files2' && !empty($data['office_files'])) {
            $dataPhotos = explode(',,', trim($data['office_files'], ',,'));
            foreach ($dataPhotos as &$photo) {
				$photo = trim($photo , ',');
				//var_dump(0, $photo);
				$fileUrl  = "/files/offices/".$dataID."/" . $photo;
				$fileUrl2 = "/files/offices/".$dataID."/0/" . $photo;

				if ($fileContent = file_get_contents( 'http://commerce.gorki.ru/' . str_replace(' ', '%20', $fileUrl) ) !== false ) {
					$dataMoveFile = file_put_contents(__DIR__ . '/public_html' . $dataFolder, $fileContent);
					if (empty($dataMoveFile)) writeToLog('File ' . $fileUrl . ' was not transferred');
				} elseif ($fileContent = file_get_contents( 'http://commerce.gorki.ru/' . str_replace(' ', '%20', $fileUrl2) ) !== false ) {
					$dataMoveFile = file_put_contents(__DIR__ . '/public_html' . $dataFolder, $fileContent);
					if (empty($dataMoveFile)) writeToLog('File ' . $fileUrl2 . ' was not transferred');
				} else {
					writeToLog('File ' . $photo . ' was not transferred');
				}
            }
			$data['office_files2'] = json_encode(array_merge($dataPhotos, $dataPhotos2), JSON_UNESCAPED_UNICODE);
			$data['office_files']  = '';
        } elseif ($field == 'office_files') {
			$data['office_files'] = '';
		}
        $sql .= "'" . $data[$field] .  "',";

    }
    $sql = substr($sql, 0, -1) . ")";
    echo '.';// . $sql . "\n\n"; //break; //die();
    $result = mysqli_query($mysqliNew, $sql);
	//if (!$result) {
	//	echo $sql; die();
	//}

	if (!in_array($data['yandex_address_str'] ?? '', $buildingListData['yandex_address_str'])) {
		$buildingListData['yandex_address_str'][$data['id']] = $data['yandex_address_str'] ?? '';
	}

    // Добавление безопасности
    // security

    $fieldsLifts2 = [
        'security_object_id' => 'id',
        'security_object_type' => 'security_type',
        'security_object_attributes' => 'dataAttr',
    ];
	$dataIngening = [];
	if (!empty($data['ingening'])) {

		$data['security_type'] = '2';
		$dataIngening = explode(',', $data['ingening']);
		$sqlSecurity = "INSERT INTO security (" . implode(", ", array_keys($fieldsLifts2)) . ") VALUES (";
		foreach ($fieldsLifts2 as $field) {
			//if ($field == 'security_type') {
			//}
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
					//'fire_alert' => ($data['fire_alert'] ?? 0) == 1 ? 1 : 0,
					'fire_exhaust' => 0,
					//'smoke_exhaust' => ($data['smoke_exhaust'] ?? 0) == 1 ? 1 : 0,
					//'security_alert' => ($data['security_alert'] ?? 0) == 1 ? 1 : 0,
					//'access_control' => ($data['access_control'] ?? 0) == 1 ? 1 : 0,
					'video_control' => in_array(2, $dataIngening) ? 1 : 0,
				]);
			}
			$sqlSecurity .= "'" . $data[$field] .  "',";
		}

		$sqlSecurity = substr($sqlSecurity, 0, -1) . ")";
		echo '.';// . $sqlSecurity . "\n\n"; //break; //die();
		$result = mysqli_query($mysqliNew, $sqlSecurity);
		//if (!$result) {
		//	echo $sqlSecurity; die();
		//}
	}

    // Добавление участка, если есть
    // land_plot

$fieldsLand = [
	'id' => 'id',
    'land_plot_square' => 'land_area',
    'land_plot_permition' => '0',
		//'land_plot_permition_text' => 'field_allow_usage',
	'land_plot_in_main_sections' => 'in_main_sections',
		'land_plot_restrictions' => 'land_use_restrictions',
	'land_plot_attributes' => '1',
		//land_plot_infrastructure -------- ?????
		'land_plot_title' => 'title',
	'land_plot_location' => 'location_id',
	'land_plot_category' => '0',
	'land_plot_last_update' => 'dt_update_full',
		//'land_plot_management_company' => 'company_id',
		//'land_plot_management_company_type' => 'company_id_type',
		//'land_plot_owner' => 'owners',
		//'land_plot_owner_type' => 'own_type_land',
		'land_plot_owner_contact' => 'clyent_id',
	'land_plot_address' => 'yandex_address_str',
	'land_plot_kadastr' => '1',
	'land_plot_description' => 'land_options',
	'land_plot_in_complex' => '0',
	'land_plot_complex_id' => '0',
	'land_plot_latitude' => 'c_x',
	'land_plot_longitude' => 'c_y',
		//'land_plot_author_id' => 'author_id',
    'land_plot_department' => '2',
	'land_plot_department_id' => 'depID',
];
	if (!empty($data['land_area'])) {
		$sqlLand = "INSERT INTO land_plot (" . implode(", ", array_keys($fieldsLand)) . ") VALUES (";
		foreach ($fieldsLand as $field) {
			if (in_array($field, ['dt_update_full', 'author_id', 'land_area', 'own_type_land', 'location_id'])) {
				$data[$field] = intval($data[$field] ?? 0);
			}
			if ($field = 'clyent_id') {
				$data[$field] = $dataID + ($contactMaxID['contact_max_id'] ?? 0);
			}
			if ($field == 'id') {
				if (!empty($buildingMaxID['building_max_id'])) $buildingMaxID['building_max_id']++;
				$data[$field] = $dataID + ($buildingMaxID['building_max_id'] ?? 0) + 1;
			}
			if ($field == 'depID') {
				$data[$field] = $dataID + ($buildingMaxID['building_max_id'] ?? 0) + 1;
			}

			$sqlLand .= "'" . $data[$field] .  "',";
		}
		$sqlLand = substr($sqlLand, 0, -1) . ")";
		echo '.';// .  $sqlLand . "\n\n"; //break; // die();
		$result = mysqli_query($mysqliNew, $sqlCommun);
		//if (!$result) {
		//	echo $sqlLand; die();
		//}
	}

    // Добавление коммуникаций
    // communications

    $fieldsLifts3 = [
        'communications_object_id' => 'id',
        'communications_object_type' => '1', // относиться к зданию
        'communications_electricity_power' => 'power',
		'communications_one_time_load' => 'power_a',
		'communications_electr_reliability_cat' => 'power_b',
		'communications_reserve_power_supply' => 'power_c',
        'communications_electricity' => 'electricity',
        'communications_ventilation' => 'communications_ventilation',
        'communications_air_conditioning' => 'communications_air_conditioning',
        'communications_internet' => 'internet_type',
    ];
    $data['1'] = '1';
    $data['electricity'] = '0';
    $sqlCommun = "INSERT INTO communications (" . implode(", ", array_keys($fieldsLifts3)) . ") VALUES (";
    foreach ($fieldsLifts3 as $field) {
        if (in_array($field, ['electricity']) && !empty($data['power'])) {
            $data['communications_electricity'] = 4;
        }
        //if (in_array($field, ['power', 'gas', 'steam']) && !empty($data[$field])) {
        //    $data[$field] = 1;
        //}
        if ($field == 'communications_heating') {
			if (in_array($data['heating'], [3])) {
				$data['communications_heating'] = '2';
			} elseif (in_array($data['heating'], [1])) {
				$data['communications_heating'] = '5';
			} elseif (in_array($data['heating'], [2])) {
				$data['communications_heating'] = '2,5';
			} else {
				$data['communications_heating'] = '1';
			}
        }
        if ($field == 'internet_type') {
			$data[$field] = in_array(5, $dataIngening) ? 2 : 1;
        }
        if ($field == 'communications_ventilation') {
			$data['communications_ventilation'] = 0;
			if (in_array($data['ventilation'], [2, 3, 4])) {
				$data['communications_ventilation'] = 3;
			} elseif ($data['ventilation'] == 1 || empty($data['ventilation'])) {
				$data['communications_ventilation'] = 1;
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
		if (in_array($field, ['power', 'power_a', 'power_b', 'power_c', 'power'])) {
            $data[$field] = intval($data[$field] ?? 0);
        }
        $sqlCommun .= "'" . $data[$field] .  "',";
    }

    $sqlCommun = substr($sqlCommun, 0, -1) . ")";
    echo '.';// .  $sqlCommun . "\n\n"; //break;
    $result = mysqli_query($mysqliNew, $sqlCommun);
	//if (!$result) {
	//	echo $sqlCommun; die();
	//}
    //die();

    // Добавление парковок и въездов
    // parking_and_entrance

    $fieldsLifts4 = [
        'parking_object_id' => 'id',
        'parking_object_type' => '1', // ?????????????????????
        'parking_object_attributes' => 'dataAttr',
    ];
    $data['1'] = '1';
    $sqlParking = "INSERT INTO parking (" . implode(", ", array_keys($fieldsLifts4)) . ") VALUES (";
    foreach ($fieldsLifts4 as $field) {
        if ($field == 'dataAttr') {
            $data['dataAttr'] = json_encode([
                'spontaneous' => [
                    'is_exist' => !empty($data['parking_s']) && !empty($data['parking_s']) ? 1 : 0,
                    'is_paid' => 0,
                    'amount' => $data['parking_s'] ?? 0,
                    'one_car_price' => '0',
                ],
                'fenced' => [
                    'is_exist' => !empty($data['parking_o']) && !empty($data['parking_o']) ? 1 : 0,
                    'is_paid' => 0,
                    'amount' => $data['parking_o'] ?? 0,
                    'one_car_price' => '0',
                ],
                'in_building' => [
                    'is_exist' => 0,
                    'is_paid' => 0,
                    'amount' => 0,
                    'one_car_price' => 0,
                ],
                'multi_level' => [
                    'is_exist' => !empty($data['parking_m']) && !empty($data['parking_p']) ? 1 : 0,
                    'is_paid' => 0,
                    'amount' => $data['parking_p'] ?? 0,
                    'one_car_price' => '0',
                ],
                'underground' => [
                    'is_exist' => !empty($data['parking_p']) && !empty($data['parking_p']) ? 1 : 0,
                    'is_paid' => 0,
                    'amount' => $data['parking_p'] ?? 0,
                    'one_car_price' => '0',
                ],
                'individual' => [
                    'is_exist' => !empty($data['fence']) && !empty($data['parking_car']) ? 1 : 0,
                    'is_paid' => !empty($data['fence']) && !empty($data['parking_car_type']) ? 1 : 0,
                    'amount' => 0,
                    'one_car_price' => !empty($data['fence']) && !empty($data['parking_car_value']) ? 1 : 0,
                ],
                'city' => [
                    'is_exist' => 0,
                    'is_paid' => 0,
                    'amount' => 0,
                    'one_car_price' => 0,
                ],
                'cargo_to_10t' => [
                    'is_exist' => !empty($data['parking_lorry']) ? 1 : 0,
                    'is_paid' => !empty($data['parking_lorry_type']) ? 1 : 0,
                    'amount' => 0,
                    'one_car_price' => $data['parking_lorry_value'] ?? 0,
                ],
                'cargo_over_10t' => [
                    'is_exist' => !empty($data['parking_truck']) ? 1 : 0,
                    'is_paid' => !empty($data['parking_truck_type']) ? 1 : 0,
                    'amount' => 0,
                    'one_car_price' => $data['parking_truck_value'] ?? 0,
                ],
            ]);
        }
        $sqlParking .= "'" . $data[$field] .  "',";
    }

    $sqlParking = substr($sqlParking, 0, -1) . ")";
    echo '.';// . $sqlParking . "\n\n"; //break;
    $result = mysqli_query($mysqliNew, $sqlParking);
	//if (!$result) {
	//	echo $sqlParking; die();
	//}
    //die();

	// Перенос лифтов с l_cranes
	$fieldsLifts = [
		'mechanism_object_id' => 'id',
		'mechanism_object_type' => '1',
		'mechanism_object_attributes' => 'dataAttr',
	];

	for ($i = 1; $i <= intval($data['lifts']); $i++) {
		//var_dump($data);
		$data['1'] = '2';
		$sqlLifts = "INSERT INTO lifting_mechanisms (" . implode(", ", array_keys($fieldsLifts)) . ") VALUES (";
		foreach ($fieldsLifts as $field) {
			if ($field == 'id') {
				$data[$field] = intval($data[$field]);
			}
			if ($field == 'dataAttr') {
				$data['dataAttr'] = json_encode([
					'type' => 4,
					'location' => 1,
					'control_type' => 2,
					'description' => !empty($data['lifts_text']) ? $data['lifts_text'] : '',
				]);
			}
			$sqlLifts .= "'" . $data[$field] .  "',";
		}

		$sqlLifts = substr($sqlLifts, 0, -1) . ")";
		echo '.';// . $sqlLifts . "\n\n"; break; //die();
		$result = mysqli_query($mysqliNew, $sqlLifts);
		//if (!$result) {
		//	echo $sqlLifts; die();
		//}
	}
	if ($loppIndex % 500 == 0) { echo '__|__'; sleep(2); }
	$loppIndex++;
	//break;
	//die();
}
//die();

echo 'unset';
unset($buildingListData);
sleep(2);

/*
DELETE FROM `building_object` WHERE `id` > 21479;
DELETE FROM `offer` WHERE `id` > 13820;
*/

// Перенос блоков объектов (строений и участков) //c_offices_blocks
$fieldsObjBuilding = [
    //'id' => 'id',
    //'b_obj_photo' => 'photo_block',
    'b_obj_building_id' => 'parent_id',
	'b_obj_offer_id' => '0',
	'b_obj_full_square_min' => 'block_area_min',
	'b_obj_full_square_max' => 'block_area_max',
		//'b_obj_storage_square_min' => 'area_warehouse_min',
		//'b_obj_storage_square_max' => 'area_warehouse_max',
	'b_obj_office_square_min' => 'block_area_min',
	'b_obj_office_square_max' => 'block_area_max',
		//'b_obj_retail_square_min' => '0',
		//'b_obj_retail_square_max' => '0',
		//'b_obj_technical_square_min' => 'area_tech_min',
		//'b_obj_technical_square_max' => 'area_tech_max',
		//'b_obj_public_square_min' => '0',
		//'b_obj_public_square_max' => '0',
		//'b_obj_floor_type' => 'floor_types',
	'b_obj_floor' => 'floor',
	'b_obj_special_floor' => '0',
	'b_obj_ceiling_height_min' => '0',
	'b_obj_ceiling_height_max' => '0',
		//'b_obj_floor_load_min' => 'load_floor_min',
		//'b_obj_floor_load_max' => 'load_floor_max',
	'b_obj_columns_grid' => '0',
		//b_obj_gate
		//'b_obj_cross_docking' => 'cross_docking',
		//'b_obj_layout_features' => 'column_grids',
		//'b_obj_charging_room' => 'charging_room',
		//'b_obj_storage_methods' => 'safe_type',

	'b_obj_department' => '2',
	'b_obj_department_id' => 'depID',
	'b_obj_all_moved_data' => 'all_moved_data',
];

// Перенос предложений //c_offices_blocks
$fieldsOffer = [
    //'id' => 'id',
	'offer_object_id' => 'parent_id',
	'offer_original_id' => '0',
'offer_company_id' => '0',
	'offer_contact_id' => 'clyent_id',
'offer_consultant_id' => '0',
	'offer_hide' => '0',
	'offer_personal_exam' => '0',
		'offer_signing_contract' => 'contract_is_signed', // + contract_is_signed_type (1 - обычный, 2 Эксклюзив)
	'offer_type' => 'deal_type',

	'offer_owner_percent' => 'owner_pays_howmuch',
		'offer_owner_percent_type' => '0',
		'offer_owner_percent_type2' => '0',
		'offer_client_percent' => 'commission_client',
	'offer_tax_system' => 'tax_form',
		'offer_sale_legal_entity' => '0',
	'offer_opex' => 'exp_include',
		'offer_ku_communal' => 'public_services',
		//offer_ku_communal_type => public_services
	'offer_vacation' => 'block_finishing_a',
		'offer_deposit' => 'deposit',
		'offer_built_to_rent' => 'built_to_suit',
		'offer_built_to_suit' => 'built_to_suit',
		'offer_construction_months' => 'built_to_suit_time',
		'offer_project_availability' => 'built_to_suit_plan',

		'offer_with_rental_business' => 'rent_business',
		//offer_annual_rental_flow
		//offer_monthly_rental_flow
		//offer_net_operating_income
		'offer_net_profit' => 'rent_business_profit',
		//offer_capitalization_rate
		'offer_payback_period_chod' => 'rent_business_payback',
		//offer_payback_period_map
		'offer_long_contracts_percent' => 'rent_business_long_contracts',
		//offer_object_occupancy_percent

		//offer_investment_project
		//offer_project_type
		//offer_need_change_tou
		//offer_project_stage
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
		//'offer_inventory' => 'price_safe_service_inventory', // Из таблицы c_industry_blocks !!!!!!!!!!!!!!!!!!!!!!!
		//offer_disposal_waste
		//offer_management_inventory
		//offer_acceptance_refunds
		//offer_pack_repair
		//offer_archive_storage
		//offer_zpl_services
		//offer_delivery_by_city
		//offer_delivery_by_region
		//offer_delivery_by_country

		'offer_room_purpose' => '1',
		//'offer_access_to_object' => 'access_control', // ???????
		'offer_electricity_power' => 'power_value',
		//'offer_water_supply_power' => 'water_value',
		//'offer_gas_for_prod' => 'gas',
		//'offer_steam_for_prod' => 'steam',

		'offer_full_price_min' => 'price_floor_min',
		'offer_full_price_max' => 'price_floor_max',
		//'offer_warehouse_price_min'
		//offer_warehouse_price_max
		//offer_warehouse_price_type
		'offer_office_price_min' => 'price_office_min', // IF empty get from c_industry_blocks
		'offer_office_price_max' => 'price_office_max', // IF empty get from c_industry_blocks
		//offer_office_price_type
		//offer_retail_price_min
		//offer_retail_price_max
		//offer_retail_price_type
		//'offer_technical_price_min' => 'price_tech_min', // IF empty get from c_industry_blocks
		//'offer_technical_price_max' => 'price_tech_max', // IF empty get from c_industry_blocks
		//offer_technical_price_type
		//offer_public_price_min
		//offer_public_price_max
		//offer_public_price_type
		//'offer_land_plot_price_min' => 'price_field_min', // IF empty get from c_industry_blocks
		//'offer_land_plot_price_max' => 'price_field_max', // IF empty get from c_industry_blocks
		//offer_land_plot_price_type
		//offer_entrance_attributes
		//offer_infrastructure_attributes
		//offer_infrastructure_railway = // railway + railway_value

		'offer_offer_description' => '1',
		//'offer_documents_layouts' => 'building_layouts_block', // Из таблицы c_industry_blocks !!!!!!!!!!!!!!!!!!!!!!!
		//offer_tenant_id
		//offer_rental_period
	'offer_status' => 'result',
	'offer_status_reason' => '1',
	'offer_last_update' => 'dt_update_full',

	'offer_department' => '2',
	'offer_department_id' => 'depID',
	'offer_all_moved_data' => 'all_moved_data',
];


//$resultSql = mysqli_query($mysqliOld, "SELECT * FROM `c_offices_blocks`"); // JOIN
for ($i = 1; $i <= 3; $i++) {

	$resultSql = $mysqliOld->query("SELECT * FROM `c_offices_blocks` LIMIT " . (($i-1) * 10000) . ", " . ($i * 10000));
	if (empty($resultSql)) break;
	$data['all_moved_data'] = '';

	while ($data = $resultSql->fetch_assoc()) {
	//foreach ($result as $data) {
		//var_dump('======================================================================', $data); // die();
	// Перенос блоков объектов (строений и участков) //c_offices_blocks

		$sql = "INSERT INTO building_object (" . implode(", ", array_keys($fieldsObjBuilding)) . ") VALUES (";
		$data['0'] = 0;
		$data['1'] = '';
		$data['2'] = '2';
		foreach ($fieldsObjBuilding as $field) {
			if ($field == 'parent_id') {
				$data[$field] = $dataID + ($buildingMaxID['building_max_id'] ?? 0);
			}
			if ($field == 'depID') {
				$data[$field] = $data['id'];
			}
			if (in_array($field, ['block_area_min', 'block_area_max', 'floor'])) {
				$data[$field] = abs($data[$field] ?? 0);
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
		//if (!$result) {
		//	echo $sql; die();
		//}

		//var_dump('======================================================================', $data); // die();
	// Перенос предложений //c_offices_blocks
		$sql = "INSERT INTO offer (" . implode(", ", array_keys($fieldsOffer)) . ") VALUES (";
		$data['0'] = 0;
		$data['1'] = '';
		foreach ($fieldsOffer as $field) {

			if (in_array($field, ['sale_company', 'price_opex_inc', 'cross_docking', 'pallet_place_min'])) {
				$data[$field] = $data[$field] == 1 ? 1 : 0;
			}
			if ($field == 'dt_update_full') {
				if (!empty($data[$field])) $data[$field] = strtotime($data[$field]);
				else $data[$field] = 0;
			}
			if ($field == 'depID') {
				$data[$field] = $data['id'];
			}
			if ($field == 'parent_id') {
				$data[$field] = $dataID + ($buildingMaxID['building_max_id'] ?? 0);
			}
			if ($field == 'contract_is_signed') {
				$data[$field] = 3;
				if (!empty($data[$field]) && $data[$field] == 1) {
					$data[$field] = (!empty($data['contract_is_signed_type']) && in_array($data['contract_is_signed_type'], [1,2]) ? $data['contract_is_signed_type'] : 3);
				}
			}
			if ($field == 'exp_include') {
				$data[$field] = 2;
				if (!empty($data[$field]) && $data[$field] == 2) {
					$data[$field] = 1;
				} else {
					$data[$field] = 0;
				}
			}
			if ($field == 'tax_form') {
				$data[$field] = 0;
				if ($data[$field] == 1) $data[$field] = 2;
				elseif ($data[$field] == 2) $data[$field] = 3;
				elseif ($data[$field] == 3) $data[$field] = 4;
			}
			if (in_array($field, ['owner_pays_howmuch', 'deposit', 'built_to_suit', 'built_to_suit_time', 'built_to_suit_plan', 'rent_business', 'rent_business_profit', 'rent_business_payback', 'rent_business_long_contracts', 'power_value', 'agent_id', 'commission_owner', 'commission_client', 'public_services', 'price_floor_min', 'price_floor_max', 'price_office_min', 'price_office_max', 'deal_type', 'block_finishing_a'])) {
				$data[$field] = abs($data[$field] ?? 0);
			}
			if ($field == 'clyent_id') {
				$data[$field] = $dataID + ($contactMaxID['contact_max_id'] ?? 0);
			}

			if ($field == 'result') {
				//$data['offer_status'] = empty($data['result']) ? 1 : 0;
				$sql .= "'" . (empty($data['result']) ? 1 : 0) .  "',";
			} elseif ($field == 'all_moved_data') {
				$sql .= "'" . json_encode($data['all_moved_data'] ?? '') . "',";
			} else {
				$sql .= "'" . $data[$field] .  "',";
			}

		}

		$sql = substr($sql, 0, -1) . ")";
		echo '.';// . $sql . "\n\n"; break; //die();
		$result = mysqli_query($mysqliNew, $sql);
		//if (!$result) {
		//	echo $sql; die();
		//}
		if ($loppIndex % 500 == 0) { echo '__/\__'; sleep(2); }
		$loppIndex++;
	}
}


// $mysqliOld->close();
$mysqliNew->close();

?>
