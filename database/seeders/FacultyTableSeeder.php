<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FacultyTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('faculty')->insert([
            [                   
                'school_id' => '2010493812',
                'id_number' => '201601302',
                'f_name' => 'Alice',
                'm_name' => 'C',
                'l_name' => 'Johnson',
                'email' => 'alice.johnson@example.com',
            ],
            [
                'school_id' => '2042679183',
                'id_number' => '201701532',
                'f_name' => 'Bob',
                'm_name' => 'D',
                'l_name' => 'Brown',
                'email' => 'bob.brown@example.com',
            ],
        ]);
    }
}