<?php

namespace App\Console\Commands;

use App\Models\Camera;
use Illuminate\Console\Command;
use Illuminate\Contracts\Console\PromptsForMissingInput;

class MakeCameraCommand extends Command implements PromptsForMissingInput
	{
		protected $signature='make:camera {name} {proto} {host} {user} {password}';
		protected $description='';
		
		public function handle ():void
			{
				$cam=new Camera ($this->argument ());
				$cam->password=$this->argument ('password');
				$cam->save ();
			}
	}
