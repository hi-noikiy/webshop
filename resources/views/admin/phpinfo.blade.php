@extends('master', ['pagetitle' => 'Admin / phpinfo'])

@section('title')
    <h3>Phpinfo</h3>
@endsection

@section('content')
    <?php phpinfo(); ?>
@endsection
