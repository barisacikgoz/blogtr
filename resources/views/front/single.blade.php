@extends('front.layouts.master')
@section('title', $article->title)
@section('bg', $article->image)
@section('content')

            <div class="col-md-9 mx-auto">
                {!! $article->content !!}
                <br><br>
                <span>Okunma Sayısı : <b>{{$article->hit}}</b></span>
            </div>
    @include('front.widgets.categoryWidget')
@endsection
