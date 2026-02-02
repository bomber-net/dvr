<?php

namespace App\Services\DVR;

use App\Models\Camera;
use Illuminate\Filesystem\LocalFilesystemAdapter;
use Illuminate\Support\Facades\Concurrency;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Storage;

final class DVRService
	{
		private const string SIGNATURE="\x00\x00\x00\x01\x40\x01";
		private const int OFFSET=6;
		private LocalFilesystemAdapter $disk;
		
		public function __construct (array $config)
			{
				/** @var LocalFilesystemAdapter $disk */
				$disk=Storage::disk ($config['diskname']);
				$this->disk=$disk;
			}
		
		public function run (Camera $cam):void
			{
				$name=$cam->name;
				$socketFile="/tmp/$name.sock";
				$outDir=$this->disk->path ($name);
				Concurrency::run ([
					fn () => $this->writer ($cam,$socketFile,$outDir),
					fn () => $this->receiver ($cam,$socketFile),
				]);
			}
		
		private function writer (Camera $cam,string $socketFile,string $outDir):void
			{
				File::ensureDirectoryExists ($outDir);
				if (file_exists ($socketFile)) unlink ($socketFile);
				$socket=socket_create (AF_UNIX,SOCK_STREAM,0);
				socket_bind ($socket,$socketFile,0);
				socket_listen ($socket);
				$read=socket_accept ($socket);
				$acc='';
				while ($data=socket_read ($read,8192))
					{
						$acc.=$data;
						if ($pos=strpos ($acc,self::SIGNATURE,self::OFFSET))
							{
								$chunk=substr ($acc,0,$pos);
								$acc=substr ($acc,$pos);
								$ts=now ()->format ('Y-m-d H');
								file_put_contents ("$outDir/$cam->name $ts.hevc",$chunk,FILE_APPEND);
							}
					}
				socket_close ($socket);
				unlink ($socketFile);
			}
		
		private function receiver (Camera $cam,string $socketFile):void
			{
				$url="{$cam->proto->value}://$cam->user:$cam->password@$cam->host";
				$cmd="ffmpeg -hide_banner -i $url -c copy -f hevc unix://$socketFile 2>/dev/null";
				while (!file_exists ($socketFile)) usleep (1000);
				Process::run ($cmd);
			}
	}
