<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StudentsTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('students')->insert([
            [
                'school_id' => '2060283853',
                'id_number' => '202001408',
                'f_name' => 'John',
                'm_name' => 'A',
                'l_name' => 'Doe',
                'email' => 'john.doe@example.com',
                'program' => 'BSCSSE',
            ],
            [
                'school_id' => '2059401485',
                'id_number' => '202001345',
                'f_name' => 'Jane',
                'm_name' => 'B',
                'l_name' => 'Smith',
                'email' => 'jane.smith@example.com',
                'program' => 'BSCSSE',
            ],
        ]);
    }
}