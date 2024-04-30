<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'admin',
            'national_code' => '0010000000',
            'email' => config('technopay.admin_email'),
            'mobile' => config('technopay.admin_mobile'),
        ]);

        $this->call([
            OrderSeeder::class,
        ]);
    }
}
