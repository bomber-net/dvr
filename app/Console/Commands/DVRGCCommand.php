<?php

namespace App\Console\Commands;

use App\Services\DVR\DVRGC;
use Illuminate\Console\Command;

class DVRGCCommand extends Command
	{
		protected $signature='dvrgc';
		protected $description='';
		
		public function handle (DVRGC $gc):void
			{
				$gc->run ();
			}
	}
