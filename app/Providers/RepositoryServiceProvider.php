<?php

namespace App\Providers;

use App\Repositories\Contracts\EbookRepositoryInterface;
use App\Repositories\Contracts\OrderRepositoryInterface;
use App\Repositories\Contracts\TransactionRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Eloquent\EbookRepository;
use App\Repositories\Eloquent\OrderRepository;
use App\Repositories\Eloquent\TransactionRepository;
use App\Repositories\Eloquent\UserRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /** @var array<class-string, class-string> */
    public array $bindings = [
        EbookRepositoryInterface::class       => EbookRepository::class,
        OrderRepositoryInterface::class       => OrderRepository::class,
        TransactionRepositoryInterface::class => TransactionRepository::class,
        UserRepositoryInterface::class        => UserRepository::class,
    ];

    public function register(): void
    {
        foreach ($this->bindings as $abstract => $concrete) {
            $this->app->bind($abstract, $concrete);
        }
    }
}
