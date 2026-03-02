<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;
use Illuminate\Support\Facades\DB;

class RekamMedisController extends Controller
{
    public function index()
    {
        // 1. Ambil nama database gibs dari konfigurasi (supaya dinamis sesuai .env Anda)
        $dbGibs = config('database.connections.mysql_gibs.database');

        // 2. Mengambil riwayat rekam medis dengan Join Lintas Database
        $riwayats = DB::table('rekam_medis')
            ->join($dbGibs . '.siswa as siswa', 'rekam_medis.id_siswa', '=', 'siswa.id_siswa')
            ->leftJoin($dbGibs . '.kelas as kelas', 'siswa.id_kelas', '=', 'kelas.id_kelas')
            ->select('rekam_medis.*', 'siswa.nama_siswa', 'kelas.nama_kelas')
            ->orderBy('rekam_medis.created_at', 'desc')
            ->paginate(15);

        return view('rekam_medis.index', compact('riwayats'));
    }

    public function create()
    {
        // 1. Ambil data siswa dan nama kelasnya
        $siswasRaw = Siswa::select('siswa.id_siswa', 'siswa.nama_siswa', 'siswa.nis', 'kelas.nama_kelas')
            ->leftJoin('kelas', 'siswa.id_kelas', '=', 'kelas.id_kelas')
            ->orderBy('siswa.nama_siswa', 'asc')
            ->get();

        // 2. Kelompokkan berdasarkan kata pertama dari nama_kelas (Tingkat)
        $siswasGrouped = $siswasRaw->groupBy(function ($item) {
            if (empty($item->nama_kelas)) {
                return 'Tanpa Kelas';
            }
            // Ambil kata pertama (contoh: "XII MIPA 1" menjadi "XII")
            $parts = explode(' ', trim($item->nama_kelas));
            return $parts[0];
        });

        // 3. Urutkan kelompok berdasarkan tingkat (Romawi)
        $urutanTingkat = ['VII' => 1, 'VIII' => 2, 'IX' => 3, 'X' => 4, 'XI' => 5, 'XII' => 6];
        $siswas = $siswasGrouped->sortBy(function ($item, $key) use ($urutanTingkat) {
            return $urutanTingkat[$key] ?? 99; // 99 ditaruh paling bawah (untuk 'Tanpa Kelas')
        });

        return view('rekam_medis.create', compact('siswas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_siswa' => 'required',
            'keluhan' => 'required'
        ]);

        try {
            DB::table('rekam_medis')->insert([
                'id_siswa' => $request->id_siswa,
                'id_perawat' => auth()->user()->id_user ?? null,
                'tanggal' => now()->toDateString(),
                'keluhan' => $request->keluhan,
                'diagnosa' => $request->diagnosa,
                'tindakan' => $request->tindakan,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return redirect()->back()->with('success', 'Data rekam medis berhasil disimpan.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function markAsHealthy($id_siswa)
    {
        try {
            $updated = DB::connection('mysql_gibs')->table('kehadiran_harian')
                ->where('id_siswa', $id_siswa)
                ->where('tanggal', now()->toDateString())
                ->update(['status' => 'H']);

            if ($updated) {
                return redirect()->back()->with('success', 'Siswa ditandai sehat. Status absensi hari ini otomatis di-set menjadi Hadir.');
            } else {
                // Jika belum ada data absensi yang dibuat oleh guru di hari itu
                return redirect()->back()->with('warning', 'Siswa ditandai sehat, tetapi belum ada absensi yang dibuat oleh guru hari ini. Guru tetap bisa mengabsen Hadir secara manual.');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
