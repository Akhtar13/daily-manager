<?php

namespace Database\Seeders;

use App\Models\Activity;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ActivitySeeder extends Seeder
{
    public function run(): void
    {
        collect(['Breakfast', 'Running'])->each(
            fn (string $name) => Activity::updateOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name, 'is_active' => true],
            ),
        );
    }
}
