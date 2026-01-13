<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
	{
		public function up ():void
			{
				Schema::create ('cameras',static function (Blueprint $table)
					{
						$table->id ();
						$table->text ('name')->unique ();
						$table->text ('proto')->index ();
						$table->text ('host');
						$table->text ('user');
						$table->text ('password');
						$table->timestamps ();
					});
			}
		
		public function down ():void
			{
				Schema::dropIfExists ('cameras');
			}
	};
