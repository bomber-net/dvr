<?php

use Illuminate\Support\Str;

return
	[
		'default'=>env ('CACHE_STORE','database'),
		'stores'=>
			[
				'file'=>
					[
						'driver'=>'file',
						'path'=>storage_path ('framework/cache/data'),
						'lock_path'=>storage_path ('framework/cache/data'),
					],
				'failover'=>
					[
						'driver'=>'failover',
						'stores'=>
							[
								'file',
							],
					],
			],
		'prefix'=>env ('CACHE_PREFIX',Str::slug ((string)env ('APP_NAME','laravel')).'-cache-'),
	];
