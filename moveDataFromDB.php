<?php

require realpath(__DIR__ . "/") . '/vendor/autoload.php';

$secrets = require realpath(__DIR__ . "/") . "/config/secrets.php";
//$config = require  realpath(__DIR__ . "/") . "/config/web.php";

function writeToLog($text) {
    $log = '[' . date('Y-m-d H:i:s') . '] ' . $text;
    file_put_contents(__DIR__ . '/moveDataFromDB.log', $log . PHP_EOL, FILE_APPEND);
}

$mysqliOld = new mysqli($secrets['db_old']['host'], $secrets['db_old']['username'], $secrets['db_old']['password'], $secrets['db_old']['dbname']);
$mysqliNew = new mysqli($secrets['db']['host'], $secrets['db']['username'], $secrets['db']['password'], $secrets['db']['dbname']);
//$mysqliNew = new mysqli($secrets['db']['host'], $secrets['db']['username'], $secrets['db']['password'], 'user_test_backend');

if (!$mysqliOld || !$mysqliOld ) {
    die("Ошибка подключения: " . mysqli_connect_error());
}

$loppIndex = 0;

// Перенос таблиц для правил локаций (метро, шоссе, шоссе Москвы, регионы, районы, города, ж/д станции)
$tablesLocationToMove = [
    'l_metros' => [
        'newTable' => 'metro',
        'fields' => [
            'id' => 'id',
            'metro_title' => 'title',
        ],
    ],
    'l_highways' => [
        'newTable' => 'highway',
        'fields' => [
            'id' => 'id',
            'highway_title' => 'title',
        ],
    ],
    'l_highways_moscow' => [
        'newTable' => 'highway',
        'fields' => [
            //'id' => 'id',
            'highway_title' => 'title',
        ],
    ],
    'l_regions' => [
        'newTable' => 'region',
        'fields' => [
            'id' => 'id',
            'region_title' => 'title',
        ],
    ],
    'l_districts' => [
        'newTable' => 'district',
        'fields' => [
            'id' => 'id',
            'districts_title' => 'title',
            'district_type' => 'district_type',
        ],
    ],
    'l_towns' => [
        'newTable' => 'locality',
        'fields' => [
            'id' => 'id',
            'locality_title' => 'title',
            'locality_type' => 'town_type',
            'locality_district' => 'town_district',
        ],
    ],
    'l_railway_stations' => [
        'newTable' => 'railway_station',
        'fields' => [
            'id' => 'id',
            'railway_station_title' => 'title',
        ],
    ],
];

foreach ($tablesLocationToMove as $tableKey => $tData) {
    $result = mysqli_query($mysqliOld, "SELECT * FROM " . $tableKey);
    foreach ($result as $data) {
        //var_dump($data);
        $sql = "INSERT INTO " . $tData['newTable'] . " (" . implode(", ", array_keys($tData['fields'])) . (
            ($tableKey == 'l_highways' || $tableKey == 'l_highways_moscow') ? ", highway_type" : ""
        ) . ") VALUES (";
        foreach ($tData['fields'] as $field) {
			if (in_array($field, ['town_district', 'town_type', 'district_type'])) {
				$data[$field] = intval($data[$field] ?? 0);
			}
            $sql .= "'" . $data[$field] .  "',";
        }

        if ($tableKey == 'l_highways') $sql .= "'1',";
        if ($tableKey == 'l_highways_moscow') $sql .= "'2',";
        $sql = substr($sql, 0, -1) . ")";
        echo '.'; //$sql . "\n\n"; break; //die();
        $result = mysqli_query($mysqliNew, $sql);
        //break; //die();
    }

}
//die();

// Перенос правил локаций // l_locations
$highwaysCnt = mysqli_query($mysqliOld, "SELECT COUNT(id) as highways_cnt FROM l_highways");
$highwaysCnt = mysqli_fetch_array($highwaysCnt);

