<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Buat Jadwal Status Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-8">
                    <form method="POST" action="{{ route('lapangan-status.store') }}">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="lapangan_id" value="Lapangan" />
                                <select id="lapangan_id" name="lapangan_id" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 rounded-md shadow-sm" required>
                                    @foreach($lapangans as $lapangan)
                                        <option value="{{ $lapangan->id }}" {{ old('lapangan_id') == $lapangan->id ? 'selected' : '' }}>{{ $lapangan->name }}</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('lapangan_id')" class="mt-2" />
                            </div>
                             <div>
                                <x-input-label for="status" value="Status" />
                                <select id="status" name="status" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 rounded-md shadow-sm" required>
                                    <option value="available" {{ old('status') == 'available' ? 'selected' : '' }}>Available</option>
                                    <option value="unavailable" {{ old('status') == 'unavailable' ? 'selected' : '' }}>Unavailable</option>
                                </select>
                                <x-input-error :messages="$errors->get('status')" class="mt-2" />
                            </div>
                             <div>
                                <x-input-label for="date" value="Tanggal (Kosongkan jika berlaku setiap hari)" />
                                <x-text-input id="date" name="date" type="date" class="mt-1 block w-full" :value="old('date')" />
                                <x-input-error :messages="$errors->get('date')" class="mt-2" />
                            </div>
                            <div class="md:col-span-2">
                                <x-input-label for="session_ids" value="Pilih Sesi (Kosongkan jika berlaku semua sesi)" />
                                <select id="session_ids" name="session_ids[]" multiple class="mt-1 block w-full h-40 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 rounded-md shadow-sm">
                                     @foreach($sessionHours as $session)
                                        <option value="{{ $session->id }}">{{ \Carbon\Carbon::parse($session->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($session->end_time)->format('H:i') }}</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('session_ids')" class="mt-2" />
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-8">
                            <a href="{{ route('lapangan-status.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-100">Batal</a>
                            <x-primary-button class="ml-4">Simpan Jadwal</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>