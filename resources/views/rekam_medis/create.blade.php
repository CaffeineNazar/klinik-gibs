<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 w-full">
            <div>
                <h2 class="font-extrabold text-2xl text-slate-800 tracking-tight">
                    {{ __('Input Rekam Medis') }}
                </h2>
                <p class="text-sm text-slate-500 mt-1">Cari siswa dan catat keluhan medis hari ini.</p>
            </div>
            <div class="inline-flex items-center gap-2 bg-white px-4 py-2.5 rounded-2xl shadow-sm border border-slate-200/60">
                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <span class="text-sm font-bold text-slate-700">Form Baru</span>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6 fade-in">

        @if (session('success'))
        <div class="p-4 flex items-center gap-3 text-teal-800 bg-teal-50 border border-teal-200 rounded-2xl font-semibold shadow-sm animate-fade-in">
            <svg class="w-5 h-5 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            {{ session('success') }}
        </div>
        @endif
        @if (session('error'))
        <div class="p-4 flex items-center gap-3 text-rose-800 bg-rose-50 border border-rose-200 rounded-2xl font-semibold shadow-sm animate-fade-in">
            <svg class="w-5 h-5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            {{ session('error') }}
        </div>
        @endif

        <div class="bg-white overflow-hidden shadow-sm border border-slate-200/60 sm:rounded-3xl p-6 lg:p-8"
            x-data="{ 
                rawSearch: '', 
                search: '', 
                isLoading: false,
                timeout: null,
                selectedTingkat: '', 
                isModalOpen: false, 
                selectedSiswaId: '', 
                selectedSiswaName: '' 
            }"
            x-init="$watch('isModalOpen', value => {
                if (value) {
                    document.body.classList.add('overflow-hidden');
                } else {
                    document.body.classList.remove('overflow-hidden');
                }
            })">

            <div class="mb-10">
                <div class="flex items-center gap-3 mb-5">
                    <div class="flex items-center justify-center w-8 h-8 rounded-full bg-slate-100 text-slate-700 font-bold text-sm">1</div>
                    <label class="block text-slate-800 text-lg font-extrabold">Pilih Tingkat Kelas</label>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
                    @foreach($siswas as $tingkat => $daftarSiswa)
                    <button
                        type="button"
                        @click="
                                selectedTingkat = '{{ $tingkat }}'; 
                                rawSearch = ''; 
                                search = ''; 
                                isLoading = true; 
                                clearTimeout(timeout); 
                                timeout = setTimeout(() => isLoading = false, 400);
                            "
                        :class="selectedTingkat === '{{ $tingkat }}' 
                                ? 'bg-slate-800 text-white border-slate-800 shadow-lg shadow-slate-900/20 ring-2 ring-yellow-400 ring-offset-2 transform scale-[1.02]' 
                                : 'bg-white text-slate-700 border-slate-200 hover:border-yellow-400 hover:shadow-md'"
                        class="border rounded-2xl py-4 px-4 font-semibold text-center flex flex-col items-center justify-center min-h-[90px] transition-all duration-300 ease-in-out cursor-pointer group">

                        <span class="text-lg block" :class="selectedTingkat === '{{ $tingkat }}' ? 'text-white' : 'text-slate-800 group-hover:text-yellow-600'">
                            {{ $tingkat === 'Tanpa Kelas' ? 'Tanpa Kelas' : 'Kelas ' . $tingkat }}
                        </span>

                        <div class="mt-2 flex items-center justify-center px-3 py-1 rounded-full text-xs font-medium transition-colors"
                            :class="selectedTingkat === '{{ $tingkat }}' ? 'bg-slate-700 text-yellow-400' : 'bg-slate-100 text-slate-500'">
                            {{ count($daftarSiswa) }} Siswa
                        </div>
                    </button>
                    @endforeach
                </div>
            </div>

            <div x-show="selectedTingkat !== ''" style="display: none;"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform -translate-y-4"
                x-transition:enter-end="opacity-100 transform translate-y-0"
                class="border-t border-dashed border-slate-200 pt-8 mb-8">

                <div class="flex items-center gap-3 mb-5">
                    <div class="flex items-center justify-center w-8 h-8 rounded-full bg-slate-100 text-slate-700 font-bold text-sm">2</div>
                    <label class="block text-slate-800 text-lg font-extrabold">Cari & Pilih Siswa</label>
                </div>

                <div class="relative max-w-2xl">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <input x-model="rawSearch"
                        @input="
                            isLoading = true; 
                            clearTimeout(timeout); 
                            timeout = setTimeout(() => { search = rawSearch; isLoading = false; }, 500)
                        "
                        type="text" placeholder="Ketik nama siswa..."
                        class="w-full shadow-sm border-slate-200 rounded-2xl focus:ring-yellow-400 focus:border-yellow-400 py-3.5 pl-11 pr-12 bg-slate-50 text-slate-800 font-medium placeholder-slate-400 transition-colors">

                    <div x-show="isLoading" class="absolute right-4 top-3.5">
                        <svg class="animate-spin h-6 w-6 text-yellow-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div x-show="selectedTingkat !== ''" style="display: none;"
                x-transition:enter="transition ease-out duration-300 delay-100"
                x-transition:enter-start="opacity-0 transform translate-y-4"
                x-transition:enter-end="opacity-100 transform translate-y-0">

                @foreach($siswas as $tingkat => $daftarSiswa)
                <div class="border border-slate-200 rounded-3xl shadow-sm bg-white overflow-hidden" x-show="selectedTingkat === '{{ $tingkat }}'">

                    <div class="w-full text-left font-bold text-lg bg-slate-50 p-5 flex justify-between items-center border-b border-slate-200">
                        <span class="text-slate-800 flex items-center gap-2">
                            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            {{ $tingkat === 'Tanpa Kelas' ? 'Siswa Tanpa Kelas' : 'Daftar Siswa Tingkat ' . $tingkat }}
                        </span>
                    </div>

                    <div class="bg-white max-h-[450px] overflow-y-auto custom-scrollbar p-2">

                        <div x-show="isLoading" class="p-4 space-y-4">
                            <template x-for="i in 3">
                                <div class="animate-pulse flex justify-between items-center p-4 border border-slate-100 rounded-2xl mb-2">
                                    <div class="flex items-center gap-4 w-full">
                                        <div class="h-12 w-12 bg-slate-200 rounded-2xl"></div>
                                        <div class="w-full">
                                            <div class="h-5 bg-slate-200 rounded w-1/3 mb-2"></div>
                                            <div class="h-4 bg-slate-100 rounded w-1/4"></div>
                                        </div>
                                    </div>
                                    <div class="h-8 w-8 bg-slate-200 rounded-full hidden sm:block"></div>
                                </div>
                            </template>
                        </div>

                        <div x-show="!isLoading" class="space-y-2">
                            @foreach($daftarSiswa as $s)
                            <div class="p-4 border border-transparent border-b-slate-100 last:border-b-transparent hover:bg-yellow-50/50 hover:border-yellow-100 rounded-2xl cursor-pointer transition-all duration-200 flex justify-between items-center group"
                                x-show="search === '' || '{{ strtolower($s->nama_siswa) }}'.includes(search.toLowerCase())"
                                @click="selectedSiswaId = '{{ $s->id_siswa }}'; selectedSiswaName = '{{ $s->nama_siswa }}'; isModalOpen = true;">

                                <div class="flex items-center gap-4">
                                    <div class="h-12 w-12 rounded-2xl bg-slate-100 flex items-center justify-center text-slate-600 font-extrabold text-lg group-hover:bg-white group-hover:text-yellow-600 group-hover:shadow-sm border border-slate-200 group-hover:border-yellow-200 transition-all">
                                        {{ substr($s->nama_siswa, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="font-bold text-slate-800 text-lg group-hover:text-yellow-700 transition-colors">{{ $s->nama_siswa }}</div>
                                        <div class="flex items-center gap-2 mt-1">
                                            <span class="text-[11px] font-bold text-slate-500 bg-slate-100 px-2 py-0.5 rounded-md border border-slate-200">NIS: {{ $s->nis }}</span>
                                            <span class="text-[11px] font-bold text-slate-600 bg-white px-2 py-0.5 rounded-md border border-slate-200 shadow-sm">{{ $s->nama_kelas ?? '-' }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="text-slate-400 bg-white border border-slate-200 p-2.5 rounded-xl hidden sm:block group-hover:border-yellow-400 group-hover:bg-yellow-400 group-hover:text-slate-900 group-hover:shadow-md transition-all">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                </div>
                            </div>
                            @endforeach

                            <div class="p-10 text-center text-slate-500 flex flex-col items-center justify-center"
                                x-show="search !== '' && !Array.from($el.parentElement.children).some(el => el.style.display !== 'none' && !el.classList.contains('p-10'))">
                                <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mb-3">
                                    <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <p>Tidak ada siswa bernama "<span x-text="search" class="font-bold text-slate-800"></span>".</p>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <template x-teleport="body">
                <div x-show="isModalOpen" style="display: none;" class="fixed inset-0 z-[9999] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">

                        <div x-show="isModalOpen" @click="isModalOpen = false"
                            x-transition:enter="ease-out duration-300"
                            x-transition:enter-start="opacity-0"
                            x-transition:enter-end="opacity-100"
                            x-transition:leave="ease-in duration-200"
                            x-transition:leave-start="opacity-100"
                            x-transition:leave-end="opacity-0"
                            class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" aria-hidden="true"></div>

                        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                        <div x-show="isModalOpen"
                            x-transition:enter="ease-out duration-300"
                            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                            x-transition:leave="ease-in duration-200"
                            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                            class="relative inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl sm:w-full border border-slate-100">

                            <form action="{{ route('rekam_medis.store') }}" method="POST">
                                @csrf
                                <div class="bg-white px-4 pt-6 pb-4 sm:p-8 sm:pb-6">
                                    <div class="sm:flex sm:items-start">
                                        <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">

                                            <div class="flex items-center justify-between border-b border-slate-100 pb-4 mb-5">
                                                <h3 class="text-xl leading-6 font-extrabold text-slate-800" id="modal-title">
                                                    Detail Rekam Medis
                                                </h3>
                                                <button type="button" @click="isModalOpen = false" class="text-slate-400 hover:text-rose-500 hover:bg-rose-50 p-2 rounded-full transition-colors">
                                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                </button>
                                            </div>

                                            <div class="mb-6 bg-slate-50 border border-slate-200 p-4 rounded-2xl flex items-center gap-4">
                                                <div class="w-12 h-12 bg-white rounded-xl border border-slate-200 flex items-center justify-center text-slate-600 font-bold shadow-sm">
                                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                    </svg>
                                                </div>
                                                <div>
                                                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Pasien</span> <br />
                                                    <span class="font-extrabold text-lg text-slate-800" x-text="selectedSiswaName"></span>
                                                </div>
                                            </div>

                                            <input type="hidden" name="id_siswa" x-model="selectedSiswaId">

                                            <div class="space-y-5">
                                                <div>
                                                    <label class="block text-slate-700 text-sm font-bold mb-2">Keluhan Utama <span class="text-rose-500">*</span></label>
                                                    <textarea name="keluhan" rows="3" placeholder="Contoh: Pusing, mual, demam..." class="shadow-sm border-slate-300 rounded-xl w-full py-2.5 px-3 focus:ring-yellow-400 focus:border-yellow-400 bg-slate-50 focus:bg-white transition-colors placeholder-slate-400 text-slate-800 font-medium" required></textarea>
                                                </div>

                                                <div>
                                                    <label class="block text-slate-700 text-sm font-bold mb-2 flex items-center justify-between">
                                                        <span>Diagnosa</span>
                                                        <span class="text-xs text-slate-400 font-medium bg-slate-100 px-2 py-0.5 rounded">Opsional</span>
                                                    </label>
                                                    <input type="text" name="diagnosa" placeholder="Contoh: Gejala Tipes..." class="shadow-sm border-slate-300 rounded-xl w-full py-2.5 px-3 focus:ring-yellow-400 focus:border-yellow-400 bg-slate-50 focus:bg-white transition-colors placeholder-slate-400 text-slate-800 font-medium">
                                                </div>

                                                <div>
                                                    <label class="block text-slate-700 text-sm font-bold mb-2 flex items-center justify-between">
                                                        <span>Tindakan / Obat</span>
                                                        <span class="text-xs text-slate-400 font-medium bg-slate-100 px-2 py-0.5 rounded">Opsional</span>
                                                    </label>
                                                    <input type="text" name="tindakan" placeholder="Contoh: Diberikan paracetamol dan istirahat..." class="shadow-sm border-slate-300 rounded-xl w-full py-2.5 px-3 focus:ring-yellow-400 focus:border-yellow-400 bg-slate-50 focus:bg-white transition-colors placeholder-slate-400 text-slate-800 font-medium">
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <div class="bg-slate-50 px-4 py-4 sm:px-8 sm:flex sm:flex-row-reverse border-t border-slate-100">
                                    <button type="submit" class="w-full inline-flex justify-center items-center gap-2 rounded-xl border border-transparent shadow-sm px-6 py-2.5 bg-yellow-400 text-base font-bold text-slate-900 hover:bg-yellow-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-400 sm:ml-3 sm:w-auto sm:text-sm transition-all hover:shadow-md">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                                        </svg>
                                        Simpan Rekam Medis
                                    </button>
                                    <button type="button" @click="isModalOpen = false" class="mt-3 w-full inline-flex justify-center rounded-xl border border-slate-200 shadow-sm px-6 py-2.5 bg-white text-base font-bold text-slate-700 hover:bg-slate-50 hover:text-slate-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-200 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-all">
                                        Batal
                                    </button>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </template>
        </div>
    </div>
</x-app-layout>