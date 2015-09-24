@extends('master')

@section('title')
        <h3>Assortiment</h3>
@stop

@section('content')
        <div class="row">
                <div class="col-md-12">
                        <table class="table table-striped">
                                <tbody>
                                <tr>
                                        <td>
                                                <b>Sanitair</b>
                                        </td>
                                        <td>
                                                Sphinx, Venlo, F. Grohe, Bette, Viega, Geberit, Wisa, Jada, Linido,
                                                McAlpine, Pressalit, Pagette, Trendline, Carrara &amp; Matta, Schell,
                                                Geesa,
                                                Haceka, Daalderop/Itho, Rada, Presto, SFA (sanibroyeurs etc.) en nog
                                                veel meer.
                                        </td>
                                </tr>
                                <tr>
                                        <td>
                                                <b>Dakbedekking</b>
                                        </td>
                                        <td>
                                                Icopal en Troelstra en de Vries produkten voor bitumineuze daken. PIR
                                                dakisolatie en
                                                EPDM dakbedekking en lichtkoepels.
                                        </td>
                                </tr>
                                <tr>
                                        <td>
                                                <b>Non-ferro materiaal</b>
                                        </td>
                                        <td>Bladlood, Rheinzink , Halcor, Wicu, zinken en koperen HWA systemen en nog
                                                veel meer.
                                        </td>
                                </tr>
                                <tr>
                                        <td>
                                                <b>Riolering/HWA systemen</b>
                                        </td>
                                        <td>
                                                PVC, PP, PE afvoersystemen voor binnen- en buitenriolering en
                                                hemelwaterafvoer,
                                                drinkwaterleidingsystemen alles van fabrikaat Dyka, PE van Akatherm,
                                                daarnaast
                                                een groot assortiment vetafscheiders en vuilwaterpompen van fabrikaat
                                                Kessel.
                                        </td>
                                </tr>
                                <tr>
                                        <td>
                                                <b>Fittingen en afsluiters</b>
                                        </td>
                                        <td>
                                                Bonfix, Viega pex-fit, Profipress/Sanpress en draadfittingen, Uponor,
                                                Henco,
                                                Raminex en Watts keerkleppen.
                                        </td>
                                </tr>
                                <tr>
                                        <td>
                                                <b>Gereedschap, bevestigingsmateriaal en lijmen</b>
                                        </td>
                                        <td>Flamco, Walraven, Stanley, Bison en nog veel meer.</td>
                                </tr>
                                <tr>
                                        <td>
                                                <b>Rookgasafvoermateriaal</b>
                                        </td>
                                        <td>
                                                Burgerhout, aluminium dikwandig, dunwandig en NEN afvoermateriaal,
                                                PP en RVS afvoermateriaal, dakdoorvoeren voor geysers, kachels, HR en VR
                                                ketels in
                                                PP/RVS/aluminium, schoorstenen, ventilatiesystemen en warmte
                                                terugwinningsystemen.
                                        </td>
                                </tr>
                                <tr>
                                        <td>
                                                <b>Soldeermiddelen en kitten</b>
                                        </td>
                                        <td>Shell tixophalte, Bison/Griffon professional, Kayser propaangereedschap.
                                        </td>
                                </tr>
                                <tr>
                                        <td>
                                                <b>Ventilatie en ventilatoren</b>
                                        </td>
                                        <td>
                                                Soler &amp; Palau/Itho ventilatoren en woonhuisboxen, Spiralo
                                                mechanische
                                                ventilatie systemen, Anjo dakventilatoren, kiezelbakken, noodoverlaten
                                                en
                                                ontluchtingen voor bitumen en pvc daken.
                                        </td>
                                </tr>
                                <tr>
                                        <td>
                                                <b>Centrale verwarming</b>
                                        </td>
                                        <td>
                                                CV buizen, Heimeier afsluiters, radiator toebehoren, Reflex
                                                expansievaten, Magnum
                                                elektrische vloerverwarming, Viega vloerverwarming, Robot
                                                vloerverwarming, Stelrad
                                                radiatoren en Remeha cv ketels.
                                        </td>
                                </tr>
                                </tbody>
                        </table>
                </div>
        </div>

        <div class="row">
                <div class="col-md-10 col-md-offset-1 hidden-xs well well-lg">
                        <p>Om de website van de fabrikant te bezoeken, kunt u eenvoudig op het logo klikken.</p>
                        <br>

                        <div class="sidebar">
                                @foreach($manufacturers->data as $manufacturer)
                                        <div>
                                                <a href="{{ $manufacturer->siteUrl }}" target="_blank">
                                                        <img src="/img/slider/{{ $manufacturer->imageLink }}" alt="{{ $manufacturer->alt }}" height="70">
                                                </a>
                                        </div>
                                @endforeach
                        </div>
                </div>
        </div>
@stop

@section('extraCSS')
        <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/jquery.slick/1.3.15/slick.css"/>
@stop

@section('extraJS')
        <script type="text/javascript" src="//cdn.jsdelivr.net/jquery.slick/1.3.8/slick.min.js"></script>

        <script type="text/javascript">
                $('.sidebar').slick({
                        accessibility: false,
                        autoplay: true,
                        autoplaySpeed: 3000,
                        speed: 1000,
                        variableWidth: false,
                        pauseOnHover: true,
                        slidesToShow: 4,
                        infinite: true,
                        cssEase: 'ease'
                });
        </script>
@stop
