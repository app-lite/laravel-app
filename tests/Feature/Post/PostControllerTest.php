<?php

declare(strict_types=1);

namespace Tests\Feature\Post;

use App\Domain\Post\Constant\PostCategoryEnum;
use App\Domain\Post\Constant\PostEnum;
use Faker\Factory;
use Illuminate\Support\Facades\DB;
use Tests\DbWebTestCase;

class PostControllerTest extends DbWebTestCase
{
    public function testFetchPost()
    {
        $postCategoryData1 = [
            'id' => '00000000-0000-0000-0000-000000000001',
            'title' => 'Category 1',
            'description' => 'Description 1',
            'created_at' => new \DateTimeImmutable(),
            'updated_at' => new \DateTimeImmutable(),
        ];
        DB::table(PostCategoryEnum::DB_TABLE)->insert($postCategoryData1);

        $postDataList = [];
        for ($i = 1; $i <= 3; $i++) {
            $postId = "00000000-0000-0000-0000-00000000000{$i}";
            $postDataList[] = [
                'id' => $postId,
                'category_id' => $postCategoryData1['id'],
                'title' => "Post {$i}",
                'text' => "Message {$i}",
                'created_at' => new \DateTimeImmutable(),
                'updated_at' => new \DateTimeImmutable(),
            ];
        }
        DB::table(PostEnum::DB_TABLE)->insert($postDataList);
        $response = $this->get('/post');

        $response->assertSee('Category 1');
        for ($i = 1; $i <= 3; $i++) {
            $response->assertSee("Post {$i}");
        }

        self::assertDatabaseHas(PostCategoryEnum::DB_TABLE,
            [
                'id' => $postCategoryData1['id'],
                'title' => 'Category 1',
            ]
        );

        self::assertDatabaseHas(PostEnum::DB_TABLE,
            [
                'id' => '00000000-0000-0000-0000-000000000001',
                'title' => 'Post 1',
            ]
        );
    }

    public function testCreatePost()
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
        $postId1 = $faker->uuid();
        $postTitle1 = $faker->text(64);
        $postText1 = $faker->text();

        /** @var \Illuminate\Http\Response $response */
        $response = $this->post('/post/store', [
            'id' => $postId1,
            'category_id' => $postCategoryData1['id'],
            'title' => $postTitle1,
            'text' => $postText1,
        ]);

        $response->assertStatus(302);

        $this->followRedirects($response)->assertSee($postTitle1);

        self::assertDatabaseHas(PostEnum::DB_TABLE,
            [
                'id' => $postId1,
                'title' => $postTitle1,
            ]
        );
    }

    public function testCreatePostWithSelectedCategoryIdIsInvalid()
    {
        $faker = Factory::create();
        $postId1 = $faker->uuid();
        $postTitle1 = $faker->text(64);
        $postText1 = $faker->text();

        $response = $this->followingRedirects()->post('/post/store', [
            'id' => $postId1,
            'category_id' => '00000000-0000-0000-0000-000000000000',
            'title' => $postTitle1,
            'text' => $postText1,
        ]);

        $response->assertStatus(200);

        $response->assertSee('The selected category id is invalid.');

        self::assertDatabaseMissing(PostEnum::DB_TABLE,
            [
                'id' => $postId1,
                'title' => $postTitle1,
            ]
        );
    }

    public function testCreatePostWithoutCategoryId()
    {
        $faker = Factory::create();
        $postId1 = $faker->uuid();
        $postTitle1 = $faker->text(64);
        $postText1 = $faker->text();

        $response = $this->followingRedirects()->post('/post/store', [
            'id' => $postId1,
            'title' => $postTitle1,
            'text' => $postText1,
        ]);

        $response->assertStatus(200);

        $response->assertSee('The category id field is required.');

        self::assertDatabaseMissing(PostEnum::DB_TABLE,
            [
                'id' => $postId1,
                'title' => $postTitle1,
            ]
        );
    }

    public function testCreatePostWithoutTitleAndText()
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
        $postId1 = $faker->uuid();
        $postTitle1 = null;
        $postText1 = null;

        $response = $this->followingRedirects()->post('/post/store', [
            'id' => $postId1,
            'category_id' => $postCategoryData1['id'],
            'title' => $postTitle1,
            'text' => $postText1,
        ]);

        $response->assertStatus(200);

        $response->assertSee('The title field is required.');
        $response->assertSee('The text field is required.');

        self::assertDatabaseMissing(PostEnum::DB_TABLE,
            [
                'id' => $postId1,
                'title' => $postTitle1,
            ]
        );
    }
}
