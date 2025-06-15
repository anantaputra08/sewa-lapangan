<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Manajemen Hari') }}
            </h2>
            <a href="{{ route('days.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                {{ __('Tambah Hari') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 p-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-gray-700 dark:text-green-400" role="alert">
                    {{ session('success') }}
                </div>
            @endif
             @if (session('error'))
                <div class="mb-4 p-4 text-sm text-red-700 bg-red-100 rounded-lg dark:bg-gray-700 dark:text-red-400" role="alert">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="relative overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th class="px-6 py-3">Nama Hari</th>
                                    <th class="px-6 py-3">Dibuat Pada</th>
                                    <th class="px-6 py-3 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($days as $day)
                                <tr class="bg-white dark:bg-gray-800 border-b dark:border-gray-700">
                                    <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">{{ $day->name }}</td>
                                    <td class="px-6 py-4">{{ $day->created_at->format('d M Y, H:i') }}</td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex justify-center items-center space-x-4">
                                            <a href="{{ route('days.edit', $day) }}" class="font-medium text-indigo-600 dark:text-indigo-500 hover:underline">Edit</a>
                                            <form action="{{ route('days.destroy', $day) }}" method="POST" onsubmit="return confirm('Anda yakin ingin menghapus hari ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="font-medium text-red-600 dark:text-red-500 hover:underline">Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-10 text-center text-gray-500">Belum ada data hari.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-6">
                        {{ $days->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>