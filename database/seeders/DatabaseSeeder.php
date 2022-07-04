<?php

namespace Database\Seeders;

use App\Domain\Post\Constant\PostCategoryEnum;
use App\Domain\Post\Constant\PostEnum;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(\Faker\Generator $faker)
    {
        $postCategoryDataList = [];
        $postDataList = [];
        for ($i = 0; $i <= 4; $i++) {
            $postCategoryId = $faker->uuid();
            $postCategoryDataList[] = [
                'id' => $postCategoryId,
                'title' => $faker->text(64),
                'description' => $faker->realText(),
                'created_at' => $faker->dateTime(),
                'updated_at' => $faker->dateTime(),
            ];
            for ($k = 0; $k <= rand(1000, 5000); $k++) {
                $postDataList[] = [
                    'id' => $faker->uuid(),
                    'category_id' => $postCategoryId,
                    'title' => $faker->text(),
                    'text' => $faker->realText(),
                    'created_at' => $faker->dateTime(),
                    'updated_at' => $faker->dateTime(),
                ];
            }
        }

        DB::table(PostCategoryEnum::DB_TABLE)->insert($postCategoryDataList);
        DB::table(PostEnum::DB_TABLE)->insert($postDataList);
    }
}