$fields = [
    'id' => 'id',
    'location_region' => 'region',
    'location_outside_mkad' => 'outside_mkad',
    'location_inside_mkad' => 'show_inside_mkad',
    'location_in_mo' => 'show_in_mo',
    'location_locality' => 'town', // + towns_relevant
    'location_adjacent_to_mo' => 'near_mo',
    'location_type' => 'town_type',
    'location_near_locality' => 'town_central',
    'location_direction_mo' => 'direction', // + direction_relevant
    'location_district' => 'district',
    'location_district_type' => 'district_type',
    'location_msk_highway' => 'highway_moscow', // + highways_moscow_relevant
    'location_highway' => 'highway', // + highways_relevant
    'location_metro' => 'metro',
];
$result = mysqli_query($mysqliOld, "SELECT * FROM l_locations");
foreach ($result as $data) {
    //var_dump($data);
    $sql = "INSERT INTO location (" . implode(", ", array_keys($fields)) . ") VALUES (";
    foreach ($fields as $field) {
        if (in_array($field, ['metro', 'town_central', 'highway', 'highway_moscow', 'direction']) /*&& !empty($data[$field])*/) {
            $newData = [];
            if ($field == 'highway') {
                if (!empty($data['highways_relevant'])) {
                    foreach (json_decode($data['highways_relevant']) as $newDataVal) {
                        if (!empty($newDataVal)) $newData[] = $newDataVal;
                    }
                }
            }
            if ($field == 'highway_moscow') {
                if (!empty($data['highways_moscow_relevant'])) {
                    foreach (json_decode($data['highways_moscow_relevant']) as $newDataVal) {
                        if (!empty($newDataVal)) $newData[] = $newDataVal + ($highwaysCnt['highways_cnt'] ?? 0);
                    }
                }
            }
            if ($field == 'town_central') {
                if (!empty($data['towns_relevant'])) {
                    foreach (json_decode($data['towns_relevant']) as $newDataVal) {
                        if (!empty($newDataVal)) $newData[] = $newDataVal;
                    }
                }
            }
            if ($field == 'direction') {
                if (!empty($data['direction_relevant'])) {
                    foreach (json_decode($data['direction_relevant']) as $newDataVal) {
                        if (!empty($newDataVal)) $newData[] = $newDataVal;
                    }
                }
            }
            if (!empty($data[$field])) {
                $data[$field] = !empty($newData) ? json_encode(array_merge([$data[$field]], $newData)) : json_encode([ $data[$field] ]);
            } elseif (!empty($newData)) {
                $data[$field] = json_encode($newData);
            }
        }
        if (in_array($field, ['near_mo', 'town_type', 'district_type', 'district', 'outside_mkad', 'show_inside_mkad', 'show_in_mo'])) {
            $data[$field] = intval($data[$field] ?? 0);
        }

        $sql .= "'" . $data[$field] .  "',";
    }
    $sql = substr($sql, 0, -1) . ")";
    echo '.'; //echo $sql . "\n\n"; //break; //die();
    $result = mysqli_query($mysqliNew, $sql); //die();
}
// die();
// get('db_old')

// Перенос комплексов //c_industry_complex
$fields = [
    'id' => 'id',
    'complex_title' => 'title',
    'complex_location' => 'location_id',
];
$result = mysqli_query($mysqliOld, "SELECT * FROM c_industry_complex");
foreach ($result as $data) {
    //var_dump($data);
    $sql = "INSERT INTO complex (" . implode(", ", array_keys($fields)) . ") VALUES (";
    foreach ($fields as $field) {
        //if (in_array($field, ['near_mo', 'town_type'])) {
        //    $data[$field] = intval($data[$field] ?? 0);
        //}

        $sql .= "'" . $data[$field] .  "',";
    }
    $sql = substr($sql, 0, -1) . ")";
    echo '.'; //echo $sql . "\n\n"; //break; //die();
    $result = mysqli_query($mysqliNew, $sql); //die();
}
// die();

