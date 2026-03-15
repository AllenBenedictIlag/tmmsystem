<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <title>{{ $title ?? 'The Mica Media' }}</title>

    @vite(['resources/css/app.css','resources/js/app.js'])

</head>


<body class="min-h-screen bg-gradient-to-br from-[#faf9ff] via-[#f3efff] to-[#eae4ff] text-gray-800">


    @php
    $user = auth()->user();
    $currentRoute = Route::currentRouteName();

    $todayRecord = \App\Models\AttendanceRecord::where('user_id', $user->id)
    ->whereDate('date', now('Asia/Manila')->toDateString())
    ->first();

    if ($user->hasRole('Admin')) {
    $dashboardRoute = 'admin.dashboard';
    } elseif ($user->hasRole('CEO')) {
    $dashboardRoute = 'ceo.dashboard';
    } elseif ($user->hasRole('CORE')) {
    $dashboardRoute = 'core.dashboard';
    } elseif ($user->hasRole('CREATIVES')) {
    $dashboardRoute = 'creative.dashboard';
    } elseif ($user->hasRole('SOCIAL MEDIA MANAGER')) {
    $dashboardRoute = 'smm.dashboard';
    } else {
    $dashboardRoute = null;
    }

    $roleName = $user->roles->first()->name ?? '';
    @endphp



    <!-- TOP BAR -->
    <div class="flex justify-between items-center px-12 py-8">

        <h1 class="text-2xl font-semibold tracking-wide text-[#9c8be8]">
            The Mica Media
        </h1>


        <div class="flex items-center gap-6">


            <!-- Attendance -->
            <div class="relative">

                <button onclick="toggleAttendance()" class="px-4 py-2 bg-white/60 backdrop-blur-md rounded-xl text-sm shadow-soft hover:bg-white/80 transition">

                    Attendance ▾

                </button>
                <div id="attendanceMenu" class="hidden absolute right-0 mt-3 w-48 bg-white rounded-2xl shadow-2xl border border-purple-100 p-4 space-y-2 z-50 animate-fadeIn">

                    {{-- TIME IN --}}
                    @if(!$todayRecord || !$todayRecord->time_in)
                    <form method="POST" action="{{ route('attendance.timein') }}">
                        @csrf
                        <button class="w-full bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 transition">
                            Time In
                        </button>
                    </form>
                    @endif


                    {{-- BREAK START --}}
                    @if($todayRecord && $todayRecord->time_in && !$todayRecord->break_start && !$todayRecord->time_out)
                    <form method="POST" action="{{ route('attendance.break.start') }}">
                        @csrf
                        <button class="w-full bg-yellow-500 text-white px-4 py-2 rounded-lg hover:bg-yellow-600 transition">
                            Break Start
                        </button>
                    </form>
                    @endif


                    {{-- BREAK END --}}
                    @if($todayRecord && $todayRecord->break_start && !$todayRecord->break_end && !$todayRecord->time_out)
                    <form method="POST" action="{{ route('attendance.break.end') }}">
                        @csrf
                        <button class="w-full bg-orange-500 text-white px-4 py-2 rounded-lg hover:bg-orange-600 transition">
                            Break End
                        </button>
                    </form>
                    @endif


                    {{-- TIME OUT --}}
                    @if($todayRecord && $todayRecord->time_in && !$todayRecord->time_out)
                    <form method="POST" action="{{ route('attendance.timeout') }}">
                        @csrf
                        <button class="w-full bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition">
                            Time Out
                        </button>
                    </form>
                    @endif

                </div>

            </div>



            <div class="relative">

                <button onclick="toggleUserMenu()" class="flex items-center gap-4 bg-white/70 backdrop-blur-xl px-4 py-2 rounded-2xl shadow-lg border border-purple-100 hover:bg-white transition">

                    <div class="w-9 h-9 rounded-full bg-gradient-to-br from-[#7f6ed6] to-[#6b5cff] flex items-center justify-center text-white text-sm font-semibold">
                        {{ strtoupper(substr($user->name,0,1)) }}
                    </div>

                    <div class="text-left">
                        <p class="text-sm font-semibold text-gray-900 leading-tight">
                            {{ $user->name }}
                        </p>
                        <p class="text-xs text-[#7f6ed6] uppercase tracking-wider">
                            {{ $roleName }}
                        </p>
                    </div>

                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>

                </button>



                <div id="userMenu" class="hidden absolute right-0 mt-3 w-60 bg-white rounded-2xl shadow-2xl border border-purple-100 p-4 space-y-2 z-50">

                

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left px-3 py-2 rounded-xl text-sm text-purple-500 hover:bg-purple-50 transition">
                            Sign Out
                        </button>
                    </form>

                </div>

            </div>
        </div>


        <script>
            function toggleAttendance() {
                document.getElementById('attendanceMenu').classList.toggle('hidden');
            }

            function toggleUserMenu() {
                document.getElementById('userMenu').classList.toggle('hidden');
            }

            document.addEventListener('click', function(event) {

                const attendance = document.getElementById('attendanceMenu');
                const userMenu = document.getElementById('userMenu');

                if (!event.target.closest('[onclick="toggleAttendance()"]') && !event.target.closest('#attendanceMenu')) {
                    attendance.classList.add('hidden');
                }

                if (!event.target.closest('[onclick="toggleUserMenu()"]') && !event.target.closest('#userMenu')) {
                    userMenu.classList.add('hidden');
                }

            });

        </script>

    </div>



    <div class="flex px-12 pb-16 gap-12 items-start max-w-[1400px] mx-auto">

        <!-- SIDEBAR -->
        <aside class="w-64 bg-white/40 backdrop-blur-2xl rounded-3xl p-6 shadow-2xl border border-purple-100">
            <nav class="space-y-3 text-sm">

                <p class="text-xs uppercase tracking-wider text-gray-400 mb-3 px-2">
                    Overview
                </p>

                <!-- DASHBOARD -->
                <a href="{{ $dashboardRoute ? route($dashboardRoute) : url('/') }}" class="block px-5 py-3 rounded-2xl transition
{{ str_contains($currentRoute,'dashboard')
? 'bg-white shadow-lg text-[#6d5ce7] border border-purple-200'
: 'hover:bg-white/40 text-gray-600' }}">

                    Dashboard

                </a>



                {{-- CEO MODULES --}}
                @if($user->hasRole('CEO'))

                <a href="{{ route('ceo.approvals.index') }}" class="block px-5 py-3 rounded-2xl transition
{{ str_contains($currentRoute,'approvals')
? 'bg-white shadow-lg text-[#6d5ce7] border border-purple-200e-200'
: 'hover:bg-white/40 text-gray-600' }}">

                    Approvals

                </a>


                <a href="{{ route('ceo.calendar.approvals') }}" class="block px-5 py-3 rounded-2xl transition
{{ str_contains($currentRoute,'calendar-approvals')
? 'bg-white shadow-lg text-[#6d5ce7] border border-purple-200'
: 'hover:bg-white/40 text-gray-600' }}">

                    Calendar Approvals

                </a>

                <p class="text-xs uppercase tracking-wider text-gray-400 mt-6 mb-3 px-2">
                    Management
                </p>

                <a href="{{ route('ceo.projects.index') }}" class="block px-5 py-3 rounded-2xl transition
{{ str_contains($currentRoute,'ceo.projects')
? 'bg-white shadow-lg text-[#6d5ce7] border border-purple-200'
: 'hover:bg-white/40 text-gray-600' }}">

                    Projects

                </a>

                <a href="{{ route('ceo.clients.index') }}" class="block px-5 py-3 rounded-2xl transition
{{ str_contains($currentRoute,'ceo.clients')
? 'bg-white shadow-lg text-[#6d5ce7] border border-purple-200'
: 'hover:bg-white/40 text-gray-600' }}">

                    Clients

                </a>

                <a href="{{ route('ceo.performance.index') }}" class="block px-5 py-3 rounded-2xl transition
{{ str_contains($currentRoute,'ceo.performance')
? 'bg-white shadow-lg text-[#6d5ce7] border border-purple-200'
: 'hover:bg-white/40 text-gray-600' }}">

                    Performance Dashboard

                </a>

                @endif



                {{-- CORE TEAM MODULES --}}
                @if($user->hasRole('CORE'))

                <a href="{{ route('core.projects.index') }}" class="block px-5 py-3 rounded-2xl transition
{{ str_contains($currentRoute,'core.projects')
? 'bg-white shadow-lg text-[#6d5ce7] border border-purple-200e-200'
: 'hover:bg-white/40 text-gray-600' }}">
                    Projects
                </a>

                <a href="{{ route('core.clients.index') }}" class="block px-5 py-3 rounded-2xl transition
{{ str_contains($currentRoute,'core.clients')
? 'bg-white shadow-lg text-[#6d5ce7] border border-purple-200e-200'
: 'hover:bg-white/40 text-gray-600' }}">
                    Clients
                </a>

                <a href="{{ route('core.contracts.index') }}" class="block px-5 py-3 rounded-2xl transition
{{ str_contains($currentRoute,'core.contracts')
? 'bg-white shadow-lg text-[#6d5ce7] border border-purple-200e-200e-200'
: 'hover:bg-white/40 text-gray-600' }}">
                    Contracts
                </a>

                @endif



                {{-- CREATIVES MODULE --}}
                @if($user->hasRole('CREATIVES'))

                <a href="{{ route('creative.tasks.index') }}" class="block px-5 py-3 rounded-2xl transition
{{ str_contains($currentRoute,'creative.tasks')
? 'bg-white shadow-lg text-[#6d5ce7] border border-purple-200e-200e-200e-200'
: 'hover:bg-white/40 text-gray-600' }}">

                    My Tasks

                </a>

                @endif



                {{-- SMM MODULE --}}
                @if($user->hasRole('SOCIAL MEDIA MANAGER'))

                <a href="{{ route('smm.tasks.index') }}" class="block px-5 py-3 rounded-2xl transition
{{ str_contains($currentRoute,'smm.tasks')
? 'bg-white shadow-lg text-[#6d5ce7] border border-purple-200e-200e-200e-200e-200e-200e-200'
: 'hover:bg-white/40 text-gray-600' }}">

                    My Tasks

                </a>

                @endif



                {{-- ADMIN MODULE --}}
                @if($user->hasRole('Admin'))

                <a href="{{ route('admin.users.index') }}" class="block px-5 py-3 rounded-2xl transition
{{ str_contains($currentRoute,'admin.users')
? 'bg-white shadow-lg text-[#6d5ce7] border border-purple-200'
: 'hover:bg-white/40 text-gray-600' }}">

                    Users

                </a>

                @endif



                <!-- ATTENDANCE -->
                <a href="{{ route('attendance.index') }}" class="block px-5 py-3 rounded-2xl transition
{{ str_contains($currentRoute,'attendance')
? 'bg-white shadow-lg text-[#6d5ce7] border border-purple-200'
: 'hover:bg-white/40 text-gray-600' }}">

                    Attendance

                </a>

            </nav>


        </aside>



        <!-- MAIN -->
        <main class="flex-1 max-w-[1000px]">


            <div class="bg-white/80 backdrop-blur-xl rounded-3xl p-12 shadow-2xl border border-purple-100 hover:shadow-purple-200/40 transition">


                @php
                $backRoute = null;

                if(str_contains($currentRoute,'.create') ||
                str_contains($currentRoute,'.edit') ||
                str_contains($currentRoute,'.show')) {

                $backRoute = \Illuminate\Support\Str::beforeLast($currentRoute,'.').'.index';
                }
                @endphp



                @if($backRoute && Route::has($backRoute))

                <a href="{{ route($backRoute) }}" class="inline-flex items-center gap-2 px-4 py-2 mb-8 rounded-xl
bg-white/80 backdrop-blur-2xl rounded-3xl p-12 shadow-2xl border border-purple-100
text-[#7f6ed6] text-sm font-medium
hover:bg-white transition shadow-soft">

                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">

                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />

                    </svg>

                    Return

                </a>

                @endif



                <h2 class="text-4xl font-bold text-[#7f6ed6] mb-2">
                    {{ $title ?? 'Dashboard' }}
                </h2>

                {{ $slot }}


            </div>

        </main>


    </div>


</body>
</html>
