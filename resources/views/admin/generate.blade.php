@extends('master')

@section('title')
        <h3>Admin <small>content genereren</small></h3>
@stop

@section('content')
        @include('admin.nav')

        <h3>Catalogus genereren</h3>

        <hr />

        <div class="row">
                <div class="col-md-12">
                        <form action="/admin/generateCatalog" method="POST" class="form form-horizontal">
                                {!! csrf_field() !!}

                                <div class="form-group">
                                        <label for="footer" class="col-sm-2 control-label">Pagina footer</label>
                                        <div class="col-sm-10">
                                                <input class="form-control" placeholder="Pagina footer" type="text" value="{{ $currentFooter }}" name="footer" maxlength="300">
                                        </div>
                                </div>

                                <div class="form-group">
                                        <div class="col-sm-offset-2 col-sm-10">
                                                <button type="submit" class="btn btn-primary">Genereren</button>
                                        </div>
                                </div>
                        </form>

                        <hr />
                </div>
        </div>
@stop