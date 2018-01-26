<div class="row">
    @foreach($slides as $slide)
        <div class="col-sm-6 col-md-4">
            <div class="card card-2">
                <div class="thumbnail">
                    <img src="{{ asset('img/carousel/'.$slide->getImage()) }}" alt="{{ $slide->getImage() }}" class="img-fluid">
                </div>

                <div class="caption">
                    <h3>{{ $slide->getTitle() }}</h3>
                    <p>{{ $slide->getCaption() }}</p>
                </div>

                <form method="POST" role="form">
                    {{ csrf_field() }}
                    {{ method_field('patch') }}

                    <input type="hidden" name="slide" value="{{ $slide->getId() }}">

                    <div class="input-group">
                        <span class="input-group-prepend">
                            <button class="btn btn-primary" type="submit"><i class="fa fa-pencil"></i></button>
                        </span>

                        <input type="number" name="order" value="{{ $slide->getOrder() }}" class="form-control"
                               placeholder="Slide nummer" aria-describedby="descr" required>

                        <div class="input-group-append">
                            <span class="input-group-text" id="descr">Slide nr</span>
                        </div>
                    </div>
                </form>

                <br />

                <form action="{{ route('admin.carousel.delete', ['id' => $slide->getId()]) }}" method="post">
                    {{ csrf_field() }}
                    {{ method_field('delete') }}

                    <button class="btn btn-outline-danger btn-block" role="button">
                        <i class="fal fa-fw fa-remove"></i> Verwijderen
                    </button>
                </form>
            </div>
        </div>

        @if ($loop->iteration % 3 === 0)
            <div class="clearfix visible-md visible-lg"></div>
            <br class="visible-md visible-lg" />
        @endif

        @if ($loop->iteration % 2 === 0)
            <div class="clearfix visible-xs visible-sm"></div>
            <br class="visible-sm" />
        @endif

        <br class="visible-xs" />
    @endforeach
</div>