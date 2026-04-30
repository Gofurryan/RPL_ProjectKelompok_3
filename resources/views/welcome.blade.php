<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Inventaris Tempat Ibadah</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Inter', sans-serif; }
        .gradient-text {
            background: linear-gradient(135deg, #0f172a 0%, #11d4d4 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .blob {
            position: absolute;
            width: 500px;
            height: 500px;
            background: linear-gradient(135deg, rgba(17, 212, 212, 0.2) 0%, rgba(255, 255, 255, 0) 100%);
            filter: blur(80px);
            border-radius: 50%;
            z-index: -1;
        }
        .animate-float { animation: float 6s ease-in-out infinite; }
        .animate-float-delayed { animation: float 6s ease-in-out 3s infinite; }
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-15px); }
            100% { transform: translateY(0px); }
        }
    </style>
</head>
<body class="bg-[#fdfdfd] antialiased text-slate-800 selection:bg-[#11d4d4] selection:text-white">

    <div class="blob top-[-200px] left-[-100px]"></div>

    <header id="header-container" class="fixed top-0 w-full z-50 transition-all duration-500 ease-in-out flex justify-center px-0 pt-0">
        
        <nav id="navbar" class="w-full max-w-full bg-white/80 backdrop-blur-md border-b border-slate-100 transition-all duration-500 ease-in-out flex items-center justify-between px-6 md:px-10 h-20 rounded-none">
            
            <a href="#" class="flex items-center gap-2 group shrink-0">
                <div class="w-10 h-10 bg-[#11d4d4] rounded-xl flex items-center justify-center shadow-lg shadow-[#11d4d4]/30 group-hover:scale-110 group-hover:rotate-3 transition-transform duration-300">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                </div>
                <span class="text-xl font-black text-slate-800 tracking-tight uppercase hidden sm:block">Inventaris <span class="text-[#11d4d4]">Ibadah</span></span>
            </a>

            <div class="hidden md:flex items-center gap-8 text-sm font-bold text-slate-500">
                <a href="#fitur" class="hover:text-[#11d4d4] hover:-translate-y-0.5 transition-all duration-300">Fitur</a>
                <a href="#cara-kerja" class="hover:text-[#11d4d4] hover:-translate-y-0.5 transition-all duration-300">Cara Kerja</a>
                <a href="#faq" class="hover:text-[#11d4d4] hover:-translate-y-0.5 transition-all duration-300">Pertanyaan Umum</a>
            </div>

            <div class="flex items-center gap-3 shrink-0">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ auth()->user()->role == 'petugas' ? route('admin.dashboard') : route('dashboard') }}" class="px-5 py-2.5 bg-[#11d4d4] text-white rounded-xl text-sm font-bold shadow-lg shadow-[#11d4d4]/30 hover:bg-[#0eb8b8] hover:-translate-y-1 hover:shadow-xl hover:shadow-[#11d4d4]/40 active:scale-95 transition-all duration-300">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-bold text-slate-500 hover:text-[#11d4d4] px-3 transition-colors duration-300">Masuk</a>
                        <a href="{{ route('register') }}" class="px-5 py-2.5 bg-[#11d4d4] text-white rounded-xl text-sm font-bold shadow-lg shadow-[#11d4d4]/30 hover:bg-[#0eb8b8] hover:-translate-y-1 hover:shadow-xl hover:shadow-[#11d4d4]/40 active:scale-95 transition-all duration-300">Daftar</a>
                    @endauth
                @endif
            </div>
        </nav>
    </header>

    <section class="pt-40 pb-20 px-6">
        <div class="max-w-7xl mx-auto grid lg:grid-cols-2 items-center gap-16">
            <div>
                <span class="inline-block px-4 py-2 rounded-full bg-[#11d4d4]/10 text-[#11d4d4] text-xs font-black uppercase tracking-widest mb-6 animate-pulse">Sistem Peminjaman Terpadu</span>
                <h1 class="text-5xl md:text-7xl font-black text-slate-900 leading-[1.1] mb-6 tracking-tight">
                    Pinjam Barang <br> <span class="gradient-text">Sekarang Jadi Mudah</span>
                </h1>
                <p class="text-lg text-slate-500 font-medium leading-relaxed mb-10 max-w-lg">
                    Kelola kebutuhan ibadah dan acara warga dengan sistem peminjaman inventaris digital yang transparan, otomatis, dan aman.
                </p>
                <div class="flex flex-wrap gap-4">
                    <a href="{{ route('register') }}" class="px-10 py-4 bg-[#11d4d4] text-white rounded-2xl font-black text-sm shadow-lg shadow-[#11d4d4]/30 hover:bg-[#0eb8b8] hover:-translate-y-1 hover:shadow-xl hover:shadow-[#11d4d4]/40 active:scale-95 active:translate-y-0 transition-all duration-300">
                        Mulai Sekarang
                    </a>
                    <a href="#fitur" class="group px-10 py-4 bg-white border-2 border-slate-200 text-slate-600 rounded-2xl font-black text-sm hover:border-[#11d4d4] hover:text-[#11d4d4] active:scale-95 transition-all duration-300 flex items-center gap-2">
                        Pelajari Fitur 
                        <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </a>
                </div>
            </div>

            <div class="relative flex justify-center">
                <div class="absolute inset-0 bg-gradient-to-tr from-[#11d4d4]/20 to-transparent rounded-[3rem] -rotate-3 scale-105 -z-10 transition-transform duration-700 hover:rotate-0"></div>
                <div class="bg-white p-3 rounded-[3rem] shadow-2xl shadow-slate-300/60 relative group">
                    <img src="https://images.unsplash.com/photo-1665322939311-c881c44193ab?q=80&w=735&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D?auto=format&fit=crop&q=80&w=800" 
                        alt="Preview App" class="rounded-[2.5rem] w-full object-cover aspect-[4/3] shadow-inner group-hover:scale-[1.01] transition-transform duration-500">
                    
                    <div class="absolute -bottom-6 -left-6 bg-white p-4 rounded-2xl shadow-xl shadow-slate-200 border border-slate-100 flex items-center gap-4 animate-float hover:scale-105 cursor-pointer transition-transform">
                        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center text-blue-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                        </div>
                        <div>
                            <p class="text-[11px] font-black text-slate-400 uppercase tracking-widest">Aset Terkelola</p>
                            <p class="text-sm font-black text-slate-800">20+ Barang Tersedia</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="fitur" class="py-32 px-6 bg-slate-50/50">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-20">
                <h2 class="text-4xl font-black text-slate-900 mb-4 tracking-tight">Kenapa Harus Inventaris Digital?</h2>
                <p class="text-slate-500 font-medium max-w-2xl mx-auto leading-relaxed">Kami menghadirkan kemudahan koordinasi antara pengurus dan warga dalam mengelola aset bersama secara tertib.</p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <div class="group bg-white p-10 rounded-[2.5rem] border border-slate-100 shadow-lg shadow-slate-200/40 hover:shadow-2xl hover:shadow-[#11d4d4]/10 hover:-translate-y-2 transition-all duration-300 cursor-default">
                    <div class="w-14 h-14 bg-emerald-100 rounded-2xl flex items-center justify-center mb-8 text-emerald-600 group-hover:bg-emerald-500 group-hover:text-white group-hover:scale-110 group-hover:rotate-6 transition-all duration-300">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h3 class="text-xl font-black text-slate-900 mb-4 group-hover:text-[#11d4d4] transition-colors">Email Reminder</h3>
                    <p class="text-slate-500 text-sm font-medium leading-relaxed">Sistem otomatis mengirimkan pengingat ke email Anda sebelum batas waktu berakhir untuk menghindari denda.</p>
                </div>

                <div class="group bg-white p-10 rounded-[2.5rem] border border-slate-100 shadow-lg shadow-slate-200/40 hover:shadow-2xl hover:shadow-[#11d4d4]/10 hover:-translate-y-2 transition-all duration-300 cursor-default">
                    <div class="w-14 h-14 bg-blue-100 rounded-2xl flex items-center justify-center mb-8 text-blue-600 group-hover:bg-blue-500 group-hover:text-white group-hover:scale-110 group-hover:-rotate-6 transition-all duration-300">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                    </div>
                    <h3 class="text-xl font-black text-slate-900 mb-4 group-hover:text-[#11d4d4] transition-colors">Integrasi WhatsApp</h3>
                    <p class="text-slate-500 text-sm font-medium leading-relaxed">Pengurus dapat langsung menghubungi peminjam melalui satu klik tombol WhatsApp untuk koordinasi cepat.</p>
                </div>

                <div class="group bg-white p-10 rounded-[2.5rem] border border-slate-100 shadow-lg shadow-slate-200/40 hover:shadow-2xl hover:shadow-[#11d4d4]/10 hover:-translate-y-2 transition-all duration-300 cursor-default">
                    <div class="w-14 h-14 bg-purple-100 rounded-2xl flex items-center justify-center mb-8 text-purple-600 group-hover:bg-purple-500 group-hover:text-white group-hover:scale-110 group-hover:rotate-6 transition-all duration-300">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    </div>
                    <h3 class="text-xl font-black text-slate-900 mb-4 group-hover:text-[#11d4d4] transition-colors">Jejak Audit Aman</h3>
                    <p class="text-slate-500 text-sm font-medium leading-relaxed">Semua aktivitas peminjaman dan persetujuan dicatat secara permanen untuk menjaga akuntabilitas aset masjid.</p>
                </div>
            </div>
        </div>
    </section>

    <section id="cara-kerja" class="py-32 px-6">
        <div class="max-w-7xl mx-auto grid lg:grid-cols-2 gap-20 items-center">
            
            <div class="relative w-full aspect-square max-w-md mx-auto">
                <div class="absolute inset-0 bg-gradient-to-tr from-[#11d4d4]/30 to-slate-100 rounded-[3.5rem] transform -rotate-6 transition-transform hover:rotate-0 duration-500"></div>
                
                <div class="absolute inset-x-8 top-12 bg-white rounded-[2rem] shadow-2xl p-6 flex flex-col gap-4 animate-float border border-slate-50 cursor-pointer hover:scale-105 transition-transform duration-300 z-20">
                    <div class="flex justify-between items-center border-b border-slate-100 pb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 bg-slate-100 rounded-xl flex items-center justify-center">🎤</div>
                            <div>
                                <div class="h-4 w-24 bg-slate-800 rounded-md mb-1"></div>
                                <div class="h-3 w-16 bg-slate-300 rounded-md"></div>
                            </div>
                        </div>
                        <div class="px-3 py-1 bg-emerald-100 rounded-full flex items-center justify-center text-[10px] font-black text-emerald-600 uppercase tracking-widest">Disetujui</div>
                    </div>
                    <div class="flex justify-between items-end">
                        <div class="space-y-2 w-full">
                            <div class="h-3 w-3/4 bg-slate-100 rounded"></div>
                            <div class="h-3 w-1/2 bg-slate-100 rounded"></div>
                        </div>
                        <div class="w-8 h-8 bg-[#11d4d4]/10 rounded-lg flex items-center justify-center text-[#11d4d4]">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </div>
                    </div>
                </div>

                <div class="absolute inset-x-12 bottom-12 bg-slate-50 rounded-[2rem] shadow-lg p-6 flex flex-col gap-4 animate-float-delayed border border-slate-100 z-10 opacity-80">
                    <div class="flex justify-between items-center border-b border-slate-200 pb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 bg-slate-200 rounded-xl flex items-center justify-center opacity-50">🔊</div>
                            <div>
                                <div class="h-4 w-32 bg-slate-300 rounded-md mb-1"></div>
                                <div class="h-3 w-20 bg-slate-200 rounded-md"></div>
                            </div>
                        </div>
                        <div class="px-3 py-1 bg-amber-100 rounded-full flex items-center justify-center text-[10px] font-black text-amber-600 uppercase tracking-widest">Pending</div>
                    </div>
                </div>
            </div>

            <div>
                <h2 class="text-4xl font-black text-slate-900 mb-10 tracking-tight leading-tight">Langkah Mudah Meminjam <br> Barang Inventaris</h2>
                
                <div class="space-y-8">
                    <div class="group flex gap-6 cursor-pointer">
                        <div class="w-12 h-12 rounded-2xl bg-[#11d4d4] text-white flex items-center justify-center shrink-0 font-black shadow-lg shadow-[#11d4d4]/30 group-hover:scale-110 transition-transform duration-300">1</div>
                        <div>
                            <h4 class="text-lg font-black text-slate-900 mb-1 group-hover:text-[#11d4d4] transition-colors">Daftar Akun</h4>
                            <p class="text-sm text-slate-500 font-medium leading-relaxed">Lakukan registrasi dan verifikasi email Anda agar terdaftar resmi dalam sistem keamanan.</p>
                        </div>
                    </div>
                    <div class="group flex gap-6 cursor-pointer">
                        <div class="w-12 h-12 rounded-2xl bg-white border-2 border-slate-100 text-slate-400 flex items-center justify-center shrink-0 font-black group-hover:border-[#11d4d4] group-hover:text-[#11d4d4] transition-colors duration-300">2</div>
                        <div>
                            <h4 class="text-lg font-black text-slate-900 mb-1 group-hover:text-[#11d4d4] transition-colors">Pilih & Ajukan</h4>
                            <p class="text-sm text-slate-500 font-medium leading-relaxed">Cek ketersediaan barang di dashboard warga dan tentukan tanggal peminjaman.</p>
                        </div>
                    </div>
                    <div class="group flex gap-6 cursor-pointer">
                        <div class="w-12 h-12 rounded-2xl bg-white border-2 border-slate-100 text-slate-400 flex items-center justify-center shrink-0 font-black group-hover:border-[#11d4d4] group-hover:text-[#11d4d4] transition-colors duration-300">3</div>
                        <div>
                            <h4 class="text-lg font-black text-slate-900 mb-1 group-hover:text-[#11d4d4] transition-colors">Ambil Barang</h4>
                            <p class="text-sm text-slate-500 font-medium leading-relaxed">Setelah status disetujui Admin, Anda dapat langsung mengambil barang di lokasi.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section id="faq" class="py-32 px-6 bg-slate-50/30 relative overflow-hidden">
        <!-- Elemen Dekoratif Background Luar -->
        <div class="absolute top-40 left-0 w-72 h-72 bg-[#11d4d4]/5 rounded-full blur-3xl -z-10"></div>
        <div class="absolute bottom-10 right-0 w-96 h-96 bg-blue-500/5 rounded-full blur-3xl -z-10"></div>

        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-16 relative z-10">
                <span class="inline-block px-4 py-2 rounded-full bg-white border border-slate-100 text-slate-500 text-xs font-black uppercase tracking-widest mb-4 shadow-sm">Bantuan Pusat</span>
                <h2 class="text-4xl font-black text-slate-900 mb-4 tracking-tight">Pertanyaan Seputar Peminjaman</h2>
                <p class="text-slate-500 font-medium max-w-2xl mx-auto leading-relaxed">Informasi yang sering ditanyakan oleh warga terkait aturan dan tata cara penggunaan inventaris bersama.</p>
            </div>
            
            <div class="grid md:grid-cols-2 gap-8 max-w-5xl mx-auto">
                
                <!-- FAQ Card 1 -->
                <div class="relative group p-8 rounded-[2.5rem] bg-white border border-slate-100 shadow-sm hover:shadow-2xl hover:shadow-[#11d4d4]/15 hover:border-[#11d4d4]/40 hover:-translate-y-2 transition-all duration-500 overflow-hidden z-10 cursor-pointer">
                    <!-- Efek Gradasi Menyala di Pojok -->
                    <div class="absolute -top-10 -right-10 w-40 h-40 bg-gradient-to-br from-[#11d4d4]/10 to-transparent rounded-full blur-2xl group-hover:scale-150 group-hover:from-[#11d4d4]/20 transition-transform duration-700 -z-10"></div>
                    
                    <!-- Ikon Animasi -->
                    <div class="w-12 h-12 bg-slate-50 border border-slate-100 text-slate-400 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-[#11d4d4] group-hover:text-white group-hover:rotate-12 group-hover:scale-110 transition-all duration-300 shadow-sm group-hover:shadow-md">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>

                    <h4 class="text-lg font-black text-slate-900 mb-3 group-hover:text-[#11d4d4] transition-colors duration-300">Siapa saja yang boleh meminjam?</h4>
                    <p class="text-sm text-slate-500 font-medium leading-relaxed">Seluruh warga yang telah memiliki akun terdaftar dan terverifikasi oleh admin berhak untuk mengajukan peminjaman barang inventaris.</p>
                </div>

                <!-- FAQ Card 2 -->
                <div class="relative group p-8 rounded-[2.5rem] bg-white border border-slate-100 shadow-sm hover:shadow-2xl hover:shadow-[#11d4d4]/15 hover:border-[#11d4d4]/40 hover:-translate-y-2 transition-all duration-500 overflow-hidden z-10 cursor-pointer">
                    <div class="absolute -top-10 -right-10 w-40 h-40 bg-gradient-to-br from-[#11d4d4]/10 to-transparent rounded-full blur-2xl group-hover:scale-150 group-hover:from-[#11d4d4]/20 transition-transform duration-700 -z-10"></div>
                    
                    <div class="w-12 h-12 bg-slate-50 border border-slate-100 text-slate-400 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-[#11d4d4] group-hover:text-white group-hover:-rotate-12 group-hover:scale-110 transition-all duration-300 shadow-sm group-hover:shadow-md">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>

                    <h4 class="text-lg font-black text-slate-900 mb-3 group-hover:text-[#11d4d4] transition-colors duration-300">Berapa lama batas waktu meminjam?</h4>
                    <p class="text-sm text-slate-500 font-medium leading-relaxed">Durasi peminjaman standar adalah 1-3 hari, tergantung jenis barang. Anda dapat melihat detail batas waktu saat mengajukan form peminjaman.</p>
                </div>

                <!-- FAQ Card 3 -->
                <div class="relative group p-8 rounded-[2.5rem] bg-white border border-slate-100 shadow-sm hover:shadow-2xl hover:shadow-[#11d4d4]/15 hover:border-[#11d4d4]/40 hover:-translate-y-2 transition-all duration-500 overflow-hidden z-10 cursor-pointer">
                    <div class="absolute -top-10 -right-10 w-40 h-40 bg-gradient-to-br from-[#11d4d4]/10 to-transparent rounded-full blur-2xl group-hover:scale-150 group-hover:from-[#11d4d4]/20 transition-transform duration-700 -z-10"></div>
                    
                    <div class="w-12 h-12 bg-slate-50 border border-slate-100 text-slate-400 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-rose-500 group-hover:text-white group-hover:rotate-12 group-hover:scale-110 transition-all duration-300 shadow-sm group-hover:shadow-md">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    </div>

                    <h4 class="text-lg font-black text-slate-900 mb-3 group-hover:text-rose-500 transition-colors duration-300">Bagaimana jika terjadi kerusakan?</h4>
                    <p class="text-sm text-slate-500 font-medium leading-relaxed">Peminjam diwajibkan lapor kepada pengurus. Segala bentuk kerusakan karena kelalaian pemakaian menjadi tanggung jawab penuh peminjam.</p>
                </div>

                <!-- FAQ Card 4 -->
                <div class="relative group p-8 rounded-[2.5rem] bg-white border border-slate-100 shadow-sm hover:shadow-2xl hover:shadow-[#11d4d4]/15 hover:border-[#11d4d4]/40 hover:-translate-y-2 transition-all duration-500 overflow-hidden z-10 cursor-pointer">
                    <div class="absolute -top-10 -right-10 w-40 h-40 bg-gradient-to-br from-[#11d4d4]/10 to-transparent rounded-full blur-2xl group-hover:scale-150 group-hover:from-[#11d4d4]/20 transition-transform duration-700 -z-10"></div>
                    
                    <div class="w-12 h-12 bg-slate-50 border border-slate-100 text-slate-400 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-amber-500 group-hover:text-white group-hover:-rotate-12 group-hover:scale-110 transition-all duration-300 shadow-sm group-hover:shadow-md">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>

                    <h4 class="text-lg font-black text-slate-900 mb-3 group-hover:text-amber-500 transition-colors duration-300">Apakah ada denda keterlambatan?</h4>
                    <p class="text-sm text-slate-500 font-medium leading-relaxed">Sistem akan mencatat riwayat keterlambatan otomatis. Pengurus berhak memberikan denda atau teguran jika terlambat mengembalikan barang.</p>
                </div>
            </div>
        </div>
    </section>

    <footer class="bg-slate-900 py-20 px-6 rounded-t-[4rem]">
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between gap-12">
            <div class="max-w-sm">
                <a href="#" class="flex items-center gap-2 mb-6 text-white group w-fit">
                    <div class="w-8 h-8 bg-[#11d4d4] rounded-lg flex items-center justify-center group-hover:rotate-12 transition-transform">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    </div>
                    <span class="text-lg font-black tracking-tight uppercase tracking-widest">Inventaris <span class="text-[#11d4d4]">Ibadah</span></span>
                </a>
                <p class="text-slate-400 text-sm leading-relaxed font-medium">Sistem Pengelolaan Aset Tempat Ibadah. Mari jaga dan gunakan amanah barang bersama dengan baik.</p>
            </div>
            
            <div class="grid grid-cols-2 gap-16">
                <div>
                    <h5 class="text-white font-black text-xs uppercase tracking-widest mb-6">Jelajahi</h5>
                    <ul class="text-slate-400 text-sm space-y-4 font-medium">
                        <li><a href="#fitur" class="hover:text-[#11d4d4] hover:pl-2 transition-all duration-300 flex items-center">Keunggulan Fitur</a></li>
                        <li><a href="#cara-kerja" class="hover:text-[#11d4d4] hover:pl-2 transition-all duration-300 flex items-center">Alur Peminjaman</a></li>
                        <li><a href="#faq" class="hover:text-[#11d4d4] hover:pl-2 transition-all duration-300 flex items-center">Pertanyaan Umum</a></li>
                    </ul>
                </div>
                <div>
                    <h5 class="text-white font-black text-xs uppercase tracking-widest mb-6">Akses Sistem</h5>
                    <ul class="text-slate-400 text-sm space-y-4 font-medium">
                        <li><a href="{{ route('login') }}" class="hover:text-[#11d4d4] hover:pl-2 transition-all duration-300 flex items-center">Masuk ke Dashboard</a></li>
                        <li><a href="{{ route('register') }}" class="hover:text-[#11d4d4] hover:pl-2 transition-all duration-300 flex items-center">Daftar Akun Baru</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="max-w-7xl mx-auto mt-20 pt-8 border-t border-slate-800 text-center text-slate-500 text-[10px] font-bold uppercase tracking-[0.2em]">
            &copy; 2026 Inventaris Ibadah - All Rights Reserved
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const headerContainer = document.getElementById('header-container');
            const navbar = document.getElementById('navbar');
            
            window.addEventListener('scroll', () => {
                if (window.scrollY > 20) {
                    // CONTAINER: Tambah jarak atas & samping
                    headerContainer.classList.add('pt-4', 'px-4');
                    headerContainer.classList.remove('pt-0', 'px-0');
                    
                    // NAVBAR: Menyusut mulus ke ukuran max-w-6xl (sangat lebar & lega)
                    navbar.classList.add('max-w-6xl', 'rounded-[2rem]', 'bg-white/95', 'shadow-2xl', 'shadow-slate-200/50', 'border', 'border-white/60');
                    navbar.classList.remove('max-w-full', 'rounded-none', 'bg-white/80', 'border-b', 'border-slate-100');
                } else {
                    // CONTAINER: Kembali nempel
                    headerContainer.classList.add('pt-0', 'px-0');
                    headerContainer.classList.remove('pt-4', 'px-4');
                    
                    // NAVBAR: Kembali penuh
                    navbar.classList.add('max-w-full', 'rounded-none', 'bg-white/80', 'border-b', 'border-slate-100');
                    navbar.classList.remove('max-w-6xl', 'rounded-[2rem]', 'bg-white/95', 'shadow-2xl', 'shadow-slate-200/50', 'border', 'border-white/60');
                }
            });
        });
    </script>

</body>
</html> 