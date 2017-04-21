<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use WTG\Customer\Models\Company;

/**
 * Class SetAdmin
 *
 * @author  Thomas Wiringa <thomas.wiringa@gmail.com>
 */
class SetAdmin extends Command
{
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
     * @return int
     */
    public function handle()
    {
        $customerNumber = $this->ask('Which company?');

        try {
            $company = Company::where('customer_number', $customerNumber)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            $this->output->error("No company found with customer number: {$customerNumber}");

            return 127;
        }

        $this->comment('This user '.($company->is_admin ? 'IS' : 'IS NOT').' an admin.');

        if ($company->is_admin) {
            $adminStatus = $this->confirm('Remove the admin status for this company?');
        } else {
            $adminStatus = $this->confirm('Make this company an admin?');
        }

        if ($adminStatus) {
            $company->setIsAdmin(!$company->getIsAdmin());

            if ($company->save()) {
                $this->output->success("Changed the admin status of company {$customerNumber}");
            }
        } else {
            $this->confirm('Admin status unchanged');
        }

        return 0;
    }
}
