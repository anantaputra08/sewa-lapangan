<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Lapangan;
use App\Models\SessionHour;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BookingController extends Controller
{
    /**
     * Menampilkan semua data booking dengan format yang benar.
     */
    public function index()
    {
        $bookings = Booking::with(['user', 'lapangan'])->latest()->get();

        $data = $bookings->map(function ($booking) {
            return [
                'booking_id' => $booking->id, // Mengirim 'id' sebagai 'booking_id'
                'user' => $booking->user,
                'lapangan' => $booking->lapangan,
                'date' => $booking->date,
                'start_time' => $booking->start_time,
                'end_time' => $booking->end_time,
                'session_hours_ids' => $booking->session_hours_ids,
                'total_price' => $booking->total_price,
                'status' => $booking->status,
                'payment_status' => $booking->payment_status,
                'created_at' => $booking->created_at,
                'updated_at' => $booking->updated_at,
            ];
        });

        return response()->json(['success' => true, 'data' => $data]);
    }

    public function getCreationData()
    {
        try {
            Log::info('Starting getCreationData method');

            // Step 1: Test database connection
            try {
                DB::connection()->getPdo();
                Log::info('Database connection successful');
            } catch (\Exception $e) {
                Log::error('Database connection failed: ' . $e->getMessage());
                return response()->json([
                    'success' => false,
                    'message' => 'Database connection failed'
                ], 500);
            }

            // Step 2: Check if tables exist
            $tables = ['users', 'lapangans', 'session_hours'];
            foreach ($tables as $table) {
                try {
                    $exists = DB::select("SHOW TABLES LIKE '$table'");
                    if (empty($exists)) {
                        Log::error("Table $table does not exist");
                        return response()->json([
                            'success' => false,
                            'message' => "Table $table does not exist"
                        ], 500);
                    }
                    Log::info("Table $table exists");
                } catch (\Exception $e) {
                    Log::error("Error checking table $table: " . $e->getMessage());
                }
            }

            // Step 3: Get users data with error handling
            $users = collect();
            try {
                // First, let's check what columns exist in users table
                $userColumns = DB::select("DESCRIBE users");
                $columnNames = collect($userColumns)->pluck('Field')->toArray();
                Log::info('Users table columns: ' . implode(', ', $columnNames));

                // Build select query based on available columns
                $selectColumns = ['id'];
                if (in_array('name', $columnNames))
                    $selectColumns[] = 'name';
                if (in_array('email', $columnNames))
                    $selectColumns[] = 'email';

                $users = User::where('role', 'user')
                    ->select($selectColumns)
                    ->get()
                    ->map(function ($user) {
                        return [
                            'id' => $user->id,
                            'name' => $user->name ?? 'No Name',
                            'email' => $user->email ?? 'No Email'
                        ];
                    });

                Log::info('Users fetched successfully: ' . $users->count());
            } catch (\Exception $e) {
                Log::error('Error fetching users: ' . $e->getMessage());
                return response()->json([
                    'success' => false,
                    'message' => 'Error fetching users: ' . $e->getMessage()
                ], 500);
            }

            // Step 4: Get lapangans data with error handling
            $lapangans = collect();
            try {
                // Check lapangans table columns
                $lapanganColumns = DB::select("DESCRIBE lapangans");
                $columnNames = collect($lapanganColumns)->pluck('Field')->toArray();
                Log::info('Lapangans table columns: ' . implode(', ', $columnNames));

                $selectColumns = ['id'];
                if (in_array('name', $columnNames))
                    $selectColumns[] = 'name';
                if (in_array('price', $columnNames))
                    $selectColumns[] = 'price';
                if (in_array('category', $columnNames))
                    $selectColumns[] = 'category';

                $lapangans = Lapangan::select($selectColumns)
                    ->get()
                    ->map(function ($lapangan) {
                        return [
                            'id' => $lapangan->id,
                            'name' => $lapangan->name ?? 'No Name',
                            'price' => $lapangan->price ?? 0,
                            'category' => $lapangan->category ?? 'No Category'
                        ];
                    });

                Log::info('Lapangans fetched successfully: ' . $lapangans->count());
            } catch (\Exception $e) {
                Log::error('Error fetching lapangans: ' . $e->getMessage());
                return response()->json([
                    'success' => false,
                    'message' => 'Error fetching lapangans: ' . $e->getMessage()
                ], 500);
            }

            // Step 5: Get sessions data with error handling
            $sessions = collect();
            try {
                // Check session_hours table columns
                $sessionColumns = DB::select("DESCRIBE session_hours");
                $columnNames = collect($sessionColumns)->pluck('Field')->toArray();
                Log::info('Session_hours table columns: ' . implode(', ', $columnNames));

                $selectColumns = ['id'];
                if (in_array('start_time', $columnNames))
                    $selectColumns[] = 'start_time';
                if (in_array('end_time', $columnNames))
                    $selectColumns[] = 'end_time';

                $sessions = SessionHour::select($selectColumns)
                    ->orderBy(in_array('start_time', $columnNames) ? 'start_time' : 'id')
                    ->get()
                    ->map(function ($session) {
                        return [
                            'id' => $session->id,
                            'start_time' => $session->start_time ?? '00:00',
                            'end_time' => $session->end_time ?? '00:00'
                        ];
                    });

                Log::info('Sessions fetched successfully: ' . $sessions->count());
            } catch (\Exception $e) {
                Log::error('Error fetching sessions: ' . $e->getMessage());
                return response()->json([
                    'success' => false,
                    'message' => 'Error fetching sessions: ' . $e->getMessage()
                ], 500);
            }

            // Step 6: Return response
            $response = [
                'success' => true,
                'data' => [
                    'users' => $users,
                    'lapangans' => $lapangans,
                    'sessions' => $sessions,
                ]
            ];

            Log::info('getCreationData completed successfully');
            return response()->json($response);

        } catch (\Exception $e) {
            Log::error('Unexpected error in getCreationData: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Unexpected error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mendapatkan ketersediaan sesi untuk lapangan dan tanggal tertentu.
     * Khusus untuk admin dengan informasi lengkap termasuk harga.
     */
    public function getAvailableSessions(Request $request)
    {
        try {
            Log::info('Starting getAvailableSessions method', $request->all());

            $validator = Validator::make($request->all(), [
                'lapangan_id' => 'required|integer|exists:lapangans,id',
                'date' => 'required|date|after_or_equal:today'
            ]);

            if ($validator->fails()) {
                Log::warning('Validation failed for getAvailableSessions: ', $validator->errors()->toArray());
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Get lapangan data for price calculation
            $lapangan = Lapangan::findOrFail($request->lapangan_id);
            $basePricePerHour = $lapangan->price ?? 0;

            Log::info('Lapangan found', ['id' => $lapangan->id, 'name' => $lapangan->name, 'price' => $basePricePerHour]);

            // Get all booked session IDs for this lapangan and date
            $bookedSessionIds = Booking::where('lapangan_id', $request->lapangan_id)
                ->where('date', $request->date)
                ->whereIn('status', ['pending', 'confirmed'])
                ->get()
                ->pluck('session_hours_ids')
                ->flatten()
                ->unique()
                ->filter()
                ->values()
                ->toArray();

            Log::info('Booked session IDs', ['booked_ids' => $bookedSessionIds]);

            // Get all available sessions
            $allSessions = SessionHour::orderBy('start_time')->get();

            Log::info('All sessions count', ['count' => $allSessions->count()]);

            // Map sessions with availability and pricing info
            $sessionAvailability = $allSessions->map(function ($session) use ($bookedSessionIds, $basePricePerHour) {
                // Calculate price based on time (similar to logic in main BookingController)
                $priceForSession = $basePricePerHour;
                $startHour = (int) Carbon::parse($session->start_time)->format('H');

                // Price adjustment logic
                if ($startHour >= 15 && $startHour < 18) { // Afternoon session
                    $priceForSession += 25000;
                } elseif ($startHour >= 18) { // Evening session
                    $priceForSession += 50000;
                }

                $isAvailable = !in_array($session->id, $bookedSessionIds);

                return [
                    'id' => $session->id,
                    'start_time' => Carbon::parse($session->start_time)->format('H:i'),
                    'end_time' => Carbon::parse($session->end_time)->format('H:i'),
                    'description' => $session->description ?? null,
                    'is_available' => $isAvailable,
                    'price' => $priceForSession,
                    'price_formatted' => 'Rp ' . number_format($priceForSession, 0, ',', '.'),
                    'time_category' => $this->getTimeCategory($startHour)
                ];
            });

            // Separate available and unavailable sessions for easier frontend handling
            $availableSessions = $sessionAvailability->where('is_available', true)->values();
            $unavailableSessions = $sessionAvailability->where('is_available', false)->values();

            $response = [
                'success' => true,
                'data' => [
                    'lapangan' => [
                        'id' => $lapangan->id,
                        'name' => $lapangan->name,
                        'base_price' => $basePricePerHour,
                        'base_price_formatted' => 'Rp ' . number_format($basePricePerHour, 0, ',', '.')
                    ],
                    'date' => $request->date,
                    'all_sessions' => $sessionAvailability,
                    'available_sessions' => $availableSessions,
                    'unavailable_sessions' => $unavailableSessions,
                    'summary' => [
                        'total_sessions' => $allSessions->count(),
                        'available_count' => $availableSessions->count(),
                        'unavailable_count' => $unavailableSessions->count(),
                        'is_fully_booked' => $availableSessions->isEmpty()
                    ]
                ]
            ];

            Log::info('getAvailableSessions completed successfully', [
                'total_sessions' => $allSessions->count(),
                'available_count' => $availableSessions->count()
            ]);

            return response()->json($response);

        } catch (\Exception $e) {
            Log::error('Error in getAvailableSessions: ' . $e->getMessage(), [
                'request_data' => $request->all(),
                'trace' => $e->getTraceAsString(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error fetching session availability: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Helper method to categorize time slots
     */
    private function getTimeCategory($hour)
    {
        if ($hour >= 6 && $hour < 12) {
            return 'morning'; // Pagi
        } elseif ($hour >= 12 && $hour < 15) {
            return 'afternoon'; // Siang
        } elseif ($hour >= 15 && $hour < 18) {
            return 'evening'; // Sore
        } elseif ($hour >= 18 && $hour < 24) {
            return 'night'; // Malam
        } else {
            return 'late_night'; // Dini hari
        }
    }

    // Menyimpan data booking baru
    public function store(Request $request)
    {
        // Log the incoming request for debugging
        Log::info('Creating booking with data: ', $request->all());

        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer|exists:users,id',
            'lapangan_id' => 'required|integer|exists:lapangans,id',
            'date' => 'required|date|after_or_equal:today',
            'session_hours_ids' => 'required|array|min:1',
            'session_hours_ids.*' => 'integer|exists:session_hours,id',
            'status' => 'required|string|in:pending,confirmed,cancelled,completed',
            'payment_status' => 'required|string|in:unpaid,paid,refunded',
        ]);

        if ($validator->fails()) {
            Log::warning('Validation failed for booking creation: ', $validator->errors()->toArray());
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $lapangan = Lapangan::findOrFail($request->lapangan_id);
            $sessionHours = SessionHour::whereIn('id', $request->session_hours_ids)
                ->orderBy('start_time')
                ->get();

            if ($sessionHours->count() !== count($request->session_hours_ids)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Some session hours not found'
                ], 422);
            }

            // Check for existing bookings on the same date and sessions
            $existingBooking = Booking::where('lapangan_id', $request->lapangan_id)
                ->where('date', $request->date)
                ->where('status', '!=', 'cancelled')
                ->get();

            foreach ($existingBooking as $booking) {
                $existingSessionIds = is_array($booking->session_hours_ids)
                    ? $booking->session_hours_ids
                    : json_decode($booking->session_hours_ids, true);

                if (is_array($existingSessionIds)) {
                    $overlap = array_intersect($existingSessionIds, $request->session_hours_ids);
                    if (!empty($overlap)) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Some sessions are already booked for this date'
                        ], 422);
                    }
                }
            }

            $totalPrice = $lapangan->price * $sessionHours->count();

            $booking = Booking::create([
                'user_id' => $request->user_id,
                'lapangan_id' => $request->lapangan_id,
                'date' => $request->date,
                'start_time' => $sessionHours->first()->start_time,
                'end_time' => $sessionHours->last()->end_time,
                'session_hours_ids' => $request->session_hours_ids,
                'total_price' => $totalPrice,
                'status' => $request->status,
                'payment_status' => $request->payment_status,
            ]);

            Log::info('Booking created successfully: ', ['booking_id' => $booking->id]);

            return response()->json([
                'success' => true,
                'message' => 'Booking berhasil dibuat',
                'data' => $booking
            ], 201);

        } catch (\Exception $e) {
            Log::error('Failed to create booking: ' . $e->getMessage(), [
                'request_data' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to create booking: ' . $e->getMessage()
            ], 500);
        }
    }

    // Memperbarui booking yang ada
    public function update(Request $request, $id)
    {
        // 1. Cari booking secara manual berdasarkan ID dari URL
        $booking = Booking::find($id);

        // 2. Jika tidak ditemukan, kembalikan error 404
        if (!$booking) {
            return response()->json(['success' => false, 'message' => 'Booking tidak ditemukan.'], 404);
        }

        // 3. Lanjutkan validasi seperti biasa
        $validator = Validator::make($request->all(), [
            'status' => 'required|string|in:pending,confirmed,cancelled,completed',
            'payment_status' => 'required|string|in:unpaid,paid,refunded',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        // 4. Lakukan update
        $booking->update($validator->validated());

        return response()->json(['success' => true, 'message' => 'Booking berhasil diperbarui', 'data' => $booking->fresh()]);
    }

    // Menghapus booking
    public function destroy(Booking $booking)
    {
        $booking->delete();
        return response()->json(['success' => true, 'message' => 'Booking berhasil dihapus']);
    }
}
