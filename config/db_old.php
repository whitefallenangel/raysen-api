<?php

return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=localhost;dbname=user_pennylane',
    'username' => 'root',
    'password' => 'q12we34r!',
    'charset' => 'utf8',

    // Schema cache options (for production environment)
    'enableSchemaCache' => true,
    'schemaCacheDuration' => 120,
    'schemaCache' => 'cache',
];