<table style="border: 1px solid #ccc; width: 100%">
    <thead>
    	<th style="text-align: left;">Bedrijf</th>
    	<th style="text-align: left;">Afleveradres</th>
    </thead>

    <tbody style="text-align: left;">
        <tr>
			<td>{{ Auth::user()->company_id }}</td>
			<td></td>
        </tr>
    	<tr>
    		<td>{{ Auth::user()->company->company }}</td>
    		<td>{{ $address->name }}</td>
    	</tr>
    	<tr>
    		<td>{{ Auth::user()->company->street }}</td>
    		<td>{{ $address->street }}</td>
    	</tr>
    	<tr>
    		<td>{{ Auth::user()->company->postcode }} {{ Auth::user()->company->city }}</td>
    		<td>{{ $address->postcode }} {{ $address->city }}</td>
    	</tr>
    	<tr>
    		<td>{{ Auth::user()->email }}</td>
    		<td>{{ $address->telephone }}</td>
    	</tr>
        <tr>
			<td></td>
			<td>{{ $address->mobile }}</td>
        </tr>
    </tbody>
</table>

<br />
<br />
<br />

@if ($comment)
    <table>
        <tbody>
            <tr>
                <td>{{ $comment }}</td>
            </tr>
        </tbody>
    </table>

    <br />
    <br />
    <br />
@endif

<table style="border: 1px solid #ccc; width: 100%; border-collapse: collapse;">
	<thead>
		<th style="text-align: left;">Product nummer</th>
		<th style="text-align: left;">Product omschrijving</th>
		<th style="text-align: left;">Aantal</th>
	</thead>

	<tbody style="text-align: left;">
		@foreach ($cart as $product)
			<tr style="padding: 5px 0px;">
				<td style="border-bottom: 1px solid #ccc;">{{ $product->id }}</td>
				<td style="border-bottom: 1px solid #ccc;">{{ $product->name }}</td>
				<td style="border-bottom: 1px solid #ccc;">{{ $product->qty }}</td>
			</tr>
		@endforeach
	</tbody>
</table>
