<?php

namespace App\Providers;

use App\Application\Validation\Post\PostCategoryValidation;
use App\Application\Validation\Post\PostValidation;
use App\Domain\Post\Repository\PostCategoryRepositoryContract;
use App\Domain\Post\Repository\PostRepositoryContract;
use App\Domain\Shared\Repository\TransactionContract;
use App\Infrastructure\Application\Validation\Post\Laravel\LaravelPostCategoryValidation;
use App\Infrastructure\Application\Validation\Post\Laravel\LaravelPostValidation;
use App\Infrastructure\Domain\Post\Repository\Laravel\LaravelPostCategoryRepository;
use App\Infrastructure\Domain\Post\Repository\Laravel\LaravelPostRepository;
use App\Infrastructure\Domain\Shared\Repository\LaravelTransaction;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public array $bindings = [
        TransactionContract::class => LaravelTransaction::class,
        PostCategoryRepositoryContract::class => LaravelPostCategoryRepository::class,
        PostRepositoryContract::class => LaravelPostRepository::class,

        PostCategoryValidation::class => LaravelPostCategoryValidation::class,
        PostValidation::class => LaravelPostValidation::class,
    ];

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
