<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

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

    public function login(Request $request): JsonResponse
    {
        $validated = $request->validate([
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
            'name' => $demoAccount['name'],
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

    public function storeTarif(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'jenis_kendaraan' => ['required', 'in:mobil,motor,becak,truk,bus,sepeda,lainnya'],
            'tarif_per_jam' => ['required', 'integer', 'min:1'],
        ]);

        $tarifId = DB::table('tb_tarif')->insertGetId($validated);

        $this->writeActivity(null, 'Menambahkan tarif '.ucfirst($validated['jenis_kendaraan']));

        return response()->json(['id' => $tarifId], 201);
    }

    public function storeArea(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nama_area' => ['required', 'string', 'max:50'],
            'kapasitas' => ['required', 'integer', 'min:1'],
        ]);

        $areaId = DB::table('tb_area_parkir')->insertGetId([
            ...$validated,
            'terisi' => 0,
        ]);

        $this->writeActivity(null, 'Menambahkan area '.$validated['nama_area']);

        return response()->json(['id' => $areaId], 201);
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
            'plat_nomor' => ['required', 'string', 'max:15'],
            'jenis_kendaraan' => ['required', 'string', 'max:20'],
            'id_area' => ['required', 'integer', 'exists:tb_area_parkir,id_area'],
        ]);

        $vehicle = DB::table('tb_kendaraan')
            ->where('plat_nomor', $validated['plat_nomor'])
            ->first();

        if (! $vehicle) {
            $vehicleId = DB::table('tb_kendaraan')->insertGetId([
                'plat_nomor' => $validated['plat_nomor'],
                'jenis_kendaraan' => $validated['jenis_kendaraan'],
                'nama_pemilik' => 'Umum',
            ]);
        } else {
            $vehicleId = $vehicle->id_kendaraan;
        }

        $tariff = DB::table('tb_tarif')
            ->where('jenis_kendaraan', strtolower($validated['jenis_kendaraan']))
            ->first();

        if (! $tariff) {
            return response()->json(['message' => 'Tarif untuk jenis kendaraan ini belum tersedia.'], 422);
        }

        $transactionId = DB::table('tb_transaksi')->insertGetId([
            'id_kendaraan' => $vehicleId,
            'waktu_masuk' => now(),
            'id_tarif' => $tariff->id_tarif,
            'durasi_jam' => 0,
            'biaya_total' => 0,
            'status' => 'masuk',
            'id_area' => $validated['id_area'],
        ]);

        DB::table('tb_area_parkir')->where('id_area', $validated['id_area'])->increment('terisi');
        $this->writeActivity(null, 'Mencatat kendaraan masuk '.$validated['plat_nomor']);

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
