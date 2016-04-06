@extends('master', ['pagetitle' => 'Webshop / Product ' . $pack->number])

@section('title')
    <h3>{{ $pack->name }}</h3>
@stop

@section('content')
    @if (Auth::check())
        <div class="modal fade" id="addProductModal" tabindex="-1" role="dialog" aria-labelledby="addToCart"
             aria-hidden="true">
            <form class="form" action="/cart/add/pack" method="POST">
                <!-- Non editable form data -->
                <input class="hidden" name="pack" value="{{ $pack->id }}">
                <input class="hidden" name="ref" value="{{ Input::get('ref') }}">
                {!! csrf_field() !!}

                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h4 class="modal-title">Toevoegen aan de winkelwagen</h4>
                        </div>
                        <table class="table">
                            <tbody>
                            <tr>
                                <td><b>Aantal producten</b></td>
                                <td>{{ count($pack->products) }}</td>
                            </tr>
                            </tbody>
                        </table>
                        <div class="modal-footer">
                            <div class="row">
                                <div class="col-md-6">
                                    <button type="button" class="btn btn-danger btn-block" data-dismiss="modal">
                                        Annuleren
                                    </button>
                                </div>

                                <br class="hidden-md hidden-lg"/>

                                <div class="col-md-6">
                                    <button type="submit" class="btn btn-success">Toevoegen</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    @endif

    <div class="row">
        <div class="col-md-4" id="image">
            <div class="well well-lg text-center">
                <img src="/img/specials/{{ $pack->image ? $pack->image : 'default.jpg' }}" alt="{{ $pack->image }}">
            </div>
        </div>
        <div class="col-md-8">
            <div class="panel panel-default">
                <div class="panel-heading">
                    @if (Auth::check())
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="panel-title">Producten</div>
                            </div>
                            <div class="col-sm-6 text-right">
                                <div class="btn-group">
                                    <button class="btn btn-success" type="button" data-toggle="modal" data-target="#addProductModal">
                                        <span class="glyphicon glyphicon-shopping-cart"></span> <b>+</b>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="panel-title">Producten</div>
                            </div>
                            <div class="col-sm-6 text-right">
                                <button class="btn btn-success" data-toggle="modal" data-target="#loginModal">
                                    <span class="glyphicon glyphicon-shopping-cart"></span> <b>+</b>
                                </button>
                            </div>
                        </div>
                    @endif
                </div>
                <table class="table">
                    @foreach($pack->products as $product)
                        <tr>
                            <td>{{ $product->details->number }}</td>
                            <td>{{ $product->details->name }}</td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
@stop

@section('extraCSS')
    <style type="text/css">
        .panel-body {
            padding: 0 !important;
        }
    </style>
@stop

@section('extraJS')
    <script type="text/javascript">
        $('#changeFav').hover( // The button on the product page
                function () {   // Runs when the mouse enters the button
                    var artNr = $('#changeFav').data("id");

                    $.ajax({
                        url: "/account/isFav",
                        type: "POST",
                        dataType: "text",
                        data: {product: artNr, _token: '{!! csrf_token() !!}'},
                        success: function (data) {
                            if (data === 'IN_ARRAY') {
                                $("#defaultFav").hide();
                                $("#okFav").hide();
                                $("#addFav").hide();
                                $("#removeFav").show();
                            } else if (data === 'NOT_IN_ARRAY') {
                                $("#defaultFav").hide();
                                $("#okFav").hide();
                                $("#removeFav").hide();
                                $("#addFav").show();
                            } else if (data === 'ERROR') {
                                alert("Something went wrong");
                            }
                        },
                        done: function (data) {
                            console.log(data);
                        }
                    });
                }, function () { // Runs when the mouse leaves the button
                    $("#removeFav").hide();
                    $("#addFav").hide();
                    $("#okFav").hide();
                    $("#defaultFav").show();
                }
        );

        $('#changeFav').click(function () { //The button the the product page and the fav list
            var artNr = $('#changeFav').data("id");

            $.ajax({
                url: "/account/modFav",
                type: "POST",
                dataType: "text",
                data: {product: artNr, _token: '{!! csrf_token() !!}'},
                success: function (data) {
                    if (data === 'SUCCESS') {
                        $("#removeFav").hide();
                        $("#addFav").hide();
                        $("#defaultFav").hide();
                        $("#okFav").show();

                        if ($('#changeFav').data("refresh") === true) {
                            location.reload(); // refresh the page
                        }
                    } else if (data === 'ERROR') {
                        alert("Something went wrong");
                    }
                }
            });
        });

        $(document).ready(function () {
            $('#qty').keyup(function () {
                if (isNaN($('#qty').val()) || $('#qty').val() == "") {
                    $('#addToCart').attr("disabled", "disabled");
                } else {
                    $('#addToCart').removeAttr('disabled');
                }
            });
        });
    </script>
@stop
