@extends('master', ['pagetitle' => 'Admin / Actiepaketten / Overzicht'])

@section('title')
    <h3>Admin <small>actiepaketten overzicht</small></h3>
@stop

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
                            <label for="image" class="col-sm-2 control-label">Afbeelding</label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <span class="input-group-btn">
                                            <span class="btn btn-primary btn-file">
                                                    Bladeren&hellip; <input type="file" name="image">
                                            </span>
                                    </span>
                                    <input type="text" class="form-control" readonly id="fileName">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="title" class="col-sm-2 control-label">Naam*</label>
                            <div class="col-sm-10">
                                <input class="form-control" placeholder="Naam" type="text" name="name" maxlength="100" required>
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
                            {{ strlen($pack->name) > 50 ? substr($pack->name, 0 , 47) . "..." : $pack->name }}
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

        .btn-file {
            position: relative;
            overflow: hidden;
        }
        .btn-file input[type=file] {
            position: absolute;
            top: 0;
            right: 0;
            min-width: 100%;
            min-height: 100%;
            font-size: 100px;
            text-align: right;
            filter: alpha(opacity=0);
            opacity: 0;
            background: red;
            cursor: inherit;
            display: block;
        }
        input[readonly] {
            background-color: white !important;
            cursor: default !important;
        }
    </style>
@endsection

@section('extraJS')
    <script type="text/javascript">
        $(document).on('change', '.btn-file :file', function() {
            var input = $(this),
                    numFiles = input.get(0).files ? input.get(0).files.length : 1,
                    label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
            input.trigger('fileselect', [numFiles, label]);
        });

        $(document).ready( function() {
            $('.btn-file :file').on('fileselect', function(event, numFiles, label) {

                var input = $(this).parents('.input-group').find(':text'),
                        log = numFiles > 1 ? numFiles + ' files selected' : label;

                if( input.length ) {
                    input.val(log);
                } else {
                    if( log ) alert(log);
                }

            });
        });

        /**
         * Show a confirmation modal to make sure if the user wants to delete the product pack
         *
         * @param sender
         */
        function showConfirmationModal(sender) {
            $('#packName').html($(sender).attr('data-name'));
            $('#packId').val($(sender).attr('data-id'));

            $('#removePackModal').modal('show');
        }
    </script>
@stop