@extends('layout.default')

@section('title', 'New Post')

@section('content')
<h1><a href="{{url('/')}}" class="header-menu">Back</a>
  New Post
</h1>

<form method="post" action="{{ url('/posts') }}">
   {{ csrf_field() }}
   <p>
     <input type="text" name="title" placeholder="enter title" value="{{ old('title') }}">
     @if ($errors->has('title'))
     <span class="error">{{ $errors->first('title') }}</span>
     @endif
   </p>
   <p>
     <textarea name="body" placeholder="enter body" value="{{ old('body') }}"></textarea>
     @if ($errors->has('body'))
     <span class="error">{{ $errors->first('body') }}</span>
     @endif
   </p>
   <p>
     <input type="radio" name="category_id" value="1">日記
     <input type="radio" name="category_id" value="2">プログラミング
     <input type="radio" name="category_id" value="3">その他
   </p>
   <p><input type="submit" value="Add"></p>
</form>
@endsection
