<x-layout>
    <x-slot:title>Buat Notulen Agenda: {{ $agenda->nama_agenda }}</x-slot:title>

    <style>
        /* Modern Button Animations */
        .modern-btn {
            position: relative;
            overflow: hidden;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .modern-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s ease;
        }
        
        .modern-btn:hover::before {
            left: 100%;
        }
        
        .modern-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }
        
        .modern-btn:active {
            transform: translateY(0);
        }
        
        .back-btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 8px;
            padding: 10px 20px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        
        .back-btn:hover {
            color: white;
            text-decoration: none;
        }
        
        .submit-btn {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            border: none;
            border-radius: 8px;
            padding: 12px 24px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
    </style>

    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <div class="p-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Buat Notulen Agenda: {{ $agenda->nama_agenda }}</h2>

            @if ($errors->any())
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                    <p class="font-bold">Terjadi kesalahan:</p>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- PERBAIKAN: Menggunakan agenda_id untuk parameter route --}}
            <form action="{{ route('agenda.notulen.store', $agenda->agenda_id) }}" method="POST">
                @csrf

                <div class="mb-6">
                    <label for="pembuat" class="block text-sm font-medium text-gray-700 mb-1">Pembuat Notulen <span class="text-red-500">*</span></label>
                    <input type="text" name="pembuat" id="pembuat" value="{{ old('pembuat') }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                </div>

                <div class="mb-6">
                    <label for="isi_notulen" class="block text-sm font-medium text-gray-700 mb-1">Isi Notulen <span class="text-red-500">*</span></label>
                    <textarea name="isi_notulen" id="isi_notulen" rows="10" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>{{ old('isi_notulen') }}</textarea>
                </div>

                <div class="flex justify-between">
                    {{-- PERBAIKAN: Menggunakan array asosiatif dan agenda_id --}}
                    <a href="{{ route('agenda.show', ['agenda' => $agenda->agenda_id]) }}" class="modern-btn back-btn">
                        <svg class="w-4 h-4 transition-transform duration-300 hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Batal
                    </a>
                    <button type="submit" class="modern-btn submit-btn">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Simpan Notulen
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layout>
<x-layout>
    <x-slot:title>Buat Notulen Agenda: {{ $agenda->nama_agenda }}</x-slot:title>

    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <div class="p-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Buat Notulen Agenda: {{ $agenda->nama_agenda }}</h2>

            @if ($errors->any())
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                    <p class="font-bold">Terjadi kesalahan:</p>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- PERBAIKAN: Menggunakan agenda_id untuk parameter route --}}
            <form action="{{ route('agenda.notulen.store', $agenda->agenda_id) }}" method="POST">
                @csrf

                <div class="mb-6">
                    <label for="pembuat" class="block text-sm font-medium text-gray-700 mb-1">Pembuat Notulen <span class="text-red-500">*</span></label>
                    <input type="text" name="pembuat" id="pembuat" value="{{ old('pembuat') }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                </div>

                <div class="mb-6">
                    <label for="isi_notulen" class="block text-sm font-medium text-gray-700 mb-1">Isi Notulen <span class="text-red-500">*</span></label>
                    <textarea name="isi_notulen" id="isi_notulen" rows="10" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>{{ old('isi_notulen') }}</textarea>
                </div>

                <div class="flex justify-between">
                    {{-- PERBAIKAN: Menggunakan array asosiatif dan agenda_id --}}
                    <a href="{{ route('agenda.show', ['agenda' => $agenda->agenda_id]) }}" class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Batal
                    </a>
                    <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Simpan Notulen
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layout>
