@extends('layouts.app.layout')

@section('content')

    <?php /** @var \App\Domain\Post\Entity\PostCategory[] $postCategoryList */ ?>
    <?php /** @var \App\Domain\Post\Entity\Post[][] $postListGroupByCategoryId */ ?>

    <div class="container">

        <div class="my-5">

            <div class="text-end mb-3">
                <a href="{{ route('post.post.index') }}" class="btn btn-link">Home</a>
                <a href="{{ route('post.category.create') }}" class="btn btn-primary">Add category</a>
                <a href="{{ route('post.post.create') }}" class="btn btn-success">Add post</a>
            </div>

            <p>Post count - {{ $postCount }}</p>

            @foreach($postCategoryList as $postCategory)
                <h3>{{ $postCategory->getTitle() }}</h3>
                @if(array_key_exists($postCategory->getId(), $postListGroupByCategoryId))
                    @foreach($postListGroupByCategoryId[$postCategory->getId()] as $post)
                        <p><span class="{{ $loop->index === 0 ? 'text-primary' : 'text-secondary' }}">{{ $post->getCreatedAt()->format('Y-m-d H:i:s') }}</span> - {{ $post->getTitle() }}</p>
                    @endforeach
                @endif
            @endforeach

        </div>

    </div>

@endsection
