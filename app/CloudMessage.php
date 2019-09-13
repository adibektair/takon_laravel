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

        ignore_user_abort(true);
        set_time_limit(0);
        ob_start();

        $serverProtocol = filter_input(INPUT_SERVER, 'SERVER_PROTOCOL', FILTER_SANITIZE_STRING);
        header($serverProtocol . ' 200 OK');
        // Disable compression (in case content length is compressed).
        header('Content-Encoding: none');
        header('Content-Length: ' . ob_get_length());
        // Close the connection.
        header('Connection: close');
        ob_end_flush();
        ob_flush();
        flush();

        $context  = stream_context_create( $options );
        $result = file_get_contents( $url, false, $context );
        $response = json_decode( $result );
    }




}