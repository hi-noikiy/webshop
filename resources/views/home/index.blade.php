@extends('layouts.home', ['pagetitle' => 'Home'])

@section('title')
    <h2>Welkom op de webshop van Wiringa Technische Groothandel</h2>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div id="slideshow" class="carousel slide" data-ride="carousel">
                <ol class="carousel-indicators">
                    {{--@foreach($carouselSlides as $carouselSlide)--}}
                    {{--<li data-target="#slideshow" data-slide-to="{{ $loop->index }}"--}}
                    {{--class="{{ ($loop->first ? 'active' : '') }}"></li>--}}
                    <li data-target="#slideshow" data-slide-to="0"></li>
                    <li data-target="#slideshow" data-slide-to="1"></li>
                    <li data-target="#slideshow" data-slide-to="2"></li>
                    {{--@endforeach--}}
                </ol>

                <div class="carousel-inner">
                    {{--@foreach ($carouselSlides as $slide)--}}
                    {{--<div class="item {{ ($loop->first ? 'active' : '') }}">--}}
                    {{--<img src="/img/carousel/{{ $slide->Image }}" alt="{{ $slide->Image }}" style="height: 300px">--}}
                    {{--<div class="carousel-caption">--}}
                    {{--<h3>{{ $slide->Title }}</h3>--}}
                    {{--<p>{{ $slide->Caption }}</p>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    <div class="item active">
                        <img src="https://lorempixel.com/450/300/cats" alt="" class="img-responsive">
                        <div class="carousel-caption">
                            <h3>Foo</h3>
                            <p>Lorem ipsum dolor sit amet</p>
                        </div>
                    </div>
                    <div class="item">
                        <img src="https://lorempixel.com/300/300/cats" alt="" class="img-responsive">
                        <div class="carousel-caption">
                            <h3>Foo</h3>
                            <p>Lorem ipsum dolor sit amet</p>
                        </div>
                    </div>
                    <div class="item">
                        <img src="https://lorempixel.com/150/300/cats" alt="" class="img-responsive">
                        <div class="carousel-caption">
                            <h3>Foo</h3>
                            <p>Lorem ipsum dolor sit amet</p>
                        </div>
                    </div>
                    {{--@endforeach--}}
                </div>

                <a class="carousel-control left" href="#slideshow" data-slide="prev"><span class="icon-prev"></span></a>
                <a class="carousel-control right" href="#slideshow" data-slide="next"><span class="icon-next"></span></a>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="news">
                <h3 class="text-center news-header">Nieuws</h3>

                @if($news)
                    {!! $news !!}
                @else
                    <p>
                        Er is op dit moment geen nieuws
                    </p>
                @endif
            </div>
        </div>
    </div>
@endsection
