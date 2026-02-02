<?php

namespace App\Services\DVR;

use Illuminate\Support\ServiceProvider;

class DVRServiceProvider extends ServiceProvider
	{
		public function register ():void
			{
				$config=config ('dvr');
				$this->app->singleton (DVRService::class,fn () => new DVRService($config));
				$this->app->singleton (DVRGC::class,fn () => new DVRGC ($config));
			}
		
		public function boot ():void
			{
			}
	}
