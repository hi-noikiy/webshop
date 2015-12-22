@extends('master', ['pagetitle' => 'Admin / phpinfo'])

@section('title')
    <h3>Phpinfo</h3>
@stop

@section('content')
    <?php phpinfo(); ?>
@stop