// Перенос объектов (строения и участки) //c_industry
$fieldsLand = [
    'id' => 'id',
	'land_plot_department_id' => 'id',
    'land_plot_square' => 'area_field_full',
    'land_plot_permition' => '0', ///////////// TODO - FROM TEXT TO INT !!!!!!!!!!!!!!!!!!!!!!!!!
    'land_plot_permition_text' => 'field_allow_usage',
    'land_plot_in_main_sections' => 'in_main_sections',
    'land_plot_restrictions' => 'land_use_restrictions',
    'land_plot_attributes' => '1',
    //land_plot_infrastructure -------- ?????
    'land_plot_title' => 'title',
    'land_plot_location' => 'location_id',
    'land_plot_category' => 'land_category', // возможно l_category
    'land_plot_last_update' => 'last_update',
    'land_plot_management_company' => 'company_id',
    'land_plot_management_company_type' => 'company_id_type',
    'land_plot_owner' => 'owners',
    'land_plot_owner_type' => 'own_type_land',
    'land_plot_owner_contact' => 'contact_id',
    'land_plot_address' => 'address',
    'land_plot_kadastr' => 'cadastral_number',
    'land_plot_description' => 'description',
    'land_plot_in_complex' => 'land_plot_in_complex',
    'land_plot_complex_id' => 'complex_id',
    'land_plot_latitude' => 'latitude',
    'land_plot_longitude' => 'longitude',
    'land_plot_author_id' => 'author_id',
    'land_plot_from_mkad' => 'from_mkad',
    'land_plot_photo' => 'photo',
    'land_plot_test_only' => 'test_only',
    'land_plot_layouts' => 'building_layouts',
    'land_plot_department' => '1',
];
$fieldsBuilding = [
    'id' => 'id',
	'building_department_id' => 'id',
    'building_ready_status' => '0',
    'building_square' => 'area_building',
    'building_floors_counts' => 'floors',
    'building_allowed_electro_power' => 'power',
    'building_external_decor' => 'facing_type',
    'building_in_main_sections' => 'in_main_sections',
    'building_restrictions' => 'land_use_restrictions',
    'building_attributes' => '1',
    //`building_infrastructure` -------- ?????
    'building_title' => 'title',
    'building_location' => 'location_id',
    'building_last_update' => 'last_update',
    'building_management_company' => 'company_id',
    'building_management_company_type' => 'company_id_type',
    'building_owner' => 'owners',
    'building_owner_type' => 'own_type_land',
    'building_line' => 'first_line',
    'building_owner_contact' => 'contact_id',
    'building_type' => 'type',
    'building_address' => 'address',
    'building_kadastr' => 'cadastral_number',
    'building_year_construction' => 'year_build',
    'building_year_repairs' => 'year_repair',
    'building_description' => 'description',
    'building_class' => 'object_class',
    'building_complex_id' => 'complex_id',
    'building_in_complex' => 'building_in_complex',
    'building_latitude' => 'latitude',
    'building_longitude' => 'longitude',
    'building_author_id' => 'author_id',
    'building_from_mkad' => 'from_mkad',
    'building_photo' => 'photo',
    'building_test_only' => 'test_only',
    'building_property_documents' => 'building_property_documents',
    'building_layouts' => 'building_layouts',
    'building_department' => '1',
];

