<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AppConfigurationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('app_configurations')->insert([
            'android_app_link'    => null,
            'android_app_version' => null,
            'force_app_update'    => false,
            'app_maintenance'     => false,
        ]);
    }
}
