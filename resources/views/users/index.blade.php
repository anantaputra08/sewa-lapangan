<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Manajemen Pengguna') }}
            </h2>
            <a href="{{ route('users.create') }}"
                class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition ease-in-out duration-150">
                + {{ __('Buat Pengguna Baru') }}
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

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="relative overflow-x-auto">
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                            <thead
                                class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3">Pengguna</th>
                                    <th scope="col" class="px-6 py-3">Email & Telepon</th>
                                    <th scope="col" class="px-6 py-3">Role</th>
                                    <th scope="col" class="px-6 py-3 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($users as $user)
                                    <tr
                                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center">
                                                {{-- Cek apakah pengguna memiliki foto --}}
                                                @if ($user->photo)
                                                    <img class="w-10 h-10 rounded-full mr-4 object-cover"
                                                        src="{{ asset('storage/' . $user->photo) }}"
                                                        alt="{{ $user->name }}">
                                                @else
                                                    {{-- Fallback jika tidak ada foto --}}
                                                    <img class="w-10 h-10 rounded-full mr-4 object-cover"
                                                        src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=random&color=fff"
                                                        alt="{{ $user->name }}">
                                                @endif

                                                <div
                                                    class="font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                                    {{ $user->name }}
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div>{{ $user->email }}</div>
                                            <div class="text-xs text-gray-500">{{ $user->phone ?? 'No Phone' }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span
                                                class="px-2 py-1 font-semibold leading-tight rounded-full text-xs
                                            @if ($user->role == 'admin') bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-300 @endif
                                            @if ($user->role == 'user') bg-gray-200 text-gray-800 dark:bg-gray-600 dark:text-gray-300 @endif
                                        ">
                                                {{ ucfirst($user->role) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <a href="{{ route('users.edit', $user) }}"
                                                class="font-medium text-indigo-600 dark:text-indigo-500 hover:underline mr-3">Edit</a>
                                            <form action="{{ route('users.destroy', $user) }}" method="POST"
                                                class="inline"
                                                onsubmit="return confirm('Anda yakin ingin menghapus pengguna ini? Semua data terkait akan ikut terhapus.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="font-medium text-red-600 dark:text-red-500 hover:underline">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4"
                                            class="px-6 py-10 text-center text-gray-500 dark:text-gray-400">
                                            Tidak ada data pengguna.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6">
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
