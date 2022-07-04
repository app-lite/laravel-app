<?php

declare(strict_types=1);

namespace App\UI\Http\Web\Controller\Shared\Post;

use App\Application\Command\Post\Post\PostCommand;
use App\Application\Command\Post\Post\PostHandler;
use App\Application\Query\Post\PostCategoryFetcher;
use App\Application\Query\Post\PostFetcher;
use App\Application\Validation\Post\PostValidation;
use Psr\Http\Message\ServerRequestInterface;

class PostController
{
    public function index(
        PostCategoryFetcher $postCategoryFetcher,
        PostFetcher $postFetcher,
    ) {
        $postCategoryList = $postCategoryFetcher->getList();
        $postListGroupByCategoryId = $postFetcher->getByLimitGroupByCategoryId(3);

        $postCategoryList = array_merge(array_flip(array_keys($postListGroupByCategoryId)), $postCategoryList);

        $postCount = $postFetcher->count();

        return view(
            'views.shared.post.post.index',
            compact(
                'postCategoryList',
                'postListGroupByCategoryId',
                'postCount',
            )
        );
    }

    public function create(PostCategoryFetcher $postCategoryFetcher)
    {
        $postCategoryList = $postCategoryFetcher->getList();

        return view(
            'views.shared.post.post.create',
            compact('postCategoryList')
        );
    }

    public function store(
        ServerRequestInterface $request,
        PostValidation $validation,
        PostHandler $postHandler,
    ) {
        $data = $request->getParsedBody();
        /** @var \Illuminate\Contracts\Validation\Validator $validator */
        $validator = $validation->validate($data);
        if ($validator->fails()) {
            return redirect()
                ->route('post.post.create')
                ->withErrors($validator->errors())
                ->withInput();
        }
        $command = PostCommand::createFromData($data);
        $postHandler->handle($command);
        return redirect()->route('post.post.index')
            ->with('success', 'Post created');
    }
}
