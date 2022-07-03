<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Post\Entity;

use App\Application\Command\Post\PostCategory\PostCategoryCommand;
use App\Domain\Post\Entity\PostCategory;
use Tests\TestCase;

class PostCategoryEntityTest extends TestCase
{
    public function testNewPostCategory(): void
    {
        $dataPostCategory1 = (require base_path('tests/_data/Post/post_category_list.php'))['post_category_1'];
        $postCategory1 = PostCategory::createFromCommand(PostCategoryCommand::createFromData($dataPostCategory1));

        self::assertNotEmpty($postCategory1);
        self::assertEquals($postCategory1->getId(), $dataPostCategory1['id']);
        self::assertEquals($postCategory1->getTitle(), $dataPostCategory1['title']);
        self::assertEquals($postCategory1->getDescription(), $dataPostCategory1['description']);
    }
}
