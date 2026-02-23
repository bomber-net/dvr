<?php

namespace App\Models;

use App\Enums\CameraProtocol;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property int            $id
 * @property string         $name
 * @property CameraProtocol $proto
 * @property string         $host
 * @property string         $user
 * @property string         $password
 * @property Carbon         $created_at
 * @property Carbon         $updated_at
 * @property ?string        $ip
 */
class Camera extends Model
	{
		protected $fillable=['name','proto','host','user'];
		protected $casts=
			[
				'proto'=>CameraProtocol::class,
				'password'=>'encrypted',
			];
		protected $hidden=['password'];
		
		public function ip ():Attribute
			{
				return Attribute::get (static function (mixed $value,array $attributes)
					{
						return real_ip ($attributes['host']);
					});
			}
	}
