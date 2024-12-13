<!DOCTYPE html>
<html>

<head>
    <title>Orders</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        header {
            display: flex;
            align-items: center;
            justify-content: flex-start; /* Aligns logo and text to the left */
            margin-bottom: 10px;
            margin-left: 30px;
        }

        header img {
            width: 120px; /* Increased size of the logo */
            float: left;
        }

        header div {
            text-align: center; /* Aligns text to the left */
            margin-right: 40px;
        }

        header h2 {
            margin: 5px 0;
            font-size: 24px; /* Increased font size */
            font-weight: bold;
        }

        header p {
            margin: 3px 0;
            font-size: 14px;
        }

        .divider {
            border-top: 2px solid black;
            margin: 10px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table th, table td {
            border: 1px solid black;
            padding: 8px;
            text-align: center;
        }

        table th {
            background-color: #71afec;
        }

        .title {
            text-align: center;
            margin-top: 50px;
            margin-bottom: 30px;
            font-size: 18px;
            font-weight: bold;
            border-top: 2px solid black;
            border-bottom: 2px solid black;
            padding: 10px 0;
          }


    </style>
</head>

<body>
    <header>
        <img src="{{ public_path('assets/images/favicon.png') }}" alt="Logo">
        <div>
            <h2>Custowear</h2>
            <p>Jl. Andalas, Padang, Sumatera Barat</p>
            <p>Email: info@company.com | Telp: (021) 1234567</p>
            <p>Tanggal: {{ date('m/d/Y') }}</p>
        </div>
    </header>

    <div class="title">Orders Report</div>

    <table>
        <thead>
            <tr>
                <th>Order No</th>
                <th>Name</th>
                <th>Phone</th>
                <th>Subtotal</th>
                <th>Tax</th>
                <th>Total</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($orders as $order)
                <tr>
                    <td>{{ $order->id }}</td>
                    <td>{{ $order->name }}</td>
                    <td>{{ $order->phone }}</td>
                    <td>{{ number_format($order->subtotal, 2) }}</td>
                    <td>{{ number_format($order->tax, 2) }}</td>
                    <td>{{ number_format($order->total, 2) }}</td>
                    <td>{{ ucfirst($order->status) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
