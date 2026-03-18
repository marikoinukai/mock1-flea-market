@extends('layouts.app')

@section('content')
    <div class="verify-page">
        <div class="verify-card">
            <p class="verify-message">
                登録していただいたメールアドレスに認証メールを送付しました。<br>
                メール認証を完了してください。
            </p>

            <div class="verify-action">
                <a href="http://localhost:8025" target="_blank" rel="noopener noreferrer" class="verify-button">
                    認証はこちらから
                </a>
            </div>

            <div class="verify-resend">
                <form method="POST" action="{{ url('/email/verification-notification') }}">
                    @csrf
                    <button type="submit" class="verify-resend__link">
                        認証メールを再送する
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
