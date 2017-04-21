<form id="deleteUserForm" action="{{ route('admin.user::delete') }}" method="post">
    {{ csrf_field() }}
    <input type="hidden" name="company_id">

    <div class="modal fade" id="deleteUserModal" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Debiteur verwijderen</h4>
                </div>
                <div class="modal-body">
                    <p>U staat op het punt om debiteur <span id="company-id"></span> te verwijderen.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-danger">Verwijderen</button>
                </div>
            </div>
        </div>
    </div>
</form>