$result = mysqli_query($mysqliOld, "SELECT * FROM `c_industry`"); // JOIN c_industry_complex (c_industry.complex_id) и взять недостающие данные
foreach ($result as $data) {
    //var_dump('======================================================================', $data); //var_dump($data['id']); die();
    $fields = !empty($data['is_land']) ? $fieldsLand : $fieldsBuilding;
    $dataTable = !empty($data['is_land']) ? 'land_plot' : 'building';
    $sql = "INSERT INTO {$dataTable} (" . implode(", ", array_keys($fields)) . ") VALUES (";
    $data['0'] = 0;
    $data['1'] = '';
    $data['2'] = '1';
    $data['in_main_sections'] = 3;
    foreach ($fields as $field) {
        if (in_array($field, ['building_in_complex', 'land_plot_in_complex']) ) {
            if ($field == 'building_in_complex' && !empty($data['building_in_complex'])) {
                $data[$field] = '1';
            } elseif ($field == 'building_in_complex') {
                $data[$field] = '3';
            }
            if ($field == 'land_plot_in_complex' && !empty($data['land_plot_in_complex'])) {
                $data[$field] = '1';
            } elseif ($field == 'land_plot_in_complex') {
                $data[$field] = '3';
            }
        }
        if ($field == 'land_use_restrictions') {
            $data[$field] = ($data['land_use_restrictions'] ?? 0) == 1 ? ' ' : '';
        }
        if (in_array($field, ['contact_id', 'type', 'test_only', 'author_id', 'from_mkad', 'power', 'own_type_land', 'year_build', 'year_repair', 'floors' , 'area_building', 'area_field_full', 'location_id'])) {
            $data[$field] = intval($data[$field] ?? 0);
        }
        if ($field == 'company_id_type') {
            if (!empty($data['company_id'])) {
                $resultCompany = mysqli_query($mysqliNew, "SELECT id, formOfOrganization FROM `company` WHERE id = " . $data['company_id']);
                $company = mysqli_fetch_array($resultCompany);
                $data[$field] = $company['formOfOrganization'] ?? 0;
            } else {
                $data[$field] = 0;
            }
        }
        if ($field == 'photo' && !empty($data['photo'])) {
            $dataPhotos = json_decode($data['photo'], true);

            foreach ($dataPhotos as $photo) {
               if (!file_exists( __DIR__ . '/public_html' . dirname($photo) )) {
                    mkdir(__DIR__ . '/public_html' . dirname($photo), 0755, true);
               }
               $fileContent  = file_get_contents('https://pennylane.pro' . str_replace(' ', '%20', $photo));
               $dataMoveFile = file_put_contents(__DIR__ . '/public_html' . $photo, $fileContent);
               if (empty($dataMoveFile)) writeToLog('Photo ' . $photo . ' was not transferred');
            }
        }
        if ($field == 'building_property_documents' && !empty($data['building_property_documents'])) {
            $dataDocs = json_decode($data['building_property_documents'], true);

            foreach ($dataDocs as $doc) {
               if (!file_exists( __DIR__ . '/public_html' . dirname($doc) )) {
                    mkdir(__DIR__ . '/public_html' . dirname($doc), 0755, true);
               }
               $fileContent  = file_get_contents('https://pennylane.pro' . str_replace(' ', '%20', $doc));
               $dataMoveFile = file_put_contents(__DIR__ . '/public_html' . $doc, $fileContent);
               if (empty($dataMoveFile)) writeToLog('Document ' . $doc . ' was not transferred');
            }
        }
        $sql .= "'" . $data[$field] .  "',";

    }
    $sql = substr($sql, 0, -1) . ")";
    echo '.'; // $sql . "\n\n"; //break; //die();
    $result = mysqli_query($mysqliNew, $sql); //die();

    // Добавление безопасности
    // security

    $fieldsLifts2 = [
        'security_object_id' => 'id',
        'security_object_type' => 'security_type',
        'security_object_attributes' => 'dataAttr',
    ];
    $sqlSecurity = "INSERT INTO security (" . implode(", ", array_keys($fieldsLifts2)) . ") VALUES (";
    foreach ($fieldsLifts2 as $field) {
        if ($field == 'security_type') {
            $data['security_type'] = empty($data['is_land']) ? '1' : '2';
        }
        if ($field == 'dataAttr') {
            if (empty($data['is_land'])) {
                $data['dataAttr'] = json_encode([
                    'boom_barrier' => ($data['barrier'] ?? 0) == 1 ? 1 : 0,
                    'fence' => ($data['fence'] ?? 0) == 1 ? 1 : 0,
                    'perimeter_security' => 0,
                    'security_alert' => ($data['security_alert'] ?? 0) == 1 ? 1 : 0,
                    'access_control' => ($data['access_control'] ?? 0) == 1 ? 1 : 0,
                    'video_control' => ($data['video_control'] ?? 0) == 1 ? 1 : 0,
                ]);
            } else {
                $data['dataAttr'] = json_encode([
                    'fire_alert' => ($data['fire_alert'] ?? 0) == 1 ? 1 : 0,
                    'fire_exhaust' => 0,
                    'smoke_exhaust' => ($data['smoke_exhaust'] ?? 0) == 1 ? 1 : 0,
                    'security_alert' => ($data['security_alert'] ?? 0) == 1 ? 1 : 0,
                    'access_control' => ($data['access_control'] ?? 0) == 1 ? 1 : 0,
                    'video_control' => ($data['video_control'] ?? 0) == 1 ? 1 : 0,
                ]);
            }
        }
        $sqlSecurity .= "'" . $data[$field] .  "',";
    }

    $sqlSecurity = substr($sqlSecurity, 0, -1) . ")";
    echo '.'; //$sqlSecurity . "\n\n"; //break; die();
    $result = mysqli_query($mysqliNew, $sqlSecurity); //die();

    // Добавление коммуникаций
    // communications

    $fieldsLifts3 = [
        'communications_object_id' => 'id',
        'communications_object_type' => '1', // относиться к зданию
        'communications_electricity_power' => 'power',
        'communications_electricity' => 'electricity',
        'communications_water_supply' => 'water',
        'communications_water_supply_power' => 'water_value',
        'communications_sewerage' => 'communications_sewerage',
        'communications_sewerage_power' => 'sewage_central_value',
        'communications_stormwater_drainage' => 'sewage_rain',
        'communications_heating' => 'heating',
        'communications_heating_power' => '0',
        'communications_gas' => 'gas',
        'communications_gas_power' => 'gas_value',
        'communications_steam' => 'steam',
        'communications_steam_power' => 'steam_value',
        //'communications_lighting' => 'dataAttr',
        'communications_ventilation' => 'communications_ventilation',
        'communications_air_conditioning' => 'communications_air_conditioning',
        'communications_bathroom' => '0',
        'communications_internet' => 'internet_type',
    ];
    $data['0'] = '0';
    $data['1'] = '1';
    $sqlCommun = "INSERT INTO communications (" . implode(", ", array_keys($fieldsLifts3)) . ") VALUES (";
    foreach ($fieldsLifts3 as $field) {
        if (in_array($field, ['electricity']) && !empty($data['power'])) {
            $data['electricity'] = 4;
        }
        if (in_array($field, ['sewage_rain']) && !empty($data[$field])) {
            $data['communications_stormwater_drainage'] = 1;
        }
        if (in_array($field, ['power', 'gas', 'steam']) && !empty($data[$field])) {
            $data[$field] = 1;
        }
        if ($field == 'communications_sewerage') {
            $data['communications_sewerage'] = ($data['sewage_central'] ?? 0) == 1 ? 5 : 2;
        }
        if ($field == 'communications_heating') {
            $data['communications_heating'] = ($data['heating'] ?? 0) == 1 ? 5 : 2;
        }
        if ($field == 'communications_ventilation') {
            $data['communications_ventilation'] = in_array($data['ventilation'], [1, 2, 3]) ? $data['ventilation'] : 1;
        }
        if ($field == 'communications_air_conditioning') {
            $data['communications_air_conditioning'] = ($data['ventilation'] ?? 0) == 4 ? 2 : 0;
        }
		if (in_array($field, ['sewage_central_value', 'gas_value', 'steam_value', 'power', 'water_value', 'sewage_central_value', 'electricity'])) {
            $data[$field] = intval($data[$field] ?? 0);
        }
        $sqlCommun .= "'" . $data[$field] .  "',";
    }

    $sqlCommun = substr($sqlCommun, 0, -1) . ")";
    echo '.'; // $sqlCommun . "\n\n"; //break;
    $result = mysqli_query($mysqliNew, $sqlCommun);
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
                    'is_exist' => 0,
                    'is_paid' => 0,
                    //'amount' => '0',
                    //'one_car_price' => '0',
                ],
                'fenced' => [
                    'is_exist' => !empty($data['fence']) && !empty($data['parking_car']) ? 1 : 0,
                    'is_paid' => !empty($data['fence']) && !empty($data['parking_car_type']) ? 1 : 0,
                    'amount' => 0,
                    'one_car_price' => !empty($data['fence']) && !empty($data['parking_car_value']) ? 1 : 0,
                ],
                'in_building' => [
                    'is_exist' => 0,
                    'is_paid' => 0,
                    'amount' => 0,
                    'one_car_price' => 0,
                ],
                'multi_level' => [
                    'is_exist' => 0,
                    'is_paid' => 0,
                    'amount' => 0,
                    'one_car_price' => 0,
                ],
                'underground' => [
                    'is_exist' => 0,
                    'is_paid' => 0,
                    'amount' => 0,
                    'one_car_price' => 0,
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

	if (!empty($data['entry_territory'])) {
		$fieldsLifts6 = [
			'entrance_object_id' => 'id',
			'entrance_object_type' => '1', // относиться к зданию
			'entrance_object_attributes' => 'dataAttr',
		];
		$data['1'] = '1';
		$sqlEntrance = "INSERT INTO railway (" . implode(", ", array_keys($fieldsLifts6)) . ") VALUES (";
		foreach ($fieldsLifts6 as $field) {
			if ($field == 'dataAttr') {
				$data['dataAttr'] = json_encode([
					'type' => $data['entry_territory'] == 2 ? 1 : 2,
				]);
			}
			$sqlEntrance .= "'" . $data[$field] .  "',";
		}
		$sqlEntrance = substr($sqlEntrance, 0, -1) . ")";
		echo '.'; // $sqlEntrance . "\n\n"; //break;
	}

    $sqlParking = substr($sqlParking, 0, -1) . ")";
    echo '.'; $sqlParking . "\n\n"; //break;
    $result = mysqli_query($mysqliNew, $sqlParking);
    //die();

    // Добавление Ж/Д ветки
    // railway + railway_value

	if (empty($data['railway']) || $data['railway'] == 2) continue;
    $fieldsLifts5 = [
        'railway_object_id' => 'id',
        'railway_object_type' => '1', // относиться к зданию
        'railway_object_attributes' => 'dataAttr',
    ];
    $data['1'] = '1';
    $sqlRailway = "INSERT INTO railway (" . implode(", ", array_keys($fieldsLifts5)) . ") VALUES (";
    foreach ($fieldsLifts5 as $field) {
        if ($field == 'dataAttr') {
            $data['dataAttr'] = json_encode([
                'type' => 0,
                'length' => $data['railway_value'] ?? 0,
                'number_of_van' => 0,
                'railway_ramp' => 0,
                'railway_ramp_length' => 0,
                'condition' => 0,
                'under_supervision' => 0,
                'documents_exist' => 0,
                'description' => '',
                'photo' => '',
                'documents' => '',
            ]);
        }
        $sqlRailway .= "'" . $data[$field] .  "',";
    }

    $sqlRailway = substr($sqlRailway, 0, -1) . ")";
    echo '.'; // $sqlRailway . "\n\n"; //break;
    $result = mysqli_query($mysqliNew, $sqlRailway);
    //die();
	if ($loppIndex % 500 == 0) { echo '__|__'; sleep(2); }
	$loppIndex++;

}

