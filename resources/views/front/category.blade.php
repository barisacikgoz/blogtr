@extends('front.layouts.master')
@section('title', $category->name. ' Kategorisi.')

@section('content')
@if(count($articles) > 0 )
    <div class="col-md-9 mx-auto">
        @foreach($articles as $article)
            <div class="post-preview">
                <a href="{{route('single', [$article->getCategory->slug, $article->slug])}}">
                    <h2 class="post-title">
                        {{$article->title}}
                    </h2>
                    <img src="{{$article->image}}" />
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
            @else
                <div class="alert alert-danger float-right m-auto">
                    <h2>Bu kategoriye ait yazı bulunamadı</h2>
                </div>
            @endif
    </div>
    @include('front.widgets.categoryWidget')
@endsection
