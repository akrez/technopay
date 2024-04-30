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
            'email' => config('technopay.ADMIN_EMAIL'),
            'mobile' => config('technopay.ADMIN_MOBILE'),
        ]);

        $this->call([
            OrderSeeder::class,
        ]);
    }
}
