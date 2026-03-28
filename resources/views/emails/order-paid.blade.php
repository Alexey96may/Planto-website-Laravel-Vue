<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; color: #333; line-height: 1.6; }
        .container { width: 100%; max-width: 600px; margin: 0 auto; border: 1px solid #e0e0e0; border-radius: 8px; overflow: hidden; }
        .header { background-color: #1a1a1a; color: #ffffff; padding: 20px; text-align: center; }
        .content { padding: 30px; }
        .order-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .order-table th { text-align: left; border-bottom: 2px solid #eee; padding: 10px; }
        .order-table td { padding: 10px; border-bottom: 1px solid #eee; }
        .footer { background-color: #f9f9f9; padding: 20px; text-align: center; font-size: 12px; color: #777; }
        .total { font-weight: bold; font-size: 18px; color: #2d5a27; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Planto</h1>
        </div>
        <div class="content">
            <h2>Hello, {{ $order->customer_name }}!</h2>
            <p>Thank you for your order! We've received your payment and are now preparing your plants for shipment.</p>

            <p><strong>Order number:</strong> #{{ $order->id }}</p>
            <p><strong>Delivery address:</strong> {{ $order->delivery_address }}</p>

            <table class="order-table">
                <thead>
                    <tr>
                        <th>Product</th> 
                        <th>Quantity</th> 
                        <th>Price</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                        <tr>
                            <td>{{ $item->product_name }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>${{ number_format($item->price, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <p class="total" style="text-align: right;">Total: ${{ number_format($order->total_price, 2) }}</p>
        </div>
        <div class="footer">
            <p>© {{ date('Y') }} Planto. With love for nature.</p>
            <p>If you have any questions, just reply to this email.</p>
        </div>
    </div>
</body>
</html>