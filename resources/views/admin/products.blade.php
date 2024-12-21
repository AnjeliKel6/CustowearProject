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
        font-size: 15px; /* Ukuran font default untuk tabel */
        border: 1px solid #ddd;
    }

    .wg-table .table thead th {
        font-size: 16px; /* Ukuran font header tabel */
        font-weight: bold;
        text-align: center;
        background-color: #f8f9fa; /* Warna latar belakang header */
    }

    .wg-table .table tbody td {
        font-size: 15px; /* Ukuran font isi tabel */
        text-align: center;
        vertical-align: middle;
    }

    .wg-table .table tbody tr:hover {
        background-color: #f1f1f1; /* Warna latar belakang saat di-hover */
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
            <h3>All Products</h3>
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
                    <div class="text-tiny">All Products</div>
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
                <a class="tf-button style-1 w208" href="{{ route('admin.product.add') }}"><i
                        class="icon-plus"></i>Add new</a>
            </div>
            <div class="table-responsive">
                @if(Session::has('status'))
                    <p class="alert alert-success">{{ Session::get('status') }}</p>
                @endif
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Price</th>
                            <th>SalePrice</th>
                            <th>SKU</th>
                            <th>Category</th>
                            <th>Brand</th>
                            <th>Featured</th>
                            <th>Stock</th>
                            <th>Quantity</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $product )
                        <tr>
                            <td>{{ $product->id }}</td>
                            <td class="pname">
                                <div class="image">
                                    <img src="{{ asset('uploads/products/thumbnails') }}/{{ $product->image }}" alt="{{ $product->name }}" class="image">
                                </div>
                                <div class="name">
                                    <a href="#" class="body-title-2">{{ $product->name }}</a>
                                    <div class="text-tiny mt-3">{{ $product->slug }}</div>
                                </div>
                            </td>
                            <td>Rp.{{ number_format($product->regular_price, 2) }}</td>
                            <td>Rp.{{ number_format($product->sale_price, 2) }}</td>
                            <td>{{ $product->SKU }}</td>
                            <td>{{ $product->category->name }}</td>
                            <td>{{ $product->brand->name }}</td>
                            <td>{{ $product->featured == 0 ? "No":"Yes" }}</td>
                            <td>{{ $product->stock_status }}</td>
                            <td>{{ $product->quantity }}</td>
                            <td>
                                <div class="list-icon-function">
                                    <a href="#" target="_blank">
                                        <div class="item eye">
                                            <i class="icon-eye"></i>
                                        </div>
                                    </a>
                                    <a href="{{ route('admin.product.edit',['id'=>$product->id]) }}">
                                        <div class="item edit">
                                            <i class="icon-edit-3"></i>
                                        </div>
                                    </a>
                                    <form action="{{ route('admin.product.delete',['id'=>$product->id]) }}" method="POST">
                                        @csrf
                                            @method('DELETE')
                                        <div class="item text-danger delete">
                                            <i class="icon-trash-2"></i>
                                        </div>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="divider"></div>
            <div class="flex items-center justify-between flex-wrap gap10 wgp-pagination">

                {{ $products->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(function(){
        $('.delete').on('click',function(e){
            e.preventDefault();
            var form = $(this).closest('form');
            swal({
                title: "Are you sure?",
                text: "You want to delete this record?",
                type:"warning",
                buttons:["No","Yes"],
                confirmButtonColor:'#dc3545'
            }).then(function(result){
                if(result){
                    form.submit();
                }
            });
        });
    });
</script>

@endpush
