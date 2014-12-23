@extends('master')

@section('title')
        <h1>Welkom op de website van Wiringa Technische Groothandel</h1>
@stop

@section('content')
        <div class="row">
                <div class="col-md-5">
                        <div id="slideshow" class="carousel slide">
                                <ol class="carousel-indicators">
                                        <li data-target="#slideshow" data-slide-to="0" class="active"></li>
                                        <li data-target="#slideshow" data-slide-to="1" class=""></li>
                                        <li data-target="#slideshow" data-slide-to="2" class=""></li>
                                        <li data-target="#slideshow" data-slide-to="3" class=""></li>
                                        <li data-target="#slideshow" data-slide-to="4" class=""></li>
                                </ol>
                                <div class="carousel-inner">
                                        <div class="item active">
                                                <img src="/img/carousel/profactie.jpg" alt="profactie.jpg">
                                                <div class="carousel-caption">
                                                        <h3>Viega Profipress actiepakket</h3>
                                                        <p>Viega Profipress actiepakket voor een scherpe prijs!!!</p>
                                                </div>
                                        </div>
                                        <div class="item">
                                                <img src="/img/carousel/prestactie.jpg" alt="prestactie.jpg">
                                                <div class="carousel-caption">
                                                        <h3>Viega Prestabo actiepakket</h3>
                                                        <p>Viega Prestabo actiepakket voor een scherpe prijs!!!</p>
                                                </div>
                                        </div>
                                        <div class="item">
                                                <img src="/img/carousel/182150050.jpg" alt="182150050.jpg">
                                                <div class="carousel-caption">
                                                        <h3>Blucher afvoerputten</h3>
                                                        <p>Blucher vloerputten programma</p>
                                                </div>
                                        </div>
                                        <div class="item">
                                                <img src="/img/carousel/ra280326.jpg" alt="ra280326.jpg">
                                                <div class="carousel-caption">
                                                        <h3>Raminex terugstroombeveiligingen</h3>
                                                        <p>Kogelkraan met keerklep in easy service (ES) uitvoering.</p>
                                                </div>
                                        </div>
                                        <div class="item">
                                                <img src="/img/carousel/bu400480100.jpg" alt="bu400480100.jpg">
                                                <div class="carousel-caption">
                                                        <h3>EcoFlo</h3>
                                                        <p>Tapwater opwarmen via het rookgaskanaal. Nieuw van Burgerhout.</p>
                                                </div>
                                        </div>

                                </div>
                                <a class="carousel-control left" href="#slideshow" data-slide="prev"><span class="icon-prev"></span></a>
                                <a class="carousel-control right" href="#slideshow" data-slide="next"><span class="icon-next"></span></a>
                        </div>
                </div>

                <div class="col-md-7">
                        @if(Auth::check())
                                <p>Welkom op de vernieuwde website. De belangrijkste wijziging is onze nieuwe webshop.</p>
                                <p>Mocht u een rechtstreekse link gebruiken naar de oude webwinkel dan zal deze nu niet meer werken.</p>
                        @else
                                <div class="row">
                                        <div class="col-md-7">
                                                <p>
                                                        Welkom op de vernieuwde website. <br />
                                                        De belangrijkste wijziging is onze nieuwe webshop.
                                                </p>
                                                <p>Mocht u een rechtstreekse link gebruiken naar de oude webwinkel dan zal deze nu niet meer werken.</p>
                                        </div>
                                        <div class="col-md-5">
                                                <div class="well well-sm text-center">
                                                        <h3>Klant worden?</h3>
                                                        <a class="btn btn-block btn-success" href="/register">Ja, ik wil klant worden!</a>
                                                </div>
                                        </div>
                                </div>
                        @endif
                </div>
        </div>
@stop