<div class="modal fade" id="addAddressDialog" tabindex="-1" role="dialog" aria-labelledby="addAddress" aria-hidden="true">
    <form class="form-horizontal" action="{{ route('customer::account.addresses::add') }}" method="POST" role="form">
        {{ csrf_field() }}

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