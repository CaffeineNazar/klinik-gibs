<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Riwayat Rekam Medis') }}
        </h2>
    </x-slot>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/monthSelect/style.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/monthSelect/index.js"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/id.js"></script>

    <div class="py-4" x-data="{ 
        search: new URLSearchParams(window.location.search).get('search') || '',
        month: new URLSearchParams(window.location.search).get('month') || '',
        kelas: new URLSearchParams(window.location.search).get('kelas') || '',
        isLoading: false,
        timeout: null,
        isEditModalOpen: false, 
        editData: { id: '', keluhan: '', diagnosa: '', tindakan: '' },
        
        filterData() {
            this.isLoading = true;
            clearTimeout(this.timeout);
            
            this.timeout = setTimeout(() => {
                let url = new URL(window.location.href);
                
                if (this.search) url.searchParams.set('search', this.search);
                else url.searchParams.delete('search');
                
                if (this.month) url.searchParams.set('month', this.month);
                else url.searchParams.delete('month');

                if (this.kelas) url.searchParams.set('kelas', this.kelas);
                else url.searchParams.delete('kelas');
                
                url.searchParams.delete('page');
                
                window.location.href = url.toString();
            }, 600);
        }
    }">
        <div class="max-w-full mx-auto px-2 sm:px-4 lg:px-4">

            @if (session('success'))
            <div class="mb-4 p-4 text-green-700 bg-green-50 border-l-4 border-green-500 rounded-r-lg font-medium shadow-sm flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                {{ session('success') }}
            </div>
            @endif
            @if (session('warning'))
            <div class="mb-4 p-4 text-yellow-700 bg-yellow-50 border-l-4 border-yellow-500 rounded-r-lg font-medium shadow-sm flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
                {{ session('warning') }}
            </div>
            @endif
            @if (session('error'))
            <div class="mb-4 p-4 text-red-700 bg-red-50 border-l-4 border-red-500 rounded-r-lg font-medium shadow-sm flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
                {{ session('error') }}
            </div>
            @endif

            <div class="mb-4 flex flex-col xl:flex-row gap-3 justify-between items-center bg-white p-3 rounded-xl shadow-sm border border-gray-200">
                <div class="relative w-full xl:w-1/3">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <input type="text" x-model="search" @input="filterData()" placeholder="Cari nama pasien..."
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5 transition-colors">

                    <div x-show="isLoading" class="absolute inset-y-0 right-0 flex items-center pr-3">
                        <svg class="animate-spin h-5 w-5 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>
                </div>

                <div class="w-full xl:w-auto flex flex-col sm:flex-row items-center gap-2">

                    <div class="relative w-full sm:w-auto">
                        <input type="text" placeholder="Semua Bulan" readonly class="w-full sm:w-auto bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5 cursor-pointer transition-colors min-w-[170px] pl-3 pr-10"
                            x-ref="monthPicker"
                            x-init="
                                let fp = flatpickr($refs.monthPicker, {
                                    locale: 'id', // Bahasa Indonesia
                                    plugins: [
                                        new monthSelectPlugin({
                                            shorthand: false, // Menampilkan nama bulan penuh
                                            dateFormat: 'Y-m', // Format yang dikirim ke URL (contoh: 2026-03)
                                            altFormat: 'F Y'   // Format yang ditampilkan di layar (contoh: Maret 2026)
                                        })
                                    ],
                                    altInput: true,
                                    altInputClass: 'w-full sm:w-auto bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5 cursor-pointer transition-colors min-w-[170px]',
                                    defaultDate: month,
                                    placeholder: '-- Semua Bulan --',
                                    onChange: function(selectedDates, dateStr) {
                                        month = dateStr;
                                        filterData();
                                    }
                                });
                                // Pantau jika nilai month di-reset menjadi kosong, bersihkan Flatpickr
                                $watch('month', value => {
                                    if (!value) fp.clear();
                                });
                            "
                            class="hidden">
                    </div>

                    <select x-model="kelas" @change="filterData()" class="w-full sm:w-auto bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5 cursor-pointer transition-colors min-w-[150px]">
                        <option value="">Semua Kelas</option>
                        @foreach($kelasList as $k)
                        <option value="{{ $k->id_kelas }}">{{ $k->nama_kelas }}</option>
                        @endforeach
                    </select>



                    <button type="button" x-show="search !== '' || month !== '' || kelas !== ''" @click="search = ''; month = ''; kelas = ''; filterData()"
                        class="w-full sm:w-auto p-2.5 bg-red-50 text-red-600 hover:bg-red-100 rounded-lg border border-red-200 transition-colors flex items-center justify-center gap-2" title="Reset Semua Filter">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        <span class="sm:hidden font-bold">Reset Filter</span>
                    </button>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden relative">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-left border-collapse">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="py-4 px-5 text-xs font-bold text-gray-500 uppercase tracking-wider w-40">Waktu Diperiksa</th>
                                <th scope="col" class="py-4 px-5 text-xs font-bold text-gray-500 uppercase tracking-wider">Siswa</th>
                                <th scope="col" class="py-4 px-5 text-xs font-bold text-gray-500 uppercase tracking-wider w-1/5">Detail Keluhan</th>
                                <th scope="col" class="py-4 px-5 text-xs font-bold text-gray-500 uppercase tracking-wider w-1/5">Penanganan Medis</th>
                                <th scope="col" class="py-4 px-5 text-xs font-bold text-gray-500 uppercase tracking-wider w-40">HRT</th>
                                <th scope="col" class="py-4 px-5 text-xs font-bold text-gray-500 uppercase tracking-wider text-center w-32">Tindakan</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100 bg-white" x-show="isLoading" style="display: none;">
                            <template x-for="i in 5" :key="i">
                                <tr class="animate-pulse">
                                    <td class="py-4 px-5 align-top">
                                        <div class="h-4 bg-gray-200 rounded w-24 mb-2"></div>
                                        <div class="h-3 bg-gray-200 rounded w-16 mb-2"></div>
                                        <div class="h-4 bg-gray-200 rounded w-20"></div>
                                    </td>
                                    <td class="py-4 px-5 align-top">
                                        <div class="flex items-start">
                                            <div class="h-10 w-10 bg-gray-200 rounded-full mr-3 shrink-0"></div>
                                            <div class="w-full">
                                                <div class="h-5 bg-gray-200 rounded w-3/4 mb-2 mt-1"></div>
                                                <div class="h-4 bg-gray-200 rounded w-1/2 mb-1"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-4 px-5 align-top">
                                        <div class="h-16 bg-gray-100 border border-gray-200 rounded-lg w-full"></div>
                                    </td>
                                    <td class="py-4 px-5 align-top">
                                        <div class="space-y-2">
                                            <div class="h-12 bg-gray-100 border border-gray-200 rounded-lg w-full"></div>
                                        </div>
                                    </td>
                                    <td class="py-4 px-5 align-top">
                                        <div class="h-8 bg-gray-100 border border-gray-200 rounded-lg w-full"></div>
                                    </td>
                                    <td class="py-4 px-5 align-middle">
                                        <div class="h-8 bg-gray-200 rounded-lg w-full mb-2"></div>
                                        <div class="h-8 bg-gray-200 rounded-lg w-full"></div>
                                    </td>
                                </tr>
                            </template>
                        </tbody>

                        <tbody class="divide-y divide-gray-100 bg-white" x-show="!isLoading">
                            @forelse ($riwayats as $r)
                            <tr class="hover:bg-slate-50 transition-colors duration-150 group">

                                {{-- KOLOM WAKTU --}}
                                <td class="py-4 px-5 align-top whitespace-nowrap">
                                    @php
                                    $waktu = \Carbon\Carbon::parse($r->created_at);
                                    $jamString = $waktu->format('H:i');
                                    $jamInt = $waktu->hour;

                                    if ($jamInt >= 5 && $jamInt < 12) {
                                        $periode='Morning' ;
                                        $badgeColor='bg-amber-100 text-amber-700 border-amber-200' ;
                                        $icon='M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z' ;
                                        } elseif ($jamInt>= 12 && $jamInt < 18) {
                                            $periode='Afternoon' ;
                                            $badgeColor='bg-orange-100 text-orange-700 border-orange-200' ;
                                            $icon='M3 15h18M9 21V3m6 18V3M4.22 4.22l15.56 15.56M4.22 19.78L19.78 4.22' ;
                                            } else {
                                            $periode='Evening' ;
                                            $badgeColor='bg-indigo-100 text-indigo-700 border-indigo-200' ;
                                            $icon='M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z' ;
                                            }
                                            @endphp

                                            <div class="flex flex-col items-start">
                                            <span class="text-sm font-bold text-gray-900 mb-1">
                                                {{ $waktu->translatedFormat('d F Y') }}
                                            </span>
                                            <div class="flex items-center text-gray-500 text-xs font-medium mb-2">
                                                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                {{ $jamString }} WITA
                                            </div>
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider border {{ $badgeColor }} shadow-sm">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icon }}"></path>
                                                </svg>
                                                {{ $periode }}
                                            </span>
                </div>
                </td>

                {{-- KOLOM NAMA SISWA & KELAS --}}
                <td class="py-4 px-5 align-top">
                    <div class="flex items-start">
                        <div class="flex-shrink-0 h-10 w-10 rounded-full bg-blue-100 border border-blue-200 flex items-center justify-center text-blue-700 font-bold text-lg mr-3 shadow-sm">
                            {{ strtoupper(substr($r->nama_siswa, 0, 1)) }}
                        </div>
                        <div class="flex flex-col">
                            <div class="font-bold text-gray-900 text-base leading-tight">{{ $r->nama_siswa }}</div>
                            <div class="mt-2 flex flex-col items-start gap-1.5">
                                <span class="bg-gray-100 text-gray-600 border border-gray-200 px-2 py-0.5 rounded-md text-xs font-semibold">
                                    Kelas {{ $r->nama_kelas ?? 'Tanpa Kelas' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </td>

                {{-- KOLOM KELUHAN --}}
                <td class="py-4 px-5 align-top">
                    <div class="bg-red-50 border border-red-100 p-2.5 rounded-lg">
                        <p class="text-sm text-red-900 leading-relaxed">{{ $r->keluhan }}</p>
                    </div>
                </td>

                {{-- KOLOM DIAGNOSA & TINDAKAN --}}
                <td class="py-4 px-5 align-top">
                    <div class="space-y-2">
                        @if($r->diagnosa)
                        <div class="bg-blue-50 border border-blue-100 p-2.5 rounded-lg">
                            <div class="text-[10px] font-bold text-blue-500 uppercase tracking-wider mb-0.5">Diagnosa</div>
                            <div class="text-sm text-blue-900">{{ $r->diagnosa }}</div>
                        </div>
                        @endif

                        @if($r->tindakan)
                        <div class="bg-emerald-50 border border-emerald-100 p-2.5 rounded-lg">
                            <div class="text-[10px] font-bold text-emerald-500 uppercase tracking-wider mb-0.5">Tindakan</div>
                            <div class="text-sm text-emerald-900">{{ $r->tindakan }}</div>
                        </div>
                        @endif

                        @if(!$r->diagnosa && !$r->tindakan)
                        <div class="inline-flex items-center text-xs font-medium text-gray-400 bg-gray-50 py-1 px-2 rounded-md border border-gray-200">
                            <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                            Belum ada detail
                        </div>
                        @endif
                    </div>
                </td>

                {{-- KOLOM HRT (WALI KELAS) --}}
                <td class="py-4 px-5 align-top">
                    <div class="inline-flex items-center text-xs font-medium text-gray-700 bg-white border border-gray-200 px-2.5 py-1.5 rounded-lg shadow-sm">
                        <svg class="w-4 h-4 mr-1.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        {{ $r->nama_hrt ?? 'Belum Diatur' }}
                    </div>
                </td>

                {{-- KOLOM AKSI --}}
                <td class="py-4 px-5 align-middle text-center">
                    <div class="flex flex-col space-y-2">
                        {{-- Tombol Sudah Sehat --}}
                        @if (\Carbon\Carbon::parse($r->tanggal)->isToday())
                        <form action="{{ route('rekam_medis.sehat', $r->id_siswa) }}" method="POST" class="w-full" onsubmit="return confirm('Tandai siswa ini telah sehat & ubah absensi kelas menjadi Hadir?');">
                            @csrf
                            <button type="submit" class="w-full bg-emerald-500 hover:bg-emerald-600 text-white text-xs font-bold py-2 px-3 rounded-lg shadow-sm transition-all focus:ring-2 focus:ring-emerald-300 focus:outline-none flex items-center justify-center gap-1.5 group-hover:shadow">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Sembuh
                            </button>
                        </form>
                        @else
                        <div class="w-full bg-gray-100 text-gray-400 text-[11px] font-semibold py-2 px-2 rounded-lg border border-gray-200 cursor-not-allowed uppercase tracking-wider" title="Hanya bisa mengubah status di hari yang sama">
                            Lewat Tanggal
                        </div>
                        @endif

                        {{-- TOMBOL EDIT DETAIL --}}
                        <button type="button"
                            @click="editData = { 
                                                    id: '{{ $r->id_rekam_medis }}', 
                                                    keluhan: {{ json_encode($r->keluhan ?? '') }}, 
                                                    diagnosa: {{ json_encode($r->diagnosa ?? '') }}, 
                                                    tindakan: {{ json_encode($r->tindakan ?? '') }} 
                                                }; isEditModalOpen = true;"
                            class="w-full bg-white text-blue-600 border border-blue-200 hover:bg-blue-50 hover:border-blue-300 text-xs font-semibold py-2 px-3 rounded-lg shadow-sm transition-all focus:ring-2 focus:ring-blue-100 focus:outline-none inline-flex items-center justify-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            Edit
                        </button>
                    </div>
                </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="py-16 px-4 text-center">
                        <div class="flex flex-col items-center justify-center">
                            <div class="bg-gray-50 rounded-full p-4 mb-4">
                                <svg class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900 mb-1">Data Tidak Ditemukan</h3>
                            <p class="text-sm text-gray-500 max-w-sm mx-auto">Riwayat rekam medis tidak tersedia. Coba hapus/ubah filter pencarian Anda.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
                </tbody>
                </table>
            </div>
        </div>

        <div class="mt-4" x-show="!isLoading">
            {{ $riwayats->links() }}
        </div>
    </div>

    <div x-show="isEditModalOpen" style="display: none;" class="fixed inset-0 z-[9999] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">

            <div x-show="isEditModalOpen" @click="isEditModalOpen = false" x-transition.opacity class="fixed inset-0 bg-gray-900 bg-opacity-50 backdrop-blur-sm transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="isEditModalOpen" x-transition class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl sm:w-full relative">

                <form :action="`/rekam-medis/${editData.id}`" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="bg-white px-6 pt-6 pb-6">
                        <div class="sm:flex sm:items-start">
                            <div class="w-full text-left">
                                <h3 class="text-xl font-bold text-gray-900 border-b border-gray-100 pb-3 mb-5 flex items-center" id="modal-title">
                                    <svg class="w-6 h-6 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    Edit Rekam Medis
                                </h3>

                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-gray-700 text-sm font-bold mb-1.5">Keluhan (Wajib)</label>
                                        <textarea x-model="editData.keluhan" name="keluhan" rows="3" class="shadow-sm border-gray-300 rounded-lg w-full py-2.5 px-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-gray-50 text-gray-900" required></textarea>
                                    </div>
                                    <div>
                                        <label class="block text-gray-700 text-sm font-bold mb-1.5">Diagnosa <span class="text-gray-400 font-normal ml-1">(Opsional)</span></label>
                                        <input type="text" x-model="editData.diagnosa" name="diagnosa" class="shadow-sm border-gray-300 rounded-lg w-full py-2.5 px-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-gray-50 text-gray-900" placeholder="Contoh: Gejala Magh...">
                                    </div>
                                    <div>
                                        <label class="block text-gray-700 text-sm font-bold mb-1.5">Tindakan <span class="text-gray-400 font-normal ml-1">(Opsional)</span></label>
                                        <input type="text" x-model="editData.tindakan" name="tindakan" class="shadow-sm border-gray-300 rounded-lg w-full py-2.5 px-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-gray-50 text-gray-900" placeholder="Contoh: Diberikan Promag...">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-6 py-4 sm:flex sm:flex-row-reverse border-t border-gray-100 gap-3">
                        <button type="submit" class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-5 py-2.5 bg-blue-600 text-sm font-bold text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:w-auto transition-colors">
                            Simpan Perubahan
                        </button>
                        <button type="button" @click="isEditModalOpen = false" class="mt-3 w-full inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-5 py-2.5 bg-white text-sm font-bold text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-200 sm:mt-0 sm:w-auto transition-colors">
                            Batal
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
    </div>
</x-app-layout>