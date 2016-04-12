@extends('master', ['pagetitle' => 'Admin / Actiepaket / Aanpassen'])

@section('title')
    <h3>Admin <small>actiepaket aanpassen</small></h3>
@stop

@section('content')

    @include('admin.nav')

    <form action="{{ url('admin/packs/addProduct') }}" method="POST" enctype="multipart/form-data" class="form form-horizontal">
        <div class="modal fade" id="addProductModal" tabindex="-1" role="dialog" aria-labelledby="#addProductLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Sluiten</span></button>
                        <h4 class="modal-title" id="addProductLabel">Product aan actiepakket toevoegen</h4>
                    </div>
                    <div class="modal-body">
                        {!! csrf_field() !!}

                        <input type="hidden" value="{{ $pack->id }}" name="pack">

                        <div class="form-group">
                            <label for="title" class="col-sm-4 control-label">Product nummer*</label>
                            <div class="col-sm-8">
                                <input class="form-control" onkeyup="getProductName(this)" placeholder="Product nummer" type="text" name="product" maxlength="8" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="title" class="col-sm-4 control-label">Aantal*</label>
                            <div class="col-sm-8">
                                <input class="form-control" placeholder="Aantal" type="text" name="amount" maxlength="100" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="title" class="col-sm-4 control-label">Naam</label>
                            <div class="col-sm-8">
                                <input class="form-control" type="text" id="name" disabled>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Sluiten</button>
                        <button type="submit" class="btn btn-primary">Toevoegen</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <form action="{{ url('admin/packs/removeProduct') }}" method="POST" enctype="multipart/form-data" class="form form-horizontal">
        <div class="modal fade" id="removeProductModal" tabindex="-1" role="dialog" aria-labelledby="#removeProductLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Sluiten</span></button>
                        <h4 class="modal-title" id="removeProductLabel">Product '<span id="productNumber"></span>' verwijderen</h4>
                    </div>
                    <div class="modal-body">
                        {!! csrf_field() !!}
                        <input name="pack" value="{{ $pack->id }}" type="hidden">
                        <input name="product" id="productId" type="hidden">

                        <p>
                            Weet u zeker dat u dit product wilt verwijderen?
                        </p>

                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-danger">Verwijderen</button>
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Sluiten</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <div class="text-center">
        <h3>{{ $pack->product->name }}</h3>
    </div>

    <hr />

    <a data-toggle="modal" data-target="#addProductModal" class="btn btn-link btn-block btn-lg"><i class="fa fa-plus"></i> Product toevoegen</a>

    <hr />

    <table class="table table-striped">
        <thead>
        <tr>
            <th></th>
            <th>Product</th>
            <th>Naam</th>
            <th>Aantal</th>
            <th class="text-center">Verwijderen</th>
        </tr>
        </thead>

        <tbody>

        @foreach($pack->products as $prod)
            <tr>
                <td class="product-thumbnail"><img src="/img/products/{{ $prod->details->image }}"></td>
                <td>{{ $prod->details->number }}</td>
                <td><a href="/product/{{ $prod->details->number }}">{{ $prod->details->name }}</a></td>
                <td>{{ $prod->amount }}</td>
                <td class="no-padding"><button onclick="showConfirmationModal(this)" data-product-id="{{ $prod->id }}" data-product-number="{{ $prod->details->number }}" class="btn btn-danger delete-button"><i class="fa fa-remove"></i></button></td>
            </tr>
        @endforeach

        </tbody>
    </table>

    <hr />

    <a href="{{ url('admin/packs') }}" class="btn btn-success btn-block btn-lg">Terug naar overzicht</a>

@endsection

@section('extraJS')
    <script>
        function getProductName(sender) {
            var val     = $(sender).val();
            var target  = $("#name");

            setTimeout(function() {
                if ($(sender).val() == val && val.length >= 7) {
                    $.ajax({
                        url: '/admin/api/product/' + val,
                        dataType: 'json',
                        success: function (data) {
                            if ($(sender).val() == val) {
                                $(target).val(data.payload.name);
                            }
                        }
                    });
                }
            }, 100);
        }

        function showConfirmationModal(sender) {
            var productNumber   = $(sender).attr('data-product-number');
            var productId       = $(sender).attr('data-product-id');

            $('#productNumber').html(productNumber);
            $('#productId').val(productId);

            $('#removeProductModal').modal('show');
        }
    </script>
@endsection

@section('extraCSS')
    <style>
        .no-padding {
            padding: 0 !important;
            position: relative;
        }

        .delete-button {
            position: absolute;
            border-radius: 0 !important;
            width: 100%;
            bottom: 0;
            right: 0;
            left: 0;
            top: 0;
        }
    </style>
@endsection