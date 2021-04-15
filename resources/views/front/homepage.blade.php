@extends('front.layouts.master')
@section('title', 'Anasayfa')

@section('content')

    <div class="col-md-9 mx-auto">
        @foreach($articles as $article)
            <div class="post-preview">
                <a href="{{route('single', [$article->getCategory->slug, $article->slug])}}">
                    <h2 class="post-title">
                        {{$article->title}}
                    </h2>
                    <img src="{{$article->image}}" width="820" height="400" />
                    <h3 class="post-subtitle">
                        {!! Str::limit($article->content, 120) !!}
                    </h3>
                </a>
                <p class="post-meta">Kategori :<a href="#">{{$article->getCategory->name}}</a>
                    <span class="float-right">{{$article->created_at->diffForHumans()}}</span>
                </p>
            </div>
            @if(!$loop->last)
                <hr>
            @endif

        @endforeach
    </div>
    @include('front.widgets.categoryWidget')
@endsection
