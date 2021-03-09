<?php
namespace Giorgio\Seeder;

use Giorgio\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminTableSeeder extends Seeder
{
    public function run()
    {
        Admin::query()->truncate();
        Admin::query()->create([
            'name' => 'admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('admin999'),
        ]);
    }
}
