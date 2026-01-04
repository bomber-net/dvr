<?php
return
	[
		'default'=>env ('DB_CONNECTION','sqlite'),
		'connections'=>
			[
				'mariadb'=>
					[
						'driver'=>'mariadb',
						'url'=>env ('DB_URL'),
						'strict'=>false,
						'options'=>extension_loaded ('pdo_mysql')?array_filter ([PDO::MYSQL_ATTR_SSL_CA=>env ('MYSQL_ATTR_SSL_CA'),PDO::ATTR_EMULATE_PREPARES=>true]):[],
					],
			],
		'migrations'=>
			[
				'table'=>'migrations',
				'update_date_on_publish'=>true,
			],
	];
