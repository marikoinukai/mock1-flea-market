<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\RegisterResponse as RegisterResponseContract;
use Illuminate\Http\Request;

class RegisterResponse implements RegisterResponseContract
{
    public function toResponse($request)
    {
        // 登録直後はプロフィール編集へ
        return redirect()->route('profile.edit');
    }
}
