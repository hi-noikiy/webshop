@extends('master', ['pagetitle' => 'Admin / Actiepaketten / Overzicht'])

@section('title')
    <h3>Admin <small>actiepaketten overzicht</small></h3>
@endsection

@section('content')

    @include('admin.nav')

    <form action="{{ url('admin/packs/add') }}" method="POST" enctype="multipart/form-data" class="form form-horizontal">
        <div class="modal fade" id="addProductPack" tabindex="-1" role="dialog" aria-labelledby="#addProductPackLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Sluiten</span></button>
                        <h4 class="modal-title" id="addProductPackLabel">Actiepakket toevoegen</h4>
                    </div>
                    <div class="modal-body">
                        {!! csrf_field() !!}
                        <div class="form-group">
                            <label for="title" class="col-sm-4 control-label">Artikelnummer*</label>
                            <div class="col-sm-8">
                                <input onkeyup="getProductName(this)" class="form-control" placeholder="Artikelnummer" type="text" name="product" maxlength="100" required>
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

    <form action="{{ url('admin/packs/remove') }}" method="POST" enctype="multipart/form-data" class="form form-horizontal">
        <div class="modal fade" id="removePackModal" tabindex="-1" role="dialog" aria-labelledby="#removePackLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Sluiten</span></button>
                        <h4 class="modal-title" id="addProductPackLabel">Actiepakket '<span id="packName"></span>' verwijderen</h4>
                    </div>
                    <div class="modal-body">
                        {!! csrf_field() !!}
                        <input name="pack" id="packId" type="hidden">

                        <p>
                            Weet u zeker dat u dit actiepakket wilt verwijderen?
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

    <br />

    <div class="row">
        @if (count($packs))
            @foreach($packs as $pack)
                <div class="col-md-4">
                    <div class="actiepaket">
                        <div class="title">
			    @if ($pack->product === null)
                                Dit product ({{ $pack->product_number }}) bestaat niet meer
                            @else
                                {{ strlen($pack->product->name) > 40 ? substr($pack->product->name, 0 , 37) . "..." : $pack->product->name }}
                            @endif
                        </div>
                        <div class="product-list">
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Aantal</th>
                                </tr>
                                </thead>

                                <tbody>
                                    @foreach($pack->products as $product)
                                        <tr>
                                            <td>{{ $product->product }}</td>
                                            <td>{{ $product->amount }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="actiepaket-actions">
                            <a href="{{ url('admin/packs/edit/' . $pack->id) }}" class="btn btn-primary btn-block">Aanpassen</a>
                            <button onclick="showConfirmationModal(this)" data-id="{{ $pack->id }}" data-name="{{ $pack->name }}" class="btn btn-danger btn-block">Verwijderen</button>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif

        <div class="col-md-4">
            <div class="add-actiepaket">
                <a data-toggle="modal" data-target="#addProductPack" class="title">
                    <i class="fa fa-plus"></i> Actiepaket toevoegen
                </a>
            </div>
        </div>
    </div>
@endsection

@section('extraCSS')
    <style>
        .title {
            font-weight: 600;
        }

        .actiepaket,
        .add-actiepaket {
            border: 1px solid steelblue;
            border-radius: 5px;
        }

        .add-actiepaket {
            height: 383px;
            position: relative;
        }

        .actiepaket > .title {
            text-align: center;
            padding: 10px 0;
            border-bottom: 1px solid steelblue;
        }

        .add-actiepaket > .title {
            text-align: center;
            line-height: 25;
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            cursor: pointer;
        }

        .product-list {
            height: 225px;
            overflow-y: scroll;
        }

        .actiepaket-actions {
            padding: 20px 10px;
        }
    </style>
@endsection

@section('extraJS')
    <script type="text/javascript">
        /**
         * Get some product info
         *
         * @param sender
         * @return void
         */
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

        /**
         * Show a confirmation modal to make sure if the user wants to delete the product pack
         *
         * @param sender
         * @return void
         */
        function showConfirmationModal(sender) {
            $('#packName').html($(sender).attr('data-name'));
            $('#packId').val($(sender).attr('data-id'));

            $('#removePackModal').modal('show');
        }
    </script>
@endsection
