<?php
// config
$secrets = require realpath(__DIR__ . "/") . "/config/secrets.php";

// Подключение к базе данных
$db = new mysqli($secrets['db']['host'], $secrets['db']['username'], $secrets['db']['password'], $secrets['db']['dbname']);
if ($db->connect_errno) {
    echo "Failed " . $db->connect_error;
    exit();
}

// Открытие CSV-файла
if (($handle = fopen("vri_all_list.csv", "r")) !== FALSE) {
    while (($row = fgetcsv($handle)) !== FALSE) {
        // Формирование SQL-запроса
        $sql = "INSERT INTO vri (vri_title, vri_description, vri_code) VALUES ('" . implode("', '", $row) . "')";
        $db->query($sql);
    }
    fclose($handle);
}
?>