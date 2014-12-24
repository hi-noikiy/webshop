@extends('master')

@section('title')
        <h3>Webshop</h3>
@stop

@section('content')
        <div class="panel panel-default">
                <div class="panel-heading">
                        <h4 class="panel-title text-center">
                                Snel zoeken
                        </h4>
                </div>
                <div class="panel-body">
                        <form action="/search" method="POST" role="search" class="form-horizontal" name="advancedsearch" id="searchForm quickSearch">
                                <div class="row">
                                        <div class="col-md-4">
                                                <div class="form-group">
                                                        <label class="col-sm-2 control-label">Merk</label>
                                                        <div class="col-sm-10">
                                                                <select onchange="wtg.quickSearch();" name="merk" class="form-control">
                                                                        <option value="">----------</option>
                                                                        @foreach($brands as $brand)
                                                                                <option value="{{ $brand->brand }}">{{ $brand->brand }}</option>
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
                                                                                <option value="{{ $serie->series }}">{{ $serie->series }}</option>
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
                                                                                <option value="{{ $type->type }}">{{ $type->type }}</option>
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
                                                        <input id="quickSearchInput" class="form-control" name="search" placeholder="Zoeken" value="Zoeken" type="text">
						<span class="input-group-btn">
							<button type="submit" class="btn btn-primary">Zoeken</button>
						</span>
                                                </div>
                                        </div>
                                </div>
                        </form>

                        <!-- Hidden on load, will show after a dropdown option has changed -->
                        <div class="row text-center" id="searchLoading" style="display: none;">
                                <img src="/img/loading.gif" width="70px">
                        </div>
                </div>
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