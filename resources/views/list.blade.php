@extends('layout.default')

@section('head')
<title>Coder-100Days | View</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name=viewport content="width=device-width,initial-scale=1">
<link rel="shortcut icon" type="image/x-icon" href="favicon.ico">
<link rel="stylesheet" type="text/css" href="/css/list.css"/>
<link rel="stylesheet" type="text/css" href="/css/VerticalTimeline/css/component.css"/>
<link rel="stylesheet" type="text/css" href="/css/VerticalTimeline/css/default.css"/>
@endsection

@section('content')
<div id="list-header">
  <div id="list-date">
    <div id="show-all" class="date-item" data-index="0">全体に戻す</div>
    <div id="user-display-name" class="" ></div>
  </div>
</div>

<div id="search">
  <input id="search-query" type="text" placeholder="キーワード検索 (例:HTML,css..) " />
  <button id="search-submit" text="検索"><i class="fas fa-search"></i></button>
  <div id="my-button">
    <button id="show-my-tweet" text="My Tweet"/>
  </div>
</div>

<div id="user-view"></div>

<p>{{ $tweets }}</p>
<div id="list-items">
<ul class="cbp_tmtimeline">
{{-- @forelse ($tweets as $tweet)
<li>
  <time class="cbp_tmtime" datetime="{{ $tweet->sCreatedAt }}"><span>{{ $tweet->sCreatedYMD }}</span> <span>{{ $tweet->sCreatedHI }}</span></time>
  <div class="cbp_tmicon"><img class="avator" src="{{ $tweet->sProfileImageUrl }}" data-display-name="{{ $tweet->sName }}" data-username="{{ $tweet->sScreenName }}"/></div>
  <div class="cbp_tmlabel" data-username="{{ $tweet->sScreenName }}" data-tweet-url="{{ $tweet->tweetURL }}">
    <h2 class="user-name" data-username="{{ $tweet->sScreenName }}">{{ $tweet->sName }}</h2>
    <p>{{ $tweet->sText }}</p>
    <div><img class="{{ $tweet->favClass }}" data-status="{{ $tweet->isFavorited }}" data-tweet-id="{{ $tweet->sIdStr }}" src="{{ $tweet->heartIcon }}"/>
      <span class="show_on_tweet" data-username="{{ $tweet->sScreenName }}" data-tweet-url="{{ $tweet->tweetURL }}">twitterで見る</span></div>
    </div>
</li>

<img class="avator" src="{{ $tweet->sProfileImageUrl }}'" data-display-name="{{ $tweet->sName }}" data-username="{{ $tweet->sScreenName }}"/>
@empty
<p>ツイートがありません</p>
@endforelse --}}
</ul>
</div>

<!-- <div id="user-list"><div id="user-list-inner">'$tweet->userIcons.'</div></div> -->

<div style="text-align:center;padding-top:8px;"><button id="show-more-button">もっと見る</button></div>

<script type="text/javascript" src="/js/jquery-2.1.1.min.js"></script>
<script type="text/javascript" src="/js/list.js"></script>
@endsection
