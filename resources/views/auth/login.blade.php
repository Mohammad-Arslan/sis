<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>SuperNovaSchool - Login</title>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css" />
    <link rel="shortcut icon" href="{{ asset('Favicon.png') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link href='https://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet'>
    <link href='https://fonts.googleapis.com/css?family=Monomaniac One' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            background: url('background-illustration.png') no-repeat center center fixed;
            background-size: cover;
            margin: 0;
        }

        .login-wrapper {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.95);
        }

        .login-box {
            background: #fff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.08);
            width: 100%;
            max-width: 575px;
            height: 450px;
        }

        .form-control {
            height: 70px;
            background-color: rgb(255, 255, 255);
            border: 1px solid #ddd;
            border-radius: 8px;
        }

        .form-control:focus {
            border-color: #007bff;
            box-shadow: none;
        }

        .form-control.is-invalid {
            border-color: #dc3545;
        }

        .form-control.is-invalid:focus {
            border-color: #dc3545;
            box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
        }

        .invalid-feedback {
            display: block;
            width: 100%;
            margin-top: 0.25rem;
            font-size: 0.875rem;
            color: #dc3545;
        }

        .alert {
            border-radius: 8px;
            border: none;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border-left: 4px solid #dc3545;
        }

        .btn-primary {
            background-color: #007bff;
            border: none;
            font-weight: 600;
            border-radius: 8px;
            height: 55px;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .form-check-label {
            font-size: 0.9rem;
        }

        .text-small {
            font-size: 0.85rem;
        }

        .logo-img {
            max-width: 300px;
            margin-bottom: 20px;
        }

        .footer-text {
            font-size: 0.75rem;
            color: #888;
            text-align: center;
            margin-top: 20px;
        }

        a.text-primary:hover {
            text-decoration: underline;
        }

        .switch {
            position: relative;
            display: inline-block;
            width: 34px;
            height: 20px;
        }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: .4s;
            border-radius: 34px;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 14px;
            width: 14px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }

        input:checked+.slider {
            background-color: #38B593;
        }

        input:checked+.slider:before {
            transform: translateX(14px);
        }
    </style>
</head>

<body>
    <div class="login-wrapper">
        <img src="{{ asset('Group.png') }}" alt="SuperNovaSchool Logo" class="logo-img" />

        <div class="login-box">
            <h4 class="text-center font-weight-bold mb-4">Login</h4>
{{-- 
            @if ($errors->any())
                <div class="alert alert-danger mb-3" role="alert">
                    @if ($errors->has('email'))
                        <div>{{ $errors->first('email') }}</div>
                    @endif
                    @if ($errors->has('password'))
                        <div>{{ $errors->first('password') }}</div>
                    @endif
                    @if (session('status'))
                        <div>{{ session('status') }}</div>
                    @endif
                </div>
            @endif --}}

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="form-group">
                    <input type="text" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="Username" value="{{ old('email') }}" required />
                    @error('email')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-group">
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Password" required />
                    @error('password')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-group d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <label class="switch mb-0 mr-2">
                            <input type="checkbox" name="remember" id="remember">
                            <span class="slider round"></span>
                        </label>
                        <label for="remember" class="mb-0">Remember me</label>
                    </div>

                    <a href="{{ route('password.request') }}" class="text-primary text-small">Recover Password</a>
                </div>


                <button type="submit" class="btn btn-primary btn-block">Log in</button>
            </form>
        </div>

        <p class="footer-text mt-4 px-3">
            By continuing you indicate that you read and agreed to the Terms of Use
        </p>
    </div>
</body>

</html>