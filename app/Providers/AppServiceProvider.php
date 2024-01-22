<?php

namespace App\Providers;

use App\Helpers\BuilderRelationMixin;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Builder::mixin(new BuilderRelationMixin());
        Relation::mixin(new BuilderRelationMixin());
    }
}
