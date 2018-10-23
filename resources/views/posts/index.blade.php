@extends('layout.default')

@section('title', 'My Note')

@section('content')
<h1>
  <a href="{{url('/posts/create')}}" class="header-menu">New Post</a>
  My Note
</h1>
<div>
  <div class="top-category">
    @if ($select_category === '0')
    <a class="category-link selected" href="{{ url('/') }}">ホーム</a>
    @else
    <a class="category-link" href="{{ url('/') }}">ホーム</a>
    @endif

    @if ($select_category === '1')
    <a class="category-link selected" href="{{ action('PostsController@filter', 1) }}">日記</a>
    @else
    <a class="category-link" href="{{ action('PostsController@filter', 1) }}">日記</a>
    @endif

    @if ($select_category === '2')
    <a class="category-link selected" href="{{ action('PostsController@filter', 2) }}">プログラミング</a>
    @else
    <a class="category-link" href="{{ action('PostsController@filter', 2) }}">プログラミング</a>
    @endif

    @if ($select_category === '3')
    <a class="category-link selected" href="{{ action('PostsController@filter', 3) }}">その他</a>
    @else
    <a class="category-link" href="{{ action('PostsController@filter', 3) }}">その他</a>
    @endif
  </div>
  @forelse  ($posts as $post)
  <div class="blog-item">
    @if ($post->category_id === '1')
    <p class="blog-category dairy">日記</p>
    @elseif ($post->category_id === '2')
    <p class="blog-category programing">プログラミング</p>
    @elseif ($post->category_id === '3')
    <p class="blog-category other">その他</p>
    @endif
    <p class="blog-created">{{$post->created_at}}</p>
    <a class="blog-title" href="{{ action('PostsController@show', $post) }}">{{$post->title}}</a>
    <div class="blog-body">{!! nl2br(e($post->body)) !!}</div>
    <div class="blog-comment">
      <p>コメント：{{ $post->comments->count()}}</p>
    </div>
  </div>
  @empty
  <p>記事がありません</p>
  @endforelse
</div>
<script src="/js/main.js"></script>
@endsection
