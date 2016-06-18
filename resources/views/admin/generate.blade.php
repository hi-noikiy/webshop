@extends('master', ['pagetitle' => 'Admin / Catalogus'])

@section('title')
        <h3>Admin <small>catalogus genereren</small></h3>
@endsection

@section('content')
        @include('admin.nav')

        <h3>Catalogus genereren</h3>

        <hr />

        <div class="row">
                <div class="col-md-12">
                        <form action="/admin/catalog" method="POST" class="form form-horizontal">
                                {!! csrf_field() !!}

                                <div class="form-group">
                                        <label for="footer" class="col-sm-2 control-label">Pagina footer</label>
                                        <div class="col-sm-10">
                                                <input class="form-control" placeholder="Pagina footer" type="text" value="{{ $currentFooter }}" name="footer" maxlength="300">
                                        </div>
                                </div>

                                <div class="form-group">
                                        <div class="col-sm-offset-2 col-sm-10">
                                                <button type="submit" class="btn btn-primary">Genereren</button>
                                        </div>
                                </div>
                        </form>

                        <hr />
                </div>
        </div>

        <hr />

        <h3>Prijslijst genereren</h3>

        <hr />

        <div class="row">
                <div class="col-md-12">
                        <form action="/admin/pricelist" method="POST" class="form form-horizontal" enctype="multipart/form-data">
                                {!! csrf_field() !!}

                                <div class="form-group">
                                        <label for="file" class="col-sm-2 control-label">Bestand debiteur</label>
                                        <div class="col-sm-10">
                                                <div class="input-group">
                                                        <span class="input-group-btn">
                                                                <span class="btn btn-primary btn-file">
                                                                        Bladeren&hellip; <input type="file" name="file" required>
                                                                </span>
                                                        </span>
                                                        <input type="text" class="form-control" readonly id="fileName">
                                                </div>
                                        </div>
                                </div>

                                <div class="form-group">
                                        <label for="user_id" class="col-sm-2 control-label">Debiteur nummer</label>
                                        <div class="col-sm-10">
                                                <input class="form-control" placeholder="Debiteur nummer" type="text" name="user_id" value="{{ old('user_id') }}" maxlength="10" required>
                                        </div>
                                </div>

                                <div class="form-group">
                                        <label for="position" class="col-sm-2 control-label">Positie productnummer</label>
                                        <div class="col-sm-10">
                                                <input class="form-control" placeholder="Positie productnummer" type="number" name="position" value="{{ old('position') }}" maxlength="1" required>
                                        </div>
                                </div>

                                <div class="form-group">
                                        <label for="position" class="col-sm-2 control-label">Separator</label>
                                        <div class="col-sm-10">
                                                <input class="form-control" placeholder="Separator" type="text" name="separator" value="{{ (old('separator') === null ? ';' : old('separator')) }}" maxlength="5" required>
                                        </div>
                                </div>

                                <div class="form-group">
                                        <label for="position" class="col-sm-2 control-label"># Regels overslaan</label>
                                        <div class="col-sm-10">
                                                <input class="form-control" placeholder="Separator" type="number" name="skip" value="{{ (old('skip') === null ? '0' : old('separator')) }}" maxlength="5">
                                        </div>
                                </div>

                                <div class="form-group">
                                        <div class="col-sm-offset-2 col-sm-10">
                                                <button type="submit" class="btn btn-primary">Downloaden</button>
                                        </div>
                                </div>
                        </form>

                        <hr />
                </div>
        </div>
@endsection

@section('extraCSS')
        <style type="text/css">
                .btn-file {
                        position: relative;
                        overflow: hidden;
                }
                .btn-file input[type=file] {
                        position: absolute;
                        top: 0;
                        right: 0;
                        min-width: 100%;
                        min-height: 100%;
                        font-size: 100px;
                        text-align: right;
                        filter: alpha(opacity=0);
                        opacity: 0;
                        background: red;
                        cursor: inherit;
                        display: block;
                }
                input[readonly] {
                        background-color: white !important;
                        cursor: default !important;
                }
        </style>
@endsection

@section('extraJS')
        <script type="text/javascript">
                $(document).on('change', '.btn-file :file', function() {
                        var input = $(this),
                                numFiles = input.get(0).files ? input.get(0).files.length : 1,
                                label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
                        input.trigger('fileselect', [numFiles, label]);
                });

                $(document).ready( function() {
                        $('.btn-file :file').on('fileselect', function(event, numFiles, label) {

                                var input = $(this).parents('.input-group').find(':text'),
                                        log = numFiles > 1 ? numFiles + ' files selected' : label;

                                if( input.length ) {
                                        input.val(log);
                                } else {
                                        if( log ) alert(log);
                                }

                        });
                });
        </script>
@endsection
