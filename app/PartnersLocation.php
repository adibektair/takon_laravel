<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PartnersLocation extends Model
{

    protected $fillable = [
        "partner_id",
        "latitude",
        "longitude",
        "address"
    ];


    public function partner()
    {
        return $this->hasOne(Partner::class, "partner_id");
    }
}
