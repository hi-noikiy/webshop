@extends('master', ['pagetitle' => 'Webshop / Zoeken'])

@section('title')
    <h3>{{ $title }}</h3>
@stop

@section('content')
    <div class="alert alert-success" role="alert">
        {{ $results->total() }} resultaten gevonden in {{ $scriptTime }} seconden.
    </div>

    <table class="table table-striped">
        <thead>
        <tr>
            <th></th>
            <th class="hidden-xs">Artikelnummer</th>
            <th>Omschrijving</th>
        </tr>
        </thead>
        <tbody>
        @foreach($results as $product)
            <?php
                if (\App\Product::where('number', $product->id)->count()) {
                    $id      = $product->id;
                    $special = false;
                } else {
                    $id      = 'Actiepakket';
                    $special = true;
                }
            ?>

            <tr class="success">
                @if (!$special)
                    <td class="product-thumbnail"><img src="/img/products/{{ $product->image }}" alt="{{ $product->image }}"></td>
                    <td class="hidden-xs">{{ $id }}</td>
                    <td><a href="/product/{{ $product->id }}">{{ $product->name }}</a></td>
                @else
                    <td class="product-thumbnail"><img src="/img/specials/{{ $product->image ? $product->image : 'default.jpg' }}" alt="{{ $product->image }}"></td>
                    <td class="hidden-xs">{{ $id }}</td>
                    <td><a href="/pack/{{ $product->id }}">{{ $product->name }}</a></td>
                @endif
            </tr>
        @endforeach
        </tbody>
    </table>

    <div class="text-center">
        {!! $results->render() !!}
    </div>
@stop

@section('extraJS')
    <script type="text/javascript">
        var wtg = {
            quickSearch: function () {
                document.advancedsearch.submit();
            }
        }
    </script>
@stop
