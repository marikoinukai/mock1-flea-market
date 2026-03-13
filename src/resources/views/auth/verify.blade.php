@extends('layouts.app')

@section('content')
    <div class="auth-page">

        <div class="auth-card">

            <h2 class="auth-title">
                登録していただいたメールアドレスに認証メールを送信しました。<br>
                メール認証を完了してください。
            </h2>

            {{-- 認証リンク --}}
            <div style="text-align:center;margin-bottom:20px;">

                <form method="GET" action="{{ route('verification.notice') }}">
                    <button class="auth-submit" style="width:260px;height:56px;background:#d9d9d9;color:#000;">
                        認証はこちらから
                    </button>
                </form>

            </div>

        </div>

    </div>
@endsection
