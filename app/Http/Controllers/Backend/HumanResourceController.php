<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class HumanResourceController extends Controller
{
    public function __invoke(): View
    {
        return view('backend.hr.dashboard');
    }
}
