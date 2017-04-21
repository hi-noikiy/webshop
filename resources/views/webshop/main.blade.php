@extends('layouts.main', ['pagetitle' => 'Webshop / Home'])

@section('title')
        <h3>Webshop</h3>
@endsection

@section('content')
        <div class="panel panel-default">
                <div class="panel-heading">
                        <h4 class="panel-title text-center">
                                Snel zoeken
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
                                                                                <option value="{{ $brand }}">{{ $brand }}</option>
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
                                                                                <option value="{{ $serie }}">{{ $serie }}</option>
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
                                                                                <option value="{{ $type }}">{{ $type }}</option>
                                                                        @endforeach
                                                                </select>
                                                        </div>
                                                </div>
                                        </div>
                                </div>

                                <hr>

                                <div class="row">
                                        <div class="col-md-12">
                                                <div class="input-group">
                                                        <input id="quickSearchInput" class="form-control" name="q" placeholder="Zoeken" type="text">
        						<span class="input-group-btn">
        							<button type="submit" class="btn btn-primary">Zoeken</button>
        						</span>
                                                </div>
                                        </div>
                                </div>
                        </form>
                </div>
        </div>
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
