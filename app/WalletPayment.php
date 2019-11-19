<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WalletPayment extends Model
{
    //
	protected $fillable = [
		'id',
		'service_id',
		'amount',
		'mobile_user_id',
		'price'
	];

	public function getService(){
		return $this->hasOne('App\Service');
	}
	public function getUser(){
		return $this->hasOne('App\MobileUser');
	}

}
