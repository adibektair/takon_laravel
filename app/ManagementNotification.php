<?php


namespace App;


class ManagementNotification
{
    const API_KEY = 'AIzaSyC8UpS3aECUWjniIYvonDRK1HwQAxHqGZA';
    private $sender;
    private $amount;
    private $service;
    private $reciever;
    private $platform;

    public function __construct($sender, $amount, $reciever, $service, $platform)
    {
        $this->amount = $amount;
        $this->reciever = $reciever;
        $this->sender = $sender;
        $this->service = $service;
        $this->platform = $platform;
    }

    public function send(){
        $data = array("text" => $this->sender . " использовал " . $this->amount . " таконов услуги " . $this->service, "type" => 1, "title" => "Использование");

        if($this->platform == 1){
            $data1 = array('to' => $this->reciever,
                'notification' => ["title" => "Использование", "body" => $this->sender . " использовал " . $this->amount . " таконов услуги " . $this->service, "sound" => "default"]);
        }else{
            $data1 = array('to' => $this->reciever,
                "data" => $data);
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-type: application/json',
            'Authorization: key='.self::API_KEY
        ));
        curl_setopt($ch, CURLOPT_URL,"https://fcm.googleapis.com/fcm/send");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,
            json_encode($data1));

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $server_output = curl_exec ($ch);
        curl_close ($ch);
    }
}