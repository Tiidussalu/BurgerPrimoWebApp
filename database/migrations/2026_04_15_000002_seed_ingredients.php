<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::table('ingredients')->count() > 0) {
            return;
        }

        $now = now();
        DB::table('ingredients')->insert([
            ['name' => 'Seesami sai',      'category' => 'vöi',    'price' => 0.50, 'is_available' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Brioche kukkel',   'category' => 'vöi',    'price' => 0.75, 'is_available' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Riisikukkel',      'category' => 'vöi',    'price' => 0.60, 'is_available' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Veise',            'category' => 'pitav',  'price' => 2.50, 'is_available' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Kana',             'category' => 'pitav',  'price' => 2.00, 'is_available' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Sealiha',          'category' => 'pitav',  'price' => 2.30, 'is_available' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Vegan pihv',       'category' => 'pitav',  'price' => 2.20, 'is_available' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Grill pihv',       'category' => 'pitav',  'price' => 2.80, 'is_available' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Tavaline pihv',    'category' => 'pitav',  'price' => 2.40, 'is_available' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Cheddar',          'category' => 'juust',  'price' => 0.50, 'is_available' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Mozzarella',       'category' => 'juust',  'price' => 0.60, 'is_available' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Blue cheese',      'category' => 'juust',  'price' => 0.70, 'is_available' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Salat',            'category' => 'salat',  'price' => 0.30, 'is_available' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Tomat',            'category' => 'salat',  'price' => 0.30, 'is_available' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Kurk',             'category' => 'salat',  'price' => 0.25, 'is_available' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Sibul',            'category' => 'salat',  'price' => 0.20, 'is_available' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Avocado',          'category' => 'salat',  'price' => 0.80, 'is_available' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Kastrullsibul',    'category' => 'salat',  'price' => 0.40, 'is_available' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Ketšup',           'category' => 'lisand', 'price' => 0.20, 'is_available' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Majonees',         'category' => 'lisand', 'price' => 0.20, 'is_available' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'BBQ kaste',        'category' => 'lisand', 'price' => 0.30, 'is_available' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Chipotle kaste',   'category' => 'lisand', 'price' => 0.35, 'is_available' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Peekon',           'category' => 'lisand', 'price' => 0.60, 'is_available' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Muna',             'category' => 'lisand', 'price' => 0.50, 'is_available' => true, 'created_at' => $now, 'updated_at' => $now],
        ]);
    }

    public function down(): void
    {
        DB::table('ingredients')->truncate();
    }
};
