<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Register - NiceAdmin</title>

    <link href="{{ asset('assets/img/favicon.png') }}" rel="icon">
    <link href="{{ asset('assets/img/apple-touch-icon.png') }}" rel="apple-touch-icon">
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
        rel="stylesheet">
    <link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/boxicons/css/boxicons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">

    <style>
        /* ── Image Preview ── */
        #imagePreviewWrapper {
            display: none;
            margin-top: 10px;
            text-align: center;
        }

        #imagePreview {
            width: 90px;
            height: 90px;
            object-fit: cover;
            border-radius: 50%;
            border: 3px solid #4154f1;
        }

        /* ── Experience smooth toggle ── */
        #experienceWrapper {
            overflow: hidden;
            transition: max-height 0.35s ease, opacity 0.35s ease;
            max-height: 0;
            opacity: 0;
        }

        #experienceWrapper.show {
            max-height: 120px;
            opacity: 1;
        }

        /* ── Toast custom style ── */
        .toast-container .toast {
            min-width: 300px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
        }
    </style>
</head>

<body>
    <main>
        <div class="container">
            <section
                class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">

                            <div class="d-flex justify-content-center py-4">
                                <a href="{{ route('register') }}" class="logo d-flex align-items-center w-auto">
                                    <img src="{{ asset('assets/img/logo.png') }}" alt="">
                                    <span class="d-none d-lg-block">NiceAdmin</span>
                                </a>
                            </div>

                            <div class="card mb-3">
                                <div class="card-body">

                                    <div class="pt-4 pb-2">
                                        <h5 class="card-title text-center pb-0 fs-4">Create an Account</h5>
                                    </div>

                                    <form action="{{ route('register') }}" method="POST"
                                        class="row g-3 needs-validation" novalidate enctype="multipart/form-data">
                                        @csrf

                                        {{-- Email --}}
                                        <div class="col-12">
                                            <label for="yourEmail" class="form-label">Your Email</label>
                                            <input type="email" name="email"
                                                class="form-control @error('email') is-invalid @enderror"
                                                id="yourEmail" value="{{ old('email') }}" required>
                                            @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @else
                                                <div class="invalid-feedback">Please enter a valid Email address!</div>
                                            @enderror
                                        </div>

                                        {{-- Name --}}
                                        <div class="col-12">
                                            <label for="yourName" class="form-label">Your Name</label>
                                            <input type="text" name="name"
                                                class="form-control @error('name') is-invalid @enderror"
                                                id="yourName" value="{{ old('name') }}" required>
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @else
                                                <div class="invalid-feedback">Please enter your name!</div>
                                            @enderror
                                        </div>

                                        {{-- Phone --}}
                                        <div class="col-12">
                                            <label for="phone" class="form-label">Phone Number</label>
                                            <input type="text" name="phone"
                                                class="form-control @error('phone') is-invalid @enderror"
                                                id="phone" value="{{ old('phone') }}" required>
                                            @error('phone')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @else
                                                <div class="invalid-feedback">Please enter your phone number!</div>
                                            @enderror
                                        </div>

                                        {{-- Profile Image --}}
                                        <div class="col-12">
                                            <label for="image" class="form-label">Profile Picture</label>
                                            <input type="file" name="image"
                                                class="form-control @error('image') is-invalid @enderror"
                                                id="image" accept="image/*">
                                            @error('image')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @else
                                                <div class="invalid-feedback">Please upload a valid image!</div>
                                            @enderror

                                            {{-- Preview --}}
                                            <div id="imagePreviewWrapper">
                                                <img id="imagePreview" src="#" alt="Preview">
                                            </div>
                                        </div>

                                        {{-- Password --}}
                                        <div class="col-12">
                                            <label for="pass" class="form-label">Password</label>
                                            <input type="password" name="password"
                                                class="form-control @error('password') is-invalid @enderror"
                                                id="pass" required>
                                            @error('password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @else
                                                <div class="invalid-feedback">Please enter your password!</div>
                                            @enderror
                                        </div>

                                        {{-- Confirm Password --}}
                                        <div class="col-12">
                                            <label for="comPassword" class="form-label">Confirm Password</label>
                                            <input type="password" name="password_confirmation"
                                                class="form-control" id="comPassword" required>
                                            <div class="invalid-feedback">Please confirm your password!</div>
                                        </div>



                                        {{-- Experience (Instructor only) --}}
                                        <div class="col-12" id="experienceWrapper">
                                            <label for="experience" class="form-label">Experience</label>
                                            <input type="text" name="experience"
                                                class="form-control @error('experience') is-invalid @enderror"
                                                id="experience" value="{{ old('experience') }}">
                                            @error('experience')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @else
                                                <div class="invalid-feedback">Please enter your experience!</div>
                                            @enderror
                                        </div>

                                        {{-- Remember Me --}}
                                        <div class="col-12">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="remember"
                                                    id="rememberMe" value="1"
                                                    {{ old('remember') ? 'checked' : '' }}>
                                                <label class="form-check-label" for="rememberMe">
                                                    Remember me
                                                </label>
                                            </div>
                                        </div>

                                        {{-- Submit --}}
                                        <div class="col-12">
                                            <button class="btn btn-primary w-100" type="submit">
                                                Create Account
                                            </button>
                                        </div>
                                        <div class="col-12">
                                        <p class="small mb-0">
                                            Already have an account?
                                            <a href="{{ route('login') }}">Login</a>
                                        </p>
                                    </div>

                                    </form>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </section>
        </div>
    </main>

    {{-- ✅ Toast Container --}}
    <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 9999">

        {{-- Success --}}
        @if (session('success'))
            <div id="toastSuccess" class="toast align-items-center text-white bg-success border-0" role="alert"
                aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        <i class="bi bi-check-circle-fill me-2"></i>
                        {{ session('success') }}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto"
                        data-bs-dismiss="toast"></button>
                </div>
            </div>
        @endif

        {{-- Error --}}
        @if (session('error'))
            <div id="toastError" class="toast align-items-center text-white bg-danger border-0" role="alert"
                aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        <i class="bi bi-x-circle-fill me-2"></i>
                        {{ session('error') }}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto"
                        data-bs-dismiss="toast"></button>
                </div>
            </div>
        @endif

        {{-- Validation Errors --}}
        @if ($errors->any())
            <div id="toastValidation" class="toast align-items-center text-white bg-warning border-0" role="alert"
                aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        {{ $errors->first() }}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto"
                        data-bs-dismiss="toast"></button>
                </div>
            </div>
        @endif

    </div>

    <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>

    <script>


        /* ══════════════════════════════════════
           3) Image Preview
        ══════════════════════════════════════ */
        const imageInput          = document.getElementById('image');
        const imagePreview        = document.getElementById('imagePreview');
        const imagePreviewWrapper = document.getElementById('imagePreviewWrapper');

        imageInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = e => {
                    imagePreview.src = e.target.result;
                    imagePreviewWrapper.style.display = 'block';
                };
                reader.readAsDataURL(file);
            } else {
                imagePreviewWrapper.style.display = 'none';
                imagePreview.src = '#';
            }
        });


    </script>

</body>

</html>
