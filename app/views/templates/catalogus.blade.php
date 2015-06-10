<?php 
	$lastype 	= ''; 
	$lastserie 	= '';
?>

<!DOCTYPE html>
<html>
<head>
	<title>Wiringa Technische Groothandel catalogus</title>

	<style type="text/css">
        @import url(https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css);

        * {
        	font-family: 'Titillium Web', sans-serif !important;
        }

        h1, h2, h3, h4, h5, h6 {
        	font-weight: bold;
        }

        img {
        	max-height: 30px;
        	max-width: 30px;
        }

        table {
        	margin-bottom: 100px !important;
        }

        tr,
        .type-wrapper {
		    page-break-inside: avoid;
		}

		.series-wrapper {
			page-break-before: always; 
		}

        th, td {
        	padding: 0 8px !important;
        	font-size: 10px;
        }

        td:nth-child(1),
        th:nth-child(1) {
        	width: 7%;
        }

        td:nth-child(2),
        th:nth-child(2) {
        	width: 60%;
        }

        td:nth-child(3),
        th:nth-child(3) {
        	width: 3%;
        }

        td:nth-child(4),
        th:nth-child(4) {
        	width: 3%;
        }

        td:nth-child(5),
        th:nth-child(5) {
        	width: 7%;
        }

        td:nth-child(6),
        th:nth-child(6) {
        	width: 10%;
        }

        td:nth-child(7),
        th:nth-child(7) {
        	width: 3%;
        }

        td:nth-child(8),
        th:nth-child(8) {
        	width: 7%;
        }
	</style>
</head>
<body>

@foreach($products as $product)
	

	<?php $price = number_format((Double) preg_replace("/\,/", ".", $product->price), 2, ",", "."); ?>

	@if($lastserie === '')
		<div class="series-wrapper">
			<h5> {{ $product->series }} </h5>
	@elseif($product->series !== $lastserie && $lastserie !== '')
		<?php $lastype = ''; ?>
					</tbody>
				</table>
			</div>
		</div>
		<div class="series-wrapper">
			<h5> {{ $product->series }} </h5>
	@endif

	@if($lastype === '')
		<div class="type-wrapper">
			<h6>{{ $product->type }}</h6>
			<img src="../img/products/{{ $product->image }}">
			<table class="table table-striped">
				<thead>
					<tr>
						<th>Art nr.</th>
						<th>Naam</th>
						<th>PP</th>
						<th>Verp.</th>
						<th>Groep</th>
						<th>Fabrieksnr.</th>
						<th>Verp.</th>
						<th>Prijs</th>
					</tr>
				</thead>
					<tbody> 
	@elseif($product->type !== $lastype && $lastype !== '')
				</tbody>
			</table>
		</div>
		<div class="type-wrapper">
			<h6>{{ $product->type }}</h6>
			<img src="../img/products/{{ $product->image }}">
			<table class="table table-striped">
				<thead>
					<tr>
						<th>Art nr.</th>
						<th>Naam</th>
						<th>PP</th>
						<th>Verp.</th>
						<th>Groep</th>
						<th>Fabrieksnr.</th>
						<th>Verp.</th>
						<th>Prijs</th>
					</tr>
				</thead>
				<tbody>
	@endif
	
	<tr>
		<td>{{ $product->number }}</td>
		<td>{{ $product->name }}</td>
		<td>{{ $product->price_per }}</td>
		<td>{{ $product->registered_per }}</td>
		<td>{{ $product->group }}</td>
		<td>{{ $product->altNumber }}</td>
		<td>{{ $product->packed_per }}</td>
		<td>&euro;{{ $price }}</td>
	</tr>

	<?php 
		$lastype 	= $product->type;
		$lastserie 	= $product->series;
	?>
@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</body>
</html>