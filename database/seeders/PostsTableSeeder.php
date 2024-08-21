<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use DateTime;

class PostsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         DB::table('posts')->insert([
            [
                'user_id' => 1,
                'category_id' => 1,
                'title' => 'hoge',
                'body' => 'test',
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
            ],
            [
                'user_id' => 1,
                'category_id' => 1,
                'title' => 'hoge',
                'body' => 'test2',
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
            ],
            [
                'user_id' => 1,
                'category_id' => 1,
                'title' => 'hoge',
                'body' => 'test3',
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
            ]
            
                
        ]);
    }
}
