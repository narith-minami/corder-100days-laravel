@extends('layout.default')

@section('head')
<title>#100DaysOfCodeを発信する人のTweetViewer | Coder-100Days</title>
<link rel="stylesheet" type="text/css" href="/css/home.css"/>
<link rel="shortcut icon" type="image/x-icon" href="favicon.ico">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name=viewport content="width=device-width,initial-scale=1">
<meta name="description" content="Coder-100DaysはTwitterを利用した #100DaysOfCode を発信する人のTweetViewerです。">
@endsection

@section('content')
<div id="home-header"><span>Coder 100Days</span></div>
<div id="home-main">
  <span class="sub-title">本サービスはTwitterを利用した #100DaysOfCode を発信する人のTweetViewerです。</span>
  <button id="go-search-button" >ツイートを見る</button>
</div>
@endsection
