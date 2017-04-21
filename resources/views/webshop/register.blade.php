@extends('layouts.main', ['pagetitle' => 'Webshop / Registratie'])

@section('title')
    <h3>Registratie aanvragen</h3>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-offset-2 col-md-8">
            <form action="/register" method="POST" role="form">
                {!! csrf_field() !!}

                <p class="help-block">Velden gemarkeerd met een * zijn verplicht</p>

                <h3>Correspondentie gegevens</h3>

                <div class="form-group">
                    <label for="corContactName">Naam contactpersoon *</label>
                    <input type="text" class="form-control" placeholder="Naam contactpersoon" name="corContactName" length="100" value='{{ old('corContactName') }}' required>
                </div>

                <div class="form-group">
                    <label for="corName">Naam bedrijf *</label>
                    <input type="text" class="form-control" placeholder="Naam bedrijf" name="corName" length="100" value='{{ old('corName') }}' required>
                </div>

                <div class="form-group">
                    <label for="corAddress">Adres & Huisnr *</label>
                    <input id="corAddress" type="text" class="form-control" placeholder="Adres & Huisnr" name="corAddress" length="30" value='{{ old('corAddress') }}' required>
                </div>

                <div class="form-group">
                    <label for="corPostcode">Postcode *</label>
                    <input id="corPostcode" type="text" class="form-control" placeholder="Postcode" name="corPostcode" length="7" value='{{ old('corPostcode') }}' required>
                </div>

                <div class="form-group">
                    <label for="corCity">Plaats *</label>
                    <input id="corCity" type="text" class="form-control" placeholder="Plaats" name="corCity" length="50" value='{{ old('corCity') }}' required>
                </div>

                <div class="form-group">
                    <label for="corContactPhone">Telefoon contactpersoon</label>
                    <input type="text" class="form-control" placeholder="Telefoon contactpersoon" name="corContactPhone" length="15" value='{{ old('corContactPhone') }}'>
                </div>

                <div class="form-group">
                    <label for="corPhone">Telefoon bedrijf *</label>
                    <input id="corPhone" type="text" class="form-control" placeholder="Telefoon bedrijf" name="corPhone" length="15" value='{{ old('corPhone') }}' required>
                </div>

                <div class="form-group">
                    <label for="corFax">Fax</label>
                    <input id="corFax" type="text" class="form-control" placeholder="Fax" name="corFax" value='{{ old('corFax') }}' length="15">
                </div>

                <div class="form-group">
                    <label for="corEmail">E-mail adres *</label>
                    <input type="email" class="form-control" placeholder="E-mail adres" name="corEmail" length="50" value='{{ old('corEmail') }}' required>
                </div>

                <div class="form-group">
                    <label for="corSite">Website</label>
                    <div class="input-group">
                        <span class="input-group-addon" id="url-prefix">http://</span>
                        <input type="text" class="form-control" placeholder="Website" name="corSite" length="50" value='{{ old('corSite') }}' aria-describedby="url-prefix">
                    </div>
                </div>

                <br />

                <h3>Vestigingsadres</h3>

                <div class="form-group">
                    <label>
                        <input id="corIsDel" type="checkbox" name="corIsDel"> Neem correspondentie gegevens over
                    </label>
                </div>

                <div class="form-group">
                    <label for="delAddress">Adres & Huisnr *</label>
                    <input id="delAddress" type="text" class="form-control" placeholder="Adres & Huisnr" name="delAddress" length="30" value='{{ old('delAddress') }}' required>
                </div>

                <div class="form-group">
                    <label for="delPostcode">Postcode *</label>
                    <input id="delPostcode" type="text" class="form-control" placeholder="Postcode" name="delPostcode" length="7" value='{{ old('delPostcode') }}' required>
                </div>

                <div class="form-group">
                    <label for="delCity">Plaats *</label>
                    <input id="delCity" type="text" class="form-control" placeholder="Plaats" name="delCity" length="50" value='{{ old('delCity') }}' required>
                </div>

                <div class="form-group">
                    <label for="delPhone">Telefoon bedrijf *</label>
                    <input id="delPhone" type="text" class="form-control" placeholder="Telefoon bedrijf" name="delPhone" length="15" value='{{ old('delPhone') }}' required>
                </div>

                <div class="form-group">
                    <label for="delFax">Fax</label>
                    <input id="delFax" type="text" class="form-control" placeholder="Fax" name="delFax" length="15" value='{{ old('delFax') }}'>
                </div>

                <br />

                <h3>Betalingsgegevens</h3>

                <div class="form-group">
                    <label for="betIBAN">IBAN nummer *</label>
                    <input type="text" class="form-control" placeholder="IBAN nummer" name="betIBAN" length="40" value='{{ old('betIBAN') }}' required>
                </div>

                <div class="form-group">
                    <label for="betKvK">KvK nummer *</label>
                    <input type="text" class="form-control" placeholder="KvK nummer" name="betKvK" length="30" value='{{ old('betKvK') }}' required>
                </div>

                <div class="form-group">
                    <label for="betBTW">BTW nummer *</label>
                    <input type="text" class="form-control" placeholder="BTW nummer" name="betBTW" length="30" value='{{ old('betBTW') }}' required>
                </div>

                <br />

                <h3>Overige gegevens</h3>

                <div class="form-group">
                    <label for="digFactuur">Vanaf 2015 factureren wij digitaal. <Br />Indien u dit naar een afwijkend mailadres wilt sturen kunt u dat hier invullen.</label>
                    <input id="digFactuur" placeholder="Alternative email" class="form-control" name="digFactuur" value='{{ old('digFactuur') }}'>
                </div>

                <div class="form-group">
                    <label>
                        <input type="checkbox" name="digOrder"> Digitale orderbevestiging ontvangen
                    </label>
                </div>

                <div class="form-group">
                    <label>
                        <input type="checkbox" name="digArtikel"> Mail ontvangen bij nieuw artikelbestand
                    </label>
                </div>

                <br />

                <button type="submit" class="btn btn-primary">Versturen</button>
            </form>
        </div>
    </div>
@endsection

@section('extraJS')
    <script type="text/javascript">
        $("#corIsDel").change(function() {
            if ($(this).is(":checked")) {
                $('#delAddress').val($('#corAddress').val()).attr('readonly', 'readonly');
                $('#delPostcode').val($('#corPostcode').val()).attr('readonly', 'readonly');
                $('#delCity').val($('#corCity').val()).attr('readonly', 'readonly');
                $('#delPhone').val($('#corPhone').val()).attr('readonly', 'readonly');
                $('#delFax').val($('#corFax').val()).attr('readonly', 'readonly');
            } else {
                $('#delAddress').val("").removeAttr('readonly');
                $('#delPostcode').val("").removeAttr('readonly');
                $('#delCity').val("").removeAttr('readonly');
                $('#delPhone').val("").removeAttr('readonly');
                $('#delFax').val("").removeAttr('readonly');
            }
        });
    </script>
@endsection
