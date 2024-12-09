@extends('layouts.admin')
@section('content')
<div class="main-content-inner">
    <div class="main-content-wrap">
        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>User infomation</h3>
            <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                <li>
                    <a href="{{route('admin.index')}}">
                        <div class="text-tiny">Dashboard</div>
                    </a>
                </li>
                <li>
                    <i class="icon-chevron-right"></i>
                </li>
                <li>
                    <a href="{{route('admin.users')}}">
                        <div class="text-tiny">Users</div>
                    </a>
                </li>
                <li>
                    <i class="icon-chevron-right"></i>
                </li>
                <li>
                    <div class="text-tiny">New User</div>
                </li>
            </ul>
        </div>
        <div class="wg-box">
            <form class="form-new-product form-style-1" method="POST" action="{{route('admin.users.store')}}">
                @csrf
                <fieldset class="name">
                    <div class="body-title">Name <span class="tf-color-1">*</span></div>
                    <input class="flex-grow" type="text" placeholder="Full Name" name="name" value="{{ old('name', '') }}" required>
                </fieldset>
                @error('name') <span class="alert alert-danger text-center">{{ $message }}</span> @enderror

                <fieldset class="utype">
                    <div class="body-title">User Type</div>
                    <div class="select flex-grow">
                        <select class="" name="utype" required>
                            <option value="">Select</option>
                            <option value="USR" {{ old('utype') == 'USR' ? 'selected' : '' }}>User</option>
                            <option value="ADM" {{ old('utype') == 'ADM' ? 'selected' : '' }}>Admin</option>
                            <option value="VND" {{ old('utype') == 'VND' ? 'selected' : '' }}>Vendor</option>
                        </select>
                    </div>
                </fieldset>
                @error('utype') <span class="alert alert-danger text-center">{{ $message }}</span> @enderror

                <fieldset class="email">
                    <div class="body-title">Email <span class="tf-color-1">*</span></div>
                    <input class="flex-grow" type="email" placeholder="Email" name="email" value="{{ old('email', '') }}" required>
                </fieldset>
                @error('email') <span class="alert alert-danger text-center">{{ $message }}</span> @enderror

                <fieldset class="password">
                    <div class="body-title">Password <span class="tf-color-1">*</span></div>
                    <input class="flex-grow" type="password" placeholder="Password" name="password" tabindex="0" aria-required="true" required="">
                </fieldset>
                @error('password') <span class="alert alert-danger text-center">{{ $message }}</span> @enderror

                <fieldset class="mobile">
                    <div class="body-title">Phone Number <span class="tf-color-1">*</span></div>
                    <input class="flex-grow" type="text" placeholder="Phone Number" name="mobile" value="{{ old('mobile', '') }}" required="">
                </fieldset>
                @error('mobile') <span class="alert alert-danger text-center">{{ $message }}</span> @enderror

                <div class="bot">
                    <div></div>
                    <button class="tf-button w208" type="submit">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

{{--  @push('scripts')
    <script>
        $(function(){
            $("#myFile").on("change",function(e){
                const photoInp = $("#myFile");
                const [file] = this.files;
                if(file)
                {
                   $("#imgpreview img").attr('src',URL.createObjectURL(file));
                   $("#imgpreview").show();
                }
            });

            $("input[name='name']").on("change",function(){
                $("input[name='slug']").val(StringToSlug($(this).val()));
            });
        });

        function StringToSlug(Text)
        {
            return Text.toLowerCase()
            .replace(/[^\w ]+/g,"")
            .replace(/ +/g,"-");
        }
    </script>
@endpush  --}}
