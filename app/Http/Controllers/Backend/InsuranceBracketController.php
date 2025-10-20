<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\InsuranceBracket;
use Illuminate\View\View;

class InsuranceBracketController extends Controller
{
    public function index(): View
    {
        $brackets = InsuranceBracket::orderBy('grade')->paginate(20)->withQueryString();

        return view('backend.insurance-brackets.index', [
            'brackets' => $brackets,
        ]);
    }
}
