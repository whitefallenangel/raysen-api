<?php
// Конфигурация базы данных
$secrets = require realpath(__DIR__ . "/") . "/public_html/config/secrets.php";

$dbHost = $secrets['db']['host'];
$dbUser = $secrets['db']['username'];
$dbPass = $secrets['db']['password'];
$dbName = $secrets['db']['dbname'];
$dbName2 = $secrets['db_old']['dbname'];

// Конфигурация целевого сервера
$remoteUser = 'root';
$remoteHost = '62.217.178.108';
$remotePath = '/home/user/web/api.pennylane.pro/dbBackup/';

// Директория для временных бэкапов
$backupDir = '/home/user/web/api.pennylane.pro/dbBackup/';
$date = date('Y-m-d');
$backupFile = "{$dbName}_backup_crm_raysen_{$date}.sql";
$backupFile2 = "{$dbName2}_backup_pennylane_{$date}.sql";
$localBackupPath = $backupDir . $backupFile;
$localBackupPath2 = $backupDir . $backupFile2;

// Создаём директорию, если её нет
if (!is_dir($backupDir)) {
    mkdir($backupDir, 0755, true);
}

// Команда для создания бэкапа базы данных через mysqldump
$dumpCommand = "mysqldump -h {$dbHost} -u {$dbUser} -p{$dbPass} {$dbName} > {$localBackupPath}";

// Выполняем команду
exec($dumpCommand, $output, $returnVar);

if ($returnVar !== 0) {
    error_log("Ошибка при создании бэкапа: " . implode("\n", $output));
    exit(1);
}

// Команда для создания бэкапа базы данных через mysqldump
$dumpCommand = "mysqldump -h {$dbHost} -u {$dbUser} -p{$dbPass} {$dbName2} > {$localBackupPath2}";

// Выполняем команду
exec($dumpCommand, $output, $returnVar);

if ($returnVar !== 0) {
    error_log("Ошибка при создании бэкапа: " . implode("\n", $output));
    exit(1);
}

// Сжимаем файл в tar.gz для экономии места
$compressedFile = $localBackupPath . '.tar.gz';
$compressCommand = "tar -czf {$compressedFile} {$localBackupPath}";
exec($compressCommand, $output, $returnVar);

$compressedFile2 = $localBackupPath2 . '.tar.gz';
$compressCommand = "tar -czf {$compressedFile2} {$localBackupPath2}";
exec($compressCommand, $output, $returnVar);

if ($returnVar !== 0) {
    error_log("Ошибка при сжатии файла: " . implode("\n", $output));
    exit(1);
}

// Удаляем исходный SQL‑файл
unlink($localBackupPath);
unlink($localBackupPath2);

// Отправляем файл на удалённый сервер через SCP
/* $scpCommand = "scp {$compressedFile} {$remoteUser}@{$remoteHost}:{$remotePath}";
exec($scpCommand, $output, $returnVar);

if ($returnVar !== 0) {
    error_log("Ошибка при отправке файла на сервер: " . implode("\n", $output));
    exit(1);
} */

// Параметры FTP
$ftpHost = $secrets['ftp_options_for_sync_this_project']['host'];
$ftpUser = $secrets['ftp_options_for_sync_this_project']['username'];
$ftpPass = $secrets['ftp_options_for_sync_this_project']['password'];
$ftpPath = '/dbBackup/'; // Путь на FTP‑сервере

// Подключение к FTP
$connection = ftp_connect($ftpHost, 21, 30);
if (!$connection) {
    error_log("Ошибка подключения к FTP: не удалось установить соединение");
    exit(1);
}

// Авторизация
$login = ftp_login($connection, $ftpUser, $ftpPass);
if (!$login) {
    error_log("Ошибка авторизации на FTP");
    ftp_close($connection);
    exit(1);
}

// Включение пассивного режима (рекомендуется для большинства серверов)
ftp_pasv($connection, true);

// Отправка файла
$upload = ftp_put($connection, $ftpPath . basename($compressedFile), $compressedFile, FTP_BINARY);
$upload = ftp_put($connection, $ftpPath . basename($compressedFile2), $compressedFile2, FTP_BINARY);

if (!$upload) {
    error_log("Ошибка загрузки файла на FTP");
    ftp_close($connection);
    exit(1);
}

// Закрытие соединения
ftp_close($connection);

error_log("Файл успешно отправлен на FTP: " . basename($compressedFile));

// Логируем успешное выполнение
error_log("Бэкап успешно создан и отправлен: {$compressedFile}");
?>
