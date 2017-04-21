<div class="modal fade" id="addAccountDialog" tabindex="-1" role="dialog" aria-labelledby="addAccount" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form class="form-horizontal" method="POST" role="form">

                {{ csrf_field() }}

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Sub account toevoegen</h4>
                </div>

                <div class="modal-body">
                    <div class="form-group">
                        <label for="inputName" class="col-sm-3 hidden-xs control-label">Gebruikersnaam*</label>
                        <div class="col-xs-12 col-sm-9">
                            <input type="text" name="username" class="form-control" id="inputUsername" placeholder="Gebruikersnaam" maxlength="100" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="inputEmail" class="col-sm-3 hidden-xs control-label">Email*</label>
                        <div class="col-xs-12 col-sm-9">
                            <input type="email" name="email" class="form-control" id="inputEmail" placeholder="Email" maxlength="150" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="inputPassword" class="col-sm-3 hidden-xs control-label">Wachtwoord*</label>
                        <div class="col-xs-12 col-sm-9">
                            <input type="password" name="password" class="form-control" id="inputPassword" placeholder="Wachtwoord" maxlength="100" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="inputPasswordVerify" class="col-sm-3 hidden-xs control-label">Wachtwoord (verificatie)*</label>
                        <div class="col-xs-12 col-sm-9">
                            <input type="password" name="password_confirmation" class="form-control" id="inputPasswordVerify" placeholder="Wachtwoord (verificatie)" maxlength="100" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="checkbox">
                            <div class="col-sm-offset-3 col-sm-9">
                                <label>
                                    <input type="checkbox" name="manager" id="inputManager" value="1"> Maak dit account manager
                                </label>

                                <p class="help-block">Managers kunnen sub accounts toevoegen en verwijderen.</p>
                            </div>
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
            </form>
        </div>
    </div>
</div>