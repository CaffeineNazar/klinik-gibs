<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Riwayat Rekam Medis') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="{ 
        isEditModalOpen: false, 
        editData: { id: '', keluhan: '', diagnosa: '', tindakan: '' } 
    }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                @if (session('success'))
                <div class="mb-6 p-4 text-green-700 bg-green-100 rounded-lg font-semibold">
                    {{ session('success') }}
                </div>
                @endif
                @if (session('warning'))
                <div class="mb-6 p-4 text-yellow-700 bg-yellow-100 rounded-lg font-semibold">
                    {{ session('warning') }}
                </div>
                @endif
                @if (session('error'))
                <div class="mb-6 p-4 text-red-700 bg-red-100 rounded-lg font-semibold">
                    {{ session('error') }}
                </div>
                @endif

                <div class="overflow-x-auto rounded-lg border border-gray-200">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-100 text-gray-700 uppercase text-sm leading-normal border-b">
                                <th class="py-4 px-4 font-bold">Waktu</th>
                                <th class="py-4 px-4 font-bold">Nama Siswa</th>
                                <th class="py-4 px-4 font-bold w-1/4">Keluhan</th>
                                <th class="py-4 px-4 font-bold w-1/4">Diagnosa & Tindakan</th>
                                <th class="py-4 px-4 font-bold text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700 text-sm font-light">
                            @forelse ($riwayats as $r)
                            <tr class="border-b border-gray-200 hover:bg-yellow-50 transition">
                                <td class="py-3 px-4 align-top">
                                    <div class="font-semibold text-gray-800">{{ \Carbon\Carbon::parse($r->created_at)->format('d M Y') }}</div>
                                    <div class="text-xs font-bold text-yellow-600 mt-1 bg-yellow-100 inline-block px-2 py-1 rounded">
                                        {{ \Carbon\Carbon::parse($r->created_at)->format('H:i') }} WITA
                                    </div>
                                </td>
                                <td class="py-3 px-4 align-top">
                                    <div class="font-bold text-gray-900 text-base">{{ $r->nama_siswa }}</div>
                                    <div class="text-xs text-gray-500 mt-1">Kelas: <span class="font-medium">{{ $r->nama_kelas ?? '-' }}</span></div>
                                </td>
                                <td class="py-3 px-4 align-top text-gray-600 leading-relaxed">{{ $r->keluhan }}</td>
                                <td class="py-3 px-4 align-top">
                                    @if($r->diagnosa)
                                    <div class="font-semibold text-blue-700 text-xs uppercase tracking-wider mb-1">Diagnosa:</div>
                                    <div class="mb-2">{{ $r->diagnosa }}</div>
                                    @endif

                                    @if($r->tindakan)
                                    <div class="font-semibold text-green-700 text-xs uppercase tracking-wider mb-1">Tindakan:</div>
                                    <div>{{ $r->tindakan }}</div>
                                    @endif

                                    @if(!$r->diagnosa && !$r->tindakan)
                                    <span class="text-red-400 font-medium text-xs bg-red-50 py-1 px-2 rounded border border-red-100">Belum diisi</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-center align-middle space-y-2">

                                    {{-- Tombol Sudah Sehat (Hanya H-0) --}}
                                    @if (\Carbon\Carbon::parse($r->tanggal)->isToday())
                                    <form action="{{ route('rekam_medis.sehat', $r->id_siswa) }}" method="POST" class="block" onsubmit="return confirm('Apakah Anda yakin siswa ini sudah kembali ke kelas? Ini akan mematikan notifikasi sakit di aplikasi guru.');">
                                        @csrf
                                        <button type="submit" class="w-full bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-bold py-1.5 px-3 rounded-lg shadow-sm transition-all transform hover:scale-105 flex items-center justify-center gap-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            Sembuh
                                        </button>
                                    </form>
                                    @else
                                    <div class="text-gray-400 text-xs italic bg-gray-100 py-1.5 px-3 rounded-md block w-full text-center">
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
                                        class="w-full text-xs bg-blue-50 text-blue-600 border border-blue-200 hover:bg-blue-100 hover:text-blue-800 font-semibold py-1.5 px-3 rounded-lg transition-colors shadow-sm inline-flex items-center justify-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                        </svg>
                                        Edit Data
                                    </button>

                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="py-12 px-4 text-center text-gray-500">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <p class="mt-2 font-semibold">Belum ada riwayat rekam medis.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-6">
                    {{ $riwayats->links() }}
                </div>

            </div>
        </div>

        <div x-show="isEditModalOpen" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">

                <div x-show="isEditModalOpen" @click="isEditModalOpen = false" x-transition.opacity class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div x-show="isEditModalOpen" x-transition class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl sm:w-full">

                    <form :action="`/rekam-medis/${editData.id}`" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                                    <h3 class="text-lg leading-6 font-bold text-gray-900 border-b pb-2 mb-4" id="modal-title">
                                        Edit Detail Rekam Medis
                                    </h3>

                                    <div class="mb-4">
                                        <label class="block text-gray-700 text-sm font-bold mb-2">Keluhan (Wajib)</label>
                                        <textarea x-model="editData.keluhan" name="keluhan" rows="3" class="shadow-sm border-gray-300 rounded-md w-full py-2 px-3 focus:ring-blue-500 focus:border-blue-500" required></textarea>
                                    </div>
                                    <div class="mb-4">
                                        <label class="block text-gray-700 text-sm font-bold mb-2">Diagnosa</label>
                                        <input type="text" x-model="editData.diagnosa" name="diagnosa" class="shadow-sm border-gray-300 rounded-md w-full py-2 px-3 focus:ring-blue-500 focus:border-blue-500" placeholder="Isi diagnosa...">
                                    </div>
                                    <div class="mb-2">
                                        <label class="block text-gray-700 text-sm font-bold mb-2">Tindakan</label>
                                        <input type="text" x-model="editData.tindakan" name="tindakan" class="shadow-sm border-gray-300 rounded-md w-full py-2 px-3 focus:ring-blue-500 focus:border-blue-500" placeholder="Isi tindakan yang diberikan...">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-2">
                            <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                                Simpan Perubahan
                            </button>
                            <button type="button" @click="isEditModalOpen = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-200 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                Batal
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>