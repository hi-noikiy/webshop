@extends('master', ['pagetitle' => 'Webshop / Zoeken'])

@section('title')
    <h3>Zoeken</h3>
@endsection

@section('content')
    @if ($results->total() === 0)
        <div class="alert alert-warning" role="alert">
            Er zijn geen resultaten gevonden voor deze zoekopdracht
        </div>
    @else
        <div class="alert alert-success" role="alert">
            {{ $results->total() }} resultaten gevonden in {{ $scriptTime }} seconden.
        </div>

        @if (session()->has('changed-sku'))
            <div class="alert alert-warning" role="alert">
                Het lijkt erop dat u een oud productnummer ({{ session('changed-sku')['old'] }}) gebruikt, wij hebben dit voor u omgezet naar het nieuwe nummer: {{ session('changed-sku')['new'] }}.
            </div>
        @endif

        <div class="panel panel-primary visible-xs">
            <div class="panel-heading">
                <h4 class="panel-title text-center">
                    Zoeken
                </h4>
            </div>
            <div class="panel-body">
                <form action="/search" method="GET" class="form col-xs-12" role="search">
                    {!! csrf_field() !!}

                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Zoeken" value="{{ Input::get('q') }}" name="q" required="">
                        <span class="input-group-btn">
                            <button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-search"></i></button>
                        </span>
                    </div>
                </form>
            </div>
        </div>


        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title text-center">
                    Filter resultaten
                </h4>
            </div>
            <div class="panel-body">
                <form action="/search" method="GET" role="search" class="form-horizontal" name="advancedsearch" id="searchForm">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Merk</label>
                                <div class="col-sm-10">
                                    <select onchange="wtg.quickSearch();" name="brand" class="form-control">
                                        <option value="">----------</option>
                                        @foreach($brands as $brand)
                                            <option @if(Input::get('brand') === $brand) selected @endif value="{{ $brand }}">{{ $brand }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Serie</label>
                                <div class="col-sm-10">
                                    <select onchange="wtg.quickSearch();" name="serie" class="form-control">
                                        <option value="">----------</option>
                                        @foreach($series as $serie)
                                            <option @if(Input::get('serie') === $serie) selected @endif value="{{ $serie }}">{{ $serie }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Type</label>
                                <div class="col-sm-10">
                                    <select onchange="wtg.quickSearch();" name="type" class="form-control">
                                        <option value="">----------</option>
                                        @foreach($types as $type)
                                            <option @if(Input::get('type') === $type) selected @endif value="{{ $type }}">{{ $type }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <input name="q" type="hidden" value="{{ Input::get('q') }}">
                </form>

                <a href="/webshop" class="btn btn-default col-sm-4"><span class="glyphicon glyphicon-chevron-left"></span> Terug naar zoek pagina</a>
            </div>
        </div>


        <table class="table table-striped">
            <thead>
            <tr>
                <th></th>
                <th class="hidden-xs">Artikelnummer</th>
                <th>Omschrijving</th>

                @if(Auth::check())
                    <th class="hidden-xs">Bruto prijs</th>
                    <th class="hidden-xs">Korting</th>
                    <th>Netto prijs</th>
                @endif
            </tr>
            </thead>
            <tbody>
            @foreach($results as $product)
                <tr {{ ($product->isAction() ? 'class=success' : '') }}>
                    <td class="product-thumbnail"><img src="/img/products/{{ $product->image }}" alt="{{ $product->image }}"></td>
                    <td class="hidden-xs">{{ $product->number }}</td>
                    <td><a href="/product/{{ $product->number }}">{{ $product->name }}</a></td>

                    @if(Auth::check())
                        <td class="hidden-xs">&euro;{{ $product->real_price }}</td>
                        <td class="hidden-xs">{{ ($product->isAction() ? 'Actie' : $product->discount . '%') }}</td>
                        <td>&euro;{{ number_format($product->real_price * ((100-$product->discount) / 100), 2, ".", "") }}</td>
                    @endif
                </tr>
            @endforeach
            </tbody>
        </table>

        <div class="text-center">
            {!! $results->appends(array('brand' => request('brand'), 'serie' => request('serie'), 'type' => request('type'), 'q' => request('q')))->render() !!}
        </div>
    @endif
@endsection

@section('extraJS')
    <script type="text/javascript">
        var wtg = {
            quickSearch : function() {
                document.advancedsearch.submit();
            }
        }
    </script>
@endsection
