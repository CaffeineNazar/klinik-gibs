<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Riwayat Rekam Medis') }}
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
                                <th class="py-4 px-4 font-bold">Waktu (Real-time)</th>
                                <th class="py-4 px-4 font-bold">Nama Siswa</th>
                                <th class="py-4 px-4 font-bold">Kelas</th>
                                <th class="py-4 px-4 font-bold w-1/4">Keluhan</th>
                                <th class="py-4 px-4 font-bold">Diagnosa & Tindakan</th>
                                <th class="py-4 px-4 font-bold text-center">Aksi Absensi</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700 text-sm font-light">
                            @forelse ($riwayats as $r)
                            <tr class="border-b border-gray-200 hover:bg-yellow-50 transition">
                                <td class="py-3 px-4">
                                    <div class="font-semibold text-gray-800">{{ \Carbon\Carbon::parse($r->created_at)->format('d M Y') }}</div>
                                    <div class="text-xs font-bold text-yellow-600 mt-1 bg-yellow-100 inline-block px-2 py-1 rounded">
                                        {{ \Carbon\Carbon::parse($r->created_at)->format('H:i') }} WITA
                                    </div>
                                </td>
                                <td class="py-3 px-4 font-bold text-gray-900 text-base">
                                    {{ $r->nama_siswa }}
                                </td>
                                <td class="py-3 px-4 font-medium">{{ $r->nama_kelas ?? '-' }}</td>
                                <td class="py-3 px-4 text-gray-600 leading-relaxed">{{ $r->keluhan }}</td>
                                <td class="py-3 px-4">
                                    @if($r->diagnosa)
                                    <div class="font-semibold text-blue-700 text-xs uppercase tracking-wider mb-1">Diagnosa:</div>
                                    <div class="mb-2">{{ $r->diagnosa }}</div>
                                    @endif

                                    @if($r->tindakan)
                                    <div class="font-semibold text-green-700 text-xs uppercase tracking-wider mb-1">Tindakan:</div>
                                    <div>{{ $r->tindakan }}</div>
                                    @endif

                                    @if(!$r->diagnosa && !$r->tindakan)
                                    <span class="text-gray-400 italic">Tidak ada detail opsional</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-center align-middle">
                                    {{-- Logika Tombol Ajaib: Hanya muncul jika tanggal rekam medis adalah HARI INI --}}
                                    @if (\Carbon\Carbon::parse($r->tanggal)->isToday())
                                    <form action="{{ route('rekam_medis.sehat', $r->id_siswa) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin siswa ini sudah kembali sehat? Ini akan men-set status absennya menjadi Hadir.');">
                                        @csrf
                                        <button type="submit" class="bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-bold py-2 px-3 rounded-lg shadow-sm transition-all transform hover:scale-105 flex items-center justify-center gap-2 mx-auto">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            Sudah Sehat
                                        </button>
                                    </form>
                                    @else
                                    <div class="text-gray-400 text-xs italic bg-gray-100 py-1.5 px-3 rounded-md inline-block">
                                        Lewat Tanggal
                                    </div>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="py-12 px-4 text-center text-gray-500">
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
    </div>
</x-app-layout>