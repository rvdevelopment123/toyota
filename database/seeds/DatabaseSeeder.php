<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        $this->call('RolePermissionSeeder');
        $this->call('SiteinfoSeeder');
        $this->call('CustomerSeeder');
        $this->call('WareHouseSeeder');
        $this->call('VatRateSeeder');
        Model::reguard();
    }
}
