<?php


namespace App;


class CloudMessage
{
    const API_KEY = 'AIzaSyC8UpS3aECUWjniIYvonDRK1HwQAxHqGZA';
    private $message;
    private $title;
    private $reciever;
    private $type;
    private $name;
    private $platform;


    function __construct($message, $reciever,  $title, $type, $name)
    {
        $user = MobileUser::where('id', $reciever)->first();

        $this->reciever = $user->push_id;
        $this->title = $title;
        $this->platform = $user->platform;
        $this->message = $message;
        $this->type= $type;
        $this->name= $name;

    }

    public function setReciever($reciever){
        $this->reciever = $reciever;
    }

    function sendNotification(){
        $data = array("text" => $this->message, "type" => $this->type, "title" => $this->title, "name" => $this->name);
        if($this->platform == 1){
            $data1 = array('to' => $this->reciever,
                'notification' => ["title" => $this->title, "body" => $this->message, "sound" => "default"]);
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