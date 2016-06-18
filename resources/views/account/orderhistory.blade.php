@extends('master', ['pagetitle' => 'Account / Bestelgeschiedenis'])

@section('title')
        <h3>Account <small>bestelgeschiedenis</small></h3>
@endsection

@section('content')
        <div class="row">
                <div class="col-md-3">
                        @include('account.sidebar')
                </div>
                <div class="col-md-9">
                        @if(count($orderlist) === 0)
                                <div class="alert alert-warning text-center">U hebt nog geen orders geplaatst.</div>
                        @else
                                <div class="panel-group" id="accordion">
                                        @foreach($orderlist as $order)
                                                <?php $orderarray = unserialize($order->products); ?>
                                                <div class="panel panel-default">
                                                        <div class="panel-heading">
                                                                <h4 class="panel-title">
                                                                        <a data-toggle="collapse" data-parent="#accordion" href="#{{ $order->id }}">
                                                                                {{ $order->created_at }}
                                                                        </a>
                                                                </h4>
                                                        </div>
                                                        <div id="{{ $order->id }}" class="panel-collapse collapse">
                                                                <div class="panel-body">
                                                                        <table class="table table-striped">
                                                                                <thead>
                                                                                        <tr>
                                                                                                <th>Product nummer</th>
                                                                                                <th>Naam</th>
                                                                                                <th>Aantal</th>
                                                                                        </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                        @foreach ($orderarray as $product)
                                                                                                <tr>
                                                                                                        <td>{{ $product['id'] }}</td>
                                                                                                        <td><a href="/product/{{ $product['id'] }}">{{ $product['name'] }}</a></td>
                                                                                                        <td>{{ $product['qty'] }}</td>
                                                                                                </tr>
                                                                                        @endforeach
                                                                                </tbody>
                                                                        </table>
                                                                        <a href="/reorder/{{ $order->id }}" class="btn btn-primary">Opnieuw bestellen</a>
                                                                </div>
                                                        </div>
                                                </div>
                                        @endforeach
                                </div>

                                <div class="text-center">
                                    {!! $orderlist->render() !!}
                                </div>
                        @endif
                </div>
        </div>
@endsection
