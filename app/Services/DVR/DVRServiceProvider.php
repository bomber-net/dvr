<?php

namespace App\Services\DVR;

use Illuminate\Support\ServiceProvider;

class DVRServiceProvider extends ServiceProvider
	{
		public function register ():void
			{
				$this->app->singleton (DVRService::class,function ()
					{
						['tmpDir'=>$tmpDir,'outDir'=>$outDir]=config ('dvr');
						return new DVRService($tmpDir,$outDir);
					});
			}
		
		public function boot ():void
			{
			}
	}
