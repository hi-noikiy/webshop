@extends('master', ['pagetitle' => 'Admin / Importeren'])

@section('title')
        <h3>Admin <small>bestanden importeren</small></h3>
@endsection

@section('content')
        @include('admin.nav')

        <div class="row">
                <div class="col-md-6">
                        <h3>Artikelbestand</h3>

                        <hr />

                        <form action="{{ url('admin/import/product') }}" method="POST" enctype="multipart/form-data" class="form-horizontal" id="productForm">
                                {!! csrf_field() !!}
                                <div class="form-group">
                                        <label for="productFile" class="col-sm-2 control-label">Bestand</label>
                                        <div class="col-sm-10">
                                                <div class="input-group">
                                                        <span class="input-group-btn">
                                                                <span class="btn btn-primary btn-file">
                                                                        Bladeren&hellip; <input type="file" name="productFile" accept=".csv">
                                                                </span>
                                                        </span>
                                                        <input type="text" class="form-control" readonly id="fileName">
                                                </div>
                                        </div>
                                </div>

                                <span class="help-block col-sm-offset-2">CSV, max. {{ ini_get('upload_max_filesize') }}</span>
                                <button type="submit" class="btn btn-success col-sm-offset-2">Artikelbestand uploaden</button>
                        </form>

                        <hr />

                        <h3>Afbeeldingen</h3>

                        <hr />

                        <form action="{{ url('admin/import/image') }}" method="POST" enctype="multipart/form-data" class="form-horizontal">
                                {!! csrf_field() !!}
                                <div class="form-group">
                                        <label for="imageFile" class="col-sm-2 control-label">Bestand</label>
                                        <div class="col-sm-10">
                                                <div class="input-group">
                                                        <span class="input-group-btn">
                                                                <span class="btn btn-primary btn-file">
                                                                        Bladeren&hellip; <input type="file" name="imageFile" accept=".zip,image/*">
                                                                </span>
                                                        </span>
                                                        <input type="text" class="form-control" readonly id="fileName">
                                                </div>
                                        </div>
                                </div>

                                <span class="help-block col-sm-offset-2">ZIP/Afbeelding, max. {{ ini_get('upload_max_filesize') }}</span>
                                <button type="submit" class="btn btn-success col-sm-offset-2">Afbeeldingen uploaden</button>
                        </form>
                </div>
                <div class="col-md-6">
                        <h3>Kortingsbestand</h3>

                        <hr />

                        <form action="{{ url('admin/import/discount') }}" method="POST" enctype="multipart/form-data" class="form-horizontal">
                                {!! csrf_field() !!}
                                <div class="form-group">
                                        <label for="discountFile" class="col-sm-2 control-label">Bestand</label>
                                        <div class="col-sm-10">
                                                <div class="input-group">
                                                        <span class="input-group-btn">
                                                                <span class="btn btn-primary btn-file">
                                                                        Bladeren&hellip; <input type="file" name="discountFile" accept=".csv">
                                                                </span>
                                                        </span>
                                                        <input type="text" class="form-control" readonly id="fileName">
                                                </div>
                                        </div>
                                </div>

                                <span class="help-block col-sm-offset-2">CSV, max. {{ ini_get('upload_max_filesize') }}</span>
                                <button type="submit" class="btn btn-success col-sm-offset-2">Kortingsbestand uploaden</button>
                        </form>

                        <hr />

                        <h3>Downloads</h3>

                        <hr />

                        <form action="{{ url('admin/import/download') }}" method="POST" enctype="multipart/form-data" class="form-horizontal">
                                {!! csrf_field() !!}
                                <div class="form-group">
                                        <label for="imageFile" class="col-sm-2 control-label">Bestand</label>
                                        <div class="col-sm-10">
                                                <div class="input-group">
                                                        <span class="input-group-btn">
                                                                <span class="btn btn-primary btn-file">
                                                                        Bladeren&hellip; <input type="file" name="imageFile">
                                                                </span>
                                                        </span>
                                                        <input type="text" class="form-control" readonly id="fileName">
                                                </div>
                                        </div>
                                </div>

                                <span class="help-block col-sm-offset-2">ZIP/PDF, max. {{ ini_get('upload_max_filesize') }}</span>
                                <button type="submit" class="btn btn-success col-sm-offset-2">Bestand uploaden</button>
                        </form>
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
