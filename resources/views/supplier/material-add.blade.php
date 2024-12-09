@extends('layouts.supplier')
@section('content')
    <div class="main-content-inner">
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                <h3>Material Information</h3>
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
                        <a href="{{ route('supplier.materials') }}">
                            <div class="text-tiny">Materials</div>
                        </a>
                    </li>
                    <li>
                        <i class="icon-chevron-right"></i>
                    </li>
                    <li>
                        <div class="text-tiny">New Material</div>
                    </li>
                </ul>
            </div>

            <!-- Form for adding new material -->
            <div class="wg-box">
                <form class="form-new-product form-style-1" action="{{ route('supplier.material.store') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf

                    <!-- Image Upload -->
                    <fieldset>
                        <div class="body-title">Upload Image <span class="tf-color-1">*</span></div>
                        <div class="upload-image flex-grow">
                            <div id="imgpreview" class="item" style="display: none;">
                                <img src="#" class="effect8" alt="Preview">
                            </div>
                            <div id="upload-file" class="item up-load">
                                <label class="uploadfile" for="myFile">
                                    <span class="icon">
                                        <i class="icon-upload-cloud"></i>
                                    </span>
                                    <span class="body-text">Drop your image here or select <span class="tf-color">click to
                                            browse</span></span>
                                    <input type="file" id="myFile" name="image" accept="image/*">
                                </label>
                            </div>
                        </div>
                        @error('image')
                            <span class="alert alert-danger text-center d-block mt-2">{{ $message }}</span>
                        @enderror
                    </fieldset>

                    <!-- Material Name -->
                    <fieldset class="name">
                        <div class="body-title">Material Name <span class="tf-color-1">*</span></div>
                        <input class="flex-grow" type="text" placeholder="Material name" name="name" tabindex="0"
                            value="{{ old('name') }}" aria-required="true" required>
                        @error('name')
                            <span class="alert alert-danger text-center d-block mt-2">{{ $message }}</span>
                        @enderror
                    </fieldset>

                    <!-- Description -->
                    <fieldset class="description">
                        <div class="body-title">Description <span class="tf-color-1">*</span></div>
                        <input class="flex-grow" type="text" placeholder="Description" name="description" tabindex="0"
                            value="{{ old('description') }}" aria-required="true" required>
                        @error('description')
                            <span class="alert alert-danger text-center d-block mt-2">{{ $message }}</span>
                        @enderror
                    </fieldset>

                    <!-- Quantity -->
                    <fieldset class="quantity">
                        <div class="body-title mb-10">Quantity <span class="tf-color-1">*</span></div>
                        <input class="mb-10" type="number" placeholder="Quantity" name="quantity" tabindex="0"
                            value="{{ old('quantity') }}" aria-required="true" required>
                        @error('quantity')
                            <span class="alert alert-danger text-center d-block mt-2">{{ $message }}</span>
                        @enderror
                    </fieldset>

                    <!-- Submit Button -->
                    <div class="bot">
                        <div></div>
                        <button class="tf-button w208" type="submit">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(function() {
            // Preview uploaded image
            $("#myFile").on("change", function(e) {
                const [file] = this.files;
                if (file) {
                    $("#imgpreview img").attr("src", URL.createObjectURL(file));
                    $("#imgpreview").show();
                }
            });

            {{--  // Generate slug from name (if needed)
            $("input[name='name']").on("input", function() {
                $("input[name='slug']").val(stringToSlug($(this).val()));
            });  --}}
        });

        // Convert text to slug format
        function stringToSlug(text) {
            return text.toLowerCase()
                .replace(/[^\w\s]/g, '') // Remove special characters
                .replace(/\s+/g, '-'); // Replace spaces with dashes
        }
    </script>
@endpush
