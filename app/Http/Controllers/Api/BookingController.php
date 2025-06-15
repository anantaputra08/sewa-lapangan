<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Lapangan;
use App\Models\SessionHour;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class BookingController extends Controller
{

    /**
     * Get all lapangans and their availability for a specific date.
     */
    public function getAvailableLapangans(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $date = $request->date;
        $allLapangans = Lapangan::with('category')->get();

        // Get all session IDs booked on the given date
        $bookedSessionIdsOnDate = Booking::where('date', $date)
            ->whereIn('status', ['pending', 'confirmed'])
            ->select('lapangan_id', 'session_hours_ids')
            ->get()
            ->groupBy('lapangan_id')
            ->map(function ($bookings) {
                return $bookings->pluck('session_hours_ids')->flatten()->unique();
            });

        // Get total number of sessions available
        $totalSessionsCount = SessionHour::count();

        $lapangansWithAvailability = $allLapangans->map(function ($lapangan) use ($bookedSessionIdsOnDate, $totalSessionsCount) {
            $bookedCount = $bookedSessionIdsOnDate->get($lapangan->id, collect())->count();
            $isFullyBooked = ($totalSessionsCount > 0) && ($bookedCount >= $totalSessionsCount);

            return [
                'id' => $lapangan->id,
                'name' => $lapangan->name,
                'category' => $lapangan->category->name ?? null,
                'description' => $lapangan->description,
                'price' => $lapangan->price,
                'photo' => $lapangan->photo_url,
                'status' => $lapangan->status,
                'is_fully_booked_on_date' => $isFullyBooked,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $lapangansWithAvailability,
        ]);
    }
    /**
     * Menampilkan daftar booking.
     * Admin melihat semua booking, user hanya melihat booking miliknya.
     */
    public function index()
    {
        $user = auth()->user();

        // REFACTOR: Menggunakan query builder untuk efisiensi
        $query = Booking::with(['user', 'lapangan']);

        if ($user->role === 'admin') {
            // Admin mendapatkan semua booking
            $bookings = $query->latest()->get();
        } else {
            // User hanya mendapatkan booking miliknya
            $bookings = $query->where('user_id', $user->id)->latest()->get();
        }

        // PERF: Mengambil semua ID jam sesi yang relevan dalam satu query
        $allSessionIds = $bookings->pluck('session_hours_ids')->flatten()->unique()->filter();
        $sessionHoursData = SessionHour::whereIn('id', $allSessionIds)->get()->keyBy('id');

        // REFACTOR: Transformasi data yang lebih efisien tanpa query di dalam loop (N+1 problem)
        $result = $bookings->map(function ($booking) use ($sessionHoursData) {
            // BUG_FIX: Kolom di database adalah `session_hours_ids` (plural)
            $sessionIds = $booking->session_hours_ids ?? [];
            $sessions = collect($sessionIds)->map(function ($id) use ($sessionHoursData) {
                return $sessionHoursData->get($id);
            })->filter();

            return [
                'booking_id' => $booking->id,
                'user' => [
                    'name' => $booking->user->name ?? null,
                    'email' => $booking->user->email ?? null,
                    'phone' => $booking->user->phone ?? null,
                ],
                'lapangan' => [
                    'id' => $booking->lapangan->id ?? null,
                    'name' => $booking->lapangan->name ?? null,
                    'photo' => $booking->lapangan->photo_url ?? null,
                ],
                'date' => Carbon::parse($booking->date)->format('Y-m-d'),
                'start_time' => Carbon::parse($booking->start_time)->format('H:i'),
                'end_time' => Carbon::parse($booking->end_time)->format('H:i'),
                'session_hours' => $sessions->map(fn($s) => [
                    'id' => $s->id,
                    'start_time' => Carbon::parse($s->start_time)->format('H:i'),
                    'end_time' => Carbon::parse($s->end_time)->format('H:i'),
                ]),
                'total_price' => $booking->total_price,
                'status' => $booking->status,
                'payment_status' => $booking->payment_status,
                'created_at' => $booking->created_at,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $result
        ]);
    }

    /**
     * Membuat booking baru.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // BUG_FIX: Nama tabel adalah 'lapangans', bukan 'lapangan'
            'lapangan_id' => 'required|exists:lapangans,id',
            'date' => 'required|date|after_or_equal:today',
            // BUG_FIX: Kolom di database adalah 'session_hours', bukan 'session_hours_id'
            'session_hours_ids' => 'required|array|min:1',
            'session_hours_ids.*' => 'required|exists:session_hours,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $lapangan = Lapangan::findOrFail($request->lapangan_id);
        $sessionHours = SessionHour::whereIn('id', $request->session_hours_ids)->orderBy('start_time')->get();

        // LOGIC: Pastikan semua session ID yang diminta valid dan ditemukan
        if ($sessionHours->count() !== count($request->session_hours_ids)) {
            return response()->json(['success' => false, 'message' => 'Satu atau lebih sesi jam tidak valid.'], 422);
        }

        DB::beginTransaction();
        try {
            // --- Pengecekan Ketersediaan (Locking) ---
            // Cari booking yang sudah ada untuk lapangan dan tanggal yang sama, dan lock baris tersebut untuk mencegah race condition.
            $existingBookings = Booking::where('lapangan_id', $request->lapangan_id)
                ->where('date', $request->date)
                ->whereIn('status', ['pending', 'confirmed']) // Hanya cek status yang aktif
                ->lockForUpdate() // Mencegah user lain booking di waktu yang sama
                ->get();

            $bookedSessionIds = $existingBookings->pluck('session_hours_ids')->flatten()->toArray();

            foreach ($request->session_hours_ids as $sessionId) {
                if (in_array($sessionId, $bookedSessionIds)) {
                    $conflictingSession = $sessionHours->firstWhere('id', $sessionId);
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => 'Lapangan sudah dibooking untuk sesi jam ' . Carbon::parse($conflictingSession->start_time)->format('H:i')
                    ], 409); // 409 Conflict
                }
            }

            // --- Kalkulasi Harga dan Waktu ---
            $basePricePerHour = $lapangan->price;
            $totalPrice = 0;

            foreach ($sessionHours as $session) {
                $priceForSession = $basePricePerHour;
                $startHour = (int) Carbon::parse($session->start_time)->format('H');

                // Logika harga tambahan (contoh)
                if ($startHour >= 15 && $startHour < 18) { // Sesi sore
                    $priceForSession += 25000;
                } elseif ($startHour >= 18) { // Sesi malam
                    $priceForSession += 50000;
                }
                $totalPrice += $priceForSession;
            }

            // REFACTOR: Ambil start_time dari sesi pertama dan end_time dari sesi terakhir
            $startTime = $sessionHours->first()->start_time;
            $endTime = $sessionHours->last()->end_time;

            // --- Membuat Booking ---
            $booking = Booking::create([
                'user_id' => auth()->id(),
                'lapangan_id' => $request->lapangan_id,
                'date' => $request->date,
                'start_time' => $startTime,
                'end_time' => $endTime,
                // BUG_FIX: Nama kolom di DB adalah 'session_hours_ids'
                'session_hours_ids' => $request->session_hours_ids,
                'total_price' => $totalPrice,
                'status' => 'pending', // Status awal
                'payment_status' => 'unpaid',
            ]);

            // REFACTOR: Tabel `lapangan_statuses` sebaiknya tidak diisi dari sini.
            // Status lapangan secara implisit sudah diwakili oleh data di tabel `bookings`.
            // Jika `lapangan_statuses` bertujuan untuk memblokir manual oleh admin, prosesnya harus terpisah.

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Booking berhasil dibuat.',
                'data' => $booking
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            // Berikan pesan error yang lebih informatif saat development
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan pada server.', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Menampilkan detail satu booking.
     */
    public function show($id)
    {
        // PERF: Eager load relasi untuk efisiensi
        $booking = Booking::with(['user', 'lapangan'])->find($id);

        if (!$booking) {
            return response()->json(['success' => false, 'message' => 'Booking tidak ditemukan'], 404);
        }

        // Authorization check
        $user = auth()->user();
        if ($user->role !== 'admin' && $booking->user_id !== $user->id) {
            return response()->json(['success' => false, 'message' => 'Anda tidak memiliki akses'], 403);
        }

        // Logic yang sama dengan 'index' untuk mengambil data sesi
        $sessionIds = $booking->session_hours_ids ?? [];
        $sessions = SessionHour::whereIn('id', $sessionIds)->get();

        $bookingData = [
            'booking_id' => $booking->id,
            'user' => $booking->user,
            'lapangan' => $booking->lapangan,
            'date' => Carbon::parse($booking->date)->format('Y-m-d'),
            'start_time' => Carbon::parse($booking->start_time)->format('H:i'),
            'end_time' => Carbon::parse($booking->end_time)->format('H:i'),
            'session_hours' => $sessions,
            'total_price' => $booking->total_price,
            'status' => $booking->status,
            'payment_status' => $booking->payment_status,
            'created_at' => $booking->created_at,
        ];

        return response()->json([
            'success' => true,
            'data' => $bookingData
        ]);
    }

    /**
     * REFACTOR: Mengubah fungsi update.
     * Update booking biasanya untuk mengubah status (misal: konfirmasi pembayaran, pembatalan),
     * bukan untuk mengubah jadwal yang bisa jadi sangat kompleks.
     * Jika ingin mengubah jadwal, flow yang lebih baik adalah cancel lalu buat booking baru.
     */
    public function updateStatus(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|string|in:pending,confirmed,cancelled,completed',
            'payment_status' => 'required|string|in:unpaid,paid,refunded',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $booking = Booking::find($id);
        if (!$booking) {
            return response()->json(['success' => false, 'message' => 'Booking tidak ditemukan'], 404);
        }

        // Hanya admin yang boleh mengubah status
        if (auth()->user()->role !== 'admin') {
            return response()->json(['success' => false, 'message' => 'Anda tidak memiliki akses'], 403);
        }

        $booking->status = $request->status;
        $booking->payment_status = $request->payment_status;
        $booking->save();

        return response()->json([
            'success' => true,
            'message' => 'Status booking berhasil diperbarui.',
            'data' => $booking
        ]);
    }

    /**
     * Menghapus atau membatalkan booking.
     * Sebaiknya tidak dihapus permanen (soft delete) atau diubah statusnya menjadi 'cancelled'.
     * Di sini kita akan mengubah statusnya.
     */
    public function destroy($id)
    {
        $booking = Booking::find($id);
        if (!$booking) {
            return response()->json(['success' => false, 'message' => 'Booking tidak ditemukan'], 404);
        }

        $user = auth()->user();
        if ($user->role !== 'admin' && $booking->user_id !== $user->id) {
            return response()->json(['success' => false, 'message' => 'Anda tidak memiliki akses'], 403);
        }

        // Jangan hapus record, tapi ubah statusnya menjadi 'cancelled'
        $booking->status = 'cancelled';
        // Mungkin juga status pembayaran diubah menjadi 'refunded' jika sudah bayar
        // if ($booking->payment_status === 'paid') {
        //     $booking->payment_status = 'refunded';
        // }
        $booking->save();


        return response()->json(['success' => true, 'message' => 'Booking berhasil dibatalkan']);
    }

    /**
     * Pengecekan ketersediaan yang lebih akurat dan informatif.
     * Mengembalikan semua sesi yang tersedia beserta status dan harganya.
     */
    public function checkAvailability(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'lapangan_id' => 'required|exists:lapangans,id',
            'date' => 'required|date'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        // Ambil data lapangan untuk mendapatkan harga dasar
        $lapangan = Lapangan::findOrFail($request->lapangan_id);
        $basePricePerHour = $lapangan->price;

        // Ambil semua ID sesi yang sudah dibooking
        $bookedSessionIds = Booking::where('lapangan_id', $request->lapangan_id)
            ->where('date', $request->date)
            ->whereIn('status', ['pending', 'confirmed'])
            ->pluck('session_hours_ids')
            ->flatten()
            ->unique()
            ->toArray();

        // Ambil semua sesi yang ada
        $allSessions = SessionHour::orderBy('start_time')->get();

        // Tambahkan status 'is_available' dan 'price' pada setiap sesi
        $availabilityData = $allSessions->map(function ($session) use ($bookedSessionIds, $basePricePerHour) {
            // Logika kalkulasi harga disalin dari metode store()
            $priceForSession = $basePricePerHour;
            $startHour = (int) Carbon::parse($session->start_time)->format('H');

            // Logika harga tambahan (contoh)
            if ($startHour >= 15 && $startHour < 18) { // Sesi sore
                $priceForSession += 25000;
            } elseif ($startHour >= 18) { // Sesi malam
                $priceForSession += 50000;
            }

            return [
                'id' => $session->id,
                'description' => $session->description,
                'start_time' => Carbon::parse($session->start_time)->format('H:i'),
                'end_time' => Carbon::parse($session->end_time)->format('H:i'),
                'is_available' => !in_array($session->id, $bookedSessionIds),
                'price' => $priceForSession // **TAMBAHAN: Sertakan harga total sesi**
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $availabilityData
        ]);
    }
}
