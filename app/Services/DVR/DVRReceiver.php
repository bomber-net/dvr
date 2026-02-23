<?php

namespace App\Services\DVR;

use App\Models\Camera;
use Illuminate\Support\Facades\Process;

readonly class DVRReceiver
	{
		public function __construct (private Camera $cam,private string $socketFile)
			{
			}
		
		public function __invoke ():void
			{
				$cam=$this->cam;
				$socketFile=$this->socketFile;
				$url="{$cam->proto->value}://$cam->user:$cam->password@$cam->host";
				$cmd="ffmpeg -hide_banner -i $url -c copy -f hevc unix://$socketFile";
				while (true)
					{
						while (!(file_exists ($socketFile) && available_host ($cam->host))) usleep (1000);
						Process::run ($cmd);
					}
			}
	}
