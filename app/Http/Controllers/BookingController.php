<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Lapangan;
use App\Models\SessionHour;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BookingController extends Controller
{
    /**
     * Menampilkan halaman daftar semua booking.
     */
    public function index()
    {
        // Ambil semua booking dengan relasinya, urutkan dari yang terbaru, dan gunakan paginasi.
        $bookings = Booking::with(['user', 'lapangan'])->latest()->paginate(10);

        return view('bookings.index', compact('bookings'));
    }

    /**
     * Menampilkan form untuk membuat booking baru.
     */
    public function create()
    {
        // Ambil data yang diperlukan untuk form, seperti daftar lapangan, user, dan sesi jam.
        $lapangans = Lapangan::orderBy('name')->get();
        $users = User::where('role', 'user')->orderBy('name')->get(); // Hanya ambil user biasa
        $sessionHours = SessionHour::orderBy('start_time')->get();

        return view('bookings.create', compact('lapangans', 'users', 'sessionHours'));
    }

    /**
     * Menyimpan data booking baru dari form.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'lapangan_id' => 'required|exists:lapangans,id',
            'date' => 'required|date|after_or_equal:today',
            'session_hours_ids' => 'required|array|min:1',
            'session_hours_ids.*' => 'required|exists:session_hours,id',
        ]);

        $lapangan = Lapangan::findOrFail($request->lapangan_id);
        $sessionHours = SessionHour::whereIn('id', $request->session_hours_ids)->orderBy('start_time')->get();

        if ($sessionHours->count() !== count($request->session_hours_ids)) {
            return back()->with('error', 'Satu atau lebih sesi jam tidak valid.')->withInput();
        }

        DB::beginTransaction();
        try {
            // Pengecekan Ketersediaan (sama seperti di API)
            $existingBookings = Booking::where('lapangan_id', $request->lapangan_id)
                ->where('date', $request->date)
                ->whereIn('status', ['pending', 'confirmed'])
                ->lockForUpdate()
                ->get();

            $bookedSessionIds = $existingBookings->pluck('session_hours_ids')->flatten()->toArray();
            foreach ($request->session_hours_ids as $sessionId) {
                if (in_array($sessionId, $bookedSessionIds)) {
                    $conflictingSession = $sessionHours->firstWhere('id', $sessionId);
                    DB::rollBack();
                    return back()->with('error', 'Lapangan sudah dibooking untuk sesi jam ' . Carbon::parse($conflictingSession->start_time)->format('H:i'))->withInput();
                }
            }

            // Kalkulasi Harga
            $totalPrice = 0;
            foreach ($sessionHours as $session) {
                $priceForSession = $lapangan->price;
                $startHour = (int) Carbon::parse($session->start_time)->format('H');
                if ($startHour >= 15 && $startHour < 18)
                    $priceForSession += 25000;
                elseif ($startHour >= 18)
                    $priceForSession += 50000;
                $totalPrice += $priceForSession;
            }

            // Membuat Booking
            Booking::create([
                'user_id' => $request->user_id,
                'lapangan_id' => $request->lapangan_id,
                'date' => $request->date,
                'start_time' => $sessionHours->first()->start_time,
                'end_time' => $sessionHours->last()->end_time,
                'session_hours_ids' => $request->session_hours_ids,
                'total_price' => $totalPrice,
                'status' => 'confirmed', // Admin yang buat, langsung confirm
                'payment_status' => 'unpaid',
            ]);

            DB::commit();

            return redirect()->route('bookings.index')->with('success', 'Booking baru berhasil dibuat.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Menampilkan form untuk mengedit booking.
     */
    public function edit(Booking $booking)
    {
        // Eager load relasi untuk ditampilkan di view
        $booking->load(['user', 'lapangan']);

        // Data sesi untuk ditampilkan
        $sessionIds = $booking->session_hours_ids ?? [];
        $sessions = SessionHour::whereIn('id', $sessionIds)->orderBy('start_time')->get();

        return view('bookings.edit', compact('booking', 'sessions'));
    }

    /**
     * Mengupdate data booking (fokus pada status).
     */
    public function update(Request $request, Booking $booking)
    {
        $request->validate([
            'status' => 'required|string|in:pending,confirmed,cancelled,completed',
            'payment_status' => 'required|string|in:unpaid,paid,refunded',
        ]);

        $booking->status = $request->status;
        $booking->payment_status = $request->payment_status;
        $booking->save();

        return redirect()->route('bookings.index')->with('success', 'Status booking berhasil diperbarui.');
    }

    /**
     * Membatalkan booking (mengubah status menjadi 'cancelled').
     */
    public function destroy(Booking $booking)
    {
        $booking->status = 'cancelled';
        $booking->save();

        return redirect()->route('bookings.index')->with('success', 'Booking berhasil dibatalkan.');
    }
}
