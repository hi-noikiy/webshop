@if (count($errors) > 0 || request()->input('testnotify') === 'error')
    <div class="alert notification alert-danger">
        @foreach ($errors->all() as $error)
            {{ $error }}<br />
        @endforeach
    </div>
@endif

@if (Session::has('status') || request()->input('testnotify') === 'success')
    <div class="alert notification alert-success">
        {{ Session::get('status') }}<br />
    </div>
@endif