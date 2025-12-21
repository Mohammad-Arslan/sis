<!DOCTYPE html>
<html lang="en" class="h-full">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Academiqo - Login</title>
    <link rel="shortcut icon" href="{{ asset('assets/logo/academiqo.png') }}">
    <link href='https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap' rel='stylesheet'>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .fade-in-up {
            animation: fadeInUp 0.6s ease-out;
        }
        
        .login-container {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
        }
        
        .logo-container {
            padding: 2rem 0 1rem;
        }
        
        .logo-image {
            height: 80px;
            width: auto;
            object-fit: contain;
            filter: drop-shadow(0 4px 6px rgba(0, 0, 0, 0.1));
        }
        
        .brand-name {
            font-size: 1.75rem;
            font-weight: 700;
            color: #1a202c;
            letter-spacing: -0.5px;
            margin-top: 1rem;
        }
        
        .brand-tagline {
            font-size: 0.875rem;
            color: #718096;
            font-weight: 500;
            letter-spacing: 0.5px;
            margin-top: 0.5rem;
        }
        
        .form-title {
            font-size: 1.875rem;
            font-weight: 700;
            color: #1a202c;
            margin-bottom: 0.5rem;
        }
        
        .form-subtitle {
            font-size: 0.9375rem;
            color: #718096;
            font-weight: 400;
        }
        
        .input-group {
            position: relative;
            margin-bottom: 1.5rem;
        }
        
        .input-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 600;
            color: #374151;
            margin-bottom: 0.5rem;
        }
        
        .input-field {
            width: 100%;
            padding: 0.875rem 1rem 0.875rem 3rem;
            font-size: 0.9375rem;
            border: 2px solid #e5e7eb;
            border-radius: 0.5rem;
            background: #ffffff;
            color: #1a202c;
            transition: all 0.2s ease;
        }
        
        .input-field:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        
        .input-field::placeholder {
            color: #9ca3af;
        }
        
        .input-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            font-size: 1rem;
            pointer-events: none;
        }
        
        .password-toggle {
            position: absolute;
            right: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #9ca3af;
            cursor: pointer;
            padding: 0.5rem;
            transition: color 0.2s;
        }
        
        .password-toggle:hover {
            color: #667eea;
        }
        
        .checkbox-wrapper {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        .custom-checkbox {
            width: 1.125rem;
            height: 1.125rem;
            border: 2px solid #d1d5db;
            border-radius: 0.25rem;
            background: white;
            cursor: pointer;
            position: relative;
            transition: all 0.2s;
        }
        
        .custom-checkbox:checked {
            background: #667eea;
            border-color: #667eea;
        }
        
        .custom-checkbox:checked::after {
            content: '✓';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: white;
            font-size: 0.75rem;
            font-weight: bold;
        }
        
        .checkbox-label {
            font-size: 0.875rem;
            color: #374151;
            font-weight: 500;
            cursor: pointer;
        }
        
        .forgot-link {
            font-size: 0.875rem;
            color: #667eea;
            font-weight: 600;
            text-decoration: none;
            transition: color 0.2s;
        }
        
        .forgot-link:hover {
            color: #5568d3;
            text-decoration: underline;
        }
        
        .submit-button {
            width: 100%;
            padding: 0.875rem 1.5rem;
            font-size: 1rem;
            font-weight: 600;
            color: white;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 0.5rem;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }
        
        .submit-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
        }
        
        .submit-button:active {
            transform: translateY(0);
        }
        
        .error-message {
            background: #fee2e2;
            border: 1px solid #fecaca;
            color: #991b1b;
            padding: 0.875rem 1rem;
            border-radius: 0.5rem;
            font-size: 0.875rem;
            margin-bottom: 1.5rem;
        }
        
        .success-message {
            background: #d1fae5;
            border: 1px solid #a7f3d0;
            color: #065f46;
            padding: 0.875rem 1rem;
            border-radius: 0.5rem;
            font-size: 0.875rem;
            margin-bottom: 1.5rem;
        }
        
        .error-text {
            color: #dc2626;
            font-size: 0.8125rem;
            margin-top: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }
        
        .footer-text {
            text-align: center;
            font-size: 0.8125rem;
            color: rgba(255, 255, 255, 0.9);
            margin-top: 2rem;
        }
        
        .footer-link {
            color: white;
            text-decoration: underline;
            font-weight: 500;
        }
    </style>
</head>

<body class="flex items-center justify-center min-h-screen p-4">
    <div class="w-full max-w-md fade-in-up">
        <div class="login-container rounded-2xl p-8 sm:p-10">
            <!-- Logo Section -->
            <div class="logo-container text-center">
                <img src="{{ asset('assets/logo/academiqo.png') }}" alt="Academiqo Logo" class="logo-image mx-auto" />
                <h1 class="brand-name">Academiqo</h1>
                <p class="brand-tagline">Innovate. Educate. Elevate.</p>
            </div>
            <!-- Error Messages -->
            @if ($errors->any())
                <div class="error-message">
                    <div class="flex flex-col gap-1">
                        @foreach ($errors->all() as $error)
                            <span>{{ $error }}</span>
                        @endforeach
                    </div>
                </div>
            @endif

            @if (session('status'))
                <div class="success-message">
                    {{ session('status') }}
                </div>
            @endif

            <!-- Login Form -->
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email/Username Field -->
                <div class="input-group">
                    <label class="input-label" for="email">
                        Username or Email
                    </label>
                    <div style="position: relative;">
                        <i class="fas fa-user input-icon"></i>
                        <input 
                            type="text" 
                            name="email" 
                            id="email"
                            value="{{ old('email') }}"
                            placeholder="Enter your username or email"
                            class="input-field @error('email') border-red-500 @enderror"
                            required 
                            autofocus
                            autocomplete="username"
                        />
                    </div>
                    @error('email')
                        <div class="error-text">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Password Field -->
                <div class="input-group">
                    <label class="input-label" for="password">
                        Password
                    </label>
                    <div style="position: relative;">
                        <i class="fas fa-lock input-icon"></i>
                        <input 
                            type="password" 
                            name="password" 
                            id="password"
                            placeholder="Enter your password"
                            class="input-field pr-12 @error('password') border-red-500 @enderror"
                            required
                            autocomplete="current-password"
                        />
                        <button 
                            type="button"
                            onclick="togglePassword()"
                            class="password-toggle"
                            aria-label="Toggle password visibility"
                        >
                            <i id="eye-icon" class="fas fa-eye"></i>
                            <i id="eye-off-icon" class="fas fa-eye-slash hidden"></i>
                        </button>
                    </div>
                    @error('password')
                        <div class="error-text">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Remember Me & Forgot Password -->
                <div class="flex justify-between items-center mb-6">
                    <label class="checkbox-wrapper">
                        <input 
                            type="checkbox" 
                            name="remember" 
                            id="remember"
                            class="custom-checkbox"
                        />
                        <span class="checkbox-label">Remember me</span>
                    </label>
                    <a href="{{ route('password.request') }}" class="forgot-link">
                        Forgot Password?
                    </a>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="submit-button">
                    <span>Sign In</span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                    </svg>
                </button>
            </form>
        </div>

        <!-- Footer -->
        <p class="footer-text">
            By continuing, you indicate that you have read and agreed to the 
            <a href="#" class="footer-link">Terms of Use</a>
        </p>
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
