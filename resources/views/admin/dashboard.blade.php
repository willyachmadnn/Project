<x-layout>
    <x-slot:title>Dashboard Admin</x-slot:title>

    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <div class="p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Dashboard Admin</h2>
                <form action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                        Logout
                    </button>
                </form>
            </div>

            <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-blue-700">Selamat datang di Dashboard Admin. Anda dapat mengelola agenda pemerintah dari sini.</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="bg-white p-6 rounded-lg shadow-md border border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Agenda</h3>
                    <p class="text-gray-600 mb-4">Kelola semua agenda pemerintah yang akan ditampilkan kepada publik.</p>
                    <a href="{{ route('agenda.index') }}" class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                        Lihat Agenda
                    </a>
                </div>

                <div class="bg-white p-6 rounded-lg shadow-md border border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Tambah Agenda Baru</h3>
                    <p class="text-gray-600 mb-4">Tambahkan agenda pemerintah baru ke dalam sistem.</p>
                    <a href="{{ route('agenda.create') }}" class="inline-block bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                        Tambah Agenda
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-layout>