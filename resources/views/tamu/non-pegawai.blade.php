<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Form Tamu Non-Pegawai - Kabupaten Mojokerto</title>
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
            <h1 class="text-3xl font-bold text-gray-900">Form Tamu Non-Pegawai</h1>
            <p class="text-gray-600 mt-2">Silakan isi data diri Anda untuk registrasi kehadiran.</p>
        </div>
        <form action="{{ route('tamu.store.public') }}" method="POST" class="space-y-6">
            @csrf
            <input type="hidden" name="type" value="non-pegawai">
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
                <label for="nama" class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                <div class="mt-1">
                    <input type="text" id="nama" name="nama" required
                        class="w-full px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500"
                        placeholder="Masukkan nama lengkap Anda...">
                </div>
            </div>

            <div>
                <label for="gender" class="block text-sm font-medium text-gray-700">Jenis Kelamin</label>
                <div class="mt-1">
                    <select name="gender" id="gender" class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                        <option value="" disabled {{ old('gender') ? '' : 'selected' }}>Pilih Jenis Kelamin</option>
                        <option value="L" {{ old('gender') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="P" {{ old('gender') == 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-3">Status</label>
                <div class="space-y-2">
                    <div class="flex items-center">
                        <input type="radio" id="status_non_asn" name="status" value="non-asn" 
                               class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300" 
                               {{ old('status') == 'non-asn' ? 'checked' : '' }} required>
                        <label for="status_non_asn" class="ml-2 block text-sm text-gray-700">Non-ASN</label>
                    </div>
                    <div class="flex items-center">
                        <input type="radio" id="status_umum" name="status" value="umum" 
                               class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300" 
                               {{ old('status') == 'umum' ? 'checked' : '' }} required>
                        <label for="status_umum" class="ml-2 block text-sm text-gray-700">Umum</label>
                    </div>
                </div>
            </div>

            <div id="instansi_field" class="hidden">
                <label for="instansi" class="block text-sm font-medium text-gray-700">Instansi</label>
                <div class="mt-1">
                    <input type="text" id="instansi" name="instansi" 
                        class="w-full px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500"
                        placeholder="Masukkan nama instansi..." value="{{ old('instansi') }}">
                </div>
            </div>

            <div>
                <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    Daftar Kehadiran
                </button>
            </div>
        </form>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const statusRadios = document.querySelectorAll('input[name="status"]');
            const instansiField = document.getElementById('instansi_field');
            const instansiInput = document.getElementById('instansi');

            function toggleInstansiField() {
                const selectedStatus = document.querySelector('input[name="status"]:checked');
                
                if (selectedStatus && selectedStatus.value === 'non-asn') {
                    instansiField.classList.remove('hidden');
                    instansiInput.setAttribute('required', 'required');
                } else {
                    instansiField.classList.add('hidden');
                    instansiInput.removeAttribute('required');
                    instansiInput.value = ''; // Clear the field when hidden
                }
            }

            // Add event listeners to radio buttons
            statusRadios.forEach(radio => {
                radio.addEventListener('change', toggleInstansiField);
            });

            // Initialize on page load (for old input values)
            toggleInstansiField();
        });
    </script>
</body>
</html>