// Перенос кранов с l_cranes
$cranesMaxId = mysqli_query($mysqliOld, "SELECT MAX(id) as max_id FROM l_cranes");
$cranesMaxId = mysqli_fetch_array($cranesMaxId);

$fieldsLifts = [
    'id' => 'id',
    'mechanism_object_id' => 'object_id',
    'mechanism_object_type' => '1',
    'mechanism_object_attributes' => 'dataAttr',
];
$result = mysqli_query($mysqliOld, "SELECT * FROM l_cranes");
foreach ($result as $data) {
    //var_dump($data);
    $data['1'] = '1';
    $sqlLifts = "INSERT INTO lifting_mechanisms (" . implode(", ", array_keys($fieldsLifts)) . ") VALUES (";
    foreach ($fieldsLifts as $field) {
        if ($field == 'dataAttr') {
            $data['dataAttr'] = json_encode([
                'availability' => 1,
                'load_capacity' => $data['crane_capacity'] ?? 0,
                'classification' => $data['crane_type'] ?? 0,
                'location' => $data['crane_location'] ?? 0,
                'beam_type' => $data['crane_beam'] ?? 0,
                'span_length' => $data['crane_span'] ?? 0,
                'beams_amount' => $data['crane_beams_amount'] ?? 0,
                'mechanism_type' => 0,
                'control_type' => $data['crane_controls'] ?? '',
                'hooks_amount' => $data['crane_hooks'] ?? 0,
                'hook_height' => $data['crane_hook_height'] ?? 0,
                'condition' => $data['crane_condition'] ?? 0,
                'under_supervision' => !empty($data['crane_supervision']) && $data['crane_supervision'] == 1 ? 1 : 0,
                'documents_exist' => !empty($data['crane_documents']) && $data['crane_documents'] == 1 ? 1 : 0,
                'description' => $data['description'] ?? '',
                'photo' => $data['photo'] ?? '',
                'documents' => '',
            ]);
        }
        $sqlLifts .= "'" . $data[$field] .  "',";
    }

    $sqlLifts = substr($sqlLifts, 0, -1) . ")";
    echo '.'; // $sqlLifts . "\n\n"; //break; //die();
    $result = mysqli_query($mysqliNew, $sqlLifts);
}
//die();

