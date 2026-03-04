<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;
use Illuminate\Support\Facades\DB;

class RekamMedisController extends Controller
{
    public function index(\Illuminate\Http\Request $request)
    {
        $dbGibs = config('database.connections.mysql_gibs.database');

        // 1. Ambil daftar kelas dan ekstrak Tingkat Kelasnya saja (Kata pertama)
        $allKelas = \Illuminate\Support\Facades\DB::connection('mysql_gibs')
            ->table('kelas')
            ->select('nama_kelas')
            ->distinct()
            ->get();

        // Map untuk ambil kata pertama, Unique, dan Sort manual agar rapi
        $tingkatRaw = $allKelas->map(function ($item) {
            if (empty($item->nama_kelas)) return null;
            return strtoupper(explode(' ', trim($item->nama_kelas))[0]);
        })->filter()->unique();

        // Helper untuk sorting angka romawi/latin
        $urutanTingkat = [
            '1' => 1,
            'I' => 1,
            '2' => 2,
            'II' => 2,
            '3' => 3,
            'III' => 3,
            '4' => 4,
            'IV' => 4,
            '5' => 5,
            'V' => 5,
            '6' => 6,
            'VI' => 6,
            '7' => 7,
            'VII' => 7,
            '8' => 8,
            'VIII' => 8,
            '9' => 9,
            'IX' => 9,
            '10' => 10,
            'X' => 10,
            '11' => 11,
            'XI' => 11,
            '12' => 12,
            'XII' => 12
        ];

        $tingkatList = $tingkatRaw->sortBy(function ($item) use ($urutanTingkat) {
            return $urutanTingkat[$item] ?? 999;
        })->values();


        // 2. Siapkan Query Dasar
        $query = \Illuminate\Support\Facades\DB::table('rekam_medis')
            ->join($dbGibs . '.siswa as siswa', 'rekam_medis.id_siswa', '=', 'siswa.id_siswa')
            ->leftJoin($dbGibs . '.kelas as kelas', 'siswa.id_kelas', '=', 'kelas.id_kelas')
            ->leftJoin($dbGibs . '.guru as guru', function ($join) {
                $join->on('kelas.id_kelas', '=', 'guru.id_kelas')
                    ->where('guru.is_hrt', 1);
            })
            ->leftJoin($dbGibs . '.sakit_siswa as sakit', function ($join) {
                $join->on('rekam_medis.id_siswa', '=', 'sakit.id_siswa')
                    ->on('rekam_medis.tanggal', '=', 'sakit.tanggal');
            })
            ->select(
                'rekam_medis.*',
                'siswa.nama_siswa',
                'kelas.nama_kelas',
                'guru.nama_guru as nama_hrt',
                'sakit.status_akhir'
            );

        // 3. Filter Nama
        if ($request->filled('search')) {
            $query->where('siswa.nama_siswa', 'like', '%' . $request->search . '%');
        }

        // 4. Filter Bulan
        if ($request->filled('month')) {
            $year = date('Y', strtotime($request->month));
            $month = date('m', strtotime($request->month));

            $query->whereYear('rekam_medis.created_at', $year)
                ->whereMonth('rekam_medis.created_at', $month);
        }

        // 5. Filter Tingkat Kelas (BARU)
        // Mencari kelas yang nama depannya sesuai dengan tingkat yang dipilih
        if ($request->filled('tingkat')) {
            $query->where(function ($q) use ($request) {
                $q->where('kelas.nama_kelas', 'like', $request->tingkat . ' %') // Contoh: "X %"
                    ->orWhere('kelas.nama_kelas', '=', $request->tingkat);
            });
        }

        $riwayats = $query->orderBy('rekam_medis.created_at', 'desc')
            ->paginate(15)
            ->withQueryString();

        return view('rekam_medis.index', compact('riwayats', 'tingkatList'));
    }

    public function create()
    {
        // 1. Ambil data siswa dan nama kelasnya
        // Tambahkan orderBy kelas.nama_kelas agar urutan sub-kelas (misal: XII MIPA 1, XII MIPA 2) terurut dengan rapi
        $siswasRaw = Siswa::select('siswa.id_siswa', 'siswa.nama_siswa', 'siswa.nis', 'kelas.nama_kelas')
            ->leftJoin('kelas', 'siswa.id_kelas', '=', 'kelas.id_kelas')
            ->orderBy('kelas.nama_kelas', 'asc') // Urutkan berdasarkan nama kelas terlebih dahulu
            ->orderBy('siswa.nama_siswa', 'asc') // Lalu urutkan abjad nama siswa
            ->get();

        // 2. Kelompokkan berdasarkan kata pertama dari nama_kelas (Tingkat)
        $siswasGrouped = $siswasRaw->groupBy(function ($item) {
            if (empty($item->nama_kelas)) {
                return 'Tanpa Kelas';
            }
            // Ambil kata pertama (contoh: "XII MIPA 1" menjadi "XII")
            $parts = explode(' ', trim($item->nama_kelas));

            // Gunakan strtoupper untuk memastikan format huruf konsisten
            return strtoupper($parts[0]);
        });

        // 3. Urutkan kelompok berdasarkan tingkat kelas dari terkecil ke terbesar
        // Diperluas mencakup angka biasa dan Romawi (1-12) untuk berjaga-jaga jika ada perbedaan format
        $urutanTingkat = [
            '1' => 1,
            'I' => 1,
            '2' => 2,
            'II' => 2,
            '3' => 3,
            'III' => 3,
            '4' => 4,
            'IV' => 4,
            '5' => 5,
            'V' => 5,
            '6' => 6,
            'VI' => 6,
            '7' => 7,
            'VII' => 7,
            '8' => 8,
            'VIII' => 8,
            '9' => 9,
            'IX' => 9,
            '10' => 10,
            'X' => 10,
            '11' => 11,
            'XI' => 11,
            '12' => 12,
            'XII' => 12
        ];

        // sortBy secara default akan mengurutkan dari nilai terkecil ke terbesar (ASC)
        $siswas = $siswasGrouped->sortBy(function ($item, $key) use ($urutanTingkat) {
            return $urutanTingkat[$key] ?? 999; // 999 ditaruh paling bawah (untuk 'Tanpa Kelas')
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
