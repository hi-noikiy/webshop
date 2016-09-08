@if ($errors->has())
    <div class="alert notification alert-danger">
        @foreach ($errors->all() as $error)
            {{ $error }}<br />
        @endforeach
    </div>
@endif

@if (Session::has('status')))
    <div class="alert notification alert-success">
        {{ Session::get('status')}}<br />
    </div>
@endif