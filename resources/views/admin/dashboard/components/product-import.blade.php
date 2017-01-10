@if ($discount_import->error)
    <h3>
        <i class="fa fa-fw fa-exclamation-triangle"></i> Product import
    </h3>
@else
    <h3>
        <i class="fa fa-fw fa-check-circle"></i> Product import
    </h3>
@endif

<hr />

<table class="table">
    <tbody>
    <tr>
        <td>Laatste update</td>
        <td>{{ $product_import->updated_at->getTimestamp() === -62169984000 ? $product_import->created_at : $product_import->updated_at }}</td>
    </tr>
    <tr>
        <td>Status</td>
        <td>{!! $product_import->content !!}</td>
    </tr>
    </tbody>
</table>