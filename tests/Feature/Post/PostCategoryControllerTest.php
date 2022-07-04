<?php

declare(strict_types=1);

namespace Tests\Feature\Post;

use App\Domain\Post\Constant\PostCategoryEnum;
use Faker\Factory;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;
use Tests\DbWebTestCase;

class PostCategoryControllerTest extends DbWebTestCase
{
    public function testFetchPostCategory()
    {
        $a = 0;
        $postCategoryDataList = [];
        for ($i = 0; $i <= 2; $i++) {
            $numberCategory = ++$a;
            $postCategoryId = Uuid::uuid4();
            $postCategoryDataList[] = [
                'id' => $postCategoryId,
                'title' => "Category {$numberCategory}",
                'description' => "Description {$numberCategory}",
                'created_at' => new \DateTimeImmutable(),
                'updated_at' => new \DateTimeImmutable(),
            ];
        }
        DB::table(PostCategoryEnum::DB_TABLE)->insert($postCategoryDataList);

        $response = $this->get('/post');
        $response->assertSee('Category 1');
        $response->assertSee('Category 3');

        self::assertDatabaseHas(PostCategoryEnum::DB_TABLE,
            [
                'title' => 'Category 1',
            ]
        );

        self::assertDatabaseHas(PostCategoryEnum::DB_TABLE,
            [
                'title' => 'Category 2',
            ]
        );
    }

    public function testCreatePostCategory()
    {
        $faker = Factory::create();
        $postCategoryId1 = $faker->uuid();
        $postCategoryTitle1 = $faker->text(64);

        /** @var \Illuminate\Http\Response $response */
        $response = $this->post('/post/category/store', [
            'id' => $postCategoryId1,
            'title' => $postCategoryTitle1,
        ]);

        $response->assertStatus(302);

        $this->followRedirects($response)->assertSee($postCategoryTitle1);

        self::assertDatabaseHas(PostCategoryEnum::DB_TABLE,
            [
                'id' => $postCategoryId1,
                'title' => $postCategoryTitle1,
            ]
        );
    }

    public function testCreatePostCategoryWithoutTitle()
    {
        $faker = Factory::create();
        $postCategoryId1 = $faker->uuid();
        $postCategoryTitle1 = null;

        $response = $this->followingRedirects()->post('/post/category/store', [
            'id' => $postCategoryId1,
            'title' => $postCategoryTitle1,
        ]);

        $response->assertStatus(200);

        $response->assertSee('The title field is required.');

        self::assertDatabaseMissing(PostCategoryEnum::DB_TABLE,
            [
                'id' => $postCategoryId1,
                'title' => $postCategoryTitle1,
            ]
        );
    }

    public function testCreatePostCategoryExistsTitle()
    {
        $postCategoryData1 = [
            'id' => '00000000-0000-0000-0000-000000000001',
            'title' => 'Category 1',
            'description' => 'Description 1',
            'created_at' => new \DateTimeImmutable(),
            'updated_at' => new \DateTimeImmutable(),
        ];
        DB::table(PostCategoryEnum::DB_TABLE)->insert($postCategoryData1);

        $faker = Factory::create();
        $postCategoryId1 = $faker->uuid();
        $postCategoryTitle1 = 'Category 1';

        $response = $this->followingRedirects()->post('/post/category/store', [
            'id' => $postCategoryId1,
            'title' => $postCategoryTitle1,
        ]);

        $response->assertStatus(200);

        $response->assertSee('The title has already been taken.');

        self::assertDatabaseMissing(PostCategoryEnum::DB_TABLE,
            [
                'id' => $postCategoryId1,
                'title' => $postCategoryTitle1,
            ]
        );
    }
}
