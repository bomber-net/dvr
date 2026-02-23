<?php

namespace App\Services\DVR;

use App\Models\Camera;
use App\Services\Fork\ForkService;
use Illuminate\Filesystem\LocalFilesystemAdapter;
use Illuminate\Support\Facades\Storage;

final class DVRService
	{
		private LocalFilesystemAdapter $disk;
		
		public function __construct (array $config)
			{
				/** @var LocalFilesystemAdapter $disk */
				$disk=Storage::disk ($config['diskname']);
				$this->disk=$disk;
			}
		
		public function run (Camera $cam):array
			{
				$name=$cam->name;
				$socketFile="/tmp/$name.sock";
				$outDir=$this->disk->path ($name);
				$pids=ForkService::runAll ([
					new DVRWriter ($cam,$socketFile,$outDir),
					new DVRReceiver ($cam,$socketFile),
				]);
				while (true)
					{
						usleep (1000);
					}
			}
	}
