<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{

    public function make($status, $title, $message, $reciever_partner_id, $reciever_company_id, $for_superadmin){
        $this->status = $status;
        $this->title = $title;
        $this->message = $message;
        $this->reciever_partner_id = $reciever_partner_id;
        $this->reciever_company_id = $reciever_company_id;
        $this->main = $for_superadmin;
        $this->save();
    }

}
