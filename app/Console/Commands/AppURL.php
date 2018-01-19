<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class AppURL extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'app:url {url}';
    
    protected function getArguments()
    {
        return [
            ['url', InputArgument::OPTIONAL, 'required argument url']
        ];
    }
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update the expected app url';


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
     * @return void
     */
    public function fire()
    {
        $this->comment('');
        $this->comment('=====================================');
        $this->comment('');

        if(!$this->argument('bool')){
            $this->info('url: '.Config::get('app.url'));
        }
        else{
            try 
            {
                $path = app_path('config/'.App::environment().'/app.php');
                $contents = File::get($path);
                $contents = preg_replace("/'url' => '(.*?)'/", "'url' => '".$this->argument('bool')."'", $contents);
                File::put($path, $contents);

                $this->info('Sucessfully updated the url!');
            }
            catch(Exception $e){
                $this->info('There was a problem updating the url!');
                $this->info(json_encode($e->getMessage()));
                die;
            }
        }
    }


}
