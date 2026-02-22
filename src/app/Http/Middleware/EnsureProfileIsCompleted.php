<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureProfileIsCompleted
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        // 未ログインはここでは扱わない（authミドルウェアが担当）
        if (!$user) {
            return $next($request);
        }

        // プロフィール画面自身は除外（無限ループ防止）
        if ($request->routeIs('profile.edit') || $request->routeIs('profile.update')) {
            return $next($request);
        }

        // postal_code または address_line1 が空なら未設定とみなす
        if (empty($user->postal_code) || empty($user->address_line1)) {
            return redirect()->route('profile.edit')
                ->with('warning', '購入の前にプロフィール（住所）を設定してください。');
        }

        return $next($request);
    }
}
