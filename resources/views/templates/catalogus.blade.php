<?php 
	$lastype 	= '';
	$lastgroup	= ''; 
	$lastserie 	= '';
	$lastletter = '';
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

        h4, h6, 
        b, p {
        	margin: 0 auto;
        }

        h6.index_name {
        	position: relative;
        	right: 10000px;
        	margin: 0;
        	padding: 0;
        	font-size: 0pt;
        	top: -15px;
        }

        p.group {
        	font-size: 11pt;
        }

        p.series,
        p.type {
        	font-size: 10pt;
        }

        img {
        	max-width: 80px;
        	max-height: 80px;
        }

        .product-image { 
        	position: relative; 
        	height: 80px; 
        }

        table {
        	margin-bottom: 10px !important;
        }

        tr,
        .type-wrapper {
			page-break-inside: avoid;
        }

		.group-wrapper {
			page-break-after: always;
		}

		.type-wrapper {
			min-height: 80px;
		}

        th, td {
        	padding: 0 8px !important;
        	font-size: 7pt;
        	white-space: nowrap;
        }

        a {
        	color: black;
        }

        td:nth-child(1),
        th:nth-child(1) {
        	width: 7%;
        }

        td:nth-child(2),
        th:nth-child(2) {
        	width: 63%;
        }

        td:nth-child(3),
        th:nth-child(3) {
        	width: 7%;
        }

        td:nth-child(4),
        th:nth-child(4) {
        	width: 12%;
        }

        td:nth-child(5),
        th:nth-child(5) {
        	width: 3%;
        }

        td:nth-child(6),
        th:nth-child(6) {
        	width: 7%;
        }
	</style>

</head>
<body>

@foreach($products as $product)
	<?php $price = number_format((preg_replace("/\,/", ".", $product->price) * $product->refactor) / $product->price_per, 2, ".", ""); ?>

	@if($lastgroup === '')
		<div class="group-wrapper">
			<center><p class="group"><b>{!! $product->catalog_group !!}</b></p></center>
	@elseif($product->catalog_group !== $lastgroup && $lastgroup !== '')
		<?php $lastserie = $lastype = ''; ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
		<div class="group-wrapper">
			<center><p class="group"><b>{!! $product->catalog_group !!}</b></p></center>
	@endif

	@if($lastype === '')
		<div class="type-wrapper">
			<center>
				@if($product->series !== $lastserie)
					<p class="series"><b>{!! $product->series !!}</b></p>
				@endif
				<p class="type"><b>{!! ucfirst($product->type) !!}</b> <h6 class="index_name">{!! ucfirst($product->catalog_index) !!}</h6></p>
			</center>			
			<div class="row">
				<div class="col-xs-2 product-image">
					@if ($product->image !== 'geenafbeelding.jpg')
						<center><img src="http://wiringa.nl/img/shopimg/{!! $product->image !!}"></center>
					@endif
				</div>
				<div class="col-xs-10">
					<table class="table table-striped">
						<thead>
							<tr>
								<th>Art nr.</th>
								<th>Naam</th>
								<th>Groep</th>
								<th>Fabrieksnr.</th>
								<th>PP</th>
								<th>Prijs</th>
							</tr>
						</thead>
							<tbody> 
	@elseif($product->type !== $lastype && $lastype !== '')
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<div class="type-wrapper">
			<center>
				@if($product->series !== $lastserie)
					<p class="series"><b>{!! $product->series !!}</b></p>
				@endif
				<p class="type"><b>{!! ucfirst($product->type) !!}</b> <h6 class="index_name">{!! ucfirst($product->catalog_index) !!}</h6></p>
			</center>
			<div class="row">
				<div class="col-xs-2 product-image">
					@if ($product->image !== 'geenafbeelding.jpg')
						<center><img src="http://wiringa.nl/img/shopimg/{!! $product->image !!}"></center>
					@endif
				</div>
				<div class="col-xs-10">
					<table class="table table-striped">
						<thead>
							<tr>
								<th>Art nr.</th>
								<th>Naam</th>
								<th>Groep</th>
								<th>Fabrieksnr.</th>
								<th>PP</th>
								<th>Prijs</th>
							</tr>
						</thead>
							<tbody> 
	@endif
	
	<tr>
		<td>{!! $product->number !!}</td>
		<td>{!! $product->name !!}</td>
		<td>{!! $product->group !!}</td>
		<td>{!! $product->altNumber !!}</td>
		<td>{!! ($product->refactor == 1 ? price_per($product->registered_per) : price_per($product->packed_per)) !!}</td>
		<td>&euro;{!! $price !!}</td>
	</tr>

	<?php 
		$lastype 	= $product->type;
		$lastserie 	= $product->series;
		$lastgroup	= $product->catalog_group;
	?>
@endforeach
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</body>
</html>