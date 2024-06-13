@include('templates.partials.header')

<body>
    <div class="container-scroller">
        <div class="container-fluid page-body-wrapper full-page-wrapper">
            <div class="content-wrapper d-flex align-items-center auth">
                <div class="row flex-grow">
                    <div class="col-lg-4 mx-auto">
                        <div class="auth-form-light text-left p-5">
                            <div class="brand-logo">
                                <img src="https://demo.bootstrapdash.com/purple-new/themes/assets/images/logo.svg">
                            </div>
                            <h4>New here?</h4>
                            <h6 class="font-weight-light">Signing up is easy. It only takes a few steps</h6>
                            <form class="pt-3" method="POST" action="{{ route('doRegist') }}">
                                @csrf
                                <div class="form-group">
                                    <input type="name"
                                        class="form-control form-control-lg @error('name') is-invalid @enderror"
                                        id="exampleInputname1" placeholder="Nama Lengkap" name="name"
                                        value="{{ old('name') }}">
                                    @error('name')
                                        <div id="validationServer03Feedback" class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <input type="username"
                                        class="form-control form-control-lg @error('username') is-invalid @enderror"
                                        id="exampleInputusername1" placeholder="Username" name="username"
                                        value="{{ old('username') }}">
                                    @error('username')
                                        <div id="validationServer03Feedback" class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <input type="password"
                                        class="form-control form-control-lg @error('password') is-invalid @enderror"
                                        id="exampleInputPassword1" placeholder="Password" name="password">
                                    @error('password')
                                        <div id="validationServer03Feedback" class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <input type="password"
                                        class="form-control form-control-lg @error('password_confirmation') is-invalid @enderror"
                                        id="exampleInputPassword1" placeholder="Konfirmasi Password"
                                        name="password_confirmation">
                                    @error('password_confirmation')
                                        <div id="validationServer03Feedback" class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="mt-3 d-grid gap-2">
                                    <button type="submit"
                                        class="btn btn-block btn-gradient-primary btn-lg font-weight-medium auth-form-btn">SIGN
                                        UP</button>
                                </div>
                                <div class="text-center mt-4 font-weight-light"> Sudah Punya Akun? <a
                                        href="{{ route('login') }}" class="text-primary">Masuk</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- content-wrapper ends -->
        </div>
        <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->
    @include('templates.partials.script')
    <!-- endinject -->
</body>
