<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $statuses = [
            ['nama' => 'Pending'],
            ['nama' => 'Approved'],
            ['nama' => 'Rejected'],
            ['nama' => 'BTB'],
            ['nama' => 'SP Final']
        ];

        foreach ($statuses as $status) {
            Status::create($status);
        }
    }
}
