<script type="text/javascript">
    var $deleteUserForm = $('#deleteUserForm');
    var $addUserForm = $('#addUserForm');
    var $companyId = $addUserForm.find('#inputCompanyId');
    var $companyName = $addUserForm.find('#inputCompanyName');
    var $address = $addUserForm.find('#inputAddress');
    var $postcode = $addUserForm.find('#inputPostcode');
    var $city = $addUserForm.find('#inputCity');
    var $username = $addUserForm.find('#inputUsername');
    var $email = $addUserForm.find('#inputEmail');
    var $active = $addUserForm.find('#inputActive');
    var $deleteButton = $addUserForm.find('#deleteCompanyButton');

    $companyId.on('input', function() {
        var value = $companyId.val();

        $username.val(value);
        $deleteUserForm.find('#company-id').html(value);
        $deleteUserForm.find('input[name="company_id"]').val(value);

        if (value == $companyId.val()) {
            $.ajax({
                url: "{{ route('admin.user::get') }}",
                type: "GET",
                dataType: "json",
                data: {id: value},
                success: function(data) {
                    var data = data.payload;

                    if (value == $companyId.val() && data != null) {
                        $companyName.val(data.company);
                        $address.val(data.street);
                        $postcode.val(data.postcode);
                        $city.val(data.city);
                        $email.val(data.main_user.email);
                        $active.val(data.active);

                        $deleteButton.removeAttr('disabled');
                    }
                },
                error: function() {
                    if (value == $companyId.val()) {
                        $companyName.val('');
                        $address.val('');
                        $postcode.val('');
                        $city.val('');
                        $email.val('');
                        $active.val(0);

                        $deleteButton.attr('disabled', 'disabled');
                    }
                }
            });
        }
    });
</script>