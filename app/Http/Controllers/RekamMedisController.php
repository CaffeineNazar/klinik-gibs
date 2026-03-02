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
            DB::beginTransaction();

            // 1. Simpan rekam medis permanen di aplikasi Klinik (Local DB)
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

            // 2. Tembak ke db_gibs tabel sakit_siswa (AGAR NOTIFIKASI GURU MUNCUL)
            DB::connection('mysql_gibs')->table('sakit_siswa')->insert([
                'id_siswa' => $request->id_siswa,
                'tanggal' => now()->toDateString(),
                'status_akhir' => 'Masih Sakit', // Trigger peringatan "Sedang di klinik"
                'keterangan' => $request->keluhan,
                'created_by' => auth()->user()->id_user ?? null,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Data rekam medis berhasil disimpan & Guru otomatis menerima peringatan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function markAsHealthy($id_siswa)
    {
        try {
            DB::beginTransaction();

            // KITA HAPUS KODE UPDATE KEHADIRAN HARIAN DI SINI!
            // Biarkan absen sebelumnya tetap 'S'.

            // HANYA Update status_akhir di sakit_siswa menjadi 'Kembali ke Kelas'
            $updated = DB::connection('mysql_gibs')->table('sakit_siswa')
                ->where('id_siswa', $id_siswa)
                ->where('tanggal', now()->toDateString())
                ->update([
                    'status_akhir' => 'Kembali ke Kelas',
                    'updated_at' => now()
                ]);

            DB::commit();

            if ($updated) {
                return redirect()->back()->with('success', 'Siswa ditandai sehat. Notifikasi sakit ke guru telah dihentikan.');
            } else {
                return redirect()->back()->with('warning', 'Siswa sudah ditandai sehat sebelumnya.');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'keluhan' => 'required'
        ]);

        try {
            DB::beginTransaction();

            // 1. Update data di database Klinik
            DB::table('rekam_medis')
                ->where('id_rekam_medis', $id)
                ->update([
                    'keluhan' => $request->keluhan,
                    'diagnosa' => $request->diagnosa,
                    'tindakan' => $request->tindakan,
                    'updated_at' => now(),
                ]);

            // 2. Sinkronkan juga perubahan 'keluhan' ke tabel sakit_siswa di db_gibs (jika keluhannya ikut diedit)
            $rekam = DB::table('rekam_medis')->where('id_rekam_medis', $id)->first();

            if ($rekam) {
                DB::connection('mysql_gibs')->table('sakit_siswa')
                    ->where('id_siswa', $rekam->id_siswa)
                    ->where('tanggal', $rekam->tanggal)
                    ->update([
                        'keterangan' => $request->keluhan, // Update keluhan ke guru
                        'updated_at' => now()
                    ]);
            }

            DB::commit();
            return redirect()->back()->with('success', 'Detail rekam medis (Diagnosa/Tindakan) berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengupdate: ' . $e->getMessage());
        }
    }
}
