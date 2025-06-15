<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Manajemen Booking') }}
            </h2>
            <a href="{{ route('bookings.create') }}"
                class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition ease-in-out duration-150">
                + {{ __('Buat Booking Baru') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Session Messages -->
            @if (session('success'))
                <div class="mb-4 p-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-gray-800 dark:text-green-400"
                    role="alert">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="mb-4 p-4 text-sm text-red-700 bg-red-100 rounded-lg dark:bg-gray-800 dark:text-red-400"
                    role="alert">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="relative overflow-x-auto">
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                            <thead
                                class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3">ID</th>
                                    <th scope="col" class="px-6 py-3">User</th>
                                    <th scope="col" class="px-6 py-3">Lapangan</th>
                                    <th scope="col" class="px-6 py-3">Tanggal</th>
                                    <th scope="col" class="px-6 py-3">Total</th>
                                    <th scope="col" class="px-6 py-3">Status</th>
                                    <th scope="col" class="px-6 py-3">Pembayaran</th>
                                    <th scope="col" class="px-6 py-3 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($bookings as $booking)
                                    <tr
                                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                        <td
                                            class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                            #{{ $booking->id }}
                                        </td>
                                        <td class="px-6 py-4">{{ $booking->user->name ?? 'N/A' }}</td>
                                        <td class="px-6 py-4">{{ $booking->lapangan->name ?? 'N/A' }}</td>
                                        <td class="px-6 py-4">
                                            {{ \Carbon\Carbon::parse($booking->date)->translatedFormat('d F Y') }}</td>
                                        <td class="px-6 py-4">Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <span
                                                class="px-2 py-1 font-semibold leading-tight rounded-full text-xs
                                            @if ($booking->status == 'confirmed') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300 @endif
                                            @if ($booking->status == 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300 @endif
                                            @if ($booking->status == 'completed') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300 @endif
                                            @if ($booking->status == 'cancelled') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300 @endif
                                        ">
                                                {{ ucfirst($booking->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span
                                                class="px-2 py-1 font-semibold leading-tight rounded-full text-xs
                                            @if ($booking->payment_status == 'paid') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300 @endif
                                            @if ($booking->payment_status == 'unpaid') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300 @endif
                                            @if ($booking->payment_status == 'refunded') bg-gray-200 text-gray-800 dark:bg-gray-600 dark:text-gray-300 @endif
                                        ">
                                                {{ ucfirst($booking->payment_status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <a href="{{ route('bookings.edit', $booking) }}"
                                                class="font-medium text-indigo-600 dark:text-indigo-500 hover:underline mr-3">Edit</a>
                                            <form action="{{ route('bookings.destroy', $booking) }}"
                                                method="POST" class="inline"
                                                onsubmit="return confirm('Anda yakin ingin membatalkan booking ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="font-medium text-red-600 dark:text-red-500 hover:underline">Batal</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8"
                                            class="px-6 py-10 text-center text-gray-500 dark:text-gray-400">
                                            Tidak ada data booking.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6">
                        {{ $bookings->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
