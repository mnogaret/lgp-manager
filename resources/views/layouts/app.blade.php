<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Votre titre ici</title>
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
</head>
<body class="bg-gray-100 h-screen antialiased leading-none">
<nav class="bg-blue-500 p-4">
    <div class="container mx-auto">
        <div class="flex justify-between">
            <a href="#" class="text-white text-lg font-bold">Mon App</a>
            <div class="hidden md:flex">
                <a href="#" class="text-white px-4">Accueil</a>
                <a href="#" class="text-white px-4">À propos</a>
                <a href="#" class="text-white px-4">Contact</a>
            </div>
            <div class="md:hidden flex items-center">
                <button id="menuButton" class="text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M4 6h16M4 12h16m-7 6h7"></path>
                    </svg>
                </button>
            </div>
        </div>
        <div id="menuItems" class="mt-4 md:hidden">
            <a href="#" class="block text-white px-4 py-2">Accueil</a>
            <a href="#" class="block text-white px-4 py-2">À propos</a>
            <a href="#" class="block text-white px-4 py-2">Contact</a>
        </div>
    </div>
</nav>
    <div id="app">
        @yield('content')
    </div>
    <script src="{{ mix('js/app.js') }}"></script>
</body>
</html>