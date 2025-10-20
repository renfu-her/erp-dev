<?php

namespace App\Providers;

use App\View\Composers\FrontendEmployeeComposer;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewComposerServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        View::composer('frontend.hr.self-service', FrontendEmployeeComposer::class);
    }
}
