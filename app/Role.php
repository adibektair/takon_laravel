<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    public const ADMIN_ID = 1;
    public const PARTNER_ID = 2;
    public const COMPANY_ID = 3;
    public const CASHIER_ID = 4;
}