$result = mysqli_query($mysqliOld, "SELECT * FROM l_elevators");
foreach ($result as $data) {
    //var_dump($data);
    $data['1'] = '2';
    $sqlLifts = "INSERT INTO lifting_mechanisms (" . implode(", ", array_keys($fieldsLifts)) . ") VALUES (";
    foreach ($fieldsLifts as $field) {
        if ($field == 'id') {
            $data[$field] = intval($data[$field]) + ($cranesMaxId['max_id'] ?? 0);
        }
        if ($field == 'dataAttr') {
            $data['dataAttr'] = json_encode([
                'type' => $data['elevator_type'] ?? 0,
                'load_capacity' => $data['elevator_capacity'] ?? 0,
                'location' => $data['elevator_location'] ?? 0,
                'width' => $data['elevator_width'] ?? 0,
                'length' => $data['elevator_length'] ?? 0,
                'pallet_capacity' => $data['elevator_volume'] ?? 0,
                'people_capacity' => 0,
                'control_type' => $data['elevator_controls'] ?? '',
                'condition' => $data['elevator_condition'] ?? 0,
                'under_supervision' => !empty($data['elevator_supervision']) && $data['elevator_supervision'] == 1 ? 1 : 0,
                'documents_exist' => !empty($data['elevator_documents']) && $data['elevator_documents'] == 1 ? 1 : 0,
                'description' => $data['description'] ?? '',
                'photo' => $data['photo'] ?? '',
                'documents' => '',
            ]);
        }
        $sqlLifts .= "'" . $data[$field] .  "',";
    }

    $sqlLifts = substr($sqlLifts, 0, -1) . ")";
    echo '.'; // $sqlLifts . "\n\n"; //break; //die();
    $result = mysqli_query($mysqliNew, $sqlLifts);
}
//die();

