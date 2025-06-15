<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Edit Booking') }} #{{ $booking->id }}
            </h2>
            <a href="{{ route('bookings.index') }}"
                class="text-sm font-medium text-indigo-600 dark:text-indigo-500 hover:underline">
                &larr; {{ __('Kembali ke Daftar Booking') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <!-- Booking Details Section -->
                    <div class="space-y-4 mb-6 pb-6 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Detail Booking</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                            <div>
                                <p class="text-gray-500 dark:text-gray-400">User</p>
                                <p class="font-medium">{{ $booking->user->name }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500 dark:text-gray-400">Lapangan</p>
                                <p class="font-medium">{{ $booking->lapangan->name }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500 dark:text-gray-400">Tanggal</p>
                                <p class="font-medium">
                                    {{ \Carbon\Carbon::parse($booking->date)->translatedFormat('d F Y') }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500 dark:text-gray-400">Total Harga</p>
                                <p class="font-medium">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</p>
                            </div>
                            <div class="md:col-span-2">
                                <p class="text-gray-500 dark:text-gray-400 mb-2">Sesi Jam</p>
                                <div class="flex flex-wrap gap-2">
                                    @foreach ($sessions as $session)
                                        <span
                                            class="px-3 py-1 bg-indigo-100 text-indigo-800 text-xs font-medium rounded-full dark:bg-indigo-900 dark:text-indigo-300">
                                            {{ \Carbon\Carbon::parse($session->start_time)->format('H:i') }} -
                                            {{ \Carbon\Carbon::parse($session->end_time)->format('H:i') }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Update Status Form -->
                    <form action="{{ route('bookings.update', $booking) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="space-y-6">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Update Status</h3>
                            <!-- Status Booking -->
                            <div>
                                <x-input-label for="status" :value="__('Status Booking')" />
                                <select id="status" name="status"
                                    class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                    <option value="pending" {{ $booking->status == 'pending' ? 'selected' : '' }}>
                                        Pending</option>
                                    <option value="confirmed" {{ $booking->status == 'confirmed' ? 'selected' : '' }}>
                                        Confirmed</option>
                                    <option value="completed" {{ $booking->status == 'completed' ? 'selected' : '' }}>
                                        Completed</option>
                                    <option value="cancelled" {{ $booking->status == 'cancelled' ? 'selected' : '' }}>
                                        Cancelled</option>
                                </select>
                                <x-input-error :messages="$errors->get('status')" class="mt-2" />
                            </div>

                            <!-- Status Pembayaran -->
                            <div>
                                <x-input-label for="payment_status" :value="__('Status Pembayaran')" />
                                <select id="payment_status" name="payment_status"
                                    class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                    <option value="unpaid"
                                        {{ $booking->payment_status == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                                    <option value="paid" {{ $booking->payment_status == 'paid' ? 'selected' : '' }}>
                                        Paid</option>
                                    <option value="refunded"
                                        {{ $booking->payment_status == 'refunded' ? 'selected' : '' }}>Refunded
                                    </option>
                                </select>
                                <x-input-error :messages="$errors->get('payment_status')" class="mt-2" />
                            </div>

                            <div class="flex items-center gap-4 pt-4">
                                <x-primary-button>{{ __('Simpan Perubahan') }}</x-primary-button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
