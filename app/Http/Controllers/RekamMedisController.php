<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;
use Illuminate\Support\Facades\DB;

class RekamMedisController extends Controller
{
    public function create()
    {
        // Mengambil daftar siswa dari db_gibs untuk ditampilkan di form
        $siswa = Siswa::orderBy('nama_siswa', 'asc')->get();
        return view('rekam_medis.create', compact('siswa'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_siswa' => 'required',
            'tanggal' => 'required|date',
            'waktu_masuk' => 'required',
            'keluhan' => 'required',
            'status_izin' => 'required|in:sakit,kembali ke kelas'
        ]);

        DB::beginTransaction();
        try {
            // 1. Simpan ke db_klinik (rekam_medis)
            DB::table('rekam_medis')->insert([
                'id_siswa' => $request->id_siswa,
                'id_perawat' => auth()->user()->id_user ?? null,
                'tanggal' => $request->tanggal,
                'waktu_masuk' => $request->waktu_masuk,
                'keluhan' => $request->keluhan,
                'diagnosa' => $request->diagnosa,
                'tindakan' => $request->tindakan,
                'status_izin' => $request->status_izin,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // 2. Jika status 'sakit', sinkronkan ke db_gibs (sakit_siswa)
            if ($request->status_izin === 'sakit') {
                DB::connection('mysql_gibs')->table('sakit_siswa')->insert([
                    'id_siswa' => $request->id_siswa,
                    'tanggal' => $request->tanggal,
                    'waktu_masuk' => $request->waktu_masuk,
                    'status_akhir' => 'Masih Sakit',
                    'keterangan' => 'Dari Klinik: ' . $request->keluhan,
                    'created_by' => auth()->user()->id_user ?? null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            DB::commit();
            return redirect()->back()->with('success', 'Rekam medis berhasil disimpan dan terintegrasi dengan data kehadiran.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
