<?php

namespace App\Services\DVR;

use Illuminate\Filesystem\LocalFilesystemAdapter;
use Illuminate\Support\Facades\Storage;

readonly class DVRGC
	{
		private LocalFilesystemAdapter $disk;
		private int $quota;
		
		public function __construct (array $config)
			{
				/** @var LocalFilesystemAdapter $disk */
				$disk=Storage::disk ($config['diskname']);
				$this->disk=$disk;
				$this->quota=$config['quota'];
			}
		
		public function run ():void
			{
				$disk=$this->disk;
				$allFiles=collect ($disk->allFiles ())->map (fn (string $filename) => [$filename,$disk->size ($filename)]);
				$trimSize=$allFiles->sum (fn (array $info) => $info[1])-$this->quota;
				$allFiles->sort (function (array $infoA,array $infoB)
					{
						[$filenameA,$sizeA]=$infoA;
						[$filenameB,$sizeB]=$infoB;
						if ($byDate=strcmp (substr ($filenameA,10,-5),substr ($filenameB,10,-5))) return $byDate;
						return $sizeB-$sizeA;
					})->each (function (array $info) use ($disk,&$trimSize)
					{
						if ($trimSize<0) return false;
						$disk->delete ($info[0]);
						$trimSize-=$info[1];
					});
			}
	}
