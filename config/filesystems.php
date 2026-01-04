<?php
return
	[
		'default'=>env ('FILESYSTEM_DISK','local'),
		'disks'=>
			[
				'local'=>
					[
						'driver'=>'local',
						'root'=>storage_path ('app/private'),
						'serve'=>false,
						'throw'=>false,
						'report'=>false,
					],
				'public'=>
					[
						'driver'=>'local',
						'root'=>storage_path ('app/public'),
						'url'=>env ('APP_URL').'/storage',
						'visibility'=>'public',
						'throw'=>false,
						'report'=>false,
					],
				'dvr'=>
					[
						'driver'=>'local',
						'root'=>env ('DVR_DIR',storage_path ('app/public')),
					],
			],
		'links'=>
			[
				public_path ('storage')=>storage_path ('app/public'),
			],
	];
