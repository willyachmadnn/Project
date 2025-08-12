<x-layout>
    <x-slot:title>Dashboard Admin</x-slot:title>

    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
        {{-- Header --}}
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Dashboard</h1>
                <p class="text-sm text-gray-500">Selamat datang, {{ Auth::guard('admin')->user()->nama_admin }}!</p>
            </div>
            <form action="{{ route('logout') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded-lg text-xs">
                    Logout
                </button>
            </form>
        </div>

        {{-- Kartu Navigasi --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Kelola Agenda</h3>
                <p class="text-gray-600 mb-4 text-sm">Lihat, tambah, edit, atau hapus semua agenda pemerintah.</p>
                <a href="{{ route('agenda.index') }}" class="inline-block bg-[#8A0303] hover:bg-[#6e0202] text-white font-bold py-2 px-4 rounded-lg text-sm">
                    Lihat Daftar Agenda
                </a>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Tambah Agenda Baru</h3>
                <p class="text-gray-600 mb-4 text-sm">Buat entri agenda baru untuk dijadwalkan dalam sistem.</p>
                <a href="{{ route('agenda.create') }}" class="inline-block bg-gray-700 hover:bg-gray-800 text-white font-bold py-2 px-4 rounded-lg text-sm">
                    + Tambah Agenda
                </a>
            </div>
        </div>
    </div>
</x-layout>
