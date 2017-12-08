@php
    $isAdmin = (auth()->check() && auth()->user()->hasRole(\WTG\Models\Customer::CUSTOMER_ROLE_SUPER_ADMIN));
@endphp

<div id="block-{{ $block->getAttribute('name') }}" {!! $isAdmin ? 'contenteditable="true" class="block editable"' : 'class="block"' !!}>
    {{ $block->getAttribute('content') }}
</div>

@if ($isAdmin)
    {{--<script>--}}
        {{--var editor = CKEDITOR.inline('block-{{ $block->getAttribute('name') }}');--}}

        {{--editor.on( 'change', function( evt ) {--}}
            {{--var data = evt.editor.getData();--}}
            {{--// getData() returns CKEditor's HTML content.--}}
            {{--console.log( 'Total bytes: ' + data.length );--}}
        {{--});--}}
    {{--</script>--}}
@endif