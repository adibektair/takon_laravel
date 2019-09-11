<?php

namespace App\Console\Commands;

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

        $data = array("login" => "K770217301057", "password" => "Qwer1234", "submit" => "Войти", "is_post" => 1);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/x-www-form-urlencoded',
        ));
        curl_setopt($ch, CURLOPT_URL,"https://lk.scanoil.ru/login");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $server_output = curl_exec ($ch);
        curl_close ($ch);
        preg_match_all('/^Set-Cookie:\s*([^;]*)/mi', $server_output, $matches);
        $cookies = array();
        foreach($matches[1] as $item) {
            parse_str($item, $cookie);
            $cookies = array_merge($cookies, $cookie);
        }
        var_dump($cookies);
    }
}
