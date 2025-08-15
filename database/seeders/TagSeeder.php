<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Tag;
use Illuminate\Support\Str;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tags = ['Architecture', '3Ds Max', 'Sketchup', 'Lumion', 'AutoCad'];

        foreach ($tags as $tag) {
            Tag::create([
                'name' => $tag,
                'slug' => \Str::slug($tag)
            ]);
        }
    }
}
