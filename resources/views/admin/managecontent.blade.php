@extends('master', ['pagetitle' => 'Admin / Inhoud aanpassen'])

@section('title')
    <h3>Admin <small>inhoud aanpassen</small></h3>
@endsection

@section('content')
    @include('admin.nav')

    <br />

    <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
        <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="headingOne">
                <h4 class="panel-title">
                    <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                        Pagina inhoud
                    </a>
                </h4>
            </div>
            <div id="collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
                <div class="panel-body">
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
                                <h3>2. De inhoud aanpassen</h3>

                                <hr />

                                <div class="form-group">
                                    <label for="field" class="col-sm-2 control-label">Inhoud: </label>

                                    <div class="col-sm-10">
                                        <textarea name="content" id="page-editor" rows="30" class="form-control"></textarea>
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
                </div>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="headingTwo">
                <h4 class="panel-title">
                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                        Product omschrijving
                    </a>
                </h4>
            </div>
            <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
                <div class="panel-body">
                    <form action="{{ route('update_description') }}" method="POST" class="form-horizontal">

                        {{ csrf_field() }}

                        <div class="form-group">
                            <label for="field" class="col-sm-2 control-label">Product nummer</label>

                            <div class="col-sm-10">
                                <input placeholder="Product nummer" id="product-number" type="number" name="product" class="form-control">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="field" class="col-sm-2 control-label">Omschrijving</label>

                            <div class="col-sm-10">
                                <textarea name="content" id="description-editor" rows="30" class="form-control"></textarea>
                            </div>
                        </div>

                        <button class="btn btn-success" type="submit">Opslaan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('extraJS')
    <script type="text/javascript" src="/ckeditor/ckeditor.js"></script>
    <script type="text/javascript" src="/ckeditor/adapters/jquery.js"></script>

    <script type="text/javascript">
        $fieldSelectorInput = $('#field-selector');
        $productNumberInput = $('#product-number');
        $descriptionEditor = $('#description-editor');
        $pageEditor = $('#page-editor');

        $fieldSelectorInput.change(function() {
            $.ajax({
                url: '/admin/api/content',
                data: { page: this.value },
                dataType: 'json',
                success: function(data) {
                    var content = data.payload;

                    $pageEditor.val(content.content);
                    $('#step-2').show();
                }
            });
        });

        $productNumberInput.change(function () {
            if (this.value.length > 6) {
                $.ajax({
                    url: '/admin/api/description',
                    data: { product: this.value },
                    dataType: 'json',
                    success: function(data) {
                        console.log(data);
                        var content = data.payload;

                        $descriptionEditor.val(content.value);
                    }
                });
            }
        });

        $descriptionEditor.ckeditor();
        $pageEditor.ckeditor();
    </script>
@endsection
