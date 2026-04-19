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
                <footer class="mt-auto py-6 px-4 sm:px-6 lg:px-8 border-t border-slate-200 bg-slate-50/50">
                    <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-center gap-4">
                        <p class="text-xs font-medium text-slate-500 text-center md:text-left">
                            &copy; {{ date('Y') }} <span class="font-bold text-[#11d4d4]">Inventaris Ibadah</span>. Sistem Manajemen Aset Terpadu.
                        </p>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                            Versi 1.0.0
                        </p>
                    </div>
                </footer>
            </div>
        </main>
    </div>
    @livewireScripts
    
</body>
</html>