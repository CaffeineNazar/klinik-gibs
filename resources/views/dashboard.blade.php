<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 w-full">
            <div>
                <h2 class="font-extrabold text-2xl text-slate-800 tracking-tight">
                    {{ __('Overview Klinik') }}
                </h2>
                <p class="text-sm text-slate-500 mt-1">Pantau status kesehatan siswa GIBS secara realtime.</p>
            </div>
            
        </div>
    </x-slot>

    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <div class="space-y-6">

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 fade-in">

            <div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-3xl p-6 shadow-xl shadow-slate-900/10 text-white relative overflow-hidden group transition-transform duration-300 hover:-translate-y-1">
                <div class="absolute -right-6 -top-6 bg-yellow-500/10 rounded-full w-32 h-32 blur-2xl group-hover:bg-yellow-500/20 transition-all"></div>
                <div class="relative z-10 flex justify-between items-start">
                    <div>
                        <p class="text-slate-300 text-sm font-medium mb-1">Pasien Hari Ini</p>
                        <h3 class="text-5xl font-extrabold tracking-tighter text-white">{{ $todayCount }}</h3>
                    </div>
                    <div class="p-3 bg-slate-700/50 rounded-2xl backdrop-blur-sm border border-slate-600/50 text-yellow-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="relative z-10 mt-6 pt-4 border-t border-slate-700 flex items-center justify-between">
                    <span class="text-xs font-medium text-slate-300 flex items-center gap-1.5">
                        <div class="w-1.5 h-1.5 rounded-full bg-yellow-400"></div>
                        Diperbarui realtime
                    </span>
                </div>
            </div>

            <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-200/60 hover:shadow-xl hover:shadow-teal-500/10 transition-all duration-300 hover:-translate-y-1">
                <div class="flex justify-between items-start mb-4">
                    <div class="p-3 bg-teal-50 rounded-2xl text-teal-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <span class="text-xs font-bold text-teal-700 bg-teal-50 border border-teal-100 px-2.5 py-1 rounded-full">Bulan Ini</span>
                </div>
                <h3 class="text-3xl font-bold text-slate-800 tracking-tight">{{ $monthCount }}</h3>
                <p class="text-sm font-medium text-slate-500 mt-1">Total Kunjungan</p>
            </div>

            <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-200/60 hover:shadow-xl hover:shadow-rose-500/10 transition-all duration-300 hover:-translate-y-1 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-24 h-24 bg-rose-50/50 rounded-bl-full -z-10"></div>
                <div class="flex justify-between items-start mb-4">
                    <div class="p-3 bg-rose-50 rounded-2xl text-rose-500">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <span class="flex h-3 w-3 relative">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-3 w-3 bg-rose-500"></span>
                    </span>
                </div>
                <h3 class="text-3xl font-bold text-rose-600 tracking-tight">{{ $sickCount }}</h3>
                <p class="text-sm font-medium text-slate-500 mt-1">Siswa Sakit / Pantau</p>
            </div>

            <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-200/60 hover:shadow-xl hover:shadow-yellow-500/10 transition-all duration-300 hover:-translate-y-1">
                <div class="flex justify-between items-start mb-4">
                    <div class="p-3 bg-amber-50 rounded-2xl text-amber-500">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                        </svg>
                    </div>
                </div>
                <h3 class="text-xl font-bold text-slate-800 leading-tight truncate" title="{{ $topDisease }}">{{ Str::limit($topDisease, 15) }}</h3>
                <p class="text-sm font-medium text-amber-600 mt-1">Penyakit Terbanyak</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 fade-in" style="animation-delay: 0.1s;">
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-200/60 lg:col-span-2 relative">
                <div class="flex justify-between items-center mb-2">
                    <div>
                        <h3 class="text-lg font-extrabold text-slate-800">Tren Kunjungan</h3>
                        <p class="text-sm text-slate-500">Analisis volume pasien 7 hari terakhir</p>
                    </div>
                </div>
                <div id="chartTrend" class="-ml-2 mt-4"></div>
            </div>

            <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-200/60 flex flex-col justify-center">
                <h3 class="text-lg font-extrabold text-slate-800 text-center mb-1">Top Keluhan</h3>
                <p class="text-xs text-slate-500 text-center mb-6">Persentase kasus bulan ini</p>
                <div id="chartKeluhan" class="flex justify-center drop-shadow-sm"></div>
            </div>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 pb-4 fade-in" style="animation-delay: 0.2s;">

            <div class="bg-white rounded-3xl shadow-sm border border-slate-200/60 overflow-hidden xl:col-span-2 flex flex-col">
                <div class="px-6 py-5 border-b border-slate-100 flex justify-between items-center bg-white">
                    <div class="flex items-center gap-3">
                        <div class="bg-rose-100 p-2.5 rounded-xl text-rose-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-extrabold text-slate-800 text-lg">Perhatian Medis</h3>
                            <p class="text-xs text-slate-500">Siswa yang membutuhkan pemantauan hari ini</p>
                        </div>
                    </div>
                    <span class="bg-rose-50 text-rose-600 text-sm px-4 py-1.5 rounded-full font-bold border border-rose-100">{{ count($currentSickStudents) }} Pasien</span>
                </div>

                <div class="overflow-x-auto flex-1 p-2 custom-scrollbar">
                    <table class="w-full text-sm text-left border-collapse">
                        <tbody>
                            @forelse($currentSickStudents as $s)
                            <tr class="group hover:bg-yellow-50/50 transition-all duration-200 border-b border-dashed border-slate-200 last:border-0">
                                <td class="p-4 rounded-l-2xl">
                                    <div class="flex items-center gap-4">
                                        <div class="h-12 w-12 rounded-2xl bg-slate-100 flex items-center justify-center text-slate-700 font-extrabold text-lg border border-slate-200 group-hover:border-yellow-300 group-hover:bg-white transition-colors">
                                            {{ substr($s->nama_siswa, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="font-bold text-slate-800 text-base">{{ $s->nama_siswa }}</div>
                                            <div class="flex items-center gap-2 mt-1">
                                                <span class="text-xs font-semibold bg-slate-100 text-slate-600 px-2.5 py-0.5 rounded-md">{{ $s->nama_kelas }}</span>
                                                <span class="text-xs font-semibold bg-rose-50 text-rose-600 px-2.5 py-0.5 rounded-md border border-rose-100">{{ $s->keterangan }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="p-4 align-middle">
                                    <div class="text-slate-800 font-semibold">{{ \Carbon\Carbon::parse($s->tanggal)->format('d M Y') }}</div>
                                    <div class="text-xs text-slate-400 font-medium">{{ \Carbon\Carbon::parse($s->tanggal)->diffForHumans() }}</div>
                                </td>
                                <td class="p-4 align-middle text-right rounded-r-2xl">
                                    <div class="text-xs text-slate-500 mb-1">HRT: <span class="font-bold text-slate-700">{{ $s->hrt ?? '-' }}</span></div>
                                    @if($s->no_hp)
                                    <a href="https://wa.me/{{ $s->no_hp }}" target="_blank" class="inline-flex items-center justify-center gap-1.5 bg-yellow-400 hover:bg-yellow-500 text-slate-900 text-xs font-bold px-4 py-2 rounded-xl transition-all shadow-sm focus:ring-2 focus:ring-yellow-400 focus:ring-offset-1">
                                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z" />
                                        </svg>
                                        Hubungi
                                    </a>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="p-10 text-center">
                                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-teal-50 text-teal-500 mb-4">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>
                                    <p class="text-slate-800 font-bold text-lg">Semua Sehat!</p>
                                    <p class="text-slate-500 text-sm mt-1">Tidak ada siswa yang perlu dipantau saat ini.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="bg-white rounded-3xl shadow-sm border border-slate-200/60 flex flex-col h-full">
                <div class="px-6 py-5 border-b border-slate-100 flex justify-between items-center">
                    <h3 class="font-extrabold text-slate-800 text-lg">Log Aktivitas</h3>
                    <a href="{{ route('rekam_medis.index') }}" class="text-slate-400 p-2 hover:bg-slate-100 hover:text-slate-800 rounded-xl transition-colors" title="Lihat Semua">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                        </svg>
                    </a>
                </div>

                <div class="p-6 flex-1 overflow-y-auto custom-scrollbar">
                    <div class="relative border-l-2 border-slate-100 ml-3 space-y-6">
                        @foreach($recentActivities as $index => $r)
                        <div class="relative pl-6 group">
                            <div class="absolute -left-[9px] top-1 h-4 w-4 rounded-full border-2 border-white bg-yellow-400 group-hover:scale-125 transition-transform shadow-sm"></div>

                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="font-bold text-slate-800 text-sm group-hover:text-yellow-600 transition-colors">{{ $r->nama_siswa }}</p>
                                    <p class="text-xs text-slate-500 font-medium">{{ $r->nama_kelas }}</p>
                                </div>
                                <div class="text-right">
                                    <span class="text-xs font-bold text-slate-700 bg-slate-100 px-2 py-1 rounded-lg">{{ \Carbon\Carbon::parse($r->created_at)->format('H:i') }}</span>
                                    <p class="text-[10px] text-slate-400 mt-1 font-medium">{{ \Carbon\Carbon::parse($r->created_at)->diffForHumans() }}</p>
                                </div>
                            </div>
                        </div>
                        @endforeach

                        @if(count($recentActivities) == 0)
                        <div class="text-center py-4 text-sm text-slate-400 italic font-medium">Belum ada aktivitas hari ini.</div>
                        @endif
                    </div>
                </div>
            </div>

        </div>

        <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-200/60 fade-in" style="animation-delay: 0.3s;">
            <div class="flex justify-between items-center mb-2">
                <h3 class="text-lg font-extrabold text-slate-800">Distribusi Kelas Sakit</h3>
            </div>
            <div id="chartKelas" class="h-64 -ml-2"></div>
        </div>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // ApexCharts Global Config matching the Inter font from your layout
            window.Apex = {
                chart: {
                    fontFamily: 'Inter, sans-serif',
                    animations: {
                        enabled: true,
                        easing: 'easeinout',
                        speed: 800
                    }
                },
                tooltip: {
                    theme: 'light',
                    style: {
                        fontSize: '12px',
                        fontFamily: 'Inter, sans-serif'
                    },
                    y: {
                        title: {
                            formatter: function() {
                                return ''
                            }
                        }
                    }
                }
            };

            // 1. Chart Trend Kunjungan (Yellow/Amber Theme)
            var optionsTrend = {
                chart: {
                    type: 'area',
                    height: 320,
                    toolbar: {
                        show: false
                    }
                },
                series: [{
                    name: 'Kunjungan',
                    data: @json($visitors)
                }],
                xaxis: {
                    categories: @json($dates),
                    labels: {
                        style: {
                            colors: '#64748B',
                            fontWeight: 600
                        }
                    },
                    axisBorder: {
                        show: false
                    },
                    axisTicks: {
                        show: false
                    }
                },
                yaxis: {
                    labels: {
                        style: {
                            colors: '#64748B',
                            fontWeight: 600
                        }
                    }
                },
                colors: ['#F59E0B'], // Amber-500
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.45,
                        opacityTo: 0.05,
                        stops: [20, 100]
                    }
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    curve: 'smooth',
                    width: 4
                },
                grid: {
                    borderColor: '#F1F5F9',
                    strokeDashArray: 4,
                    xaxis: {
                        lines: {
                            show: true
                        }
                    },
                    yaxis: {
                        lines: {
                            show: true
                        }
                    }
                },
                markers: {
                    size: 0,
                    colors: ['#F59E0B'],
                    strokeColors: '#fff',
                    strokeWidth: 2,
                    hover: {
                        size: 6
                    }
                }
            };
            new ApexCharts(document.querySelector("#chartTrend"), optionsTrend).render();

            // 2. Chart Top Keluhan (Earthy & Slate Palette)
            var optionsKeluhan = {
                chart: {
                    type: 'donut',
                    height: 320
                },
                series: @json($keluhanData),
                labels: @json($keluhanLabels),
                colors: ['#1E293B', '#EAB308', '#14B8A6', '#F43F5E', '#F97316'], // Slate, Yellow, Teal, Rose, Orange
                legend: {
                    position: 'bottom',
                    markers: {
                        radius: 8
                    },
                    itemMargin: {
                        horizontal: 10,
                        vertical: 5
                    }
                },
                dataLabels: {
                    enabled: false
                },
                plotOptions: {
                    pie: {
                        donut: {
                            size: '75%',
                            labels: {
                                show: true,
                                name: {
                                    color: '#64748B',
                                    fontSize: '12px',
                                    fontWeight: 600
                                },
                                value: {
                                    show: true,
                                    fontSize: '28px',
                                    fontWeight: 800,
                                    color: '#0F172A'
                                },
                                total: {
                                    show: true,
                                    label: 'Total',
                                    color: '#64748B',
                                    fontWeight: 600
                                }
                            }
                        }
                    }
                },
                stroke: {
                    show: true,
                    colors: '#ffffff',
                    width: 3
                }
            };
            new ApexCharts(document.querySelector("#chartKeluhan"), optionsKeluhan).render();

            // 3. Chart Sebaran Kelas (Dark Slate Bars)
            var optionsKelas = {
                chart: {
                    type: 'bar',
                    height: 250,
                    toolbar: {
                        show: false
                    }
                },
                series: [{
                    name: 'Pasien',
                    data: @json($tingkatData)
                }],
                xaxis: {
                    categories: @json($tingkatLabels),
                    labels: {
                        style: {
                            colors: '#64748B',
                            fontWeight: 600
                        }
                    },
                    axisBorder: {
                        show: false
                    },
                    axisTicks: {
                        show: false
                    }
                },
                yaxis: {
                    labels: {
                        style: {
                            colors: '#64748B',
                            fontWeight: 600
                        }
                    }
                },
                colors: ['#1E293B'], // Slate-800
                plotOptions: {
                    bar: {
                        borderRadius: 8,
                        columnWidth: '25%',
                        endingShape: 'rounded'
                    }
                },
                dataLabels: {
                    enabled: false
                },
                grid: {
                    show: true,
                    borderColor: '#F1F5F9',
                    strokeDashArray: 4,
                    yaxis: {
                        lines: {
                            show: true
                        }
                    },
                    xaxis: {
                        lines: {
                            show: false
                        }
                    }
                }
            };
            new ApexCharts(document.querySelector("#chartKelas"), optionsKelas).render();
        });
    </script>
</x-app-layout>