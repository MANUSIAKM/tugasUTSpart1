<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>@yield('title')</title>
</head>
<body class="bg-gray-50 antialiased flex flex-col min-h-screen">
    @include('partials.header')

    <main class="flex-1"> 
        @yield('content')
    </main>

    @include('partials.footer')
</body>
</html>