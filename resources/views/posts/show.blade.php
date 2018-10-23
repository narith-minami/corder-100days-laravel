@extends('layout.default')

@section('title', $post->title)

@section('content')
<h1>
  <a href="{{url('/')}}" class="header-menu">Back</a>
  {{$post->title}}
</h1>
<p>{!! nl2br(e($post->body)) !!}</p>

<hr>

<a href="{{ action('PostsController@edit', $post) }}" class="edit">[Edit]</a>
<a href="#" class="del" data-id="{{ $post->id }}">[x]</a>
<form id="form_{{ $post->id }}" method="post" action="{{ url('/posts', $post->id) }}">
  {{ csrf_field() }}
  {{ method_field('delete') }}
</form>

<h2>Comments</h2>
<ul>
  @forelse ($post->comments as $comment)
  <li>
    {{ $comment->body }}
    <a href="#" class="del" data-id="{{ $comment->id }}">[x]</a>
    <form id="form_{{ $comment->id }}" method="post" action="{{ action('CommentsController@destroy', [$post,$comment]) }}">
      {{ csrf_field() }}
      {{ method_field('delete') }}
    </form>
  </li>
  @empty
  <li>No comments yet</li>
  @endforelse
</ul>

<form method="post" action="{{ action('CommentsController@store', $post) }}">
   {{ csrf_field() }}
   <p>
     <input type="text" name="body" placeholder="enter commnet" value="{{ old('comment') }}">
     @if ($errors->has('comment'))
     <span class="error">{{ $errors->first('comment') }}</span>
     @endif
   </p>
   <p><input type="submit" value="Add Commnet"></p>
</form>
<script src="/js/main.js"></script>
@endsection
