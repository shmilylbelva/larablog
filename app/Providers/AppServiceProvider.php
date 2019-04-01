<?php

namespace App\Providers;

use App\Http\ViewComposers\CategoryComposer;
use App\Models\Article;
use App\Models\Config;
use App\Models\Link;
use App\Models\Tag;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use App\Models\Comment;
use App\Observers\CommentObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        \Carbon\Carbon::setLocale('zh');
        Schema::defaultStringLength(191);

        view()->composer('layouts.main',CategoryComposer::class);//导航栏视图共享
        view()->composer(['layouts.main','login.create','users.create'], function ($view) {
            $view->with('cfg',(object)Config::getAll());
        });//配置信息视图共享
        view()->composer(['layouts.main'], function ($view) {
            $view->with('hot_articles', Article::getHot());
        });
        view()->composer(['layouts.main'], function ($view) {
            $view->with('recent_articles',Article::getRecent());
        });//配置信息视图共享
//
        view()->composer(['layouts.main'], function ($view) {
            $view->with('files',Article::getFile());
        });//配置信息视图共享
//
        view()->composer(['layouts.main'], function ($view) {
            $view->with('tags',Tag::getHot(80)->shuffle());
        });//配置信息视图共享
//
        view()->composer(['layouts.main'], function ($view) {
            $view->with('links',Link::getAll());
        });//配置信息视图共享


        Comment::observe(CommentObserver::class);


    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        \API::error(function (\Illuminate\Database\Eloquent\ModelNotFoundException $exception) {
            abort(404);
        });

        \API::error(function (\Illuminate\Auth\Access\AuthorizationException $exception) {
            abort(403, $exception->getMessage());
        });
    }
}
