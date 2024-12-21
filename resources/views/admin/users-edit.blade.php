@extends('layouts.admin')
@section('content')
    <div class="main-content-inner">
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                <h3>Reset User Password</h3>
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
                        <a href="{{ route('admin.users') }}">
                            <div class="text-tiny">Users</div>
                        </a>
                    </li>
                    <li>
                        <i class="icon-chevron-right"></i>
                    </li>
                    <li>
                        <div class="text-tiny">Reset Password</div>
                    </li>
                </ul>
            </div>
            <div class="wg-box">
                <form class="form-reset-password form-style-1" method="POST" action="{{ route('admin.users.update') }}">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id" value="{{ $users->id }}" />

                    <fieldset class="password">
                        <div class="body-title">New Password <span class="tf-color-1">*</span></div>
                        <input class="flex-grow" type="password" placeholder="New Password" name="password" tabindex="0"
                            aria-required="true" required="">
                    </fieldset>
                    @error('password')
                        <span class="alert alert-danger text-center">{{ $message }}</span>
                    @enderror

                    <fieldset class="password_confirmation">
                        <div class="body-title">Confirm Password <span class="tf-color-1">*</span></div>
                        <input class="flex-grow" type="password" placeholder="Confirm Password" name="password_confirmation" tabindex="0"
                            aria-required="true" required="">
                    </fieldset>
                    @error('password_confirmation')
                        <span class="alert alert-danger text-center">{{ $message }}</span>
                    @enderror

                    <div class="bot">
                        <div></div>
                        <button class="tf-button w208" type="submit">Reset Password</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
