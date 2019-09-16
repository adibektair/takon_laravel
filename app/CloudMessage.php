<?php


namespace App;


class CloudMessage
{


    public function sendSilentThroughNode($to, $platform, $message, $type, $title){


        $data = array("text" => $message, "type" => $type, "title" => $title);
        if($platform == 1){
            $data1 = array('to' => $to,
                'notification' => ["title" => $title, "body" => $message, "sound" => "default"]);
        }else{
            $data1 = array('to' => $to,
                "data" => $data);
        }
//        $info = ['to' => $to,'content_available' => $content_available, 'data' => $data ];
        $url = "http://localhost:8081";
        $options = array(
            'http' => array(
                'method'  => 'POST',
                'content' => json_encode( $data1 ),
                'header'=>  "Content-Type: application/json\r\n" .
                    "Accept: application/json\r\n"
            )
        );

        $context  = stream_context_create( $options );
        $result = file_get_contents( $url, false, $context );
        $response = json_decode( $result );
    }




}