@extends('layouts.admin')
@section('content')
    <style>
        /* Tombol Ekspor PDF */
        .export-button {
            text-align: right;
            margin-bottom: 15px;
        }

        .export-button .btn-danger {
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 7px;
        }

        /* Tabel */
        .wg-table .table {
            font-size: 15px;
            /* Ukuran font default untuk tabel */
            border: 1px solid #ddd;
        }

        .wg-table .table thead th {
            font-size: 16px;
            /* Ukuran font header tabel */
            font-weight: bold;
            text-align: center;
            background-color: #f8f9fa;
            /* Warna latar belakang header */
        }

        .wg-table .table tbody td {
            font-size: 15px;
            /* Ukuran font isi tabel */
            text-align: center;
            vertical-align: middle;
        }

        .wg-table .table tbody tr:hover {
            background-color: #f1f1f1;
            /* Warna latar belakang saat di-hover */
        }

        .table-striped th:nth-child(2),
        .table-striped td:nth-child(2) {
            width: 250px;
            padding-bottom: 5px;
        }

        /* Badge Status */
        .badge {
            font-size: 14px;
            padding: 5px 10px;
            border-radius: 5px;
            display: inline-block;
        }

        .badge.bg-success {
            background-color: #28a745;
            color: white;
        }

        .badge.bg-danger {
            background-color: #dc3545;
            color: white;
        }

        .badge.bg-warning {
            background-color: #ffc107;
            color: black;
        }

        /* Responsiveness */
        .table-responsive {
            overflow-x: auto;
        }

        @media (max-width: 768px) {
            .export-button {
                text-align: center;
            }

            .export-button .btn-danger {
                width: 100%;
            }
        }
    </style>
    <div class="main-content-inner">
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                <h3>Orders</h3>
                <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                    <li>
                        <a href="{{ route('admin.index') }}">
                            <div class="text-tiny">Dashboard</div>
                        </a>
                    </li>
                    <li>
                        <i class="icon-chevron-right"></i>
                    </li>
                    <li>
                        <div class="text-tiny">Orders</div>
                    </li>
                </ul>
            </div>

            <div class="wg-box">
                <div class="flex items-center justify-between gap10 flex-wrap">
                    <div class="wg-filter flex-grow">
                        <form class="form-search">
                            <fieldset class="name">
                                <input type="text" placeholder="Search here..." class="" name="name"
                                    tabindex="2" value="" aria-required="true" required="">
                            </fieldset>
                            <div class="button-submit">
                                <button class="" type="submit"><i class="icon-search"></i></button>
                            </div>
                        </form>
                    </div>
                    <div class="export-button">
                        <a href="{{ route('admin.export-pdf') }}" class="btn btn-danger">Ekspor PDF</a>
                    </div>
                </div>
                <div class="wg-table table-all-user">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th style="width:70px">OrderNo</th>
                                    <th class="text-center">Name</th>
                                    <th class="text-center">Phone</th>
                                    <th class="text-center">Subtotal</th>
                                    <th class="text-center">Tax</th>
                                    <th class="text-center">Total</th>

                                    <th class="text-center">Status</th>
                                    <th class="text-center">Order Date</th>
                                    <th class="text-center">Total Items</th>
                                    <th class="text-center">Delivered On</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($orders as $order)
                                    <tr>
                                        <td class="text-center">{{ $order->id }}</td>
                                        <td class="text-center">{{ $order->name }}</td>
                                        <td class="text-center">{{ $order->phone }}</td>
                                        <td class="text-center">Rp{{ $order->subtotal }}</td>
                                        <td class="text-center">Rp{{ $order->tax }}</td>
                                        <td class="text-center">Rp{{ $order->total }}</td>
                                        <td class="text-center">
                                            @if ($order->status == 'delivered')
                                                <span class="badge bg-success">Delivered</span>
                                            @elseif($order->status == 'canceled')
                                                <span class="badge bg-danger">Canceled</span>
                                            @else
                                                <span class="badge bg-warning">Ordered</span>
                                            @endif
                                        </td>
                                        <td class="text-center">{{ $order->created_at }}</td>
                                        <td class="text-center">{{ $order->orderItems->count() }}</td>
                                        <td class="text-center">{{ $order->delivered_date }}</td>
                                        <td class="text-center">
                                            <a href="{{ route('admin.order.details', ['order_id' => $order->id]) }}">
                                                <div class="list-icon-function view-icon">
                                                    <div class="item eye">
                                                        <i class="icon-eye"></i>
                                                    </div>
                                                </div>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="divider"></div>
                <div class="flex items-center justify-between flex-wrap gap10 wgp-pagination">
                    {{ $orders->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
@endsection
