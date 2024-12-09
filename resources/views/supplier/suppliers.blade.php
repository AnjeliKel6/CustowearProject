@extends('layouts.supplier')
@section('content')

<style>
    .table-striped th:nth-child(2), .table-striped td:nth-child(2) {
    width: 250px;
    padding-bottom: 5px;
}
    /* Style khusus untuk tabel */
    .custom-table img {
        width: 90px; /* Ukuran gambar */
        height: auto;
        display: block;
        margin: 0 auto; /* Pusatkan gambar secara horizontal */
        vertical-align: middle;
    }

    .custom-table td, .custom-table th {
        vertical-align: middle; /* Pusatkan konten secara vertikal */
        text-align: center; /* Pusatkan teks secara horizontal */
        font-size: 16px; /* Ukuran font */
    }

    .custom-table .list-icon-function .item i {
        font-size: 24px; /* Perbesar ukuran ikon */
    }

    .custom-table .list-icon-function .item {
        margin-right: 30px; /* Tambahkan jarak antar ikon */
    }

    .custom-table .list-icon-function {
        display: flex;
        justify-content: center; /* Pusatkan ikon secara horizontal */
        align-items: center; /* Pusatkan ikon secara vertikal */
    }

    .custom-table .name a {
        font-size: 16px; /* Ukuran font nama */
        font-weight: bold; /* Gaya tebal */
    }

    /* Kolom pertama (#) lebih kecil */
    .custom-table th:first-child, .custom-table td:first-child {
        width: 50px; /* Lebar kolom pertama */
    }
</style>

    <div class="main-content-inner">
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                <h3>Suppliers</h3>
                <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                    <li>
                        <a href="{{ route('supplier.index') }}">
                            <div class="text-tiny">Dashboard</div>
                        </a>
                    </li>
                    <li>
                        <i class="icon-chevron-right"></i>
                    </li>
                    <li>
                        <div class="text-tiny">All Supplier</div>
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
                    <a class="tf-button style-1 w208" href="{{ route('supplier.suppliers.add') }}"><i class="icon-plus"></i>Add
                        new user</a>
                </div>
                <div class="wg-table table-all-user">
                    <div class="table-responsive">
                        @if (Session::has('status'))
                            <p class="alert alert-success">{{ Session::get('status') }}</p>
                        @endif
                        <table class="table table-striped table-bordered text-center custom-table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($suppliers as $supplier)
                                <tr>
                                    <td>{{ ($suppliers->currentPage() - 1) * $suppliers->perPage() + $loop->iteration }}</td>
                                        <td>{{ $supplier->name }}</td>
                                        <td>{{ $supplier->email }}</td>
                                        <td>{{ $supplier->phone }}</td>
                                        <td>
                                            <div class="list-icon-function">
                                                <a href="#" target="_blank">
                                                    <div class="item eye">
                                                        <i class="icon-eye"></i>
                                                    </div>
                                                </a>
                                                <a href="{{ route('supplier.suppliers.edit', ['id' => $supplier->id]) }}">
                                                    <div class="item edit">
                                                        <i class="icon-edit-3"></i>
                                                    </div>
                                                </a>
                                                <div class="list-icon-function">
                                                    <form action="{{ route('supplier.suppliers.delete',['id' => $supplier->id]) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <div class="item text-danger delete">
                                                            <i class="icon-trash-2"></i>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
                <div class="divider"></div>
                <div class="flex items-center justify-between flex-wrap gap10 wgp-pagination">
                    {{ $suppliers->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(function() {
            $('.delete').on('click', function(e) {
                e.preventDefault();
                var form = $(this).closest('form');
                swal({
                    title: "Are you sure?",
                    text: "You want to delete this record?",
                    type: "warning",
                    buttons: ["No", "Yes"],
                    confirmButtonColor: '#dc3545'
                }).then(function(result) {
                    if (result) {
                        form.submit();
                    }
                });
            });
        });
    </script>
@endpush