// Перенос блоков объектов (строений и участков) //c_industry_offers_mix
$fieldsObjBuilding = [
    'id' => 'id',
    'b_obj_photo' => 'photo_block',
    'b_obj_building_id' => 'object_id',
	'b_obj_offer_id' => 'offer_id',
	'b_obj_full_square_min' => '0', // area_floor_full from c_industry_offers_mix
	'b_obj_full_square_max' => '0', // area_floor_max from c_industry_offers_mix
    'b_obj_storage_square_min' => 'area_warehouse_min',
    'b_obj_storage_square_max' => 'area_warehouse_max',
    'b_obj_office_square_min' => 'area_office_min',
    'b_obj_office_square_max' => 'area_office_max',
    'b_obj_retail_square_min' => '0',
    'b_obj_retail_square_max' => '0',
    'b_obj_technical_square_min' => 'area_tech_min',
    'b_obj_technical_square_max' => 'area_tech_max',
    'b_obj_public_square_min' => '0',
    'b_obj_public_square_max' => '0',
    'b_obj_floor_type' => 'floor_types',
    'b_obj_floor' => 'floor_min',
    'b_obj_special_floor' => '0',
    'b_obj_ceiling_height_min' => 'floor_min',
    'b_obj_ceiling_height_max' => 'floor_max',
    'b_obj_floor_load_min' => 'load_floor_min',
    'b_obj_floor_load_max' => 'load_floor_max',
    'b_obj_columns_grid' => 'column_grids',
    //b_obj_gate
    'b_obj_cross_docking' => 'cross_docking',
    'b_obj_layout_features' => 'column_grids',
    'b_obj_charging_room' => 'charging_room',
    'b_obj_storage_methods' => 'safe_type',
	'b_obj_department' => '1',
	//'b_obj_department_id' => 'depID',
];
$fieldsObjLand = [
    'id' => 'id',
    'b_obj_land_plot_id' => 'object_id',
    'lp_object_square_min' => 'area_field_min',
    'lp_object_square_max' => 'area_field_max',
    //'land_plot_permition' => 'field_allow_usage', // ?????????????????????? TO DO FROM TEXT TO INT из базы c_industry_offers_mix
    'lp_object_length' => 'land_length',
    'lp_object_width' => 'land_width',
    'lp_object_coverage' => 'floor_types',
    'lp_object_relief' => 'landscape_type',
    'lp_object_encumbrances' => 'land_use_restrictions',
    'lp_obj_photo' => 'photo_block',
];

$result = mysqli_query($mysqliOld, "SELECT * FROM `c_industry_blocks`"); // JOIN
foreach ($result as $data) {
    //var_dump('======================================================================', $data); // die();
    $fields = !empty($data['is_land']) ? $fieldsObjLand : $fieldsObjBuilding;
    $dataTable = !empty($data['is_land']) ? 'land_plot_object' : 'building_object';
    $sql = "INSERT INTO {$dataTable} (" . implode(", ", array_keys($fields)) . ") VALUES (";
    $data['0'] = 0;
    $data['1'] = '';
    foreach ($fields as $field) {
        if ($field == 'photo_block' && !empty($data['photo_block'])) {
            $dataPhotos = json_decode($data['photo_block'], true);

			if (is_array($dataPhotos) || is_object($dataPhotos)) {
				foreach ($dataPhotos as $photo) {
					if (!file_exists( __DIR__ . '/public_html' . dirname($photo) )) {
						mkdir(__DIR__ . '/public_html' . dirname($photo), 0755, true);
					}
					$imageContent = @file_get_contents('https://pennylane.pro' . str_replace(' ', '%20', $photo));
					$dataMoveFile = file_put_contents(__DIR__ . '/public_html' . $photo, $imageContent);
					if (empty($dataMoveFile)) writeToLog('Block photo ' . $photo . ' was not transferred');
				}
			}
        }
        if (in_array($field, ['land_use_restrictions'])) {
            $data[$field] = ($data[$field] ?? 0) == 1 ? ' - ' : '';
        }
        if (in_array($field, ['charging_room', 'cross_docking'])) {
            $data[$field] = ($data[$field] ?? 0) == 1 ? 1 : 0;
        }
        if (in_array($field, ['area_warehouse_min', 'area_warehouse_max', 'area_tech_min', 'area_tech_max', 'floor_min', 'floor_max', 'area_office_min', 'area_office_max', 'load_floor_min', 'load_floor_max'])) {
            $data[$field] = abs($data[$field] ?? 0);
        }
        if ($field == 'field_allow_usage') {
            $data[$field] = 0;
        }
        if ($field == 'landscape_type' && empty($data[$field])) {
            $data[$field] = 0;
        }

        $sql .= "'" . $data[$field] .  "',";
    }

    $sql = substr($sql, 0, -1) . ")";
    echo '.'; // $sql . "\n\n"; //break; //die();
    $result = mysqli_query($mysqliNew, $sql); //die();
	if ($loppIndex % 1000 == 0) { echo '__-__'; sleep(2); }
	$loppIndex++;
}
//die();

