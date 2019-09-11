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
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "is_post=1&login=K770217301057&password=Qwer1234&submit=%D0%92%D0%BE%D0%B9%D1%82%D0%B8",
            CURLOPT_HTTPHEADER => array(
                "Accept: */*",
                "Accept-Encoding: gzip, deflate",
                "Cache-Control: no-cache",
                "Connection: keep-alive",
                "Content-Type: application/x-www-form-urlencoded",
                "Cookie: session=ka5k40njfo8dbcrnebbvkvl800",
                "Postman-Token: bbb884ee-b5ad-4bba-8fb2-2c55881acbf2,1b5bcacb-0eae-41eb-9fee-3a7945afc679",
                "Referer: http://lk.scanoil.ru/cabinet",
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


        preg_match_all('/^Set-Cookie:\s*([^;]*)/mi', $response, $matches);
        $cookies = array();
        foreach($matches[1] as $item) {
            parse_str($item, $cookie);
            $cookies = array_merge($cookies, $cookie);
        }
        var_dump($cookies);

    }
}
