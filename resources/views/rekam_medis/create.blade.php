<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Input Rekam Medis') }}
        </h2>
    </x-slot>

    <div class="py-1">
        <div class="max-w-full mx-auto px-2 sm:px-2 lg:px-2">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                @if (session('success'))
                <div class="mb-6 p-4 text-green-700 bg-green-100 rounded-lg font-semibold">
                    {{ session('success') }}
                </div>
                @endif
                @if (session('error'))
                <div class="mb-6 p-4 text-red-700 bg-red-100 rounded-lg font-semibold">
                    {{ session('error') }}
                </div>
                @endif

                <div x-data="{ 
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

                    <div class="mb-8">
                        <label class="block text-gray-700 text-base font-bold mb-4 border-b pb-2">1. Pilih Tingkat Kelas</label>

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
                                        ? 'bg-blue-600 text-white border-blue-600 shadow-md ring-2 ring-blue-300 transform scale-105' 
                                        : 'bg-white text-gray-700 border-gray-300 hover:border-blue-400 hover:bg-blue-50 shadow-sm'"
                                class="border rounded-xl py-3 px-4 font-semibold text-center flex flex-col items-center justify-center min-h-[80px] transition-all duration-200 ease-in-out cursor-pointer">
                                <span class="text-lg block">{{ $tingkat === 'Tanpa Kelas' ? 'Tanpa Kelas' : 'Kelas ' . $tingkat }}</span>
                                <span :class="selectedTingkat === '{{ $tingkat }}' ? 'text-blue-100' : 'text-gray-500'" class="text-xs font-normal mt-1">
                                    {{ count($daftarSiswa) }} Siswa
                                </span>
                            </button>
                            @endforeach
                        </div>
                    </div>

                    <div class="mb-6 relative" x-show="selectedTingkat !== ''" style="display: none;" x-transition>
                        <label class="block text-gray-700 text-base font-bold mb-3">2. Cari & Pilih Siswa</label>

                        <input x-model="rawSearch"
                            @input="
                                isLoading = true; 
                                clearTimeout(timeout); 
                                timeout = setTimeout(() => { search = rawSearch; isLoading = false; }, 500)
                            "
                            type="text" placeholder="Ketik nama siswa untuk mencari..."
                            class="w-full shadow-sm border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 py-3 px-4 bg-gray-50 text-lg pr-12">

                        <div x-show="isLoading" class="absolute right-4 top-11">
                            <svg class="animate-spin h-6 w-6 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </div>
                    </div>

                    <div class="space-y-4" x-show="selectedTingkat !== ''" style="display: none;" x-transition>
                        @foreach($siswas as $tingkat => $daftarSiswa)

                        <div class="border rounded-md shadow-sm bg-white" x-show="selectedTingkat === '{{ $tingkat }}'">

                            <div class="w-full text-left font-bold text-lg bg-blue-50 p-4 flex justify-between items-center rounded-t-md border-b border-blue-100">
                                <span class="text-blue-800">{{ $tingkat === 'Tanpa Kelas' ? 'Siswa Tanpa Kelas' : 'Daftar Siswa Tingkat ' . $tingkat }}</span>
                            </div>

                            <div class="bg-white rounded-b-md max-h-[500px] overflow-y-auto relative">

                                <div x-show="isLoading" class="p-4 space-y-4">
                                    <template x-for="i in 3">
                                        <div class="animate-pulse flex justify-between items-center border-b pb-4 last:border-b-0">
                                            <div class="w-full">
                                                <div class="h-5 bg-gray-200 rounded w-1/3 mb-2"></div>
                                                <div class="h-4 bg-gray-200 rounded w-1/2"></div>
                                            </div>
                                            <div class="h-10 w-10 bg-gray-200 rounded-full hidden sm:block"></div>
                                        </div>
                                    </template>
                                </div>

                                <div x-show="!isLoading">
                                    @foreach($daftarSiswa as $s)
                                    <div class="p-4 border-b last:border-b-0 hover:bg-blue-50 cursor-pointer transition flex justify-between items-center group"
                                        x-show="search === '' || '{{ strtolower($s->nama_siswa) }}'.includes(search.toLowerCase())"
                                        @click="selectedSiswaId = '{{ $s->id_siswa }}'; selectedSiswaName = '{{ $s->nama_siswa }}'; isModalOpen = true;">
                                        <div>
                                            <div class="font-semibold text-gray-800 text-lg group-hover:text-blue-700 transition-colors">{{ $s->nama_siswa }}</div>
                                            <div class="text-sm text-gray-500 mt-1">
                                                NIS: {{ $s->nis }} <span class="mx-1">|</span> Kelas: <span class="font-medium text-gray-700">{{ $s->nama_kelas ?? '-' }}</span>
                                            </div>
                                        </div>
                                        <div class="text-blue-500 bg-blue-100 p-2 rounded-full hidden sm:block group-hover:bg-blue-600 group-hover:text-white transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    @endforeach

                                    <div class="p-8 text-center text-gray-500"
                                        x-show="search !== '' && !Array.from($el.parentElement.children).some(el => el.style.display !== 'none' && !el.classList.contains('p-8'))">
                                        Tidak ada siswa yang cocok dengan pencarian "<span x-text="search" class="font-bold"></span>".
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <template x-teleport="body">
                        <div x-show="isModalOpen" style="display: none;" class="fixed inset-0 z-[9999] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">

                                <div x-show="isModalOpen" @click="isModalOpen = false" x-transition.opacity class="fixed inset-0 bg-gray-900 bg-opacity-50 backdrop-blur-sm transition-opacity" aria-hidden="true"></div>
                                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                                <div x-show="isModalOpen" x-transition class="relative inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl sm:w-full">
                                    <form action="{{ route('rekam_medis.store') }}" method="POST">
                                        @csrf
                                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                            <div class="sm:flex sm:items-start">
                                                <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                                                    <h3 class="text-xl leading-6 font-bold text-gray-900 border-b pb-3 mb-4" id="modal-title">
                                                        Form Input Rekam Medis
                                                    </h3>

                                                    <div class="mb-5 bg-blue-50 border border-blue-100 p-4 rounded-lg">
                                                        <span class="text-sm text-gray-600">Siswa yang diperiksa:</span> <br />
                                                        <span class="font-bold text-xl text-blue-800" x-text="selectedSiswaName"></span>
                                                    </div>

                                                    <input type="hidden" name="id_siswa" x-model="selectedSiswaId">

                                                    <div class="mb-4">
                                                        <label class="block text-gray-700 text-sm font-bold mb-2">Keluhan <span class="text-red-500">*</span></label>
                                                        <textarea name="keluhan" rows="3" placeholder="Contoh: Pusing, mual, demam..." class="shadow-sm border-gray-300 rounded-md w-full py-2 px-3 focus:ring-blue-500 focus:border-blue-500" required></textarea>
                                                    </div>
                                                    <div class="mb-4">
                                                        <label class="block text-gray-700 text-sm font-bold mb-2">Diagnosa <span class="text-gray-400 font-normal">(Opsional)</span></label>
                                                        <input type="text" name="diagnosa" placeholder="Contoh: Gejala Tipes..." class="shadow-sm border-gray-300 rounded-md w-full py-2 px-3 focus:ring-blue-500 focus:border-blue-500">
                                                    </div>
                                                    <div class="mb-2">
                                                        <label class="block text-gray-700 text-sm font-bold mb-2">Tindakan <span class="text-gray-400 font-normal">(Opsional)</span></label>
                                                        <input type="text" name="tindakan" placeholder="Contoh: Diberikan paracetamol dan istirahat..." class="shadow-sm border-gray-300 rounded-md w-full py-2 px-3 focus:ring-blue-500 focus:border-blue-500">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t">
                                            <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                                                Simpan Rekam Medis
                                            </button>
                                            <button type="button" @click="isModalOpen = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
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
        </div>
    </div>
</x-app-layout>