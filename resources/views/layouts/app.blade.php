<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Inventaris Tempat Ibadah' }}</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @livewireStyles
</head>
<body class="font-display bg-background-light dark:bg-background-dark min-h-screen">
    <div class="flex h-screen overflow-hidden" x-data="{ sidebarOpen: true }">
        
        @include('layouts.partials.sidebar')

        <main class="flex-1 flex flex-col overflow-y-auto">
            @include('layouts.partials.header')

            <div class="p-8">
                {{ $slot }}
            </div>
        </main>
    </div>
    @livewireScripts
</body>
</html>