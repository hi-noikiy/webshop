<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

use DB;

class setAdmin extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'user:admin';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Set a user\'s admin state to true or false.';

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle()
	{
		$login = $this->argument('login');
		$admin = $this->argument('admin');

        $query = DB::table('users')->where('login', $login);

        if ($query->count() === 1) {
    		DB::table('users')->where('login', $login)->update(
    			     array('isAdmin' => $admin)
    			);
        } else {
            $this->error(PHP_EOL."No user found with login: ". $login .PHP_EOL);
        }
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
			array('login', 	InputArgument::REQUIRED, '(Required) Integer'),
			array('admin', 	InputArgument::REQUIRED, '(Required) Boolean'),
		);
	}

}
