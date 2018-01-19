<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;


class AppSMS extends Command {

	/**
	 * The console command name.
	 *
	 * @var	string
	 */
	protected $name = 'app:sms {bool}';
	
	protected function getArguments()
	{
	    return [
	        ['bool', InputArgument::OPTIONAL, 'required argument boolean']
	    ];
	}
	/**
	 * The console command description.
	 *
	 * @var	string
	 */
	protected $description = 'Switch SMS notifications';


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
			$this->info('sms: '.Config::get('app.sms'));
		}
		else{
			try 
			{
				$path = app_path('config/'.App::environment().'/app.php');
				$contents = File::get($path);
				if($this->argument('bool') == 'true'){
					$contents = str_replace('\'sms\' => false', '\'sms\' => true', $contents);
				}
				else{
					$contents = str_replace('\'sms\' => true', '\'sms\' => false', $contents);
				}
				File::put($path, $contents);

				$this->info('Sucessfully switched SMS!');
			}
			catch(Exception $e){
				$this->info('There was a problem switching SMS');
				$this->info(json_encode($e->getMessage()));
				$this->info(json_encode($e));
				die;
			}
		}
	}


}
