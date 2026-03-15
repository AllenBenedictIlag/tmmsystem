<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Dashboard' }}</title>
    @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="min-h-screen bg-gradient-to-br from-purple-50 via-white to-purple-100">

    <div class="max-w-7xl mx-auto px-6 py-8">

        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-extrabold bg-gradient-to-r from-purple-700 to-purple-500 bg-clip-text text-transparent">
                    {{ $title ?? 'Dashboard' }}
                </h1>
                <p class="text-sm text-purple-900/60 mt-1">
                    Logged in as {{ auth()->user()->name }} • {{ auth()->user()->getRoleNames()->first() }}
                </p>
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="rounded-xl px-4 py-2 font-semibold text-white bg-purple-600 hover:bg-purple-700 transition">
                    Logout
                </button>
            </form>
        </div>

        @if(session('success'))
            <div class="mb-4 rounded-xl bg-purple-100 text-purple-800 px-4 py-2">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 rounded-xl bg-red-100 text-red-800 px-4 py-2">
                {{ session('error') }}
            </div>
        @endif

        {{ $slot }}

    </div>

</body>
</html>