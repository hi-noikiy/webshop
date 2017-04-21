<?php

namespace App\Console\Commands;

use DB;
use Hash;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;

/**
 * Class CreateUser
 *
 * @author  Thomas Wiringa <thomas.wiringa@gmail.com>
 */
class CreateUser extends Command
{
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
        $login = $this->argument('login');
        $company = $this->argument('company');
        $street = $this->argument('street');
        $postcode = $this->argument('postcode');
        $city = $this->argument('city');
        $email = $this->argument('email');
        $admin = $this->argument('admin');

        $password = $this->secret('Enter a password for the new user');

        DB::table('users')->insert(
            [
                'login'        => $login,
                'company'      => $company,
                'street'       => $street,
                'postcode'     => $postcode,
                'city'         => $city,
                'email'        => $email,
                'active'       => 1,
                'isAdmin'      => $admin,
                'password'     => Hash::make($password),
                ]
            );
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['login',        InputArgument::REQUIRED, '(Required) Integer'],
            ['company',    InputArgument::REQUIRED, '(Required) String'],
            ['street',    InputArgument::REQUIRED, '(Required) String'],
            ['postcode',    InputArgument::REQUIRED, '(Required) String'],
            ['city',        InputArgument::REQUIRED, '(Required) String'],
            ['email',        InputArgument::REQUIRED, '(Required) String'],
            ['admin',        InputArgument::OPTIONAL, '(Optional) Boolean [Default: false]', false],
        ];
    }
}
