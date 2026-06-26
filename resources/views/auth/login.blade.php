<!DOCTYPE html>
<html lang="id">
<head>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}?v=2">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terralog Cafe Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite('resources/css/app.css')
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="min-h-screen flex items-center justify-center bg-gray-50">
    <div class="w-full max-w-md px-6">
        <div class="bg-white border border-gray-100 p-8 rounded-2xl shadow-xl">
            <!-- Logo / Brand -->
            <div class="text-center mb-8">
                <img src="{{ asset('images/logo.png') }}" alt="Terralog Logo" class="h-24 mx-auto mb-4 object-contain">
                <p class="text-gray-500 mt-2 text-sm">Masuk ke sistem kasir</p>
            </div>
            
            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-xl mb-6 text-sm flex items-center">
                    <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <form action="{{ route('login.proses') }}" method="POST" class="space-y-6" autocomplete="off">
                @csrf
                <!-- Fake inputs to prevent Chrome autofill -->
                <div style="position: absolute; width: 0; height: 0; overflow: hidden; opacity: 0;" aria-hidden="true">
                    <input type="text" name="fake_email" id="fake_email" value="" tabindex="-1">
                    <input type="password" name="fake_password" id="fake_password" value="" tabindex="-1">
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-medium mb-2" for="email">Username</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400 group-focus-within:text-blue-500 transition-colors">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        </div>
                        <input id="email" type="text" name="email" value="{{ old('email') }}" class="w-full pl-10 pr-4 py-3 bg-white border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-300 shadow-sm" placeholder="admin@terralog.com" autocomplete="off" required autofocus>
                    </div>
                </div>
                
                <div>
                    <label class="block text-gray-700 text-sm font-medium mb-2" for="password">Kata Sandi</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400 group-focus-within:text-blue-500 transition-colors">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        </div>
                        <input id="password" type="password" name="password" class="w-full pl-10 pr-4 py-3 bg-white border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-300 shadow-sm" placeholder="••••••••" required>
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full bg-gradient-to-r from-blue-600 to-blue-500 text-white font-semibold py-3 px-4 rounded-xl hover:from-blue-700 hover:to-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500/50 shadow-lg shadow-blue-500/30 transform transition-all duration-300 hover:-translate-y-0.5 active:translate-y-0 flex justify-center items-center group">
                        <span>Masuk ke Sistem</span>
                        <svg class="w-5 h-5 ml-2 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                    </button>
                </div>
            </form>
            
            <div class="mt-8 text-center">
                <p class="text-gray-400 text-xs tracking-wider">&copy; {{ date('Y') }} TERRALOG CAFE</p>
            </div>
        </div>
    </div>
</body>
</html>
