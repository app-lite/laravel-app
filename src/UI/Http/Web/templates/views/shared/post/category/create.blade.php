@extends('layouts.app.layout')

@section('content')

    <div class="container">

        <div class="my-5">

            <div class="text-end mb-3">
                <a href="{{ route('post.post.index') }}" class="btn btn-link">Home</a>
                <a href="{{ route('post.category.create') }}" class="btn btn-outline-primary">Add category</a>
                <a href="{{ route('post.post.create') }}" class="btn btn-success">Add post</a>
            </div>

            <form action="{{ route('post.category.store') }}" method="post" class="mt-5 mx-auto w-50">
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
                    <label for="description" class="form-label">Description</label>
                    <textarea name="description" class="form-control" id="description" rows="3">{{ old('description') }}</textarea>
                </div>
                <button type="submit" class="btn btn-primary">Create</button>
            </form>

        </div>

    </div>

@endsection
