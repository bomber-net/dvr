<?php

namespace App\Services\DVR;

use App\Models\Camera;
use Illuminate\Support\Facades\Concurrency;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Process;

final class DVRService
	{
		private const string SIGNATURE="\x00\x00\x00\x01\x40\x01";
		private const int OFFSET=6;
		
		public function __construct (private readonly string $tmpDir,private readonly string $outDir)
			{
			}
		
		public function run (Camera $cam):void
			{
				$socketFile="$this->tmpDir/$cam->name.sock";
				File::ensureDirectoryExists ($this->outDir);
				Concurrency::run ([
					fn () => $this->writer ($cam,$socketFile),
					fn () => $this->receiver ($cam,$socketFile),
				]);
			}
		
		private function writer (Camera $cam,string $socketFile):void
			{
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
								file_put_contents ("$this->outDir/$cam->name $ts.hevc",$chunk,FILE_APPEND);
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
