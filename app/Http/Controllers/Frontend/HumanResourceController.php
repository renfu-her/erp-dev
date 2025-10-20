<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class HumanResourceController extends Controller
{
    public function __invoke(): View
    {
        return view('frontend.hr.self-service');
    }
}
