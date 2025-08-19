<x-layout>
    <x-slot:title>Detail Notulen Agenda: {{ $agenda->nama_agenda }}</x-slot:title>

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
    </style>

    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <div class="p-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Detail Notulen Agenda</h2>

            {{-- Informasi Agenda --}}
            <div class="mb-6">
                <h3 class="text-lg font-medium text-gray-900 mb-2">Informasi Agenda</h3>
                <p class="text-gray-700">{{ $agenda->nama_agenda }}</p>
                <p class="text-sm text-gray-500">{{ $agenda->tanggal->format('d F Y') }}, {{ \Carbon\Carbon::parse($agenda->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($agenda->jam_selesai)->format('H:i') }}</p>
            </div>

            {{-- Isi Notulen --}}
            <div class="mb-6">
                <h3 class="text-lg font-medium text-gray-900 mb-2">Isi Notulen</h3>
                <div class="prose max-w-none p-4 bg-gray-50 rounded-md border">
                    {!! nl2br(e($notulen->isi_notulen)) !!}
                </div>
                <p class="text-sm text-gray-500 mt-2">Dibuat oleh: {{ $notulen->pembuat }}</p>
            </div>

            <div class="flex justify-between items-center mt-8 pt-4 border-t">
                {{-- PERBAIKAN: Menggunakan array asosiatif dan agenda_id --}}
                <a href="{{ route('agenda.show', ['agenda' => $agenda->agenda_id]) }}" class="modern-btn back-btn">
                     <svg class="w-4 h-4 transition-transform duration-300 hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                     </svg>
                     Kembali ke Detail Agenda
                 </a>

                <div class="flex space-x-3">
                    <a href="{{ route('agenda.notulen.edit', ['agendaId' => $agenda->agenda_id, 'id' => $notulen->id]) }}" class="inline-flex items-center px-4 py-2 bg-yellow-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-600 active:bg-yellow-700 focus:outline-none focus:border-yellow-700 focus:ring ring-yellow-300 disabled:opacity-25 transition ease-in-out duration-150">
                        Edit
                    </a>
                    <form action="{{ route('agenda.notulen.destroy', ['agendaId' => $agenda->agenda_id, 'id' => $notulen->id]) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus notulen ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-900 focus:outline-none focus:border-red-900 focus:ring ring-red-300 disabled:opacity-25 transition ease-in-out duration-150">
                            Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layout>
