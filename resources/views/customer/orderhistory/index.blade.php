@extends('customer.master', ['pagetitle' => 'Account / Bestelgeschiedenis'])

@section('title')
    <h3>Account <small>bestelgeschiedenis</small></h3>
@endsection

@section('customer.content')
    @if($orders->count() === 0)
        <div class="alert alert-warning text-center">U hebt nog geen orders geplaatst.</div>
    @else
        <div class="panel-group" id="accordion">
            @foreach($orders as $order)
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#{{ $order->getId() }}">
                                {{ $order->created_at }}
                            </a>
                        </h4>
                    </div>
                    <div id="{{ $order->getId() }}" class="panel-collapse collapse">
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
                                    @foreach ($order->getItems() as $orderItem)
                                        <tr>
                                            <td>{{ $orderItem->getSku() }}</td>
                                            <td><a href="/product/{{ $orderItem->getSku() }}">{{ $orderItem->getName() }}</a></td>
                                            <td>{{ $orderItem->getQty() }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <a href="{{ route('customer::account.history::reorder', ['order' => $order->getId()]) }}"
                               class="btn btn-primary">Opnieuw bestellen</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="text-center">
            {!! $orders->render() !!}
        </div>
    @endif
@endsection
