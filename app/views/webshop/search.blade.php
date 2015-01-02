@extends('master')

@section('title')
        <h3>Zoeken</h3>
@stop

@section('content')
        <div class="alert alert-success" role="alert">
                {{ $results->getTotal() }} resultaten gevonden in {{ $scriptTime }} seconden.
        </div>
        <div class="panel panel-default">
                <div class="panel-heading">
                        <h4 class="panel-title text-center">
                                Geavanceerd zoeken
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

                        <a href="javascript:window.history.go(-1);"><span class="glyphicon glyphicon-chevron-left"></span> terug naar vorige pagina</a>

                        <!-- Hidden on load, will show after a dropdown option has changed -->
                        <div class="row text-center" id="searchLoading" style="display: none;">
                                <img src="/img/loading.gif" width="70px">
                        </div>
                </div>
        </div>


        <table class="table table-striped">
                <thead>
                        <tr>
                                <th></th>
                                <th>Artikelnummer</th>
                                <th>Omschrijving</th>
                                @if(Auth::check())
                                        <th>Bruto prijs</th>
                                        <th>Korting</th>
                                        <th>Netto prijs</th>
                                @endif
                        </tr>
                </thead>
                <tbody>
                        @foreach($results as $product)
                                <tr>
                                        <td class="product-thumbnail"><img src="/img/{{ $product->image }}" alt="{{ $product->image }}"></td>
                                        <td>{{ $product->number }}</td>
                                        <td><a href="/product/{{ $product->number }}">{{ $product->name }}</a></td>
                                        @if(Auth::check())
                                                <td>&euro;{{ $product->price }}</td>
                                                <td>0%</td>
                                                <td>&euro;{{ $product->price }}</td>
                                        @endif
                                </tr>
                        @endforeach
                </tbody>
        </table>

        <div class="text-center">
                {{ $results->appends(array('brand' => Input::get('brand'), 'serie' => Input::get('serie'), 'type' => Input::get('type'), 'q' => Input::get('q')))->links() }}
        </div>
@stop

@section('extraJS')
        <script type="text/javascript">
                var wtg = {
                        quickSearch : function() {
                                document.advancedsearch.submit();
                        }
                }
        </script>
@stop