// Перенос предложений (строений и участков) //c_industry_offers_mix
$fieldsOffer = [
    'id' => 'id',
	'offer_object_id' => 'object_id',
	'offer_original_id' => 'original_id',
    'offer_company_id' => 'company_id',
    'offer_contact_id' => 'contact_id',
    'offer_consultant_id' => 'agent_id',
    'offer_hide' => 'hide_from_market',
    'offer_personal_exam' => 'agent_visited',
    'offer_signing_contract' => 'contract_is_signed', // + contract_is_signed_type (1 - обычный, 2 Эксклюзив)
	'offer_type' => 'deal_type',

    'offer_owner_percent' => 'commission_owner',
    'offer_owner_percent_type' => '0',
    'offer_owner_percent_type2' => '0',
    'offer_client_percent' => 'commission_client',
    'offer_tax_system' => 'tax_form',
    'offer_sale_legal_entity' => 'sale_company',
    'offer_opex' => 'price_opex_inc',
    'offer_ku_communal' => 'public_services',
    //offer_ku_communal_type => public_services
    'offer_vacation' => 'holidays',
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
    'offer_cross_docking' => 'cross_docking',
    //'offer_boxes_recalc'
    //offer_product_culling
    //offer_product_repack
    //offer_pallet_formation
    //offer_stretch_tape_wrapp
    //offer_batch_accounting
    //offer_serial_numbers_account
    //offer_in_fifi_lifi_fefo_lefo
    //offer_product_choice
    'offer_provision_pallets' => 'pallet_place_min',
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

    'offer_room_purpose' => 'purposes',
    //'offer_access_to_object' => 'access_control', // ???????
    'offer_electricity_power' => 'power_value',
    'offer_water_supply_power' => 'water_value',
    'offer_gas_for_prod' => 'gas',
    'offer_steam_for_prod' => 'steam',

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

    'offer_status' => 'deleted',
    'offer_offer_description' => 'description',
    //'offer_documents_layouts' => 'building_layouts_block', // Из таблицы c_industry_blocks !!!!!!!!!!!!!!!!!!!!!!!
    //offer_tenant_id
    //offer_rental_period
    'offer_status' => 'status',
    'offer_status_reason' => '1',
    'offer_last_update' => 'last_update',
	'offer_department' => '1',
	//'offer_department_id' => 'depID',
];
//$result = mysqli_query($mysqliOld, "SELECT ciom.*, building_layouts_block FROM `c_industry_offers_mix` AS ciom LEFT JOIN `c_industry_blocks` AS cib ON ciom.object_id = cib.object_id");
$result = mysqli_query($mysqliOld, "SELECT * FROM `c_industry_offers_mix`");
foreach ($result as $data) {
    //var_dump('======================================================================', $data); // die();
    $sql = "INSERT INTO offer (" . implode(", ", array_keys($fieldsOffer)) . ") VALUES (";
    $data['0'] = 0;
    $data['1'] = '';
    foreach ($fieldsOffer as $field) {

        if (in_array($field, ['sale_company', 'price_opex_inc', 'cross_docking', 'pallet_place_min'])) {
            $data[$field] = $data[$field] == 1 ? 1 : 0;
        }
        if ($field == 'contract_is_signed') {
            $data[$field] = 3;
            if (!empty($data[$field]) && $data[$field] == 1) {
                $data[$field] = (!empty($data['contract_is_signed_type']) && in_array($data['contract_is_signed_type'], [1,2]) ? $data['contract_is_signed_type'] : 3);
            }
        }
        if ($field == 'tax_form') {
            $data[$field] = 0;
            if ($data[$field] == 'с ндс') $data[$field] = 2;
            elseif ($data[$field] == 'triple net') $data[$field] = 1;
            elseif ($data[$field] == 'усн') $data[$field] = 4;
            elseif ($data[$field] == 'без ндс') $data[$field] = 4;
        }
		if (in_array($field, ['holidays', 'deposit', 'built_to_suit', 'built_to_suit_time', 'built_to_suit_plan', 'rent_business', 'rent_business_profit', 'rent_business_payback', 'rent_business_long_contracts', 'power_value', 'agent_id', 'contact_id', 'agent_visited', 'commission_owner', 'commission_client', 'public_services', 'price_floor_min', 'price_floor_max', 'price_office_min', 'price_office_max', 'deal_type'])) {
			$data[$field] = abs($data[$field] ?? 0);
		}
        if ($field == 'deleted') {
            //unset($data[$field]);
            $data['offer_status'] = empty($data[$field]) ? 1 : 0;
            $sql .= "'" . $data['offer_status'] .  "',";
        } else {
            $sql .= "'" . $data[$field] .  "',";
        }

    }

    $sql = substr($sql, 0, -1) . ")";
    echo '.'; // $sql . "\n\n"; //break; //die();
    $result = mysqli_query($mysqliNew, $sql); //die();
}


$mysqliOld->close();
$mysqliNew->close();

?>
