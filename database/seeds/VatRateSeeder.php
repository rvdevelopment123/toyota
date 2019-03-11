<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Tax;

class VatRateSeeder extends Seeder {


	public function run(){
			
		\Eloquent::unguard();


        \DB::table('taxes')->truncate();

        $tax = Tax::create(
            array(
                'name' => 'No Tax',
                'rate' => 0,
                'type' => 1,
            )
        );

        $tax = Tax::create(
            array(
                'name' => 'Zero Percent (0%)',
                'rate' => 0,
                'type' => 1,
            )
        );

        $tax = Tax::create(
            array(
                'name' => 'Five Percent (5%)',
                'rate' => 5,
                'type' => 1,
            )
        );

        $tax = Tax::create(
            array(
                'name' => 'Ten Percent (10%)',
                'rate' => 10,
                'type' => 1,
            )
        );

        $tax = Tax::create(
            array(
                'name' => 'Fifteen Percent (15%)',
                'rate' => 15,
                'type' => 1,
            )
        );      

    }

}