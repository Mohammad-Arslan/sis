<section class="wrap-bg get-center">
    <x-guest-layout>
        <div class="form-inline form-bg">
            <div class="form-img get-center">
                <img src="{{ asset('assets/logo/academiqo.png') }}" alt="Academiqo Logo">
                {{-- <x-slot name="logo">
                    <a href="/">
                        <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
                    </a>
                </x-slot> --}}
            </div>
            <div class="form-cover">
                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <!-- Validation Errors -->
                <x-auth-validation-errors class="mb-4" :errors="$errors" />

                <form method="POST" action="{{ route('password.email') }}" class="common-form mx-auto">
                    @csrf
                    <h4>Reset Password</h4>
                    <div class="mb-4 text-sm text-gray-600">
                        {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
                    </div>

                    <!-- Email Address -->
                    <div class="form-input">
                        <x-label for="email" :value="__('Email')" />

                        <x-input id="email" class="form-control" type="email" name="email" :value="old('email')" required
                            autofocus />
                    </div>

                    <div class="form-btn get-center">
                        <x-button class="form-btn get-center">
                            {{ __('Email Password Reset Link') }}
                        </x-button>
                    </div>
                </form>

            </div>
        </div>
    </x-guest-layout>
</section>
