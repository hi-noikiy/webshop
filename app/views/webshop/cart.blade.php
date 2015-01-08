@extends('master')

@section('title')
        <h3>Winkelwagen</h3>
@stop

@section('content')
        <?php $total = 0; ?>
        @if(Cart::count() > 0)
                <table class="table table-striped">
                        <thead>
                                <th>Product nummer</th>
                                <th>Naam</th>
                                <th>Bruto prijs</th>
                                <th>Korting</th>
                                <th>Netto prijs</th>
                                <th>Aantal</th>
                                <th>Netto subtotaal</th>
                                <th>Update/Verwijder</th>
                        </thead>
                        <tbody>
                                @foreach ($cart as $item)

                                        <?php
                                                $rowid 		= $item->rowid;
                                                $artNr 		= $item->id;
                                                $qty 		= $item->qty;
                                                $name 		= $item->name;
                                                $korting 	= $item->options->korting;
                                                $brutoPrice     = number_format($item->price, 2, ".", "");

                                        if ($korting === 'Actie') {
                                                $nettoPrice 	= number_format($item->price, 2, ".", "");
                                        } else {
                                                $nettoPrice 	= number_format($brutoPrice * ((100-$korting) / 100), 2, ".", "");
                                        }

                                                $total 		+= (double) ($nettoPrice * $qty);
                                        ?>

                                        <form action="/cart/update" method="POST">
                                                <input type="text" class="hidden" name="rowId" value="{{ $rowid }}">
                                                <tr {{ ($korting === "Actie" ? "class='success'" : "") }}>
                                                        <td>{{ $artNr }}</td>
                                                        <td><a href="/product/{{ $artNr }}">{{ $name }}</a></td>
                                                        <td><span class="glyphicon glyphicon-euro"></span> {{ $brutoPrice }}</td>
                                                        <td>{{ ($korting === "Actie" ? $korting : $korting . "%") }}</td>
                                                        <td><span class="glyphicon glyphicon-euro"></span> {{ $nettoPrice }}</td>
                                                        <td class="col-sm-1"><input class="form-control" name="qty" value="{{ $qty }}"></td>
                                                        <td><span class="glyphicon glyphicon-euro"></span> {{ number_format($nettoPrice * $qty, 2, ".", "") }}</td>
                                                        <td>
                                                                <div class="btn-group">
                                                                        <button type="submit" name="edit" class="btn btn-primary"><span class="glyphicon glyphicon-pencil"></span></button>
                                                                        <button type="submit" name="remove" class="btn btn-danger"><span class="glyphicon glyphicon-remove"></span></button>
                                                                </div>
                                                        </td>
                                                </tr>
                                        </form>
                                @endforeach

                                <hr />

                                <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td><b>Netto totaal:</b></td>
                                        <td><span class="glyphicon glyphicon-euro"></span> {{ number_format($total, 2, ".", "") }}</td>
                                        <td></td>
                                </tr>
                        </tbody>
                </table>

                <p>* Actieproducten zijn altijd netto en worden aangeduid met een groene regel.</p>

                <div class="btn-group pull-right">
                        <a class="btn btn-primary" href="{{ (Session::has('continueShopping') ? Session::get('continueShopping') : '/webshop') }}">Verder winkelen</a>
                        <button data-target="#confirmDialog" data-toggle="modal" class="btn btn-success">Bestelling afronden <span class="glyphicon glyphicon-arrow-right"></span></button>
                </div>

                <a class="btn btn-danger" href="/cart/destroy">Winkelwagen legen</a>

                <form action="/mail/order" method="POST">
                        <input type="text" class="hidden" value="true" name="sent">
                        <div class="modal fade" id="confirmDialog" tabindex="-1" role="dialog" aria-labelledby="finishOrder" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                                <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                        <h4 class="modal-title">Bestelling afronden</h4>
                                                </div>
                                                <div class="modal-body">
                                                        <p>
                                                                Aantal producten: {{ Cart::count() }}<Br />
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
                                                                <div class="col-lg-6">
                                                                        <button type="button" class="btn btn-danger btn-block" data-dismiss="modal">Annuleren</button>
                                                                </div>
                                                                <div class="col-lg-6">
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
                                <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                                <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                        <h4 class="modal-title">Adres toevoegen</h4>
                                                </div>
                                                <div class="modal-body">
                                                        <div class="form-group">
                                                                <label for="inputName" class="col-sm-2 control-label">Naam*</label>
                                                                <div class="col-sm-10">
                                                                        <input type="text" name="name" class="form-control" id="inputName" placeholder="Naam" maxlength="100" required>
                                                                </div>
                                                        </div>
                                                        <div class="form-group">
                                                                <label for="inputStraat" class="col-sm-2 control-label">Straat + Huisnr*</label>
                                                                <div class="col-sm-10">
                                                                        <input type="text" name="street" class="form-control" id="inputStraat" placeholder="Straat + Huisnr" maxlength="50" required>
                                                                </div>
                                                        </div>
                                                        <div class="form-group">
                                                                <label for="inputPostcode" class="col-sm-2 control-label">Postcode*</label>
                                                                <div class="col-sm-10">
                                                                        <input type="text" name="postcode" class="form-control" id="inputPostcode" placeholder="Postcode (XXXX YY)" maxlength="7" required>
                                                                </div>
                                                        </div>
                                                        <div class="form-group">
                                                                <label for="inputStraat" class="col-sm-2 control-label">Plaats*</label>
                                                                <div class="col-sm-10">
                                                                        <input type="text" name="city" class="form-control" id="inputPlaats" placeholder="Plaats" maxlength="30" required>
                                                                </div>
                                                        </div>
                                                        <div class="form-group">
                                                                <label for="inputStraat" class="col-sm-2 control-label">Telefoon</label>
                                                                <div class="col-sm-10">
                                                                        <input type="text" name="telephone" class="form-control" id="inputTelefoon" placeholder="Telefoon" maxlength="15">
                                                                </div>
                                                        </div>
                                                        <div class="form-group">
                                                                <label for="inputStraat" class="col-sm-2 control-label">Mobiel</label>
                                                                <div class="col-sm-10">
                                                                        <input type="text" name="mobile" class="form-control" id="inputMobile" placeholder="Mobiel" maxlength="15">
                                                                </div>
                                                        </div>
                                                </div>
                                                <div class="modal-footer">
                                                        <div class="row">
                                                                <div class="col-sm-6">
                                                                        <button type="button" class="btn btn-danger btn-block" data-dismiss="modal">Annuleren</button>
                                                                </div>
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

@section('extraJS')
        <script type="text/javascript">
                $('#addressId').change(function() {
                        if ($(this).val() === "-2" || $(this).val() >= 0) {
                                $("#finishOrderButton").removeAttr("disabled");
                        } else if ($(this).val() === "-3") {
                                $("#confirmDialog").modal('hide');
                                $("#addAddressDialog").modal('show');
                        } else {
                                $("#finishOrderButton").attr("disabled", "disabled");
                        }
                });
        </script>
@stop