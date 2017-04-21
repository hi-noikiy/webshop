@extends('layouts.main', ['pagetitle' => 'Home / Downloads'])

@section('title')
    <h3>Downloads</h3>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Catalogus</h3>
                </div>
                <div class="panel-body">
                    <p>
                        {!! \WTG\Block\Models\Block::getByTag('downloads.catalog')->getContent() !!}
                    </p>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Flyers</h3>
                </div>
                <div class="panel-body">
                    <p>
                        {!! \WTG\Block\Models\Block::getByTag('downloads.flyers')->getContent() !!}
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Artikel bestanden</h3>
                </div>
                <div class="panel-body">
                    <p>
                        {!! \WTG\Block\Models\Block::getByTag('downloads.files')->getContent() !!}
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
