<x-guest-layout title="Login - Flopac.id" mode="login">
    <style>
        body {
            background-color: #e6f3fd;
            font-family: 'Segoe UI', sans-serif;
            color: #016C89;
            position: relative;
            overflow: hidden;
        }

        .white-circle {
            position: absolute;
            width: 1300px;
            height: 1300px;
            background-color: white;
            border-radius: 50%;
            top: 50%;
            left: 80%;
            transform: translate(-50%, -50%);
            z-index: -1;
        }

        .form-control {
            border-radius: 30px;
            padding: 12px 20px;
        }

        .btn-login {
            background-color: #b0ddf7;
            color: #004e75;
            font-weight: 600;
            border-radius: 30px;
            padding: 12px;
            width: 100%;
            border: none;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .btn-login:hover {
            background-color: #a3d5f3;
        }

        .logo {
            width: 300px;
        }
    </style>

    <div class="container-fluid min-vh-100 d-flex justify-content-center align-items-center p-4">
        <div class="row w-100 rounded-4" style="max-width: 1000px;">
            <div class="col-md-6 bg-transparent d-flex align-items-center justify-content-center p-4">
                <img src="{{ asset('assets/login-illustration.png') }}" alt="Ilustrasi Login" class="img-fluid">
            </div>

            <div class="white-circle"></div>

            <div class="col-md-6 bg-white d-flex flex-column justify-content-center p-5">
                <div class="text-center mb-5">
                    <img src="{{ asset('assets/logo-flopac.png') }}" alt="Logo Flopac" class="logo">
                </div>

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="login" class="form-label">Username atau Email</label>
                        <input type="text" class="form-control @error('login') is-invalid @enderror" name="login"
                            value="{{ old('login') }}" required autofocus>
                        @error('login')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-2">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" name="password"
                            required>
                        @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-login mt-5">Login</button>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>