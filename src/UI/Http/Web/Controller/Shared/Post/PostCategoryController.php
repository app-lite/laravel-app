<?php

declare(strict_types=1);

namespace App\UI\Http\Web\Controller\Shared\Post;

use App\Application\Command\Post\PostCategory\PostCategoryCommand;
use App\Application\Command\Post\PostCategory\PostCategoryHandler;
use App\Application\Validation\Post\PostCategoryValidation;
use Psr\Http\Message\ServerRequestInterface;

class PostCategoryController
{
    public function create()
    {
        return view('views.shared.post.category.create');
    }

    public function store(
        ServerRequestInterface $request,
        PostCategoryValidation $validation,
        PostCategoryHandler $postCategoryHandler,
    ) {
        $data = $request->getParsedBody();
        /** @var \Illuminate\Contracts\Validation\Validator $validator */
        $validator = $validation->validate($data);
        if ($validator->fails()) {
            return redirect()
                ->route('post.category.create')
                ->withErrors($validator->errors())
                ->withInput();
        }
        $command = PostCategoryCommand::createFromData($data);
        $postCategoryHandler->handle($command);
        return redirect()->route('post.post.index')
            ->with('success', 'Category created');
    }
}
