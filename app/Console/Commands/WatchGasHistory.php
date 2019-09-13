<?php

namespace App\Console\Commands;

use HttpException;
use HttpRequest;
use Illuminate\Console\Command;

class WatchGasHistory extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'watch:gas';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Parse and compare gas history with Takons history';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {


        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://lk.scanoil.ru/login",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 100,
            CURLOPT_TIMEOUT => 2660,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "is_post=1&login=K770217301057&password=Qwer1234&submit=%D0%92%D0%BE%D0%B9%D1%82%D0%B8",
            CURLOPT_HTTPHEADER => array(
                "Accept: */*",
                "Accept-Encoding: gzip, deflate, br",
                "Cache-Control: max-age=0",
                "Connection: keep-alive",
                "Content-Type: application/x-www-form-urlencoded",
                "Referer: http://lk.scanoil.ru/",
                "User-Agent: PostmanRuntime/7.16.3",
                "cache-control: no-cache"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            echo $response;
        }

//
//        preg_match_all('/^Set-Cookie:\s*([^;]*)/mi', $response, $matches);
//        $cookies = array();
//        foreach($matches[1] as $item) {
//            parse_str($item, $cookie);
//            $cookies = array_merge($cookies, $cookie);
//        }
//        var_dump($cookies);

    }
}
