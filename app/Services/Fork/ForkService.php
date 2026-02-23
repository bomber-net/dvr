<?php

namespace App\Services\Fork;

class ForkService
	{
		public static function run (callable $routine,...$params):int
			{
				if (!$pid=pcntl_fork ())
					{
						$routine (...$params);
						die;
					}
				return $pid;
			}
		
		public static function runAll (array $routines):array
			{
				$pids=[];
				foreach ($routines as $key=>$routine)
					{
						$params=[];
						if (is_array ($routine))
							{
								$params=array_slice ($routine,1);
								$routine=$routine[0]??null;
							};
						if (is_callable ($routine)) $pids[$key]=self::run ($routine,...$params);
					}
				return $pids;
			}
	}
