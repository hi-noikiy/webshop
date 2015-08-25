<table style="border: 1px solid #ccc; width: 500px; border-collapse: collapse;">
	
	<tbody>
		<tr>
			<td style="border-bottom: 1px solid #ccc; font-weight: bold; padding: 10px">Correspondentie gegevens</td>
			<td style="border-bottom: 1px solid #ccc;">&nbsp;</td>
		</tr>
		<tr>
			<td style="border-bottom: 1px solid #ccc; font-weight: bold; padding: 10px">Naam contactpersoon</td>
			<td style="border-bottom: 1px solid #ccc; padding: 10px;">{{ $corContactName }}</td>
		</tr>
		<tr>
			<td style="border-bottom: 1px solid #ccc; font-weight: bold; padding: 10px">Naam bedrijf</td>
			<td style="border-bottom: 1px solid #ccc; padding: 10px;">{{ $corName }}</td>
		</tr>
		<tr>
			<td style="border-bottom: 1px solid #ccc; font-weight: bold; padding: 10px">Adres & Huisnr</td>
			<td style="border-bottom: 1px solid #ccc; padding: 10px;">{{ $corAddress }}</td>
		</tr>
		<tr>
			<td style="border-bottom: 1px solid #ccc; font-weight: bold; padding: 10px">Postcode en plaats</td>
			<td style="border-bottom: 1px solid #ccc; padding: 10px;">{{ $corPostcode . " " . $corCity }}</td>
		</tr>
        <tr>
                <td style="border-bottom: 1px solid #ccc; font-weight: bold; padding: 10px">Telefoon contactpersoon</td>
                <td style="border-bottom: 1px solid #ccc; padding: 10px;">{{ $corContactPhone }}</td>
        </tr>
		<tr>
			<td style="border-bottom: 1px solid #ccc; font-weight: bold; padding: 10px">Telefoon bedrijf</td>
			<td style="border-bottom: 1px solid #ccc; padding: 10px;">{{ $corPhone }}</td>
		</tr>
		<tr>
			<td style="border-bottom: 1px solid #ccc; font-weight: bold; padding: 10px">Fax</td>
			<td style="border-bottom: 1px solid #ccc; padding: 10px;">{{ $corFax }}</td>
		</tr>
		<tr>
			<td style="border-bottom: 1px solid #ccc; font-weight: bold; padding: 10px">E-Mail</td>
			<td style="border-bottom: 1px solid #ccc; padding: 10px;">{{ $corEmail }}</td>
		</tr>
		<tr>
			<td style="border-bottom: 1px solid #ccc; font-weight: bold; padding: 10px">Website</td>
			<td style="border-bottom: 1px solid #ccc; padding: 10px;">{{ $corSite }}</td>
		</tr>
		<tr><td style="border-bottom: 3px solid #ccc;">&nbsp;</td><td style="border-bottom: 3px solid #ccc;">&nbsp;</td></tr> <!-- Empty row -->
		<tr>
			<td style="border-bottom: 1px solid #ccc; font-weight: bold; padding: 10px">Afleveradres</td>
			<td style="border-bottom: 1px solid #ccc;">&nbsp;</td>
		</tr>
		<tr>
			<td style="border-bottom: 1px solid #ccc; font-weight: bold; padding: 10px">Adres & Huisnr</td>
			<td style="border-bottom: 1px solid #ccc; padding: 10px;">{{ $delAddress }}</td>
		</tr>
		<tr>
			<td style="border-bottom: 1px solid #ccc; font-weight: bold; padding: 10px">Postcode en plaats</td>
			<td style="border-bottom: 1px solid #ccc; padding: 10px;">{{ $delPostcode . " " . $delCity }}</td>
		</tr>
		<tr>
			<td style="border-bottom: 1px solid #ccc; font-weight: bold; padding: 10px">Telefoon bedrijf</td>
			<td style="border-bottom: 1px solid #ccc; padding: 10px;">{{ $delPhone }}</td>
		</tr>
		<tr>
			<td style="border-bottom: 1px solid #ccc; font-weight: bold; padding: 10px">Fax</td>
			<td style="border-bottom: 1px solid #ccc; padding: 10px;">{{ $delFax }}</td>
		</tr>
		<tr><td style="border-bottom: 3px solid #ccc;">&nbsp;</td><td style="border-bottom: 3px solid #ccc;">&nbsp;</td></tr> <!-- Empty row -->
		<tr>
			<td style="border-bottom: 1px solid #ccc; font-weight: bold; padding: 10px">Betalingsgegevens</td>
			<td style="border-bottom: 1px solid #ccc;">&nbsp;</td>
		</tr>
		<tr>
			<td style="border-bottom: 1px solid #ccc; font-weight: bold; padding: 10px">IBAN</td>
			<td style="border-bottom: 1px solid #ccc; padding: 10px;">{{ $betIBAN }}</td>
		</tr>
		<tr>
			<td style="border-bottom: 1px solid #ccc; font-weight: bold; padding: 10px">KvK nummer</td>
			<td style="border-bottom: 1px solid #ccc; padding: 10px;">{{ $betKvK }}</td>
		</tr>
		<tr>
			<td style="border-bottom: 1px solid #ccc; font-weight: bold; padding: 10px">BTW</td>
			<td style="border-bottom: 1px solid #ccc; padding: 10px;">{{ $betBTW }}</td>
		</tr>
		<tr><td style="border-bottom: 3px solid #ccc;">&nbsp;</td><td style="border-bottom: 3px solid #ccc;">&nbsp;</td></tr> <!-- Empty row -->
		<tr>
			<td style="border-bottom: 1px solid #ccc; font-weight: bold; padding: 10px">Overig</td>
			<td style="border-bottom: 1px solid #ccc;">&nbsp;</td>
		</tr>
		@if ($digFactuur)
			<tr>
				<td style="border-bottom: 1px solid #ccc; font-weight: bold; padding: 10px">Alternatieve email</td>
				<td style="border-bottom: 1px solid #ccc; padding: 10px;">{{ $digFactuur }}</td>
			</tr>
		@endif
		<tr>
			<td style="border-bottom: 1px solid #ccc; font-weight: bold; padding: 10px">Digitale orderbevestiging</td>
			<td style="border-bottom: 1px solid #ccc; padding: 10px;">{{ ($digOrder ? "Ja" : "Nee") }}</td>
		</tr>
		<tr>
			<td style="border-bottom: 1px solid #ccc; font-weight: bold; padding: 10px">Mail artikelbestand</td>
			<td style="border-bottom: 1px solid #ccc; padding: 10px;">{{ ($digArtikel ? "Ja" : "Nee") }}</td>
		</tr>
	</tbody>

</table>