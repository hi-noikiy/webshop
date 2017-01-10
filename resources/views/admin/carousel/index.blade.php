@extends('admin.master')

@section('document_start')
    <div class="modal fade" id="addSlide" tabindex="-1" role="dialog" aria-labelledby="addSlideLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Sluiten</span></button>
                    <h4 class="modal-title" id="addSlideLabel">Slide toevoegen</h4>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.carousel::create') }}" method="POST" enctype="multipart/form-data" class="form form-horizontal">
                        {{ csrf_field() }}

                        <div class="form-group">
                            <label for="image" class="col-sm-2 control-label">Afbeelding</label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <span class="input-group-btn">
                                        <span class="btn btn-primary btn-file">
                                            Bladeren&hellip; <input type="file" name="image" required>
                                        </span>
                                    </span>
                                    <input type="text" class="form-control" readonly id="fileName">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="title" class="col-sm-2 control-label">Titel</label>
                            <div class="col-sm-10">
                                <input value="{{ old('title') }}" class="form-control" placeholder="Titel" type="text" name="title" maxlength="100" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="caption" class="col-sm-2 control-label">Omschrijving</label>
                            <div class="col-sm-10">
                                <input value="{{ old('caption') }}" class="form-control" placeholder="Omschrijving" type="text" name="caption" maxlength="200" required>
                            </div>
                        </div>

                        <button type="button" class="btn btn-danger" data-dismiss="modal">Sluiten</button>
                        <button type="submit" class="btn btn-primary">Toevoegen</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-12 col-sm-8 col-sm-offset-2">
                <div class="card card-2">
                    <button class="btn btn-success btn-block" data-toggle="modal" data-target="#addSlide">
                        <i class="fa fa-plus"></i> Slide toevoegen aan de carousel
                    </button>
                </div>
            </div>
        </div>

        @include('admin.carousel.components.cards')
    </div>
@endsection