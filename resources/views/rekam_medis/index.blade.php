<x-app-layout>
    <style>
        /* Flatpickr Premium Tweaks */
        .flatpickr-calendar {
            font-family: 'Inter', sans-serif !important;
            border-radius: 1.25rem !important;
            box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1) !important;
            border: 1px solid #f1f5f9 !important;
            padding: 10px !important;
        }

        .flatpickr-day.selected,
        .flatpickr-day.startRange,
        .flatpickr-day.endRange,
        .flatpickr-day.selected.inRange,
        .flatpickr-day.startRange.inRange,
        .flatpickr-day.endRange.inRange,
        .flatpickr-day.selected:focus,
        .flatpickr-day.startRange:focus,
        .flatpickr-day.endRange:focus,
        .flatpickr-day.selected:hover,
        .flatpickr-day.startRange:hover,
        .flatpickr-day.endRange:hover,
        .flatpickr-day.selected.prevMonthDay,
        .flatpickr-day.startRange.prevMonthDay,
        .flatpickr-day.endRange.prevMonthDay,
        .flatpickr-day.selected.nextMonthDay,
        .flatpickr-day.startRange.nextMonthDay,
        .flatpickr-day.endRange.nextMonthDay {
            background: #eab308 !important;
            border-color: #eab308 !important;
            color: #1e293b !important;
            font-weight: 800;
            border-radius: 0.75rem;
        }

        .flatpickr-monthSelect-month.selected {
            background: #eab308 !important;
            border-color: #eab308 !important;
            color: #1e293b !important;
            font-weight: bold;
            border-radius: 0.5rem;
        }

        /* Table Smooth Scroll & Hide Scrollbar */
        .table-scroll::-webkit-scrollbar {
            height: 6px;
        }

        .table-scroll::-webkit-scrollbar-track {
            background: transparent;
        }

        .table-scroll::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }

        .table-scroll::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        /* Hover Reveal Magic */
        .action-reveal {
            opacity: 0;
            transform: translateX(10px);
            transition: all 0.3s ease;
        }

        tr:hover .action-reveal {
            opacity: 1;
            transform: translateX(0);
        }
    </style>

    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 w-full">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <span class="bg-yellow-100 text-yellow-800 text-[10px] font-extrabold px-2.5 py-1 rounded-md uppercase tracking-widest">Database</span>
                    <span class="flex items-center gap-1.5 text-xs font-semibold text-slate-500">
                        <span class="relative flex h-2 w-2"><span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span><span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span></span>
                        Sinkronisasi Aktif
                    </span>
                </div>
                <h2 class="font-extrabold text-3xl text-slate-900 tracking-tight leading-none">
                    Riwayat Rekam Medis
                </h2>
            </div>

            <a href="{{ route('rekam_medis.create') }}" class="inline-flex items-center justify-center gap-2 bg-slate-900 hover:bg-slate-800 text-yellow-400 px-5 py-2.5 rounded-xl font-bold transition-all shadow-lg shadow-slate-900/20 hover:shadow-slate-900/40 hover:-translate-y-0.5 focus:ring-2 focus:ring-slate-900 focus:ring-offset-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path>
                </svg>
                Input Baru
            </a>
        </div>
    </x-slot>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/monthSelect/style.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/monthSelect/index.js"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/id.js"></script>

    <div class="space-y-6 fade-in pb-12" x-data="{ 
        search: new URLSearchParams(window.location.search).get('search') || '',
        month: new URLSearchParams(window.location.search).get('month') || '',
        tingkat: new URLSearchParams(window.location.search).get('tingkat') || '',
        isLoading: false,
        timeout: null,
        isEditModalOpen: false, 
        editData: { id: '', keluhan: '', diagnosa: '', tindakan: '' },
        
        filterData() {
            this.isLoading = true;
            clearTimeout(this.timeout);
            
            this.timeout = setTimeout(() => {
                let url = new URL(window.location.href);
                if (this.search) url.searchParams.set('search', this.search); else url.searchParams.delete('search');
                if (this.month) url.searchParams.set('month', this.month); else url.searchParams.delete('month');
                if (this.tingkat) url.searchParams.set('tingkat', this.tingkat); else url.searchParams.delete('tingkat');
                url.searchParams.delete('page');
                window.location.href = url.toString();
            }, 500);
        }
    }">

        @if (session('success'))
        <div class="p-4 flex items-center gap-3 text-emerald-800 bg-emerald-50 border border-emerald-200 rounded-2xl font-bold shadow-sm animate-fade-in">
            <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            {{ session('success') }}
        </div>
        @endif
        @if (session('warning'))
        <div class="p-4 flex items-center gap-3 text-amber-800 bg-amber-50 border border-amber-200 rounded-2xl font-bold shadow-sm animate-fade-in">
            <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
            </svg>
            {{ session('warning') }}
        </div>
        @endif
        @if (session('error'))
        <div class="p-4 flex items-center gap-3 text-rose-800 bg-rose-50 border border-rose-200 rounded-2xl font-bold shadow-sm animate-fade-in">
            <svg class="w-5 h-5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
            {{ session('error') }}
        </div>
        @endif

        <div class="sticky top-[72px] md:top-0 z-20 pt-4 pb-2 bg-[#F9FAFB]/80 backdrop-blur-xl">
            <div class="bg-white p-2 sm:p-3 rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-200/80 flex flex-col xl:flex-row gap-3 justify-between items-center transition-all">

                <div class="relative w-full xl:w-2/5 group">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                        <svg class="w-5 h-5 text-slate-400 group-focus-within:text-yellow-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <input type="text" x-model="search" @input="filterData()" placeholder="Cari nama pasien atau HRT..."
                        class="bg-transparent border-none text-slate-900 font-semibold text-base focus:ring-0 block w-full pl-12 py-2.5 placeholder-slate-400">
                    <div x-show="isLoading" class="absolute inset-y-0 right-0 flex items-center pr-4">
                        <svg class="animate-spin h-5 w-5 text-yellow-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>
                </div>

                <div class="hidden xl:block w-px h-8 bg-slate-200"></div>

                <div class="w-full xl:w-auto flex flex-col sm:flex-row items-center gap-2">
                    <div class="relative w-full sm:w-auto bg-slate-50 hover:bg-slate-100 transition-colors rounded-xl border border-slate-200">
                        <input type="text" placeholder="Semua Bulan" readonly
                            class="hidden" x-ref="monthPicker"
                            x-init="
                                let fp = flatpickr($refs.monthPicker, {
                                    locale: 'id',
                                    plugins: [new monthSelectPlugin({ shorthand: false, dateFormat: 'Y-m', altFormat: 'F Y' })],
                                    altInput: true,
                                    altInputClass: 'w-full sm:w-auto bg-transparent border-none text-slate-700 font-semibold focus:ring-0 py-2.5 px-4 cursor-pointer min-w-[160px] text-sm text-center sm:text-left',
                                    defaultDate: month,
                                    placeholder: 'Semua Bulan',
                                    onChange: function(d, str) { month = str; filterData(); }
                                });
                                $watch('month', v => { if (!v) fp.clear(); });
                            ">
                    </div>

                    <select x-model="tingkat" @change="filterData()" class="w-full sm:w-auto bg-slate-50 hover:bg-slate-100 border border-slate-200 text-slate-700 font-semibold rounded-xl focus:ring-0 focus:border-slate-200 py-2.5 px-4 cursor-pointer transition-colors min-w-[150px] text-sm text-center sm:text-left">
                        <option value="">Semua Kelas</option>
                        @foreach($tingkatList as $t)
                        <option value="{{ $t }}">Tingkat {{ $t }}</option>
                        @endforeach
                    </select>

                    <button type="button" x-show="search !== '' || month !== '' || tingkat !== ''" @click="search = ''; month = ''; tingkat = ''; filterData()"
                        class="w-full sm:w-auto p-2.5 bg-rose-50 text-rose-600 hover:bg-rose-500 hover:text-white rounded-xl font-bold transition-all flex items-center justify-center gap-2" title="Reset Filter">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        <span class="sm:hidden text-sm">Reset</span>
                    </button>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-[2rem] shadow-sm border border-slate-200/50 overflow-hidden relative">
            <div class="overflow-x-auto table-scroll">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-slate-50/80 border-b border-slate-100 backdrop-blur-sm">
                        <tr>
                            <th scope="col" class="py-5 px-6 text-[10px] font-extrabold text-slate-400 uppercase tracking-[0.2em] w-48">Waktu & Sesi</th>
                            <th scope="col" class="py-5 px-6 text-[10px] font-extrabold text-slate-400 uppercase tracking-[0.2em] w-1/4">Profil Pasien</th>
                            <th scope="col" class="py-5 px-6 text-[10px] font-extrabold text-slate-400 uppercase tracking-[0.2em]">Catatan Klinis</th>
                            <th scope="col" class="py-5 px-6 text-[10px] font-extrabold text-slate-400 uppercase tracking-[0.2em] w-40 text-right">Tindakan</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-50 bg-white" x-show="isLoading" style="display: none;">
                        <template x-for="i in 5" :key="i">
                            <tr class="animate-pulse">
                                <td class="py-6 px-6">
                                    <div class="h-4 bg-slate-200 rounded w-24 mb-2"></div>
                                    <div class="h-3 bg-slate-100 rounded w-16"></div>
                                </td>
                                <td class="py-6 px-6">
                                    <div class="flex gap-4">
                                        <div class="h-12 w-12 bg-slate-200 rounded-2xl shrink-0"></div>
                                        <div>
                                            <div class="h-5 bg-slate-200 rounded w-32 mb-2"></div>
                                            <div class="h-4 bg-slate-100 rounded w-20"></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-6 px-6">
                                    <div class="h-6 bg-slate-100 rounded w-24 mb-2"></div>
                                    <div class="h-4 bg-slate-100 rounded w-3/4"></div>
                                </td>
                                <td class="py-6 px-6 text-right">
                                    <div class="h-10 bg-slate-100 rounded-xl w-24 ml-auto"></div>
                                </td>
                            </tr>
                        </template>
                    </tbody>

                    <tbody class="divide-y divide-slate-100 bg-white" x-show="!isLoading">
                        @forelse ($riwayats as $r)
                        <tr class="hover:bg-slate-50/80 transition-all duration-300 group relative">

                            <td class="py-6 px-6 align-top whitespace-nowrap">
                                @php
                                $waktu = \Carbon\Carbon::parse($r->created_at);
                                $jamInt = $waktu->hour;
                                if ($jamInt >= 5 && $jamInt < 12) { $bg='bg-amber-400' ; $dot='bg-amber-500 shadow-[0_0_8px_rgba(245,158,11,0.5)]' ; $sesi='Pagi' ; }
                                    elseif ($jamInt>= 12 && $jamInt < 18) { $bg='bg-orange-400' ; $dot='bg-orange-500 shadow-[0_0_8px_rgba(249,115,22,0.5)]' ; $sesi='Siang' ; }
                                        else { $bg='bg-indigo-500' ; $dot='bg-indigo-400 shadow-[0_0_8px_rgba(99,102,241,0.5)]' ; $sesi='Malam' ; }
                                        @endphp
                                        <div class="flex flex-col">
                                        <span class="text-[13px] font-bold text-slate-800 mb-1">{{ $waktu->translatedFormat('d M Y') }}</span>
                                        <div class="flex items-center gap-2 text-slate-500 text-xs font-semibold">
                                            <div class="relative flex h-2 w-2 items-center justify-center">
                                                <div class="absolute w-2 h-2 rounded-full {{ $dot }}"></div>
                                            </div>
                                            {{ $waktu->format('H:i') }} ({{ $sesi }})
                                        </div>
            </div>
            </td>

            <td class="py-6 px-6 align-top">
                <div class="flex items-start gap-4">
                    <div class="flex-shrink-0 h-12 w-12 rounded-2xl bg-gradient-to-br from-slate-100 to-slate-200 border border-slate-200 flex items-center justify-center text-slate-700 font-extrabold text-lg shadow-sm group-hover:shadow-md group-hover:border-yellow-300 group-hover:text-yellow-600 group-hover:from-white group-hover:to-yellow-50 transition-all duration-300">
                        {{ strtoupper(substr($r->nama_siswa, 0, 1)) }}
                    </div>
                    <div class="flex flex-col min-w-0">
                        <div class="font-bold text-slate-900 text-base truncate group-hover:text-yellow-600 transition-colors">{{ $r->nama_siswa }}</div>
                        <div class="mt-1 flex items-center gap-2">
                            <span class="text-xs font-semibold text-slate-500">Kls {{ $r->nama_kelas ?? '-' }}</span>
                            <span class="w-1 h-1 bg-slate-300 rounded-full"></span>
                            <span class="text-[11px] font-bold text-slate-400 truncate" title="HRT: {{ $r->nama_hrt ?? '-' }}">{{ $r->nama_hrt ?? 'No HRT' }}</span>
                        </div>
                    </div>
                </div>
            </td>

            <td class="py-6 px-6 align-top">
                <div class="space-y-4">
                    <div>
                        <div class="inline-flex items-center gap-1.5 text-xs font-bold text-rose-600 bg-rose-50 px-2.5 py-1 rounded-md mb-2 border border-rose-100 shadow-sm">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                            Keluhan
                        </div>
                        <p class="text-[13px] font-semibold text-slate-800 leading-relaxed ml-1">{{ $r->keluhan }}</p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mt-3 pt-4 border-t border-dashed border-slate-200">
                        @if($r->diagnosa)
                        <div>
                            <div class="inline-flex items-center gap-1.5 text-xs font-bold text-blue-600 bg-blue-50 px-2.5 py-1 rounded-md mb-2 border border-blue-100 shadow-sm">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                                Diagnosa
                            </div>
                            <p class="text-[13px] font-semibold text-slate-700 ml-1">{{ $r->diagnosa }}</p>
                        </div>
                        @endif

                        @if($r->tindakan)
                        <div>
                            <div class="inline-flex items-center gap-1.5 text-xs font-bold text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-md mb-2 border border-emerald-100 shadow-sm">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                                </svg>
                                Tindakan
                            </div>
                            <p class="text-[13px] font-semibold text-slate-700 ml-1">{{ $r->tindakan }}</p>
                        </div>
                        @endif

                        @if(!$r->diagnosa && !$r->tindakan)
                        <div class="col-span-2 text-xs font-medium text-slate-400 italic ml-1">Belum ada diagnosa/tindakan lanjutan dicatat.</div>
                        @endif
                    </div>
                </div>
            </td>

            <td class="py-6 px-6 align-middle text-right">
                <div class="flex flex-col items-end gap-2 justify-center h-full">

                    @if (\Carbon\Carbon::parse($r->tanggal)->isToday())
                    @if($r->status_akhir == 'Kembali ke Kelas')
                    <div class="inline-flex items-center gap-1.5 bg-emerald-50 text-emerald-600 text-[11px] font-extrabold py-1.5 px-3 rounded-full border border-emerald-100">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Telah Sembuh
                    </div>
                    @else
                    <form id="form-sembuh-{{ $r->id_siswa }}" action="{{ route('rekam_medis.sehat', $r->id_siswa) }}" method="POST">
                        @csrf
                        <button type="button" onclick="confirmSembuh('{{ $r->id_siswa }}')" class="inline-flex items-center gap-1.5 bg-slate-900 hover:bg-slate-800 text-yellow-400 text-[11px] font-bold py-2 px-4 rounded-full shadow-sm transition-all focus:ring-2 focus:ring-slate-900 focus:outline-none hover:-translate-y-0.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Mark Sembuh
                        </button>
                    </form>
                    @endif
                    @else
                    <div class="inline-flex items-center gap-1.5 text-slate-400 text-[10px] font-bold py-1.5 px-3 rounded-full bg-slate-50 border border-slate-100" title="Lewat Tanggal">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Selesai
                    </div>
                    @endif

                    <button type="button"
                        @click='editData = { id: "{{ $r->id_rekam_medis }}", keluhan: {!! json_encode($r->keluhan ?? "", JSON_HEX_APOS) !!}, diagnosa: {!! json_encode($r->diagnosa ?? "", JSON_HEX_APOS) !!}, tindakan: {!! json_encode($r->tindakan ?? "", JSON_HEX_APOS) !!} }; isEditModalOpen = true;'
                        class="action-reveal md:absolute md:right-6 md:top-1/2 md:-translate-y-1/2 md:mt-8 inline-flex items-center gap-1 text-xs font-bold text-slate-500 hover:text-yellow-600 bg-white border border-slate-200 hover:border-yellow-400 py-1.5 px-3 rounded-full shadow-sm transition-all">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                        </svg>
                        Edit Data
                    </button>
                </div>
            </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="py-32 px-4 text-center">
                    <div class="flex flex-col items-center justify-center">
                        <div class="relative w-24 h-24 mb-6">
                            <div class="absolute inset-0 bg-yellow-100 rounded-full blur-xl opacity-50"></div>
                            <div class="relative flex items-center justify-center w-full h-full bg-white rounded-3xl border border-slate-100 shadow-sm transform rotate-3">
                                <svg class="h-10 w-10 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                        </div>
                        <h3 class="text-2xl font-extrabold text-slate-800 mb-2">Riwayat Kosong</h3>
                        <p class="text-sm font-medium text-slate-500 max-w-sm mx-auto">Tidak ada data rekam medis yang sesuai dengan pencarian atau filter Anda saat ini.</p>
                    </div>
                </td>
            </tr>
            @endforelse
            </tbody>
            </table>
        </div>

        @if($riwayats->hasPages())
        <div class="px-6 py-5 border-t border-slate-100 bg-white" x-show="!isLoading">
            {{ $riwayats->links() }}
        </div>
        @endif
    </div>
    </div>

    <div x-show="isEditModalOpen" style="display: none;" class="fixed inset-0 z-[9999] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">

            <div x-show="isEditModalOpen" @click="isEditModalOpen = false"
                x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

            <div x-show="isEditModalOpen"
                x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-8 sm:translate-y-0 sm:scale-95"
                class="inline-block align-bottom bg-white rounded-[2rem] text-left overflow-hidden shadow-[0_20px_60px_-15px_rgba(0,0,0,0.3)] transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-white relative">

                <form :action="`/rekam-medis/${editData.id}`" method="POST">
                    @csrf @method('PUT')
                    <div class="px-8 pt-8 pb-6">
                        <div class="flex flex-col items-center text-center mb-8">
                            <div class="w-16 h-16 bg-slate-50 border border-slate-100 text-slate-800 rounded-2xl flex items-center justify-center mb-4 shadow-sm">
                                <svg class="w-8 h-8 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </div>
                            <h3 class="text-2xl font-extrabold text-slate-900 tracking-tight">Update Data Klinis</h3>
                            <p class="text-sm text-slate-500 mt-1">Perbarui catatan medis siswa.</p>
                        </div>

                        <div class="space-y-6">
                            <div>
                                <label class="block text-slate-800 text-sm font-extrabold mb-2">Keluhan Utama <span class="text-rose-500">*</span></label>
                                <textarea x-model="editData.keluhan" name="keluhan" rows="2" class="border-0 bg-slate-50 shadow-inner rounded-2xl w-full py-3 px-4 focus:ring-2 focus:ring-yellow-400 focus:bg-white text-slate-900 font-medium transition-colors resize-none" required></textarea>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-slate-800 text-sm font-extrabold mb-2">Diagnosa</label>
                                    <input type="text" x-model="editData.diagnosa" name="diagnosa" class="border-0 bg-slate-50 shadow-inner rounded-2xl w-full py-3 px-4 focus:ring-2 focus:ring-yellow-400 focus:bg-white text-slate-900 font-medium transition-colors">
                                </div>
                                <div>
                                    <label class="block text-slate-800 text-sm font-extrabold mb-2">Tindakan</label>
                                    <input type="text" x-model="editData.tindakan" name="tindakan" class="border-0 bg-slate-50 shadow-inner rounded-2xl w-full py-3 px-4 focus:ring-2 focus:ring-yellow-400 focus:bg-white text-slate-900 font-medium transition-colors">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-slate-50/50 px-8 py-5 sm:flex sm:flex-row-reverse border-t border-slate-100 gap-3">
                        <button type="submit" class="w-full inline-flex justify-center items-center rounded-2xl border border-transparent px-8 py-3 bg-slate-900 text-sm font-bold text-yellow-400 hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-900 sm:w-auto transition-all shadow-lg shadow-slate-900/20 hover:-translate-y-0.5">
                            Simpan Perubahan
                        </button>
                        <button type="button" @click="isEditModalOpen = false" class="mt-3 w-full inline-flex justify-center rounded-2xl border border-slate-200 px-8 py-3 bg-white text-sm font-bold text-slate-700 hover:bg-slate-50 focus:outline-none sm:mt-0 sm:w-auto transition-all">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function confirmSembuh(idSiswa) {
            Swal.fire({
                title: 'Tandai Telah Sehat?',
                text: "Siswa ini akan ditandai sembuh dan absensi kembali menjadi Hadir.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#10B981', // Emerald 500
                cancelButtonColor: '#94A3B8', // Slate 400
                confirmButtonText: 'Ya, Tandai Sembuh',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                customClass: {
                    confirmButton: 'rounded-xl shadow-sm',
                    cancelButton: 'rounded-xl'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('form-sembuh-' + idSiswa).submit();
                }
            });
        }
    </script>
</x-app-layout>