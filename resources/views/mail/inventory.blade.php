<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Inventory update</title>
</head>

<body>
    Hi,
    <br>
    <p>
        These products are below 10 in stock
    </p>
    <br>
    <table border="1">
        <thead>
            <tr>
                <th class="text-right">Item Name</th>
                <th class="text-right">Stock Avalaible</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($rows as $row)
                <tr>
                    <td>{{ $row['name'] }}</td>
                    <td>{{ $row['stock'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <br>
    <p>Thanks for purchasing</p>
</body>

</html>
