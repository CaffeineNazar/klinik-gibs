<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Input Rekam Medis') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
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
                    search: '', 
                    isModalOpen: false, 
                    selectedSiswaId: '', 
                    selectedSiswaName: '' 
                }">

                    <div class="mb-6">
                        <input x-model="search" type="text" placeholder="Cari nama siswa..."
                            class="w-full shadow-sm border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 py-3 px-4">
                    </div>

                    <div class="space-y-4">
                        @foreach($siswas as $tingkat => $daftarSiswa)
                        <div class="border rounded-md shadow-sm" x-data="{ expanded: true }"
                            x-show="search === '' || Array.from($refs.list_{{ Str::slug($tingkat) }}.children).some(el => el.style.display !== 'none')">

                            <button @click="expanded = !expanded" type="button" class="w-full text-left font-bold text-lg bg-gray-100 p-4 flex justify-between items-center rounded-t-md hover:bg-gray-200 transition">
                                <span>{{ $tingkat === 'Tanpa Kelas' ? 'Tanpa Kelas' : 'Tingkat Kelas ' . $tingkat }}</span>
                                <span class="text-sm font-normal text-gray-500 ml-2">({{ count($daftarSiswa) }} Siswa)</span>
                                <div class="ml-auto">
                                    <svg x-show="!expanded" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                    <svg x-show="expanded" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                    </svg>
                                </div>
                            </button>

                            <div x-show="expanded" x-ref="list_{{ Str::slug($tingkat) }}" class="bg-white rounded-b-md">
                                @foreach($daftarSiswa as $s)
                                <div class="p-4 border-b last:border-b-0 hover:bg-blue-50 cursor-pointer transition flex justify-between items-center"
                                    x-show="search === '' || '{{ strtolower($s->nama_siswa) }}'.includes(search.toLowerCase())"
                                    @click="selectedSiswaId = '{{ $s->id_siswa }}'; selectedSiswaName = '{{ $s->nama_siswa }}'; isModalOpen = true;">
                                    <div>
                                        <div class="font-semibold text-gray-800 text-lg">{{ $s->nama_siswa }}</div>
                                        <div class="text-sm text-gray-500 mt-1">
                                            NIS: {{ $s->nis }} | Kelas: <span class="font-medium">{{ $s->nama_kelas ?? '-' }}</span>
                                        </div>
                                    </div>
                                    <div class="text-blue-500 bg-blue-100 p-2 rounded-full hidden sm:block">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                        </svg>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div x-show="isModalOpen" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">

                            <div x-show="isModalOpen" @click="isModalOpen = false" x-transition.opacity class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
                            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                            <div x-show="isModalOpen" x-transition class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl sm:w-full">
                                <form action="{{ route('rekam_medis.store') }}" method="POST">
                                    @csrf
                                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                        <div class="sm:flex sm:items-start">
                                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                                <h3 class="text-lg leading-6 font-bold text-gray-900 border-b pb-2 mb-4" id="modal-title">
                                                    Form Input Rekam Medis
                                                </h3>

                                                <div class="mb-4 bg-gray-100 p-3 rounded text-sm">
                                                    Siswa terpilih: <br />
                                                    <span class="font-bold text-lg text-blue-700" x-text="selectedSiswaName"></span>
                                                </div>

                                                <input type="hidden" name="id_siswa" x-model="selectedSiswaId">

                                                <div class="mb-4">
                                                    <label class="block text-gray-700 text-sm font-bold mb-2">Keluhan (Wajib)</label>
                                                    <textarea name="keluhan" rows="3" class="shadow-sm border-gray-300 rounded-md w-full py-2 px-3 focus:ring-blue-500 focus:border-blue-500" required></textarea>
                                                </div>
                                                <div class="mb-4">
                                                    <label class="block text-gray-700 text-sm font-bold mb-2">Diagnosa (Opsional)</label>
                                                    <input type="text" name="diagnosa" class="shadow-sm border-gray-300 rounded-md w-full py-2 px-3 focus:ring-blue-500 focus:border-blue-500">
                                                </div>
                                                <div class="mb-2">
                                                    <label class="block text-gray-700 text-sm font-bold mb-2">Tindakan (Opsional)</label>
                                                    <input type="text" name="tindakan" class="shadow-sm border-gray-300 rounded-md w-full py-2 px-3 focus:ring-blue-500 focus:border-blue-500">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                                            Simpan Rekam Medis
                                        </button>
                                        <button type="button" @click="isModalOpen = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                            Batal
                                        </button>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>