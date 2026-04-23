<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verifikasi Email - Inventaris Ibadah</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" rel="stylesheet" />

    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-slate-200 antialiased text-slate-800 min-h-screen flex flex-col justify-center items-center relative py-10 px-4">

    <div class="w-full max-w-[440px] bg-[#fdfdfd] rounded-[2.5rem] p-8 md:p-10 shadow-2xl shadow-slate-300/50 border border-white z-10 text-center">
        
        <div class="w-20 h-20 bg-[#11d4d4]/10 rounded-full flex items-center justify-center mx-auto mb-6 relative">
            <span class="material-symbols-outlined text-4xl text-[#11d4d4]">mark_email_unread</span>
            <span class="absolute top-0 right-0 flex h-4 w-4">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-4 w-4 bg-emerald-500 border-2 border-white"></span>
            </span>
        </div>

        <h1 class="text-2xl font-black text-slate-900 tracking-tight mb-3">Cek Email Anda</h1>
        
        <p class="text-[13px] text-slate-500 font-medium leading-relaxed mb-6">
            Terima kasih telah mendaftar! Sebelum memulai aplikasi, kami perlu memverifikasi identitas Anda. Silakan klik tautan verifikasi yang baru saja kami kirimkan ke email Anda.
        </p>

        @if (session('status') == 'verification-link-sent')
            <div class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-100 flex items-start gap-3 text-left">
                <span class="material-symbols-outlined text-emerald-500 text-lg shrink-0">check_circle</span>
                <p class="text-xs font-bold text-emerald-700 leading-snug">
                    Tautan verifikasi baru telah berhasil dikirim ke alamat email yang Anda berikan saat pendaftaran.
                </p>
            </div>
        @endif

        <div class="flex flex-col gap-3 mt-4">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="w-full bg-[#11d4d4] text-white font-black text-sm py-3.5 rounded-2xl shadow-lg shadow-[#11d4d4]/30 hover:bg-[#0eb8b8] hover:shadow-xl active:scale-[0.98] transition-all flex justify-center items-center gap-2">
                    Kirim Ulang Email <span class="material-symbols-outlined text-[18px]">send</span>
                </button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full bg-white border-2 border-slate-200 text-slate-600 font-bold text-sm py-3 rounded-2xl hover:bg-slate-50 hover:border-slate-300 active:scale-[0.98] transition-all">
                    Keluar Akun
                </button>
            </form>
        </div>

    </div>

    <div class="w-full max-w-[440px] flex justify-between items-center text-slate-400 px-2 mt-6">
        <div class="flex items-center gap-2">
            <span class="material-symbols-outlined text-[14px]">lock</span>
            <span class="text-[10px] font-bold uppercase tracking-widest text-slate-500">Keamanan Sistem Aktif</span>
        </div>
        <span class="text-[10px] font-bold uppercase tracking-widest text-slate-400">v1.0.0</span>
    </div>

</body>
</html>