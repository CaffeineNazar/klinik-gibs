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
                    <div class="mb-4 p-4 text-green-700 bg-green-100 rounded-lg">
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('rekam_medis.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Nama Siswa</label>
                        <select name="id_siswa" class="shadow border rounded w-full py-2 px-3 text-gray-700 focus:outline-none focus:shadow-outline" required>
                            <option value="">-- Pilih Siswa --</option>
                            @foreach($siswa as $s)
                                <option value="{{ $s->id_siswa }}">{{ $s->nis }} - {{ $s->nama_siswa }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Tanggal</label>
                            <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" class="shadow border rounded w-full py-2 px-3 text-gray-700" required>
                        </div>
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Waktu Masuk</label>
                            <input type="time" name="waktu_masuk" value="{{ date('H:i') }}" class="shadow border rounded w-full py-2 px-3 text-gray-700" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Keluhan</label>
                        <textarea name="keluhan" rows="3" class="shadow border rounded w-full py-2 px-3 text-gray-700" required></textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Diagnosa (Opsional)</label>
                            <input type="text" name="diagnosa" class="shadow border rounded w-full py-2 px-3 text-gray-700">
                        </div>
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Tindakan (Opsional)</label>
                            <input type="text" name="tindakan" class="shadow border rounded w-full py-2 px-3 text-gray-700">
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Keputusan Klinik</label>
                        <div class="flex items-center gap-4">
                            <label class="inline-flex items-center">
                                <input type="radio" name="status_izin" value="kembali ke kelas" class="form-radio text-blue-600" checked>
                                <span class="ml-2">Kembali ke Kelas</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="radio" name="status_izin" value="sakit" class="form-radio text-red-600">
                                <span class="ml-2">Sakit (Otomatis Izin di Presensi)</span>
                            </label>
                        </div>
                    </div>

                    <div class="flex items-center justify-end">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Simpan Rekam Medis
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>