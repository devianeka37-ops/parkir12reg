<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $sessionUser = $request->session()->get('dashboard_user');

        if (! $sessionUser) {
            return redirect()->route('login');
        }

        $users = DB::table('tb_user')
            ->orderBy('id_user', 'desc')
            ->get();

        $vehicles = DB::table('tb_kendaraan')
            ->orderBy('id_kendaraan', 'desc')
            ->get();

        $tarif = DB::table('tb_tarif')
            ->orderBy('id_tarif', 'desc')
            ->get();

        $area = DB::table('tb_area_parkir')
            ->orderBy('id_area', 'desc')
            ->get();

        $transaksi = DB::table('tb_transaksi')
            ->leftJoin(
                'tb_kendaraan',
                'tb_transaksi.id_kendaraan',
                '=',
                'tb_kendaraan.id_kendaraan'
            )
            ->leftJoin(
                'tb_tarif',
                'tb_transaksi.id_tarif',
                '=',
                'tb_tarif.id_tarif'
            )
            ->leftJoin(
                'tb_area_parkir',
                'tb_transaksi.id_area',
                '=',
                'tb_area_parkir.id_area'
            )
            ->leftJoin(
                'tb_user',
                'tb_transaksi.id_user',
                '=',
                'tb_user.id_user'
            )
            ->select(
                'tb_transaksi.*',
                'tb_kendaraan.plat_nomor',
                'tb_kendaraan.jenis_kendaraan',
                'tb_tarif.tarif_per_jam',
                'tb_area_parkir.nama_area',
                'tb_user.nama_lengkap as nama_petugas'
            )
            ->orderBy('tb_transaksi.id_parkir', 'desc')
            ->get();

        $logs = DB::table('tb_log_aktivitas')
            ->leftJoin('tb_user', 'tb_log_aktivitas.id_user', '=', 'tb_user.id_user')
            ->select(
                'tb_log_aktivitas.waktu_aktivitas as ts',
                'tb_user.nama_lengkap as user',
                'tb_user.role',
                'tb_log_aktivitas.aktivitas as aksi'
            )
            ->orderByDesc('tb_log_aktivitas.id_log')
            ->get()
            ->map(fn ($log) => [
                'ts' => strtotime($log->ts) * 1000,
                'user' => $log->user ?? 'Sistem',
                'role' => ucfirst($log->role ?? 'Sistem'),
                'aksi' => $log->aksi,
            ]);

        return view('dhasboard', compact(
            'users',
            'vehicles',
            'tarif',
            'area',
            'transaksi',
            'logs',
            'sessionUser'
        ));
    }

    public function storeUser(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:50'],
            'username' => ['required', 'string', 'max:50', 'unique:tb_user,username'],
            'password' => ['required', 'string', 'min:6', 'max:100'],
            'role' => ['required', 'in:admin,petugas,owner'],
            'status_aktif' => ['required', 'boolean'],
        ]);

        $userId = DB::table('tb_user')->insertGetId([
            'nama_lengkap' => $validated['nama_lengkap'],
            'username' => $validated['username'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'status_aktif' => $validated['status_aktif'],
        ]);

        $this->writeActivity($userId, 'Menambahkan akun '.$validated['nama_lengkap']);

        return response()->json(['id' => $userId], 201);
    }

    public function updateUser(Request $request, int $id): JsonResponse
    {
        $user = DB::table('tb_user')->where('id_user', $id)->first();

        if (! $user) {
            return response()->json(['message' => 'Akun tidak ditemukan.'], 404);
        }

        $validated = $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:50'],
            'username' => ['required', 'string', 'max:50', Rule::unique('tb_user', 'username')->ignore($id, 'id_user')],
            'password' => ['nullable', 'string', 'min:6', 'max:100'],
            'role' => ['required', 'in:admin,petugas,owner'],
            'status_aktif' => ['required', 'boolean'],
        ]);

        $updates = [
            'nama_lengkap' => $validated['nama_lengkap'],
            'username' => $validated['username'],
            'role' => $validated['role'],
            'status_aktif' => $validated['status_aktif'],
        ];

        if (! empty($validated['password'])) {
            $updates['password'] = Hash::make($validated['password']);
        }

        DB::table('tb_user')->where('id_user', $id)->update($updates);
        $this->writeActivity($id, 'Memperbarui akun '.$validated['nama_lengkap']);

        return response()->json(['message' => 'Akun berhasil diperbarui.']);
    }

    public function deleteUser(Request $request, int $id): JsonResponse
    {
        $user = DB::table('tb_user')->where('id_user', $id)->first();

        if (! $user) {
            return response()->json(['message' => 'Akun tidak ditemukan.'], 404);
        }

        if ($request->session()->get('dashboard_user.username') === $user->username) {
            return response()->json(['message' => 'Akun yang sedang digunakan tidak dapat dihapus.'], 422);
        }

        DB::transaction(function () use ($id, $user): void {
            DB::table('tb_log_aktivitas')
                ->where('id_user', $id)
                ->update(['id_user' => null]);

            DB::table('tb_transaksi')
                ->where('id_user', $id)
                ->update(['id_user' => null]);

            DB::table('tb_user')->where('id_user', $id)->delete();
            $this->writeActivity(null, 'Menghapus akun '.$user->nama_lengkap);
        });

        return response()->json(['message' => 'Akun berhasil dihapus.']);
    }

    public function login(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['nullable', 'string', 'max:100'],
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
            'role' => ['required', 'in:admin,petugas,owner'],
        ]);

        $user = DB::table('tb_user')
            ->where('username', $validated['username'])
            ->where('role', $validated['role'])
            ->first();

        if ($user) {
            if (! $user->status_aktif || ! Hash::check($validated['password'], $user->password)) {
                return response()->json(['message' => 'Username, password, atau role tidak cocok.'], 422);
            }

            $sessionUser = [
                'name' => $user->nama_lengkap,
                'username' => $user->username,
                'role' => ucfirst($user->role),
            ];
            $request->session()->put('dashboard_user', $sessionUser);

            return response()->json($sessionUser);
        }

        $demoAccounts = [
            'admin' => ['name' => 'Devian Eka', 'password' => 'admin123', 'role' => 'admin'],
            'petugas' => ['name' => 'Joko Susilo', 'password' => 'petugas123', 'role' => 'petugas'],
            'owner' => ['name' => 'Hendra Wijaya', 'password' => 'owner123', 'role' => 'owner'],
        ];
        $demoAccount = $demoAccounts[$validated['username']] ?? null;

        if (! $demoAccount
            || $demoAccount['role'] !== $validated['role']
            || $demoAccount['password'] !== $validated['password']) {
            return response()->json(['message' => 'Username, password, atau role tidak cocok.'], 422);
        }

        $sessionUser = [
            'name' => $validated['name'] ?: $demoAccount['name'],
            'username' => $validated['username'],
            'role' => ucfirst($demoAccount['role']),
        ];
        $request->session()->put('dashboard_user', $sessionUser);

        return response()->json($sessionUser);
    }

    public function logout(Request $request)
    {
        $request->session()->forget('dashboard_user');

        return redirect()->route('login');
    }

    public function storeArea(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nama_area' => ['required', 'string', 'max:100'],
            'kapasitas' => ['required', 'integer', 'min:1'],
        ]);

        $idArea = DB::table('tb_area_parkir')->insertGetId([
            'nama_area' => $validated['nama_area'],
            'kapasitas' => $validated['kapasitas'],
            'terisi' => 0,
        ]);

        return response()->json([
            'message' => 'Area berhasil ditambahkan.',
            'id' => $idArea,
        ], 201);
    }

    public function updateArea(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'nama_area' => ['required', 'string', 'max:100'],
            'kapasitas' => ['required', 'integer', 'min:1'],
        ]);

        $area = DB::table('tb_area_parkir')
            ->where('id_area', $id)
            ->first();

        if (! $area) {
            return response()->json([
                'message' => 'Area tidak ditemukan.',
            ], 404);
        }

        // Kapasitas tidak boleh lebih kecil dari jumlah kendaraan yang sedang parkir
        if ($validated['kapasitas'] < $area->terisi) {
            return response()->json([
                'message' => 'Kapasitas tidak boleh lebih kecil dari jumlah kendaraan yang sedang parkir.',
            ], 422);
        }

        DB::table('tb_area_parkir')
            ->where('id_area', $id)
            ->update([
                'nama_area' => $validated['nama_area'],
                'kapasitas' => $validated['kapasitas'],
            ]);

        return response()->json([
            'message' => 'Area berhasil diperbarui.',
        ]);
    }

    public function deleteArea(Request $request, int $id): JsonResponse
    {
        $area = DB::table('tb_area_parkir')
            ->where('id_area', $id)
            ->first();

        if (! $area) {
            return response()->json([
                'message' => 'Area tidak ditemukan.',
            ], 404);
        }

        // Tidak boleh menghapus area kalau masih ada kendaraan di dalamnya
        if ((int) $area->terisi > 0) {
            return response()->json([
                'message' => 'Area tidak bisa dihapus karena masih ada kendaraan yang parkir.',
            ], 422);
        }

        // Tidak boleh menghapus area kalau masih ada transaksi aktif
        $dipakai = DB::table('tb_transaksi')
            ->where('id_area', $id)
            ->where('status', 'masuk')
            ->exists();

        if ($dipakai) {
            return response()->json([
                'message' => 'Area tidak bisa dihapus karena masih digunakan oleh kendaraan yang sedang parkir.',
            ], 422);
        }

        // Hapus area
        DB::table('tb_area_parkir')
            ->where('id_area', $id)
            ->delete();

        return response()->json([
            'message' => 'Area berhasil dihapus.',
        ]);
    }

    public function storeKendaraan(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'plat_nomor' => ['required', 'string', 'max:15', 'unique:tb_kendaraan,plat_nomor'],
            'jenis_kendaraan' => ['required', 'string', 'max:20'],
            'nama_pemilik' => ['required', 'string', 'max:100'],
        ]);

        $vehicleId = DB::table('tb_kendaraan')->insertGetId($validated);

        $this->writeActivity(null, 'Menambahkan kendaraan '.$validated['plat_nomor']);

        return response()->json(['id' => $vehicleId], 201);
    }

    public function storeTransaksi(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'plat_nomor' => ['required', 'string', 'max:15', 'exists:tb_kendaraan,plat_nomor'],
            'id_area' => ['required', 'integer', 'exists:tb_area_parkir,id_area'],
        ]);

        $vehicle = DB::table('tb_kendaraan')
            ->where('plat_nomor', $validated['plat_nomor'])
            ->first();

        $tariff = DB::table('tb_tarif')
            ->whereRaw('LOWER(TRIM(jenis_kendaraan)) = ?', [
                mb_strtolower(trim($vehicle->jenis_kendaraan), 'UTF-8'),
            ])
            ->first();

        if (! $tariff) {
            return response()->json(['message' => 'Tarif untuk jenis kendaraan ini belum tersedia.'], 422);
        }

        $transactionId = DB::table('tb_transaksi')->insertGetId([
            'id_kendaraan' => $vehicle->id_kendaraan,
            'waktu_masuk' => now(),
            'id_tarif' => $tariff->id_tarif,
            'durasi_jam' => 0,
            'biaya_total' => 0,
            'status' => 'masuk',
            'id_area' => $validated['id_area'],
        ]);

        DB::table('tb_area_parkir')->where('id_area', $validated['id_area'])->increment('terisi');
        $this->writeActivity(null, 'Mencatat kendaraan masuk '.$vehicle->plat_nomor);

        return response()->json(['id' => $transactionId], 201);
    }

    public function closeTransaksi(int $id): JsonResponse
    {
        $transaction = DB::table('tb_transaksi')->where('id_parkir', $id)->first();

        if (! $transaction || $transaction->status === 'keluar') {
            return response()->json(['message' => 'Transaksi tidak ditemukan atau sudah selesai.'], 422);
        }

        $duration = max(1, now()->diffInHours($transaction->waktu_masuk));
        $tariff = DB::table('tb_tarif')->where('id_tarif', $transaction->id_tarif)->first();
        $total = $duration * ($tariff->tarif_per_jam ?? 0);

        DB::table('tb_transaksi')->where('id_parkir', $id)->update([
            'waktu_keluar' => now(),
            'durasi_jam' => $duration,
            'biaya_total' => $total,
            'status' => 'keluar',
        ]);

        DB::table('tb_area_parkir')
            ->where('id_area', $transaction->id_area)
            ->where('terisi', '>', 0)
            ->decrement('terisi');

        $this->writeActivity(null, 'Mencatat kendaraan keluar #'.$id);

        return response()->json(['total' => $total], 200);
    }

    private function writeActivity(?int $userId, string $activity): void
    {
        DB::table('tb_log_aktivitas')->insert([
            'id_user' => $userId,
            'aktivitas' => $activity,
            'waktu_aktivitas' => now(),
        ]);
    }
}
