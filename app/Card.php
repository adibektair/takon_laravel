<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Card extends Model{

    protected $fillable = [
        "mobile_user_id", "last_four", "token", "comment"
    ];
}