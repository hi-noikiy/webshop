@extends('customer.master', ['pagetitle' => 'Account / Favorieten'])

@section('title')
    <h3>Account <small>favorieten</small></h3>
@endsection

@section('customer.content')
    @if($favorites->count() === 0)
        <div class="alert alert-warning text-center">
            U hebt nog geen favorieten toegevoegd.
            Favorieten kunnen toegevoegd worden door op de product pagina op de volgende knop te drukken:
            <button class="btn btn-danger" disabled><span class="glyphicon glyphicon-heart"></span></button>
        </div>
    @else
        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
            @foreach($favorites as $series => $products)
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#{{ $loop->iteration }}">
                                {{ $series }}
                            </a>
                        </h4>
                    </div>
                    <div id="{{ $loop->iteration }}" class="panel-collapse collapse">
                        <div class="panel-body">
                            @foreach($products as $product)
                                @include('customer.favorites.components.item')
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
@endsection
