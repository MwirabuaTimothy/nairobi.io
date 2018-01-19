<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class FooCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pressdesk:shout';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Pressdesk is shouting';

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
        $this->info('We are shouting!');
    }
}
