<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Conversion extends Model
{
    protected $fillable = ['first_service_id', 'second_service_id', 'coefficient', 'is_available'];

    public function firstService(){
        return $this->belongsTo(Service::class, 'first_service_id');
    }
    public function secondService(){
        return $this->belongsTo(Service::class, 'second_service_id');
    }
}
