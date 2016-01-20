@extends('master', ['pagetitle' => 'Admin / Carousel'])

@section('title')
        <h3>Admin <small>carousel manager</small></h3>
@stop

@section('content')
        @include('admin.nav')

        <div class="modal fade" id="addSlide" tabindex="-1" role="dialog" aria-labelledby="addSlideLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
                <div class="modal-content">
                        <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Sluiten</span></button>
                                <h4 class="modal-title" id="addSlideLabel">Slide toevoegen</h4>
                        </div>
                        <div class="modal-body">
                                <form action="/admin/addCarouselSlide" method="POST" enctype="multipart/form-data" class="form form-horizontal">
                                        {!! csrf_field() !!}
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
                                                        <input class="form-control" placeholder="Titel" type="text" name="title" maxlength="100" required>
                                                </div>
                                        </div>
                                        <div class="form-group">
                                                <label for="caption" class="col-sm-2 control-label">Omschrijving</label>
                                                <div class="col-sm-10">
                                                        <input class="form-control" placeholder="Omschrijving" type="text" name="caption" maxlength="200" required>
                                                </div>
                                        </div>
                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Sluiten</button>
                                        <button type="submit" class="btn btn-primary">Toevoegen</button>
                                </form>
                        </div>
                </div>
        </div>
</div>

<div class="row">
        <div class="col-md-12">
                @if ($status === 'upload_error')
                        <br />
                        <div class="alert alert-danger">
                                <p>Er is een fout opgetreden</p>
                        </div>
                @endif
                <br />
                <button class="btn btn-success btn-block" data-toggle="modal" data-target="#addSlide">Slide toevoegen aan de carousel</button>
                <br />
                <?php $count = 0; // Initialize the counter ?>
                @foreach($carouselData as $slide)
                        @if($count === 0)
                                <div class="row">
                        @endif

                        <div class="col-sm-6 col-md-4">
                                <div class="thumbnail">
                                        <img src="/img/carousel/{{{ $slide['Image'] }}}" alt="{{{ $slide['Image'] }}}" style="height: 300px">
                                        <div class="caption">
                                                <h3>{{{ $slide['Title'] }}}</h3>
                                                <p>{{{ $slide['Caption'] }}}</p>
                                                <p>
                                                        <form action="/admin/editCarouselSlide/{{{ $slide['id'] }}}" method="POST" role="form">
                                                                {!! csrf_field() !!}
                                                                <div class="input-group">
                                                                        <span class="input-group-btn">
                                                                                <button class="btn btn-primary" type="submit">Wijzigen</button>
                                                                        </span>
                                                                        <input type="number" name="order" value="{{{ $slide['Order'] }}}" class="form-control" placeholder="Slide nummer" aria-describedby="descr" required>
                                                                        <span class="input-group-addon" id="descr">Slide nr</span>
                                                                </div>
                                                        </form>
                                                </p>
                                                <p><a href="/admin/removeCarouselSlide/{{{ $slide['id'] }}}" class="btn btn-danger btn-block" role="button">Verwijderen uit carousel</a></p>
                                        </div>
                                </div>
                        </div>

                        @if($count === 2) {{-- End the row if there are 3 slides --}}
                                </div>
                                <?php $count = 0; ?>
                        @else
                                <?php $count++; ?>
                        @endif
                @endforeach
        </div>
</div>
@stop

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
@stop

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
@stop
