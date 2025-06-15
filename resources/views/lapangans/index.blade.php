<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Manajemen Lapangan') }}
            </h2>
            <a href="{{ route('lapangans.create') }}"
                class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                {{ __('Tambah Lapangan') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 p-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-gray-700 dark:text-green-400"
                    role="alert">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="relative overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead
                                class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th class="px-6 py-3">Foto</th>
                                    <th class="px-6 py-3">Nama Lapangan</th>
                                    <th class="px-6 py-3">Kategori</th>
                                    <th class="px-6 py-3">Harga</th>
                                    <th class="px-6 py-3">Status</th>
                                    <th class="px-6 py-3 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($lapangans as $lapangan)
                                    <tr class="bg-white dark:bg-gray-800 border-b dark:border-gray-700">
                                        <td class="px-6 py-4">
                                            <img src="{{ $lapangan->photo ? asset('storage/' . $lapangan->photo) : 'https://placehold.co/100x100/e2e8f0/e2e8f0?text=No+Image' }}"
                                                alt="Foto {{ $lapangan->name }}" class="w-16 h-16 object-cover rounded">
                                        </td>
                                        <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                            {{ $lapangan->name }}</td>
                                        <td class="px-6 py-4">{{ $lapangan->category->name }}</td>
                                        <td class="px-6 py-4">Rp {{ number_format($lapangan->price, 0, ',', '.') }}</td>
                                        <td class="px-6 py-4">
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $lapangan->status == 'available' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ ucfirst($lapangan->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <div class="flex justify-center items-center space-x-4">
                                                <a href="{{ route('lapangans.edit', $lapangan) }}"
                                                    class="font-medium text-indigo-600 dark:text-indigo-500 hover:underline">Edit</a>
                                                <form action="{{ route('lapangans.destroy', $lapangan) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('Yakin ingin menghapus lapangan ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="font-medium text-red-600 dark:text-red-500 hover:underline">Hapus</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-10 text-center text-gray-500">Belum ada data
                                            lapangan.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-6">
                        {{ $lapangans->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
