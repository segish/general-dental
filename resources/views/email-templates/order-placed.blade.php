<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Order Placed</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style type="text/css">
        body {
            background-color: #e9ecef;
        }

        .card {
            background-color: #ffffff;
            border-radius: 0.25rem;
            margin: 1rem 0;
            padding: 1.25rem;
        }

        .card-header {
            background-color: rgb(0,168,232);
            color: white;
            padding: 1rem;
            border-radius: 0.25rem 0.25rem 0 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
            border: 1px solid #ddd; /* Border around the table */
        }

        th,
        td {
            border: 1px solid #ddd; /* Border around cells */
            padding: 12px; /* Padding between cells vertically */
            text-align: left;
        }

        th {
            background-color: rgb(0,168,232);
            color: white;
        }

        tbody tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tbody tr:hover {
            background-color: #ddd;
        }

        tfoot {
            background-color: rgb(0,168,232);
            color: white;
        }

        .headerColor{
            color: #1a82e2;
        }
    

        img {
            height: auto;
            line-height: 100%;
            text-decoration: none;
            border: 0;
            outline: none;
        }
    </style>
</head>

<body style="background-color: #e9ecef;">
    <!-- end preheader -->

    <div class="card" style="padding: 10px 20px; ">
        <div class="card-header">
            <h4 style="color: white">{{\App\CentralLogics\translate(' Dear '.$customer->f_name.' '.$customer->l_name.' your Order with ID ( '. $order->id .' ) has been placed.')}}</h4>
            <p style="color: white">Thank you for choosing us! Your order has been successfully received and is now being processed.</p>
            <h3 style="color: white">You can check your order details below:</h3>
        </div>
  
        <table >
            <thead>
                <tr>
                    <th>Product name</th>
                    <th>Quantity</th>
                    <th>Price </th>
                    <th>Total</th>

                </tr>
            </thead>
            <tbody>
                @foreach( $details as $detail)
                <?php
                $product = json_decode($detail->product_details);
                ?>
                <tr>
                    <td>{{ $product->name}}</td>
                    <td>{{$detail->quantity}}</td>
                    <td>{{$detail->price}}</td>
                    <td>ETB {{$detail->quantity * $detail->price}}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
            </tfoot>
        </table>

       @extends('email-templates.template_footer')

    </div>

</body>

</html>