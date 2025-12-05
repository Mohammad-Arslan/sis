<!DOCTYPE html>
<html lang="en" data-theme="light">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SuperNovaSchool - Login</title>
    <link rel="shortcut icon" href="{{ asset('Favicon.png') }}">
    <link href='https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800&display=swap' rel='stylesheet'>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        .float-animation {
            animation: float 6s ease-in-out infinite;
        }
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 25%, #f093fb 50%, #4facfe 75%, #00f2fe 100%);
            background-size: 400% 400%;
            animation: gradientShift 15s ease infinite;
        }
        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        .glass-effect {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        .input-focus:focus {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(56, 181, 147, 0.3);
        }
        .btn-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 35px -5px rgba(56, 181, 147, 0.4);
        }
        .modern-button {
            width: 100%;
            background: linear-gradient(135deg, #38B593 0%, #2d8f73 100%);
            color: white;
            border: none;
            border-radius: 12px;
            padding: 16px 24px;
            font-size: 16px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            box-shadow: 0 4px 15px rgba(56, 181, 147, 0.3);
        }
        .modern-button:hover {
            background: linear-gradient(135deg, #2d8f73 0%, #38B593 100%);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(56, 181, 147, 0.4);
        }
        .modern-button:active {
            transform: translateY(0);
            box-shadow: 0 2px 10px rgba(56, 181, 147, 0.3);
        }
        .modern-button:focus {
            outline: none;
            box-shadow: 0 0 0 4px rgba(56, 181, 147, 0.2), 0 8px 25px rgba(56, 181, 147, 0.4);
        }
        .modern-button svg {
            transition: transform 0.3s ease;
        }
        .modern-button:hover svg {
            transform: translateX(4px);
        }
        .modern-input {
            background: #ffffff;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            padding: 16px 20px;
            font-size: 15px;
            transition: all 0.3s ease;
        }
        .modern-input:focus {
            outline: none;
            border-color: #38B593;
            background: #f9fafb;
            box-shadow: 0 0 0 4px rgba(56, 181, 147, 0.1);
        }
        .modern-input::placeholder {
            color: #9ca3af;
        }
        .input-wrapper {
            position: relative;
        }
        .input-icon {
            position: absolute;
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: #6b7280;
            pointer-events: none;
            z-index: 10;
        }
        .input-with-icon {
            padding-left: 50px !important;
        }
        .checkbox-modern {
            width: 20px;
            height: 20px;
            border-radius: 6px;
            border: 2px solid #d1d5db;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .custom-checkbox {
            appearance: none;
            width: 20px;
            height: 20px;
            border: 2px solid #d1d5db;
            border-radius: 6px;
            background-color: white;
            cursor: pointer;
            position: relative;
            transition: all 0.2s ease;
        }
        .custom-checkbox:checked {
            background-color: #38B593;
            border-color: #38B593;
        }
        .custom-checkbox:checked::after {
            content: '✓';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: white;
            font-size: 14px;
            font-weight: bold;
        }
        .custom-checkbox:hover {
            border-color: #38B593;
        }
        .link-modern {
            color: #38B593;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s ease;
            position: relative;
        }
        .link-modern:hover {
            color: #2d8f73;
            text-decoration: underline;
        }
        .link-modern::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: -2px;
            left: 0;
            background-color: #38B593;
            transition: width 0.3s ease;
        }
        .link-modern:hover::after {
            width: 100%;
        }
    </style>
</head>

<body class="min-h-screen relative overflow-hidden">
    <!-- Animated Background -->
    <div class="absolute inset-0 gradient-bg opacity-90"></div>
    
    <!-- Decorative Shapes -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute top-20 left-10 w-72 h-72 bg-primary/20 rounded-full mix-blend-multiply filter blur-xl opacity-70 float-animation"></div>
        <div class="absolute top-40 right-10 w-72 h-72 bg-secondary/20 rounded-full mix-blend-multiply filter blur-xl opacity-70 float-animation" style="animation-delay: 2s;"></div>
        <div class="absolute -bottom-8 left-1/2 w-72 h-72 bg-accent/20 rounded-full mix-blend-multiply filter blur-xl opacity-70 float-animation" style="animation-delay: 4s;"></div>
    </div>

    <!-- Main Content -->
    <div class="relative min-h-screen flex items-center justify-center p-4 sm:p-6 lg:p-8 z-10">
        <div class="w-full max-w-md">
            <!-- Logo Section -->
            <div class="text-center mb-8 sm:mb-10 float-animation">
                <img src="{{ asset('Group.png') }}" alt="SuperNovaSchool Logo" 
                     class="mx-auto max-w-[280px] sm:max-w-[320px] drop-shadow-2xl" />
            </div>

            <!-- Login Card -->
            <div class="card glass-effect shadow-2xl border-0">
                <div class="card-body p-6 sm:p-8 lg:p-10">
                    <!-- Header -->
                    <div class="text-center mb-6 sm:mb-8">
                        <h2 class="text-3xl sm:text-4xl font-bold bg-gradient-to-r from-primary to-secondary bg-clip-text text-transparent mb-3">
                            Welcome Back
                        </h2>
                        <p class="text-base sm:text-lg text-base-content/70 font-medium">
                            Sign in to continue to SuperNovaSchool
                        </p>
                    </div>

                    <!-- Error Messages -->
                    @if ($errors->any())
                        <div class="alert alert-error mb-4 sm:mb-6 shadow-lg animate-pulse">
                            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div class="flex flex-col gap-1">
                                @foreach ($errors->all() as $error)
                                    <span class="text-sm font-medium">{{ $error }}</span>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if (session('status'))
                        <div class="alert alert-success mb-4 sm:mb-6 shadow-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span class="text-sm font-medium">{{ session('status') }}</span>
                        </div>
                    @endif

                    <!-- Login Form -->
                    <form method="POST" action="{{ route('login') }}" class="space-y-5 sm:space-y-6">
                        @csrf

                        <!-- Email/Username Field -->
                        <div class="form-control">
                            <label class="label pb-3" for="email">
                                <span class="label-text font-semibold text-base-content text-sm sm:text-base">
                                    Username or Email
                                </span>
                            </label>
                            <div class="input-wrapper">
                                <i class="fas fa-user input-icon"></i>
                                <input 
                                    type="text" 
                                    name="email" 
                                    id="email"
                                    value="{{ old('email') }}"
                                    placeholder="Enter your username or email"
                                    class="modern-input input-with-icon w-full @error('email') border-error @enderror"
                                    required 
                                    autofocus
                                    autocomplete="username"
                                />
                            </div>
                            @error('email')
                                <label class="label pt-2 pb-0">
                                    <span class="label-text-alt text-error font-medium flex items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        {{ $message }}
                                    </span>
                                </label>
                            @enderror
                        </div>

                        <!-- Password Field -->
                        <div class="form-control">
                            <label class="label pb-3" for="password">
                                <span class="label-text font-semibold text-base-content text-sm sm:text-base">
                                    Password
                                </span>
                            </label>
                            <div class="input-wrapper">
                                <i class="fas fa-lock input-icon"></i>
                                <input 
                                    type="password" 
                                    name="password" 
                                    id="password"
                                    placeholder="Enter your password"
                                    class="modern-input input-with-icon w-full pr-14 @error('password') border-error @enderror"
                                    required
                                    autocomplete="current-password"
                                />
                                <button 
                                    type="button"
                                    onclick="togglePassword()"
                                    class="absolute right-4 top-1/2 -translate-y-1/2 p-2 rounded-lg hover:bg-gray-100 transition-colors focus:outline-none focus:ring-2 focus:ring-primary/20"
                                    aria-label="Toggle password visibility"
                                >
                                    <svg id="eye-icon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    <svg id="eye-off-icon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                    </svg>
                                </button>
                            </div>
                            @error('password')
                                <label class="label pt-2 pb-0">
                                    <span class="label-text-alt text-error font-medium flex items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        {{ $message }}
                                    </span>
                                </label>
                            @enderror
                        </div>

                        <!-- Remember Me & Forgot Password -->
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 sm:gap-0 pt-1">
                            <label class="flex items-center gap-3 cursor-pointer group" for="remember">
                                <input 
                                    type="checkbox" 
                                    name="remember" 
                                    id="remember"
                                    class="custom-checkbox"
                                />
                                <span class="text-sm sm:text-base font-medium text-gray-700 group-hover:text-gray-900 transition-colors cursor-pointer select-none">
                                    Remember me
                                </span>
                            </label>
                            <a 
                                href="{{ route('password.request') }}" 
                                class="link-modern text-sm sm:text-base"
                            >
                                Forgot Password?
                            </a>
                        </div>

                        <!-- Submit Button -->
                        <div class="form-control mt-8 sm:mt-10">
                            <button 
                                type="submit" 
                                class="modern-button"
                            >
                                <span>Sign In</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                </svg>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Footer Text -->
            <p class="text-center text-xs sm:text-sm text-white/90 mt-8 sm:mt-10 px-4 font-medium drop-shadow-lg">
                By continuing, you indicate that you have read and agreed to the 
                <a href="#" class="link link-info hover:link-hover font-semibold underline">Terms of Use</a>
            </p>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eye-icon');
            const eyeOffIcon = document.getElementById('eye-off-icon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.classList.add('hidden');
                eyeOffIcon.classList.remove('hidden');
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.remove('hidden');
                eyeOffIcon.classList.add('hidden');
            }
        }
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</body>

</html>
