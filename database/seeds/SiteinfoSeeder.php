<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Setting;

class SiteinfoSeeder extends Seeder {


	public function run(){
			
		\Eloquent::unguard();


        \DB::table('settings')->truncate();

        $siteinfo = Setting::create(
            [
                'site_name' => 'Intelle POS',
                'slogan' => 'Complete stock management.',
                'address' => 'Dhaka, Bangladesh',
                'phone' => '+880 1674871091',
                'email' => 'info@intelle-hub.com',
                'owner_name' => 'Intelle Hub Inc.',
                'currency_code' => 'USD',
                'alert_quantity' => 5,
            ]
        );      

    }

}