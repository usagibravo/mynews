@extends('layouts.front')

@section('content')
    <div class="container profile">
        <h1 "fw-bold fst-italic">プロファイル一覧</h1>
        <hr color="#c0c0c0">
            <div class="row">
            </div>
            <div class="row title">
                <div class="date col-md-2">最終更新日</div>
                <div class="name col-md-2">氏名</div>
                <div class="gender col-md-1">性別</div>
                <div class="hobby col-md-3">趣味</div>
                <div class="introduction col-md-3">自己紹介欄</div>
            </div>
            <hr color="#c0c0c0">
        @if (count($profiles) > 0)
            <div class="row">
                <div class="profile mt-3">
                    @foreach($profiles as $profile)
                        <div class="profile">
                            <div class="row">
                                <div class="date col-md-2">
                                    {{ $profile->updated_at->format('Y年m月d日') }}
                                </div>
                                <div class="name col-md-2">
                                    {{ Str::limit($profile->name, 20) }}
                                </div>
                                <div class="gender col-md-1">
                                    {{ Str::limit($profile->gender, 10) }}
                                </div>
                                <div class="hobby col-md-3">
                                    {{ Str::limit($profile->hobby, 70) }}
                                </div>
                                <div class="introduction col-md-3">
                                    {{ Str::limit($profile->introduction, 250) }}
                                </div>
                            </div>
                        </div>
                        <hr color="#c0c0c0">
                    @endforeach
                </div>
            </div>
        @endif
    </div>
@endsection