<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Post\Entity;

use App\Application\Command\Post\Post\PostCommand;
use App\Application\Command\Post\PostCategory\PostCategoryCommand;
use App\Domain\Post\Entity\Post;
use App\Domain\Post\Entity\PostCategory;
use Tests\TestCase;

class PostEntityTest extends TestCase
{
    public function testNewPost(): void
    {
        $dataPostCategory1 = (require base_path('tests/_data/Post/post_category_list.php'))['post_category_1'];
        $postCategory1 = PostCategory::createFromCommand(PostCategoryCommand::createFromData($dataPostCategory1));
        $dataPost1 = (require base_path('tests/_data/Post/post_list.php'))['post_1'];
        $post1 = Post::createFromCommand(PostCommand::createFromData($dataPost1));

        self::assertNotEmpty($post1);
        self::assertEquals($post1->getId(), $dataPost1['id']);
        self::assertEquals($post1->getTitle(), $dataPost1['title']);
        self::assertEquals($post1->getText(), $dataPost1['text']);
    }
}
