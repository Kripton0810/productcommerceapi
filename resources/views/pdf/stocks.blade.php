<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Approval Bill</title>
    <style>
        * {
            padding: 0;
            margin: 0;
            box-sizing: border-box;
            font-family: 'Times New Roman';
        }

        td,
        th,
        tr,
        table {
            border-bottom: 1px solid black;
            border-collapse: collapse;
            font-size: 16px;
            text-align: left;
            text-transform: uppercase;
        }

        thead tr {
            border-top: 1px solid black;
            border-collapse: collapse;
        }

        tfoot tr {
            border-top: transparent;

        }

        tfoot tr th td {
            border-top: transparent;
            text-align: right;

        }

        td.description,
        th.description {
            width: 20% !important;
            max-width: 20% !important;
        }

        td.quantity,
        th.quantity {
            width: 15% !important;
            max-width: 15% !important;
            word-break: break-all;
        }

        td.mrp,
        th.mrp {
            width: 15% !important;
            max-width: 15% !important;
            word-break: break-all;
        }

        td.rate,
        th.rate {
            width: 15% !important;
            max-width: 15% !important;
            word-break: break-all;
        }

        td.amt,
        th.amt {
            width: 15% !important;
            max-width: 15% !important;
            word-break: break-all;
        }

        td.serial_no,
        th.serial_no {
            width: 15% !important;
            max-width: 15% !important;
            word-break: break-all;
        }

        .centered {
            text-align: center;
            align-content: center;
            font-size: 24px;
            font-family: 'Brush Script MT';
        }

        .ticket {
            margin-top: 10px;
            margin-left: 4px;
            margin-right: 4px;
        }

        img {
            max-width: inherit;
            width: inherit;
        }

        .approval {
            font-family: 'Calibri' !important;
            margin-bottom: 10px;
        }

        .thank-you {
            margin-top: 24px;
            font-family: 'French Script MT';
        }

        .tbd tr {
            padding-top: 0.5px;
        }

        @media print {

            .hidden-print,
            .hidden-print * {
                display: none !important;
            }
        }
    </style>
</head>

<body>
    <div class="ticket">
        <p class="centered approval">Om Prakash Store</p>
        <table border="1">
            <thead>

                <tr>
                    <th scope="row" class="serial_no">S.No</th>
                    <th scope="row" class="description">Product SKU</th>
                    <th scope="row" class="description">Product Name</th>
                    <th scope="row" class="quantity">Stock</th>
                    <th scope="row" class="mrp">MRP</th>
                    <th scope="row" class="rate">RATE</th>
                </tr>
            </thead>
            <tbody class="tbd">
                <?php $i = 1; ?>
                @foreach ($data as $item)
                    <tr @if ($item['stock'] <= 10) style="background-color:#FF0000" @endif>
                        <th scope="row" class="serial_no">
                            {{ $i++ }}
                        </th>
                        <th scope="row" class="description">
                            {{ $item['sku'] }}
                        </th>
                        <th scope="row" class="quantity">{{ $item['name'] }}
                        </th>
                        <th scope="row" class="mrp">{{ $item['stock'] }}
                        </th>
                        <th scope="row" class="rate">{{ $item['price'] }}
                        </th>
                        <th scope="row" class="amt">{{ $item['rate'] }}
                        </th>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <p class="centered thank-you">
            !!! &nbsp; Thanks for your service &nbsp; !!!
        </p>
    </div>
</body>

</html>
