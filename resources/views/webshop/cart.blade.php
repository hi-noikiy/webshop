@extends('master', ['pagetitle' => 'Webshop / Winkelwagen'])

@section('title')
        <h3>Winkelwagen</h3>
@stop

@section('content')
        <?php $total = 0; ?>
        @if(Cart::count() > 0)
                <!-- Mobile friendly display -->
                <div class="row">
                        @foreach ($cart as $item)

                                <?php
                                        $rowid          = $item->rowid;
                                        $artNr          = $item->id;
                                        $qty            = $item->qty;
                                        $name           = (strlen($item->name) > 50 ? substr($item->name, 0, 47) . "..." : $item->name);
                                        $korting        = $item->options->korting;
                                        $brutoPrice     = number_format($item->price, 2, ".", "");

                                        if ($korting === 'Actie')
                                                $nettoPrice     = number_format($item->price, 2, ".", "");
                                        else
                                                $nettoPrice     = number_format($brutoPrice * ((100-$korting) / 100), 2, ".", "");

                                        $total          += (double) ($nettoPrice * $qty);
                                ?>

                                <form action="/cart/update" method="POST" class="col-sm-4">
                                        {!! csrf_field() !!}
                                        <input type="text" class="hidden" name="rowId" value="{{ $rowid }}">

                                        <div class="panel panel-primary">
                                                <div class="panel-heading">
                                                        <div class="pull-right">
                                                                <button type="submit" name="remove" class="btn btn-danger"><span class="glyphicon glyphicon-remove"></span></button>
                                                        </div>
                                                        <a href="/product/{{ $artNr }}" class="panel-heading-link">{{ $name }}</a>
                                                </div>
                                                <table class="table">
                                                        <tbody>
                                                                <tr>
                                                                        <td><b>Product nummer</b></td>
                                                                        <td>{{ $artNr }}</td>
                                                                </tr>
                                                                <tr>
                                                                        <td><b>Aantal</b></td>
                                                                        <td class="col-xs-6">
                                                                                <div class="input-group">
                                                                                        <input type="text" class="form-control" placeholder="{{ $qty }}" name="qty" value="{{ $qty }}">
                                                                                        <span class="input-group-btn">
                                                                                                <button type="submit" class="btn btn-primary" name="edit"><span class="glyphicon glyphicon-pencil"></span></button>
                                                                                        </span>
                                                                                </div>
                                                                        </td>
                                                                </tr>
                                                                <tr>
                                                                        <td><b>Netto subtotaal</b></td>
                                                                        <td><span class="glyphicon glyphicon-euro"></span> {{ number_format($nettoPrice * $qty, 2, ".", "") }}</td>
                                                                </tr>
                                                        </tbody>
                                                </table>
                                        </div>
                                </form>
                        @endforeach
                </div>

                <hr />

                <div class="row">
                        <div class="col-sm-4">
                                <a class="btn btn-danger btn-block btn-lg" href="/cart/destroy">Winkelwagen legen</a>
                        </div>

                        <br class="visible-xs" />

                        <div class="col-sm-4">
                                <a class="btn btn-primary btn-block btn-lg" href="{{ (Session::has('continueShopping') ? Session::get('continueShopping') : '/webshop') }}">Verder winkelen</a>
                        </div>

                        <br class="visible-xs" />

                        <div class="col-sm-4">
                                <button data-target="#confirmDialog" data-toggle="modal" class="btn btn-success btn-block btn-lg">Bestelling afronden <span class="glyphicon glyphicon-arrow-right"></span></button>
                        </div>
                </div>

                <div class="modal fade" id="confirmDialog" tabindex="-1" role="dialog" aria-labelledby="finishOrder" aria-hidden="true">
                        <form action="/cart/order" method="POST">
                                {!! csrf_field() !!}
                                <input type="text" class="hidden" value="true" name="sent">

                                <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                                <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                        <h4 class="modal-title">Bestelling afronden</h4>
                                                </div>
                                                <div class="modal-body">
                                                        <p>
                                                                Aantal unieke producten: {{ Cart::count(false) }}<br />
                                                                Aantal losse producten: {{ Cart::count() }}<br />
                                                                Netto totaal: </b><span class="glyphicon glyphicon-euro"></span> {{ number_format($total, 2) }}<br />
                                                        </p>

                                                        <hr />

                                                        <select id="addressId" name="addressId" class="form-control">
                                                                <option value="-1">Selecteer een adres</option>
                                                                <option value="-3">Adres toevoegen</option>
                                                                <option value="-2">Wordt gehaald</option>
                                                                @foreach ($addresses as $address)
                                                                        <option value="{{ $address->id }}">{{ $address->name }}, {{ $address->street }}, {{ $address->postcode }}, {{ $address->city }}</option>
                                                                @endforeach
                                                        </select>

                                                        <br />

                                                        <div class="form-group">
                                                                <label for="comment">Opmerking</label>
                                                                <textarea name="comment" class="form-control" rows="5" id="comment" placeholer="Opmerking"></textarea>
                                                        </div>

                                                </div>
                                                <div class="modal-footer">
                                                        <div class="row">
                                                                <div class="col-xs-6">
                                                                        <button type="button" class="btn btn-danger btn-block" data-dismiss="modal">Annuleren</button>
                                                                </div>

                                                                <div class="col-xs-6">
                                                                        <button class="btn btn-success btn-block" type="submit" id="finishOrderButton" disabled="disabled">Bestellen</button>
                                                                </div>
                                                        </div>
                                                </div>
                                        </div><!-- /.modal-content -->
                                </div><!-- /.modal-dialog -->
                        </div><!-- /.modal -->
                </form>

                <div class="modal fade" id="addAddressDialog" tabindex="-2" role="dialog" aria-labelledby="addAddress" aria-hidden="true">
                        <form class="form-horizontal" action="/account/addAddress" method="POST" role="form">
                                {!! csrf_field() !!}
                                <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                                <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                        <h4 class="modal-title">Adres toevoegen</h4>
                                                </div>
                                                <div class="modal-body">
                                                        <div class="form-group">
                                                                <label for="inputName" class="col-sm-3 hidden-xs control-label">Naam*</label>
                                                                <div class="col-xs-12 col-sm-9">
                                                                        <input type="text" name="name" class="form-control" id="inputName" placeholder="Naam" maxlength="100" required>
                                                                </div>
                                                        </div>
                                                        <div class="form-group">
                                                                <label for="inputStraat" class="col-sm-3 hidden-xs control-label">Straat + Huisnr*</label>
                                                                <div class="col-xs-12 col-sm-9">
                                                                        <input type="text" name="street" class="form-control" id="inputStraat" placeholder="Straat + Huisnr" maxlength="50" required>
                                                                </div>
                                                        </div>
                                                        <div class="form-group">
                                                                <label for="inputPostcode" class="col-sm-3 hidden-xs control-label">Postcode*</label>
                                                                <div class="col-xs-12 col-sm-9">
                                                                        <input type="text" name="postcode" class="form-control" id="inputPostcode" placeholder="Postcode (XXXX YY)" maxlength="7" required>
                                                                </div>
                                                        </div>
                                                        <div class="form-group">
                                                                <label for="inputStraat" class="col-sm-3 hidden-xs control-label">Plaats*</label>
                                                                <div class="col-xs-12 col-sm-9">
                                                                        <input type="text" name="city" class="form-control" id="inputPlaats" placeholder="Plaats" maxlength="30" required>
                                                                </div>
                                                        </div>
                                                        <div class="form-group">
                                                                <label for="inputStraat" class="col-sm-3 hidden-xs control-label">Telefoon</label>
                                                                <div class="col-xs-12 col-sm-9">
                                                                        <input type="text" name="telephone" class="form-control" id="inputTelefoon" placeholder="Telefoon" maxlength="15">
                                                                </div>
                                                        </div>
                                                        <div class="form-group">
                                                                <label for="inputStraat" class="col-sm-3 hidden-xs control-label">Mobiel</label>
                                                                <div class="col-xs-12 col-sm-9">
                                                                        <input type="text" name="mobile" class="form-control" id="inputMobile" placeholder="Mobiel" maxlength="15">
                                                                </div>
                                                        </div>
                                                </div>
                                                <div class="modal-footer">
                                                        <div class="row">
                                                                <div class="col-sm-6">
                                                                        <button type="button" class="btn btn-danger btn-block" data-dismiss="modal">Annuleren</button>
                                                                </div>

                                                                <br class="visible-xs" />

                                                                <div class="col-sm-6">
                                                                        <button type="submit" class="btn btn-success btn-block">Toevoegen</button>
                                                                </div>
                                                        </div>
                                                </div>
                                        </div>
                                </div>
                        </form>
                </div>
        @else
                <div class="alert alert-warning">Uw winkelwagen is nog leeg.</div>
        @endif
@stop

@section('extraCSS')
        <style type="text/css">
                .panel-heading-link,
                .panel-heading-link:hover {
                        color: white;
                }

                .panel-heading {
                        min-height: 65px !important;
                }
        </style>
@stop

@section('extraJS')
        <script type="text/javascript">
                $("#confirmDialog").on('hidden.bs.modal', function(e) {
                        if ($('#addressId').val() === "-3") {
                                $('#addressId').val('-1');
                                $("#addAddressDialog").modal('show');
                        }
                })

                $('#addressId').change(function() {
                        if ($('#addressId').val() === "-2" || $('#addressId').val() >= 0) {
                                $("#finishOrderButton").removeAttr("disabled");
                        } else if ($('#addressId').val() === "-3") {
                                $("#confirmDialog").modal('hide');
                        } else {
                                $("#finishOrderButton").attr("disabled", "disabled");
                        }
                });
        </script>
@stop
