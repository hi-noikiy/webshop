@extends('master')

@section('title')
        <h3>Admin <small>content manager</small></h3>
@stop

@section('content')
        @include('admin.nav')

        <div class="hidden-xs hidden-sm">
                <h3>1. Selecteer een veld om aan te passen</h3>

                <hr />

                <form action="/admin/saveContent" method="POST" class="form-horizontal">
                        {!! csrf_field() !!}
                        <div class="form-group">
                                <label for="field" class="col-sm-2 control-label">Velden: </label>

                                <div class="col-sm-10">
                                        <select name="field" id="field-selector" class="form-control">
                                                <option value="---" selected>Selecteer een veld</option>
                                                @foreach($data as $field)
                                                        <option value="{{ $field->name }}">{{ $field->page }} / {{ $field->title }}</option>
                                                @endforeach
                                        </select>
                                </div>
                        </div>

                        <div id="step-2" style="display: none;">
                                <h3>2. De HTML code aanpassen</h3>

                                <hr />

                                <div class="form-group">
                                        <label for="field" class="col-sm-2 control-label">Inhoud: </label>

                                        <div class="col-sm-10">
                                                <textarea name="content" id="editor" rows="30" class="form-control"></textarea>
                                        </div>
                                </div>

                                <hr />

                                <button type="submit" class="btn btn-success btn-block">Opslaan</button>
                        </div>
                </form>
        </div>

        <div class="hidden-md hidden-lg">
                <div class="alert alert-warning">Deze pagina kan alleen worden bekeken op grote vensters zoals een laptop of desktop.</div>
        </div>
@stop

@section('extraJS')
        <script type="text/javascript" src="/js/ckeditor/ckeditor.js"></script>
        <script type="text/javascript" src="/js/ckeditor/adapters/jquery.js"></script>

        <script type="text/javascript">
                $('#field-selector').change(function() {
                        $.ajax({
                                url: '/admin/getContent',
                                data: {page: this.value},
                                dataType: 'text',
                                success: function(data) {
                                        $('#editor').val(data);
                                        $('#step-2').show();
                                }
                        });
                });

                $('#editor').ckeditor();
        </script>
@stop
