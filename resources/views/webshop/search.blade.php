@extends('layouts.main', ['pagetitle' => 'Webshop / Zoeken'])

@section('title')
    <h3>Zoeken</h3>
@endsection

@section('content')
    @if ($paginator->total() === 0)
        <div class="alert alert-warning" role="alert">
            Er zijn geen resultaten gevonden voor deze zoekopdracht
        </div>

        <p>Bedoelde u misschien: {{ join(' ,', $suggestions) }}</p>
    @else
        <div class="alert alert-success" role="alert">
            {{ $paginator->total() }} resultaten gevonden in {{ $scriptTime }} seconden.
        </div>

        <div class="panel panel-primary visible-xs">
            <div class="panel-heading">
                <h4 class="panel-title text-center">
                    Zoeken
                </h4>
            </div>
            <div class="panel-body">
                <form action="{{ url('search') }}" method="GET" class="form col-xs-12" role="search">
                    {{ csrf_field() }}

                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Zoeken" value="{{ request('q') }}" name="q" required="">
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
                <form action="{{ url('search') }}" method="GET" role="search" class="form-horizontal" name="advancedsearch" id="searchForm">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Merk</label>
                                <div class="col-sm-10">
                                    <select onchange="wtg.quickSearch();" name="brand" class="form-control">
                                        <option value="">----------</option>
                                        @foreach($filters['brands'] as $brand)
                                            <option {{ request('brand') === $brand['key'] ? 'selected' : '' }} value="{{ $brand['key'] }}">{{ $brand['key'] }}</option>
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
                                        @foreach($filters['series'] as $serie)
                                            <option {{ request('serie') === $serie['key'] ? 'selected' : '' }} value="{{ $serie['key'] }}">{{ $serie['key'] }}</option>
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
                                        @foreach($filters['types'] as $type)
                                            <option {{ request('type') === $type['key'] ? 'selected' : '' }} value="{{ $type['key'] }}">{{ $type['key'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <input name="q" type="hidden" value="{{ request('q') }}">
                </form>

                <a href="{{ url('webshop') }}" class="btn btn-default col-sm-4"><span class="glyphicon glyphicon-chevron-left"></span> Terug naar zoek pagina</a>
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
            <?php /** @var \App\Models\Product $product */ ?>
            @foreach($paginator->items() as $product)
                <tr {{ ($product->isAction() ? 'class=success' : '') }}>
                    <td class="product-thumbnail"><img src="/img/products/{{ $product->image }}" alt="{{ $product->image }}"></td>
                    <td class="hidden-xs">{{ $product->number }}</td>
                    <td><a href="/product/{{ $product->number }}">{{ $product->name }}</a></td>

                    @if(Auth::check())
                        <td class="hidden-xs">&euro;{{ app('format')->price($product->getPrice(false)) }}</td>
                        <td class="hidden-xs">{{ ($product->isAction() ? 'Actie' : $product->getDiscount() . '%') }}</td>
                        <td>&euro;{{ app('format')->price($product->getPrice(true)) }}</td>
                    @endif
                </tr>
            @endforeach
            </tbody>
        </table>

        <div class="text-center">
            {{ $paginator->appends(array('brand' => request('brand'), 'serie' => request('serie'), 'type' => request('type'), 'q' => request('q')))->render() }}
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
