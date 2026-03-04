<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Klinik') }}
        </h2>
    </x-slot>

    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-blue-500">
                    <div class="text-gray-500 text-sm font-medium uppercase mb-1">Pasien Hari Ini</div>
                    <div class="text-3xl font-bold text-gray-800">{{ $todayCount }}</div>
                    <div class="text-xs text-blue-500 mt-1 font-semibold">Update Realtime</div>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-emerald-500">
                    <div class="text-gray-500 text-sm font-medium uppercase mb-1">Total Bulan Ini</div>
                    <div class="text-3xl font-bold text-gray-800">{{ $monthCount }}</div>
                    <div class="text-xs text-gray-400 mt-1">Kunjungan</div>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-red-500">
                    <div class="text-gray-500 text-sm font-medium uppercase mb-1">Siswa Sedang Sakit</div>
                    <div class="text-3xl font-bold text-red-600">{{ $sickCount }}</div>
                    <div class="text-xs text-red-400 mt-1 font-semibold">Butuh Pemantauan</div>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-purple-500">
                    <div class="text-gray-500 text-sm font-medium uppercase mb-1">Penyakit Terbanyak</div>
                    <div class="text-2xl font-bold text-gray-800 truncate" title="{{ $topDisease }}">{{ Str::limit($topDisease, 12) }}</div>
                    <div class="text-xs text-purple-500 mt-1">Dominasi Bulan Ini</div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                
                <div class="bg-white p-6 rounded-xl shadow-sm lg:col-span-2">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Tren Kunjungan (7 Hari Terakhir)</h3>
                    <div id="chartTrend"></div>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-sm">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Top 5 Keluhan Bulan Ini</h3>
                    <div id="chartKeluhan"></div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-sm mb-8">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Analisis Kunjungan per Tingkat Kelas</h3>
                <div id="chartKelas" class="h-64"></div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                
                <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-200">
                    <div class="bg-red-50 px-6 py-4 border-b border-red-100 flex justify-between items-center">
                        <h3 class="font-bold text-red-800">Siswa Sedang Sakit Hari Ini</h3>
                        <span class="bg-red-200 text-red-800 text-xs px-2 py-1 rounded-full font-bold">{{ count($currentSickStudents) }} Siswa</span>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead class="bg-gray-50 text-gray-500 uppercase font-bold text-xs">
                                <tr>
                                    <th class="px-6 py-3">Siswa</th>
                                    <th class="px-6 py-3">Sakit Sejak</th>
                                    <th class="px-6 py-3">HRT (Wali)</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($currentSickStudents as $s)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-3">
                                        <div class="font-bold text-gray-800">{{ $s->nama_siswa }}</div>
                                        <div class="text-xs text-gray-500">{{ $s->nama_kelas }}</div>
                                        <div class="text-xs text-red-500 italic mt-1">"{{ $s->keterangan }}"</div>
                                    </td>
                                    <td class="px-6 py-3">
                                        {{ \Carbon\Carbon::parse($s->tanggal)->format('d M Y') }}
                                        <br>
                                        <span class="text-xs text-gray-400">({{ \Carbon\Carbon::parse($s->tanggal)->diffForHumans() }})</span>
                                    </td>
                                    <td class="px-6 py-3">
                                        <div class="text-gray-700 font-semibold">{{ $s->hrt ?? '-' }}</div>
                                        @if($s->no_hp)
                                        <a href="https://wa.me/{{ $s->no_hp }}" target="_blank" class="text-green-600 text-xs hover:underline flex items-center mt-1">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                                            Hubungi
                                        </a>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-8 text-center text-gray-400 italic">Tidak ada siswa yang sedang sakit saat ini.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-200">
                    <div class="bg-gray-50 px-6 py-4 border-b border-gray-100">
                        <h3 class="font-bold text-gray-800">Aktivitas Kunjungan Terakhir</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <tbody class="divide-y divide-gray-100">
                                @foreach($recentActivities as $r)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-3">
                                        <div class="flex items-center">
                                            <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold text-xs mr-3">
                                                {{ substr($r->nama_siswa, 0, 1) }}
                                            </div>
                                            <div>
                                                <div class="font-semibold text-gray-800">{{ $r->nama_siswa }}</div>
                                                <div class="text-xs text-gray-500">{{ $r->nama_kelas }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-3 text-right">
                                        <div class="text-xs font-semibold text-gray-600">{{ \Carbon\Carbon::parse($r->created_at)->format('H:i') }}</div>
                                        <div class="text-[10px] text-gray-400">{{ \Carbon\Carbon::parse($r->created_at)->diffForHumans() }}</div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="bg-gray-50 px-6 py-3 text-center border-t border-gray-100">
                        <a href="{{ route('rekam_medis.index') }}" class="text-blue-600 text-sm font-semibold hover:underline">Lihat Semua Riwayat &rarr;</a>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            
            // 1. Chart Trend Kunjungan
            var optionsTrend = {
                chart: { type: 'area', height: 300, toolbar: { show: false } },
                series: [{ name: 'Kunjungan', data: @json($visitors) }],
                xaxis: { categories: @json($dates) },
                colors: ['#3B82F6'],
                fill: { type: 'gradient', gradient: { shadeIntensity: 1, opacityFrom: 0.7, opacityTo: 0.3 } },
                dataLabels: { enabled: false },
                stroke: { curve: 'smooth' }
            };
            new ApexCharts(document.querySelector("#chartTrend"), optionsTrend).render();

            // 2. Chart Top Keluhan (Donut)
            var optionsKeluhan = {
                chart: { type: 'donut', height: 320 },
                series: @json($keluhanData),
                labels: @json($keluhanLabels),
                colors: ['#F59E0B', '#EF4444', '#10B981', '#3B82F6', '#8B5CF6'],
                legend: { position: 'bottom' },
                dataLabels: { enabled: true }
            };
            new ApexCharts(document.querySelector("#chartKeluhan"), optionsKeluhan).render();

            // 3. Chart Sebaran Kelas (Bar)
            var optionsKelas = {
                chart: { type: 'bar', height: 250, toolbar: { show: false } },
                series: [{ name: 'Total Sakit', data: @json($tingkatData) }],
                xaxis: { categories: @json($tingkatLabels) },
                colors: ['#6366F1'],
                plotOptions: { bar: { borderRadius: 4, columnWidth: '40%' } }
            };
            new ApexCharts(document.querySelector("#chartKelas"), optionsKelas).render();
        });
    </script>
</x-app-layout>