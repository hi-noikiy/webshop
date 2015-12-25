<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

use App\Order;
use App\User;

class resendOrder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'resend:order';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Resend a specific order id';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $orderId = $this->argument('order_id');

        $order = Order::where('id', $orderId)->firstOrFail();

        $customer = User::where('User_id', $order->User_id)->firstOrFail();

        $address = new \stdClass();

        $address->name       = $customer->company;
        $address->street     = $customer->street;
        $address->postcode   = $customer->postcode;
        $address->city       = $customer->city;
        $address->telephone  = '?';
        $address->mobile     = '?';

        $data['address'] = $address;
        $data['cart']    = unserialize($order->products);
        $data['comment'] = 'Unknown :(';

        \Mail::send('email.order', $data, function($message)
        {
                $message->from('verkoop@wiringa.nl', 'Wiringa Webshop');

                $message->to('verkoop@wiringa.nl');

                $message->subject('Webshop order [Opnieuw verstuurd!]');
        });
    }

    /**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
			array('order_id', InputArgument::REQUIRED, '(Required) Integer'),
		);
	}
}
