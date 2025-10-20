<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class WebAuthController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $remember = $request->boolean('remember');

        if (! Auth::attempt($credentials, $remember)) {
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        $request->session()->regenerate();

        $target = $request->input('login_target', 'auto');
        $user = $request->user();
        $redirect = null;
        $portalMode = null;

        if ($target === 'backend') {
            if ($user->hasPermission('backend.access')) {
                $redirect = route('backend.dashboard');
                $portalMode = 'backend';
            } else {
                $request->session()->flash('status', '登入成功，但尚未開通後台權限，已帶您返回員工入口。');
                $portalMode = $user->hasPermission('frontend.portal.access') ? 'employee' : null;
            }
        } elseif ($target === 'employee') {
            if ($user->hasPermission('frontend.portal.access')) {
                $redirect = route('frontend.hr.self-service');
                $portalMode = 'employee';
            } else {
                $request->session()->flash('status', '登入成功，尚未啟用員工入口，已帶您前往可使用的後台模組。');
                $portalMode = $user->hasPermission('backend.access') ? 'backend' : null;
            }
        }

        if (! $redirect) {
            $redirect = route('frontend.home');

            if ($user->hasPermission('backend.access')) {
                $redirect = route('backend.dashboard');
                $portalMode ??= 'backend';
            } elseif ($user->hasPermission('frontend.portal.access')) {
                $redirect = route('frontend.hr.self-service');
                $portalMode ??= 'employee';
            }
        }

        $request->session()->put('portal_mode', $portalMode ?? 'auto');

        return redirect()->intended($redirect);
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard()->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();
        $request->session()->forget('portal_mode');

        return redirect()->route('login');
    }
}
