<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ReturnTakons extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'return:takons';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Returns takons from companies and users to partners when deadline comes';

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

    }
}
