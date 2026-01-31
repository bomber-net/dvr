<?php

namespace App\Services\DVR;

use Illuminate\Support\ServiceProvider;

class DVRServiceProvider extends ServiceProvider
	{
		public function register ():void
			{
				$this->app->singleton (DVRService::class,function ()
					{
						return new DVRService('/tmp',config ('filesystems.disks.dvr.root'));
					});
			}
		
		public function boot ():void
			{
			}
	}
