<?php

namespace App\Console\Commands;

use App\Models\Camera;
use App\Services\DVR\DVRService;
use Illuminate\Console\Command;
use Illuminate\Contracts\Console\PromptsForMissingInput;

class DVRCommand extends Command implements PromptsForMissingInput
	{
		protected $signature='dvr {cam}';
		protected $description='';
		
		public function handle (DVRService $service):void
			{
				if ($cam=Camera::query ()->where ('name',$this->argument ('cam'))->first ()) $service->run ($cam);
			}
	}
