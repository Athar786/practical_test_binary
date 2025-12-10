<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Customer::truncate();

        Customer::create(['name' => 'John Doe', 'email' => 'john@example.com', 'phone' => '1234567890']);
        Customer::create(['name' => 'Jane Smith', 'email' => 'jane@example.com', 'phone' => '9876543210']);
    }
}
