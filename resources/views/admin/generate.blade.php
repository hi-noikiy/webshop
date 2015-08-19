@extends('master')

@section('title')
        <h3>Admin <small>content genereren</small></h3>
@stop

@section('content')
        @include('admin.nav')

        <div class="row">
                <div class="col-md-12">
                        <h4>Catalogus genereren</h4>

                        <form action="/admin/generateCatalog" method="POST" class="form">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <button type="submit" class="btn btn-primary">Genereren</button>
                        </form>

                        <hr />
                </div>
        </div>
@stop