@extends('layouts.app.layout')

@section('content')

    <?php /** @var \App\Domain\Post\Entity\PostCategory[] $postCategoryList */ ?>

    <div class="container">

        <div class="my-5">

            <div class="text-end mb-3">
                <a href="{{ route('post.post.index') }}" class="btn btn-link">Home</a>
                <a href="{{ route('post.category.create') }}" class="btn btn-primary">Add category</a>
                <a href="{{ route('post.post.create') }}" class="btn btn-outline-success">Add post</a>
            </div>

            <form action="{{ route('post.post.store') }}" method="post" class="mt-5 mx-auto w-50">
                @csrf

                <div class="mb-3">
                    <label for="title" class="form-label fw-bold">Title</label>
                    <input type="text" name="title" class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}" id="title" value="{{ old('title') }}">
                    @if ($errors->has('title'))
                        @foreach($errors->get('title') as $error)
                            <span class="invalid-feedback">{!! $error !!}</span>
                        @endforeach
                    @endif
                </div>

                <div class="mb-3">
                    <label for="category_id" class="form-label fw-bold">Category</label>
                    <select name="category_id" class="form-select{{ $errors->has('category_id') ? ' is-invalid' : '' }}" size="5" aria-label="multiple select example">
                        @foreach($postCategoryList as $postCategory)
                            <option value="{{ $postCategory->getId() }}"{{ $postCategory->getId() === old('category_id') ? ' selected' : '' }}>{{ $postCategory->getTitle() }}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('category_id'))
                        @foreach($errors->get('category_id') as $error)
                            <span class="invalid-feedback">{!! $error !!}</span>
                        @endforeach
                    @endif
                </div>

                <div class="mb-3">
                    <label for="text" class="form-label fw-bold">Post</label>
                    <textarea name="text" class="form-control{{ $errors->has('text') ? ' is-invalid' : '' }}" id="text" rows="3">{{ old('text') }}</textarea>
                    @if ($errors->has('text'))
                        @foreach($errors->get('text') as $error)
                            <span class="invalid-feedback">{!! $error !!}</span>
                        @endforeach
                    @endif
                </div>
                <button type="submit" class="btn btn-success">Create</button>
            </form>

        </div>

    </div>

@endsection
