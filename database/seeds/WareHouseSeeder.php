<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Warehouse;

class WareHouseSeeder extends Seeder {


	public function run(){
			
		\Eloquent::unguard();


        \DB::table('warehouses')->truncate();

        $warehouse = Warehouse::create(
            [
                'name' => 'Main Branch',
                'address' => '',
                'phone' => '',
                'email' => '',
                'in_charge_name' => '',
            ]
        );      

    }

}