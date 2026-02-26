<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Klinik') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-bold mb-4">Status Autentikasi: <span class="text-green-600">Berhasil Login!</span></h3>
                    
                    <div class="bg-gray-100 p-4 rounded-md">
                        <p><strong>Nama Lengkap:</strong> {{ Auth::user()->nama }}</p>
                        <p><strong>Username:</strong> {{ Auth::user()->username }}</p>
                        <p class="mt-2">
                            <strong>Role Akun:</strong> 
                            <span class="px-3 py-1 bg-blue-500 text-white rounded-full text-sm font-semibold uppercase">
                                {{ Auth::user()->role }}
                            </span>
                        </p>
                    </div>

                    @if(Auth::user()->role == 'klinik')
                        <div class="mt-6 p-4 bg-green-100 border-l-4 border-green-500 text-green-700">
                            <strong>Akses Diterima!</strong> Anda berhasil masuk sebagai Perawat/Klinik. Anda sekarang memiliki hak untuk menginput status kesehatan siswa.
                        </div>
                    @else
                        <div class="mt-6 p-4 bg-red-100 border-l-4 border-red-500 text-red-700">
                            <strong>Peringatan!</strong> Akun Anda terdeteksi sebagai <b>{{ Auth::user()->role }}</b>. Anda mungkin tidak memiliki akses penuh di aplikasi Klinik ini.
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>