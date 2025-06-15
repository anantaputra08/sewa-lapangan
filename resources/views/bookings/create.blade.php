<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Buat Booking Baru') }}
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
                    <form action="{{ route('bookings.store') }}" method="POST">
                        @csrf
                        <div class="space-y-6">
                            <!-- User -->
                            <div>
                                <x-input-label for="user_id" :value="__('Pilih User')" />
                                <select id="user_id" name="user_id"
                                    class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                    <option value="">-- Pilih User --</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}"
                                            {{ old('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}
                                            ({{ $user->email }})</option>
                                    @endforeach
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('user_id')" />
                            </div>

                            <!-- Lapangan -->
                            <div>
                                <x-input-label for="lapangan_id" :value="__('Pilih Lapangan')" />
                                <select id="lapangan_id" name="lapangan_id"
                                    class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                    <option value="">-- Pilih Lapangan --</option>
                                    @foreach ($lapangans as $lapangan)
                                        <option value="{{ $lapangan->id }}"
                                            {{ old('lapangan_id') == $lapangan->id ? 'selected' : '' }}>
                                            {{ $lapangan->name }}</option>
                                    @endforeach
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('lapangan_id')" />
                            </div>

                            <!-- Tanggal -->
                            <div>
                                <x-input-label for="date" :value="__('Tanggal Booking')" />
                                <x-text-input type="date" id="date" name="date" class="mt-1 block w-full"
                                    :value="old('date')" min="{{ date('Y-m-d') }}" />
                                <x-input-error class="mt-2" :messages="$errors->get('date')" />
                            </div>

                            <!-- Sesi Jam -->
                            <div>
                                <x-input-label :value="__('Pilih Sesi Jam')" />
                                <div class="mt-2 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                                    @foreach ($sessionHours as $session)
                                        <label for="session-{{ $session->id }}"
                                            class="flex items-center p-3 border border-gray-300 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer">
                                            <input id="session-{{ $session->id }}" type="checkbox"
                                                name="session_hours_ids[]" value="{{ $session->id }}"
                                                {{ is_array(old('session_hours_ids')) && in_array($session->id, old('session_hours_ids')) ? 'checked' : '' }}
                                                class="w-4 h-4 text-indigo-600 bg-gray-100 border-gray-300 rounded focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                            <span class="ml-3 text-sm font-medium text-gray-900 dark:text-gray-300">
                                                {{ \Carbon\Carbon::parse($session->start_time)->format('H:i') }}
                                            </span>
                                        </label>
                                    @endforeach
                                </div>
                                <x-input-error class="mt-2" :messages="$errors->get('session_hours_ids')" />
                            </div>

                            <div class="flex items-center gap-4 pt-4">
                                <x-primary-button>{{ __('Buat Booking') }}</x-primary-button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
