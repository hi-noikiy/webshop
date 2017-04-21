@extends('layouts.main', ['pagetitle' => 'Het bedrijf'])

@section('title')
    <h3>Het bedrijf</h3>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <p class="well well-sm">
                Wiringa Technische Groothandel levert uitsluitend aan installatietechnische- en
                bouwtechnische bedrijven zoals installatie-, loodgieters- en montagebedrijven, aannemers
                en detaillisten.<br />
                Wij hebben <b>geen</b> particuliere verkoop.
            </p>

            <img src="{{ asset('img/site-bg.jpg') }}" class="img-responsive" />
        </div>
    </div>
@endsection
