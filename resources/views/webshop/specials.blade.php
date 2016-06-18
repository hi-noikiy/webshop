@extends('master', ['pagetitle' => 'Webshop / Acties'])

@section('title')
    <h3>Acties</h3>
@endsection

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
            @if(Auth::check())
                <th>Netto prijs</th>
            @endif
        </tr>
        </thead>
        <tbody>
        @foreach($results as $product)
            <?php $price = (double) number_format($product->special_price, 2, ".", ""); ?>

            <tr class="success">
                <td class="product-thumbnail"><img src="/img/products/{{ $product->image }}" alt="{{ $product->image }}"></td>
                <td class="hidden-xs">{{ $product->number }}</td>
                <td><a href="/product/{{ $product->number }}">{{ $product->name }}</a></td>
                @if(Auth::check())
                    <td>&euro;{{ number_format($price, 2, ".", "") }}</td>
                @endif
            </tr>
        @endforeach
        </tbody>
    </table>

    <div class="text-center">
        {!! $results->render() !!}
    </div>
@endsection

@section('extraJS')
    <script type="text/javascript">
        var wtg = {
            quickSearch: function () {
                document.advancedsearch.submit();
            }
        }
    </script>
@endsection
