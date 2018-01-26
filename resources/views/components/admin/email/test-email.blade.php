<h3><i class="fal fa-fw fa-envelope"></i> Verstuur test email</h3>

<hr />

<form action="{{ route('admin.email::test') }}" method="post">
    {{ csrf_field() }}

    <div class="form-group">
        <label>E-Mail</label>
        <input class="form-control" type="email" name="email" placeholder="E-Mail adres" required />
    </div>

    <button class="btn btn-success" type="submit"><i class="fal fa-fw fa-send"></i> Verstuur test mail</button>
</form>