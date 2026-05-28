<?php

return [
    'yii_env' => 'prod',
    'yii_debug' => true,
    'ftp_options_for_sync_this_project' => [
        'host' => '62.217.178.108',
        'username' => 'user_api_pennylane',
        'password' => 'studentjke2h',
    ],
    'ftp_options_for_sync_objects_project' => [
        'host' => '62.217.178.108',
        'username' => 'user_pennylane',
        'password' => 'studentjke2h',
    ],
    'ftp_options_for_sync_frontend_project' => [
        'host' => '62.217.178.108',
        'username' => 'user_clients_pennylane',
        'password' => 'studentjke2h',
    ],
    'ftp_options_for_backups_load' => [
        'host' => '62.217.178.108',
        'username' => 'user',
        'password' => 'AP8%KT5u',
    ],

    'adminEmail' => 'info@pennylane.pro',
    'senderEmail' => 'info@pennylane.pro',
    'senderUsername' => 'info@pennylane.pro',
    'senderPassword' => 'Ci5Za6To74vfs3AS',

    'tg_logger_bot' => [
        'token' => '5953633170:AAFp4Qll0QvLdgjCQFfd-lWPKPlfB6Gs_TE',
        'channel' => '@BusinessAppLoggerChannel'
    ],

	'sentry'                                => [
		'dsn' => 'https://957a6bb77ef943309e0a39533e78d4e6@glitch-tip.supermetrolog.ru/3',
	],

    'rabbit' => [
        'user' => 'admin',
        'password' => 'studentjke2h'
    ],

    'db' => [
    	'host' => 'localhost',
        'dbname' => 'user_prod_backend',
        'username' => 'root',
        'password' => 'q12we34r!',
    ],
    'db_old' => [
    	'host' => 'localhost',
        'dbname' => 'user_pennylane',
        'username' => 'root',
        'password' => 'q12we34r!'
    ],
    'importer_db' => [
        'dbname' => 'user_prod_backend',
        'username' => 'root',
        'password' => 'q12we34r!'
    ],
    'importer_db_old' => [
        'dbname' => 'user_pennylane',
        'username' => 'root',
        'password' => 'q12we34r!'
    ],
    'ssh' => [
        'reserve_server' => [
            'username' => 'root',
            'password' => 'mfNvTC_59!Jg',
            'host' => '62.217.178.108',
        ]
    ],

    'allowed_office_ips' => [
		'89.17.38.125'
	],

    'crm_telegram_bot'                      => [
		'token'   => '8359038758:AAEkZ3lDpelt_DwE-tYlA7bdGREdO6XMuv0',
		'webhook' => [
			'secret' => 'Q9x7gNmZ2eL4rC6tW8yK'
		]
    ],
    'crm_whatsapp_bot'                      => [
		'token'     => '965e1fafff00931838c7cd4e5350df6eee8ab83a',
		'profileId' => 'cfa66612-af59'
	]
];
