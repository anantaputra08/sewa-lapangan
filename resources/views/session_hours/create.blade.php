<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Tambah Jam Sesi Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-8">
                    <form method="POST" action="{{ route('session-hours.store') }}">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="start_time" value="Waktu Mulai" />
                                <x-text-input id="start_time" name="start_time" type="time" class="mt-1 block w-full"
                                    :value="old('start_time')" required />
                                <x-input-error :messages="$errors->get('start_time')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="end_time" value="Waktu Selesai" />
                                <x-text-input id="end_time" name="end_time" type="time" class="mt-1 block w-full"
                                    :value="old('end_time')" required />
                                <x-input-error :messages="$errors->get('end_time')" class="mt-2" />
                            </div>
                            <div class="md:col-span-2">
                                <x-input-label for="description" value="Deskripsi (Opsional)" />
                                <x-text-input id="description" name="description" type="text"
                                    class="mt-1 block w-full" :value="old('description')" />
                                <x-input-error :messages="$errors->get('description')" class="mt-2" />
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-8">
                            <a href="{{ route('session-hours.index') }}"
                                class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-100">Batal</a>
                            <x-primary-button class="ml-4">Simpan</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
