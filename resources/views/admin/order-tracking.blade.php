@extends('layouts.admin')

@section('content')
    <style>
        .table-striped>tbody>tr:nth-of-type(odd) {
            background-color: #f9f9f9;
        }

        .table thead th {
            background-color: #007bff;
            color: white;
        }

        .badge {
            font-size: 14px;
            padding: 5px 10px;
        }

        .wg-box {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .wg-box h5 {
            margin-bottom: 15px;
            font-size: 18px;
            font-weight: bold;
        }

        .breadcrumbs {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .breadcrumbs li {
            display: inline;
            font-size: 14px;
        }

        .breadcrumbs li a {
            text-decoration: none;
            color: #007bff;
        }

        .breadcrumbs li i {
            margin: 0 5px;
            color: #6c757d;
        }

        .image img {
            max-width: 50px;
            border-radius: 5px;
        }

        .name a {
            color: #007bff;
            text-decoration: none;
            font-weight: bold;
        }

        .name a:hover {
            text-decoration: underline;
        }
    </style>

    <div class="main-content-inner">
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                <h3>Order Tracking</h3>
                <ul class="breadcrumbs">
                    <li><a href="{{ route('admin.index') }}">Dashboard</a></li>
                    <li><i class="icon-chevron-right"></i></li>
                    <li>Order Tracking</li>
                </ul>
            </div>

            <!-- Order Details -->
            <div class="wg-box">
                <h5>Order Details</h5>
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th>Order No</th>
                            <td>{{ $order->id }}</td>
                            <th>Order Date</th>
                            <td>{{ $order->created_at->format('d M Y') }}</td>
                            <th>Status</th>
                            <td>
                                @if ($order->status == 'delivered')
                                    <span class="badge bg-success">Delivered</span>
                                @elseif($order->status == 'canceled')
                                    <span class="badge bg-danger">Canceled</span>
                                @else
                                    <span class="badge bg-warning">Ordered</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Customer Name</th>
                            <td>{{ $order->name }}</td>
                            <th>Mobile</th>
                            <td>{{ $order->phone }}</td>
                            <th>Zip Code</th>
                            <td>{{ $order->zip }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Ordered Items -->
            <div class="wg-box">
                <h5>Ordered Items</h5>
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>SKU</th>
                            <th>Category</th>
                            <th>Brand</th>
                            <th>Options</th>
                            <th>Return Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($orderItems as $item)
                            <tr>
                                <td>
                                    <div class="flex items-center gap10">
                                        <div class="image">
                                            <img src="{{ asset('uploads/products/thumbnails/' . $item->product->image) }}"
                                                 alt="{{ $item->product->name }}">
                                        </div>
                                        <div class="name">
                                            <a href="{{ route('shop.product.details', ['product_slug' => $item->product->slug]) }}"
                                               target="_blank">{{ $item->product->name }}</a>
                                        </div>
                                    </div>
                                </td>
                                <td>Rp{{ number_format($item->price, 0, ',', '.') }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>{{ $item->product->SKU }}</td>
                                <td>{{ $item->product->category->name }}</td>
                                <td>{{ $item->product->brand->name }}</td>
                                <td>{{ $item->options }}</td>
                                <td>{{ $item->rstatus == 0 ? 'No' : 'Yes' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8">No items found for this order.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Shipping Address -->
            <div class="wg-box">
                <h5>Shipping Address</h5>
                <p>{{ $order->name }}</p>
                <p>{{ $order->address }}, {{ $order->locality }}</p>
                <p>{{ $order->city }}, {{ $order->country }}</p>
                <p>Landmark: {{ $order->landmark }}</p>
                <p>Zip Code: {{ $order->zip }}</p>
                <p>Mobile: {{ $order->phone }}</p>
            </div>

            <!-- Transactions -->
            <div class="wg-box">
                <h5>Transaction Details</h5>
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th>Subtotal</th>
                            <td>Rp{{ number_format($order->subtotal, 0, ',', '.') }}</td>
                            <th>Tax</th>
                            <td>Rp{{ number_format($order->tax, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <th>Discount</th>
                            <td>Rp{{ number_format($order->discount, 0, ',', '.') }}</td>
                            <th>Total</th>
                            <td>Rp{{ number_format($order->total, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <th>Payment Mode</th>
                            <td>{{ ucfirst($transaction->mode ?? 'N/A') }}</td>
                            <th>Status</th>
                            <td>
                                @if ($transaction->status == 'approved')
                                    <span class="badge bg-success">Approved</span>
                                @elseif($transaction->status == 'declined')
                                    <span class="badge bg-danger">Declined</span>
                                @elseif($transaction->status == 'refunded')
                                    <span class="badge bg-secondary">Refunded</span>
                                @else
                                    <span class="badge bg-warning">Pending</span>
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
