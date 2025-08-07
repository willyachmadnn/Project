<x-layout>
  <x-slot:title>Agenda Pemerintah</x-slot:title>
  
  <div class="bg-white shadow-sm rounded-lg overflow-hidden">
    <div class="p-6">
      <h2 class="text-2xl font-bold text-gray-800 mb-4">Daftar Agenda Pemerintah</h2>
      
      @if(isset($agendas) && $agendas->count() > 0)
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Agenda</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tempat</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              @foreach($agendas as $agenda)
                <tr>
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $agenda->nama_agenda }}</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $agenda->tempat }}</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $agenda->tanggal->format('d-m-Y') }}</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $agenda->jam_mulai }} - {{ $agenda->jam_selesai }}</td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      @else
        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-4">
          <div class="flex">
            <div class="flex-shrink-0">
              <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
              </svg>
            </div>
            <div class="ml-3">
              <p class="text-sm text-yellow-700">Belum ada agenda yang tersedia.</p>
            </div>
          </div>
        </div>
      @endif
    </div>
  </div>
</x-layout>