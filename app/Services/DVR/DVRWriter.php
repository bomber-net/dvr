<?php

namespace App\Services\DVR;

use App\Models\Camera;
use Illuminate\Support\Facades\File;

readonly class DVRWriter
	{
		private const string SIGNATURE="\x00\x00\x00\x01\x40\x01";
		private const int OFFSET=6;
		
		public function __construct (private Camera $cam,private string $socketFile,private string $outDir)
			{
			}
		
		public function __invoke ():void
			{
				$outDir=$this->outDir;
				$socketFile=$this->socketFile;
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
								file_put_contents ("$outDir/{$this->cam->name} $ts.hevc",$chunk,FILE_APPEND);
							}
					}
				socket_close ($socket);
				unlink ($socketFile);
			}
	}
