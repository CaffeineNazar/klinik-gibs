<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Konfigurasi nama database Gibs
        $dbGibs = config('database.connections.mysql_gibs.database');

        // --- 1. KARTU RINGKASAN (STATISTIK UTAMA) ---
        
        // Pasien Hari Ini (Total Input di Rekam Medis)
        $todayCount = DB::table('rekam_medis')
            ->whereDate('created_at', Carbon::today())
            ->count();

        // Kunjungan Bulan Ini
        $monthCount = DB::table('rekam_medis')
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();

        // Siswa Sedang Sakit (Status 'Masih Sakit') - KHUSUS HARI INI
        // Menambahkan whereDate('tanggal', Carbon::today())
        $sickCount = DB::connection('mysql_gibs')->table('sakit_siswa')
            ->where('status_akhir', 'Masih Sakit')
            ->whereDate('tanggal', Carbon::today()) // Filter HANYA inputan hari ini
            ->count();

        // Penyakit/Diagnosa Terbanyak Bulan Ini
        $topDiseaseRaw = DB::table('rekam_medis')
            ->select('diagnosa', DB::raw('count(*) as total'))
            ->whereNotNull('diagnosa')
            ->where('diagnosa', '!=', '')
            ->whereMonth('created_at', Carbon::now()->month)
            ->groupBy('diagnosa')
            ->orderByDesc('total')
            ->first();
        $topDisease = $topDiseaseRaw ? $topDiseaseRaw->diagnosa : 'Belum ada data';


        // --- 2. DATA UNTUK GRAFIK (CHARTS) ---

        // A. Tren Kunjungan 7 Hari Terakhir
        $dates = collect();
        $visitors = collect();
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $dates->push($date->format('d M'));
            
            $count = DB::table('rekam_medis')
                ->whereDate('created_at', $date)
                ->count();
            $visitors->push($count);
        }

        // B. Top 5 Keluhan Bulan Ini
        $topKeluhan = DB::table('rekam_medis')
            ->select('keluhan', DB::raw('count(*) as total'))
            ->whereMonth('created_at', Carbon::now()->month)
            ->groupBy('keluhan')
            ->orderByDesc('total')
            ->limit(5)
            ->get();
        
        $keluhanLabels = $topKeluhan->pluck('keluhan');
        $keluhanData = $topKeluhan->pluck('total');

        // C. Sebaran Sakit Berdasarkan Tingkat Kelas
        $sebaranKelasRaw = DB::table('rekam_medis')
            ->join($dbGibs . '.siswa as s', 'rekam_medis.id_siswa', '=', 's.id_siswa')
            ->join($dbGibs . '.kelas as k', 's.id_kelas', '=', 'k.id_kelas')
            ->select('k.nama_kelas', DB::raw('count(*) as total'))
            ->whereMonth('rekam_medis.created_at', Carbon::now()->month)
            ->groupBy('k.nama_kelas')
            ->get();

        $sebaranTingkat = $sebaranKelasRaw->groupBy(function($item) {
            $parts = explode(' ', trim($item->nama_kelas));
            return $parts[0];
        })->map->sum('total');

        $tingkatLabels = $sebaranTingkat->keys();
        $tingkatData = $sebaranTingkat->values();


        // --- 3. TABEL DATA ---

        // A. Daftar Siswa yang Sedang Sakit - KHUSUS HARI INI
        // Juga ditambahkan filter tanggal hari ini agar sinkron dengan Card
        $currentSickStudents = DB::connection('mysql_gibs')->table('sakit_siswa as ss')
            ->join('siswa as s', 'ss.id_siswa', '=', 's.id_siswa')
            ->join('kelas as k', 's.id_kelas', '=', 'k.id_kelas')
            ->leftJoin('guru as g', function($join) {
                $join->on('k.id_kelas', '=', 'g.id_kelas')->where('g.is_hrt', 1);
            })
            ->select('s.nama_siswa', 'k.nama_kelas', 'ss.keterangan', 'ss.tanggal', 'g.nama_guru as hrt', 'g.no_hp')
            ->where('ss.status_akhir', 'Masih Sakit')
            ->whereDate('ss.tanggal', Carbon::today()) // Filter HANYA inputan hari ini
            ->orderBy('ss.tanggal', 'desc')
            ->get();

        // B. Aktivitas Kunjungan Terakhir
        $recentActivities = DB::table('rekam_medis')
            ->join($dbGibs . '.siswa as s', 'rekam_medis.id_siswa', '=', 's.id_siswa')
            ->join($dbGibs . '.kelas as k', 's.id_kelas', '=', 'k.id_kelas')
            ->select('rekam_medis.*', 's.nama_siswa', 'k.nama_kelas')
            ->orderBy('rekam_medis.created_at', 'desc')
            ->limit(5)
            ->get();

        return view('dashboard', compact(
            'todayCount', 'monthCount', 'sickCount', 'topDisease',
            'dates', 'visitors',
            'keluhanLabels', 'keluhanData',
            'tingkatLabels', 'tingkatData',
            'currentSickStudents', 'recentActivities'
        ));
    }
}