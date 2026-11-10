<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="antialiased scroll-smooth">
<head>
    @include('partials.head')
</head>
<body class="min-h-screen bg-[#F6F4EE] text-gray-900 flex items-center justify-center p-4 selection:bg-[#D6FF3D] selection:text-black font-sans">
    {{ $slot }}
</body>
</html>
