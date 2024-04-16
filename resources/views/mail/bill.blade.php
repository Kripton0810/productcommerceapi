<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Billing Document</title>
</head>

<body>
    Hi {{ $name }},
    <br>
    <p>
        Your total bill is {{ $totalAmt }} and bill ID is {{ $billId }}
    </p>
    <table border="1">
        <thead>
            <tr>
                <th class="text-right">Item Name</th>
                <th class="text-right">Quantity</th>
                <th class="text-right">Price/Unit (₹)</th>
                <th class="text-right">Total (₹)</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($rows as $row)
                <tr>
                    <td>{{ $row['name'] }}</td>
                    <td>{{ $row['qty'] }}</td>
                    <td>{{ $row['price'] }}</td>
                    <td>{{ $row['qty'] * $row['price'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <br>
    <p>Thanks for purchasing</p>
</body>

</html>
