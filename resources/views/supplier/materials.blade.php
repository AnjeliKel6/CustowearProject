@extends('layouts.supplier')
@section('content')
    <style>
        .table-striped th:nth-child(2), .table-striped td:nth-child(2) {
            width: 250px;
            padding-bottom: 5px;
        }
        /* Style khusus untuk tabel */
        .custom-table img {
            width: 90px;
            /* Ukuran gambar */
            height: auto;
            display: block;
            margin: 0 auto;
            /* Pusatkan gambar secara horizontal */
            vertical-align: middle;
        }
        .custom-table td,
        .custom-table th {
            vertical-align: middle;
            /* Pusatkan konten secara vertikal */
            text-align: center;
            /* Pusatkan teks secara horizontal */
            font-size: 16px;
            /* Ukuran font */
        }
        .custom-table .list-icon-function .item {
            margin-right: 30px;
            padding: 0ex;
            /* Tambahkan jarak antar ikon */
        }
        .custom-table .list-icon-function .item i {
            font-size: 24px;
            /* Perbesar ukuran ikon */
        }
        .custom-table .list-icon-function {
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .custom-table .name a {
            font-size: 16px;
            /* Ukuran font nama */
            font-weight: bold;
            /* Gaya tebal */
        }

        /* Kolom pertama (#) lebih kecil */
        .custom-table th:first-child,
        .custom-table td:first-child {
            width: 50px;
            /* Lebar kolom pertama */
        }
    </style>

    <div class="main-content-inner">
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                <h3>All Materials</h3>
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
                        <div class="text-tiny">All Materials</div>
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
                    <a class="tf-button style-1 w208" href="{{ route('supplier.material.add') }}"><i
                            class="icon-plus"></i>Add
                        new</a>
                </div>
                <div class="table-responsive">
                    @if (Session::has('status'))
                        <p class="alert alert-success">{{ Session::get('status') }}</p>
                    @endif
                    <table class="table table-striped table-bordered text-center custom-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Image</th>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Quantity</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($materials as $material)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <img src="{{ asset('uploads/materials') }}/{{ $material->image }}"
                                            alt="{{ $material->name }}">
                                    </td>
                                    <td>
                                        <a href="#" class="body-title-2">{{ $material->name }}</a>
                                    </td>
                                    <td>{{ $material->description }}</td>
                                    <td>{{ $material->quantity }}</td>
                                    <td>
                                        <div class="list-icon-function">
                                            {{--  <a href="#" target="_blank">
                                                <div class="item eye">
                                                    <i class="icon-eye"></i>
                                                </div>
                                            </a>  --}}
                                            <a href="{{ route('supplier.material.edit', ['id' => $material->id]) }}">
                                                <div class="item edit">
                                                    <i class="icon-edit-3"></i>
                                                </div>
                                            </a>
                                            <form action="{{ route('supplier.material.delete', ['id' => $material->id]) }}"
                                                method="POST">
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

                    {{ $materials->links('pagination::bootstrap-5') }}
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
