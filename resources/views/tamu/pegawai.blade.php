<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Form Tamu Pegawai - Kabupaten Mojokerto</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 50%, #cbd5e1 100%);
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen">
    <div class="w-full max-w-md p-8 space-y-6 bg-white rounded-2xl shadow-2xl">
        <div class="text-center">
            <img src="{{ asset('img/mojokerto_kab.png') }}" alt="Logo" class="mx-auto h-20 w-auto mb-4">
            <h1 class="text-3xl font-bold text-gray-900">Form Tamu Pegawai</h1>
            <p class="text-gray-600 mt-2">Masukkan NIP untuk registrasi kehadiran.</p>
        </div>
        <form action="{{ route('tamu.store.public') }}" method="POST" class="space-y-6">
            @csrf
            <input type="hidden" name="type" value="pegawai">
            <input type="hidden" name="agenda_id" value="{{ $agendaId }}">

            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative text-center" role="alert">
                    <strong class="font-bold">Oops! Terjadi kesalahan.</strong>
                    <ul class="mt-2">
                        @foreach ($errors->all() as $error)
                            <li class="font-semibold">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div>
                <label for="nip" class="block text-sm font-medium text-gray-700">NIP</label>
                <div class="mt-1">
                    <input type="text" name="nip" id="nip" value="{{ old('nip') }}" placeholder="Masukkan NIP" class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                </div>
            </div>

            <div>
                <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    Daftar Kehadiran
                </button>
            </div>
        </form>
    </div>
</body>
</html>