<?php


namespace App;


class CloudMessage
{
    const API_KEY = 'AIzaSyAvrCzEiWIkypjQfInobpY6YNUzk-Qk9Sk';
    private $message;
    private $title;
    private $reciever;
    private $type;
    private $name;
    private $platform;


    function __construct($message, $reciever, $platform, $title, $type, $name)
    {
        $this->title = $title;
        $this->platform = $platform;
        $this->message = $message;
        $this->reciever = $reciever;
        $this->type= $type;
        $this->name= $name;

    }

    function sendNotification(){
        $data = array("text" => $this->message, "type" => "1", "title" => $this->title);
        if($this->platform == 1){
            $data1 = array('to' => $this->reciever,
                'notification' => $data);
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