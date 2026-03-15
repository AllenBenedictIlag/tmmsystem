@props(['route'])

@php
$isActive = request()->routeIs($route);
@endphp

<a href="{{ route($route) }}"
   class="block px-4 py-3 rounded-2xl transition-all duration-200
   {{ $isActive 
        ? 'bg-gradient-to-r from-purple-600 to-pink-500 text-white shadow-lg'
        : 'text-gray-600 hover:bg-purple-100/70 hover:text-purple-700' }}">
    {{ $slot }}
</a>