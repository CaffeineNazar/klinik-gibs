<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Klinik GIBS') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .fade-in {
            animation: fadeIn 0.5s ease-out forwards;
            opacity: 0;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .swal2-popup {
            font-family: 'Inter', sans-serif !important;
        }

        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #FCD34D;
            /* yellow-300 */
            border-radius: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #FBBF24;
            /* yellow-400 */
        }
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-[#F9FAFB] text-slate-900 selection:bg-yellow-500 selection:text-white">

    <div class="min-h-screen flex flex-row relative">

        <div id="mobile-overlay" class="fixed inset-0 bg-gray-900/50 z-20 hidden md:hidden transition-opacity backdrop-blur-sm" onclick="toggleSidebar()"></div>

        <aside class="w-72 bg-gradient-to-b from-yellow-400 to-yellow-500 border-r border-yellow-300 min-h-screen fixed left-0 top-0 hidden md:flex flex-col z-30 shadow-xl transition-all duration-300 text-slate-800">

            <div class="h-20 flex items-center px-8 border-b border-slate-900/10">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 group">
                    {{-- LOGO CONTAINER DESKTOP --}}
                    <div class="relative w-14 h-14 flex items-center justify-center">
                        <div class="absolute inset-0 bg-gradient-to-tr from-yellow-200 to-orange-400 rounded-xl blur opacity-40 group-hover:opacity-80 group-hover:blur-md transition-all duration-500"></div>
                        <div class="relative w-full h-full bg-white border border-yellow-200 rounded-xl flex items-center justify-center overflow-hidden group-hover:scale-[1.02] transition-transform duration-300 shadow-sm">
                            <div class="absolute top-0 left-0 w-full h-1/2 bg-gradient-to-b from-white/50 to-transparent z-20 pointer-events-none"></div>

                            <x-application-logo class="relative z-10 w-full h-full object-contain p-1" />
                        </div>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-lg font-extrabold text-slate-900 tracking-tight leading-none transition-colors">KLINIK</span>
                        <span class="text-xs font-bold text-slate-700 tracking-[0.2em] leading-none mt-1">GIBS</span>
                    </div>
                </a>
            </div>

            <nav class="flex-1 px-4 py-8 space-y-1 overflow-y-auto custom-scrollbar">

                <div class="px-4 mb-4">
                    <span class="text-[10px] font-bold text-slate-600 uppercase tracking-widest">Main Menu</span>
                </div>

                <a href="{{ route('dashboard') }}"
                    class="flex items-center gap-3 px-4 py-3.5 rounded-xl transition-all duration-200 group relative overflow-hidden {{ request()->routeIs('dashboard') ? 'bg-slate-900/10 text-slate-900 shadow-inner font-bold' : 'text-slate-700 hover:bg-slate-900/5 hover:text-slate-900' }}">
                    @if(request()->routeIs('dashboard'))
                    <div class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-8 bg-slate-800 rounded-r-full shadow-[0_0_10px_rgba(30,41,59,0.3)]"></div>
                    @endif
                    <svg class="w-5 h-5 transition-colors {{ request()->routeIs('dashboard') ? 'text-slate-800' : 'text-slate-600 group-hover:text-slate-800' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                    </svg>
                    <span class="text-sm">Dashboard</span>
                </a>

                <div class="px-4 mt-8 mb-4">
                    <span class="text-[10px] font-bold text-slate-600 uppercase tracking-widest">Layanan Medis</span>
                </div>

                <a href="{{ route('rekam_medis.create') }}"
                    class="flex items-center gap-3 px-4 py-3.5 rounded-xl transition-all duration-200 group relative overflow-hidden {{ request()->routeIs('rekam_medis.create') ? 'bg-slate-900/10 text-slate-900 shadow-inner font-bold' : 'text-slate-700 hover:bg-slate-900/5 hover:text-slate-900' }}">
                    @if(request()->routeIs('rekam_medis.create'))
                    <div class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-8 bg-slate-800 rounded-r-full shadow-[0_0_10px_rgba(30,41,59,0.3)]"></div>
                    @endif
                    <svg class="w-5 h-5 transition-colors {{ request()->routeIs('rekam_medis.create') ? 'text-slate-800' : 'text-slate-600 group-hover:text-slate-800' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <span class="text-sm">Input Rekam Medis</span>
                </a>

                <a href="{{ route('rekam_medis.index') }}"
                    class="flex items-center gap-3 px-4 py-3.5 rounded-xl transition-all duration-200 group relative overflow-hidden {{ request()->routeIs('rekam_medis.index') ? 'bg-slate-900/10 text-slate-900 shadow-inner font-bold' : 'text-slate-700 hover:bg-slate-900/5 hover:text-slate-900' }}">
                    @if(request()->routeIs('rekam_medis.index'))
                    <div class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-8 bg-slate-800 rounded-r-full shadow-[0_0_10px_rgba(30,41,59,0.3)]"></div>
                    @endif
                    <svg class="w-5 h-5 transition-colors {{ request()->routeIs('rekam_medis.index') ? 'text-slate-800' : 'text-slate-600 group-hover:text-slate-800' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="text-sm">Riwayat Medis</span>
                </a>

            </nav>

            <div class="p-4 border-t border-slate-900/10 bg-yellow-500/30">
                <div class="flex items-center justify-between gap-3 p-3 rounded-2xl bg-white/20 border border-yellow-300 group hover:border-yellow-200 hover:shadow-md hover:bg-white/30 transition-all duration-300">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="w-10 h-10 rounded-full bg-slate-800 border-2 border-white/50 flex items-center justify-center text-yellow-400 shrink-0 shadow-sm">
                            <span class="font-bold text-sm">{{ substr(Auth::user()->nama ?? Auth::user()->name ?? 'U', 0, 1) }}</span>
                        </div>
                        <div class="flex flex-col min-w-0">
                            <p class="text-xs font-bold text-slate-900 truncate">
                                {{ Auth::user()->nama ?? Auth::user()->name ?? 'Pengguna' }}
                            </p>
                            <p class="text-[10px] text-slate-700 font-medium truncate capitalize">
                                {{ Auth::user()->role ?? 'Admin' }}
                            </p>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('logout') }}" id="sidebar-logout-form">
                        @csrf
                        <button type="submit" class="p-2 rounded-lg text-slate-700 hover:text-white hover:bg-red-500/90 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-red-400" title="Keluar">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <main class="flex-1 md:ml-72 min-h-screen flex flex-col transition-all duration-300 w-full">

            <div class="md:hidden h-16 bg-white/90 backdrop-blur-md border-b border-gray-100 flex items-center justify-between px-4 sticky top-0 z-30">
                <div class="flex items-center gap-2 group">
                    <div class="relative w-10 h-10 flex items-center justify-center">
                        <div class="absolute inset-0 bg-gradient-to-tr from-yellow-300 to-orange-400 rounded-lg blur opacity-40 group-hover:opacity-80 transition-all duration-500"></div>
                        <div class="relative w-full h-full bg-white border border-yellow-200 rounded-lg flex items-center justify-center overflow-hidden shadow-sm">
                            <div class="absolute top-0 left-0 w-full h-1/2 bg-gradient-to-b from-white/50 to-transparent z-20 pointer-events-none"></div>

                            <x-application-logo class="relative z-10 w-full h-full object-contain p-1" />
                        </div>
                    </div>
                    <span class="font-bold text-lg text-gray-900 tracking-tight">KLINIK GIBS</span>
                </div>
                <button onclick="toggleSidebar()" class="p-2 text-gray-500 hover:bg-yellow-50 hover:text-yellow-600 rounded-lg transition-colors focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
                    </svg>
                </button>
            </div>

            @isset($header)
            <header class="bg-white border-b border-gray-100 sticky top-0 z-20 hidden md:block shadow-[0_4px_20px_-10px_rgba(0,0,0,0.05)]">
                <div class="max-w-[85rem] mx-auto py-5 px-4 sm:px-6 lg:px-8">
                    <div class="flex items-center justify-between">
                        {{ $header }}
                    </div>
                </div>
            </header>
            @endisset

            <div class="flex-1 fade-in p-4 sm:p-6 lg:p-6">
                {{ $slot }}
            </div>

            <footer class="py-6 mt-auto border-t border-gray-100 bg-white md:bg-transparent">
                <div class="max-w-[85rem] mx-auto px-4 text-center">
                    <p class="text-xs font-medium text-gray-500">
                        &copy; {{ date('Y') }} <span class="text-yellow-600 font-bold">Klinik GIBS</span>. All rights reserved.
                    </p>
                </div>
            </footer>
        </main>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.querySelector('aside');
            const overlay = document.getElementById('mobile-overlay');
            if (sidebar.classList.contains('hidden')) {
                sidebar.classList.remove('hidden');
                sidebar.classList.add('flex');
                overlay.classList.remove('hidden');
            } else {
                sidebar.classList.add('hidden');
                sidebar.classList.remove('flex');
                overlay.classList.add('hidden');
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Notifications via SweetAlert2
            @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                showConfirmButton: false,
                timer: 3000,
                toast: true,
                position: 'top-end'
            });
            @endif
            @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: "{{ session('error') }}",
                confirmButtonColor: '#EF4444'
            });
            @endif
            @if(session('warning'))
            Swal.fire({
                icon: 'warning',
                title: 'Perhatian',
                text: "{{ session('warning') }}",
                confirmButtonColor: '#F59E0B'
            });
            @endif
        });

        // Logout Confirmation
        document.addEventListener('submit', function(e) {
            if (e.target && e.target.action && e.target.action.includes('logout')) {
                e.target.preventDefault();
                Swal.fire({
                    title: 'Konfirmasi Keluar',
                    text: "Apakah Anda yakin ingin keluar?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#EAB308', // Warna yellow-500
                    cancelButtonColor: '#9CA3AF',
                    confirmButtonText: 'Ya, Keluar',
                    cancelButtonText: 'Batal',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) e.target.submit();
                });
            }
        });
    </script>
</body>

</html>