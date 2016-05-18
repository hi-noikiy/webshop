<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

use DB, Hash;

class createUser extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'user:add';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Add a user to the users table.';

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle()
	{
		$login 	  = $this->argument('login');
		$company  = $this->argument('company');
		$street   = $this->argument('street');
		$postcode = $this->argument('postcode');
		$city 	  = $this->argument('city');
		$email	  = $this->argument('email');
        $admin 	  = $this->argument('admin');

        $password = $this->secret('Enter a password for the new user');

		DB::table('users')->insert(
			array(
				'login' 	=> $login,
				'company'	=> $company,
				'street'	=> $street,
				'postcode' 	=> $postcode,
				'city'		=> $city,
				'email'		=> $email,
				'active' 	=> 1,
				'isAdmin' 	=> $admin,
				'password' 	=> Hash::make($password),
				)
			);

	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
			array('login', 		InputArgument::REQUIRED, '(Required) Integer'),
			array('company', 	InputArgument::REQUIRED, '(Required) String'),
			array('street', 	InputArgument::REQUIRED, '(Required) String'),
			array('postcode', 	InputArgument::REQUIRED, '(Required) String'),
			array('city', 		InputArgument::REQUIRED, '(Required) String'),
			array('email', 		InputArgument::REQUIRED, '(Required) String'),
			array('admin', 		InputArgument::OPTIONAL, '(Optional) Boolean [Default: false]', false)
		);
	}

}
