<?php

use Illuminate\Support\Facades\Process;

if (!function_exists ('real_ip'))
	{
		function real_ip (string $host):?string
			{
				$ip=gethostbyname ($host);
				$ipPacked=inet_pton ($ip);
				return $ipPacked?$ip:null;
			}
	}
if (!function_exists ('available_host'))
	{
		function available_host (string $host):bool
			{
				$ip=real_ip ($host);
				return $ip && !Process::run ("ping -c 1 $ip")->failed ();
			}
	}
