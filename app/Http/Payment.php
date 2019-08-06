<?php


namespace App\Http;


use const http\Client\Curl\AUTH_BASIC;

class Payment
{
    const API_KEY = 'f1bfe6d357926dca0b37913171d258af';
    const ID = 'pk_0ad5acde2f593df7c5a63c9c27807';
    const CURRENCY = 'KZT';
    private $name;
    private $cryptogram;
    private $ip;
    private $amount;

    function __construct($name, $cryptogram, $ip, $amount)
    {
        $this->amount = $amount;
        $this->name = $name;
        $this->cryptogram = $cryptogram;
        $this->ip = $ip;
    }


    public function pay(){

        $data = array("Amount" => $this->amount, "Currency" => self::CURRENCY, "IpAddress" => $this->ip, "Name" => $this->name, "CardCryptogramPacket" => $this->cryptogram);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-type: application/json',
            'Authorization: Basic '. base64_encode(self::ID . ":". self::API_KEY)
        ));

        curl_setopt($ch, CURLOPT_URL,"https://api.cloudpayments.kz/payments/cards/charge");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,
            json_encode($data));

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $server_output = curl_exec ($ch);
        curl_close ($ch);
        return $server_output;

    }

}