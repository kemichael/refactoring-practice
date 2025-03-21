<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('companies')->insert([
            [
                'company_name' => 'Tech Corp',
                'street_address' => '1234 Innovation Drive, Tokyo',
                'representative_name' => 'Taro Yamada',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'company_name' => 'Green Energy Ltd.',
                'street_address' => '5678 Solar Lane, Osaka',
                'representative_name' => 'Hanako Tanaka',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'company_name' => 'AI Solutions Inc.',
                'street_address' => '9101 Deep Learning Blvd, Kyoto',
                'representative_name' => 'Kenji Sato',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
