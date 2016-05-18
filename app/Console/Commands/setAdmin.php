<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use App\User;

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
		$login = $this->ask('Which user?');

        try {
            $user = User::where('login', $login)->firstOrFail();

        } catch (\ModelNotFoundException $e) {
            $this->error(PHP_EOL . "No user found with login: ". $login . PHP_EOL);
            
            return 127;
        }
        
        $this->comment("This user " . ($user->isAdmin ? 'IS' : 'IS NOT') . " an admin.");

        $user->isAdmin = $this->confirm('Make this user an admin? (Answering \'no\' will remove the admin status)');

        $user->save(); 
	}

}
