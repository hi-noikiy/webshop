<html style="font-family: 'Nunito Sans', sans-serif;">
<head style="font-family: 'Nunito Sans', sans-serif;">
    <style type="text/css" style="font-family: 'Nunito Sans', sans-serif;">
        @font-face {
            font-family: 'Nunito Sans';
            font-style: normal;
            font-weight: 400;
            src: local('Nunito Sans Regular'), local('NunitoSans-Regular'), url(https://fonts.gstatic.com/s/nunitosans/v1/iJ4p9wO0GDKJ-D5teKuZqo4P5ICox8Kq3LLUNMylGO4.woff2) format('woff2');
            unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2212, U+2215, U+E0FF, U+EFFD, U+F000;
        }
        @font-face {
            font-family: 'Nunito Sans';
            font-style: italic;
            font-weight: 200;
            src: local('Nunito Sans ExtraLight Italic'), local('NunitoSans-ExtraLightItalic'), url(https://fonts.gstatic.com/s/nunitosans/v1/ORCQQ32ldzJ6bFTh_zXqV_OFzGtJPNEsduSbsc1I9r8.woff2) format('woff2');
            unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2212, U+2215, U+E0FF, U+EFFD, U+F000;
        }

        * {
            font-family: 'Nunito Sans', sans-serif;
        }

        table {
            border-collapse: collapse;
        }

        .location-table td {
            text-align: center;
        }

        .product-table {
            border-top: 3px solid #c2272d;
            padding: 5px;
            width: 100%;
        }

        .product-table th {
            padding: 10px 0;
        }

        .product-table td {
            border-top: 1px solid #efefef;
            border-bottom: 1px solid #efefef;
        }

        .comment-table {
            text-align: left;
            color: #aaa;
            border-left: 10px solid #aaa;
            padding-left: 15px;
        }

        .comment-table th {
            padding: 0 0 10px 10px;
            font-style: italic;
            font-weight: 200;
            font-size: 35px;
        }

        .comment-table td {
            padding: 20px 10px 0;
        }
    </style>
</head>

<body style="font-family: 'Nunito Sans', sans-serif;">
<table class="location-table" style="width: 100%;font-family: 'Nunito Sans', sans-serif;border-collapse: collapse;">
    <thead style="font-family: 'Nunito Sans', sans-serif;">
    <th style="font-family: 'Nunito Sans', sans-serif;">Afleveradres</th>
    <th style="font-family: 'Nunito Sans', sans-serif;">Factuuradres</th>
    </thead>

    <tbody style="font-family: 'Nunito Sans', sans-serif;">
    <tr style="font-family: 'Nunito Sans', sans-serif;">
        <td style="font-family: 'Nunito Sans', sans-serif;text-align: center;">{{ $address->name }}</td>
        <td style="font-family: 'Nunito Sans', sans-serif;text-align: center;">{{ Auth::user()->company_id }}</td>
    </tr>
    <tr style="font-family: 'Nunito Sans', sans-serif;">
        <td style="font-family: 'Nunito Sans', sans-serif;text-align: center;">{{ $address->street }}</td>
        <td style="font-family: 'Nunito Sans', sans-serif;text-align: center;">{{ Auth::user()->company->company }}</td>
    </tr>
    <tr style="font-family: 'Nunito Sans', sans-serif;">
        <td style="font-family: 'Nunito Sans', sans-serif;text-align: center;">{{ $address->postcode }} {{ $address->city }}</td>
        <td style="font-family: 'Nunito Sans', sans-serif;text-align: center;">{{ Auth::user()->company->street }}</td>
    </tr>
    <tr style="font-family: 'Nunito Sans', sans-serif;">
        <td style="font-family: 'Nunito Sans', sans-serif;text-align: center;">{{ $address->telephone }}</td>
        <td style="font-family: 'Nunito Sans', sans-serif;text-align: center;">{{ Auth::user()->company->postcode }} {{ Auth::user()->company->city }}</td>
    </tr>
    <tr style="font-family: 'Nunito Sans', sans-serif;">
        <td style="font-family: 'Nunito Sans', sans-serif;text-align: center;">{{ $address->mobile }}</td>
        <td style="font-family: 'Nunito Sans', sans-serif;text-align: center;">{{ Auth::user()->email }}</td>
    </tr>
    </tbody>
</table>

<br style="font-family: 'Nunito Sans', sans-serif;">

@if ($comment)
    <table class="comment-table" style="font-family: 'Nunito Sans', sans-serif;border-collapse: collapse;text-align: left;color: #aaa;border-left: 10px solid #aaa;padding-left: 15px;">
        <thead style="font-family: 'Nunito Sans', sans-serif;">
        <tr style="font-family: 'Nunito Sans', sans-serif;">
            <th style="font-family: 'Nunito Sans', sans-serif;padding: 0 0 10px 10px;font-style: italic;font-weight: 200;font-size: 35px;">Opmerking</th>
        </tr>
        </thead>
        <tbody style="font-family: 'Nunito Sans', sans-serif;">
        <tr style="font-family: 'Nunito Sans', sans-serif;">
            <td style="font-family: 'Nunito Sans', sans-serif;padding: 20px 10px 0;">{{ $comment }}</td>
        </tr>
        </tbody>
    </table>
@endif

<br style="font-family: 'Nunito Sans', sans-serif;">

<table class="product-table" style="font-family: 'Nunito Sans', sans-serif;border-collapse: collapse;border-top: 3px solid #c2272d;padding: 5px;width: 100%;">
    <thead style="font-family: 'Nunito Sans', sans-serif;">
    <th style="text-align: left;font-family: 'Nunito Sans', sans-serif;padding: 10px 0;">Product nummer</th>
    <th style="text-align: left;font-family: 'Nunito Sans', sans-serif;padding: 10px 0;">Product omschrijving</th>
    <th style="text-align: left;font-family: 'Nunito Sans', sans-serif;padding: 10px 0;">Aantal</th>
    </thead>

    <tbody style="text-align: left;font-family: 'Nunito Sans', sans-serif;">
    @foreach ($cart as $product)
        <tr style="padding: 5px 0;font-family: 'Nunito Sans', sans-serif;">
            <td style="font-family: 'Nunito Sans', sans-serif;border-top: 1px solid #efefef;border-bottom: 1px solid #efefef;">{{ $product->id }}</td>
            <td style="font-family: 'Nunito Sans', sans-serif;border-top: 1px solid #efefef;border-bottom: 1px solid #efefef;">{{ $product->name }}</td>
            <td style="font-family: 'Nunito Sans', sans-serif;border-top: 1px solid #efefef;border-bottom: 1px solid #efefef;">{{ $product->qty }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>