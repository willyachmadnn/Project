<x-layout title="Tambah OPD - {{ $agenda->nama_agenda }}">
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">Tambah OPD</h1>
                    <p class="text-gray-600">{{ $agenda->nama_agenda }}</p>
                    <div class="flex items-center mt-2 text-sm text-gray-500">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a1 1 0 011-1h6a1 1 0 011 1v4h3a2 2 0 012 2v1a2 2 0 01-2 2H6a2 2 0 01-2-2V9a2 2 0 012-2h2z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 17v2a2 2 0 01-2 2H10a2 2 0 01-2-2v-2m8 0V9a2 2 0 00-2-2H10a2 2 0 00-2 2v8.01"></path>
                        </svg>
                        {{ $agenda->tempat }} • {{ \Carbon\Carbon::parse($agenda->tanggal)->format('d M Y') }} • {{ $agenda->jam_mulai }} - {{ $agenda->jam_selesai }}
                    </div>
                </div>
                <a href="{{ route('agenda.show', $agenda->agenda_id) }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white font-medium rounded-lg transition-colors duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali
                </a>
            </div>
        </div>

        <!-- Form Section -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
                <h2 class="text-xl font-semibold text-gray-900">Pilih OPD untuk Diundang</h2>
                <p class="text-sm text-gray-600 mt-1">Centang OPD yang ingin diundang ke agenda ini</p>
            </div>
            
            <form action="{{ route('agenda.tamu.simpan-opd', $agenda->agenda_id) }}" method="POST" id="opdForm" class="p-6">
                @csrf
                <input type="hidden" name="agenda_id" value="{{ $agenda->agenda_id }}">
                
                <!-- Search Box -->
                <div class="mb-6">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <input type="text" id="searchOpd" placeholder="Cari OPD..." 
                               class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>

                <!-- Select All/None -->
                <div class="flex items-center justify-between mb-4 p-3 bg-gray-50 rounded-lg">
                    <span class="text-sm font-medium text-gray-700">Total OPD: {{ $opds->count() }}</span>
                    <div class="flex space-x-2">
                        <button type="button" id="selectAll" 
                                class="px-3 py-1 text-xs font-medium text-blue-600 bg-blue-100 rounded-md hover:bg-blue-200 transition-colors duration-200">
                            Pilih Semua
                        </button>
                        <button type="button" id="selectNone" 
                                class="px-3 py-1 text-xs font-medium text-gray-600 bg-gray-100 rounded-md hover:bg-gray-200 transition-colors duration-200">
                            Batal Pilih
                        </button>
                    </div>
                </div>

                <!-- OPD List -->
                <div class="space-y-2 max-h-96 overflow-y-auto" id="opdList">
                    @foreach($opds as $opd)
                        <div class="opd-item flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors duration-200" 
                             data-opd-name="{{ strtolower($opd->nama_opd) }}">
                            <input type="checkbox" id="opd_{{ $opd->opd_id }}" name="opd_ids[]" value="{{ $opd->opd_id }}" 
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded opd-checkbox">
                            <label for="opd_{{ $opd->opd_id }}" class="ml-3 flex-1 cursor-pointer">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-medium text-gray-900 opd-name">{{ $opd->nama_opd }}</span>
                                    <span class="text-xs text-gray-500">ID: {{ $opd->opd_id }}</span>
                                </div>
                            </label>
                        </div>
                    @endforeach
                </div>

                <!-- Selected Count -->
                <div class="mt-6 p-3 bg-blue-50 rounded-lg">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-blue-900">
                            <span id="selectedCount">0</span> OPD dipilih
                        </span>
                        <div class="flex space-x-3">
                            <button type="button" onclick="window.history.back()" 
                                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                                Batal
                            </button>
                            <button type="submit" id="submitBtn" 
                                    class="px-6 py-3 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors duration-200 disabled:bg-gray-400 disabled:cursor-not-allowed flex items-center" 
                                    disabled>
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span id="submitText">Simpan OPD Terpilih</span>
                                <svg class="animate-spin -mr-1 ml-3 h-5 w-5 text-white hidden" id="loadingIcon" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const checkboxes = document.querySelectorAll('input[name="opd_ids[]"]');
    const selectedCountEl = document.getElementById('selectedCount');
    const submitBtn = document.getElementById('submitBtn');
    const selectAllBtn = document.getElementById('selectAll');
    const selectNoneBtn = document.getElementById('selectNone');
    const searchInput = document.getElementById('searchOpd');
    const opdItems = document.querySelectorAll('.opd-item');
    const submitText = document.getElementById('submitText');
    const loadingIcon = document.getElementById('loadingIcon');

    // Update selected count
    function updateSelectedCount() {
        const selectedCount = document.querySelectorAll('input[name="opd_ids[]"]:checked').length;
        selectedCountEl.textContent = selectedCount;
        submitBtn.disabled = selectedCount === 0;
    }

    // Add event listeners to checkboxes
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateSelectedCount);
    });

    // Select all
    selectAllBtn.addEventListener('click', function() {
        const visibleCheckboxes = Array.from(checkboxes).filter(cb => {
            return !cb.closest('.opd-item').style.display || cb.closest('.opd-item').style.display !== 'none';
        });
        visibleCheckboxes.forEach(checkbox => {
            checkbox.checked = true;
        });
        updateSelectedCount();
    });

    // Select none
    selectNoneBtn.addEventListener('click', function() {
        checkboxes.forEach(checkbox => {
            checkbox.checked = false;
        });
        updateSelectedCount();
    });

    // Search functionality
    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        
        opdItems.forEach(item => {
            const opdName = item.getAttribute('data-opd-name');
            if (opdName.includes(searchTerm)) {
                item.style.display = 'flex';
            } else {
                item.style.display = 'none';
            }
        });
    });

    // Form submission
    document.getElementById('opdForm').addEventListener('submit', function(e) {
        const selectedOpds = Array.from(document.querySelectorAll('input[name="opd_ids[]"]:checked'));
        
        if (selectedOpds.length === 0) {
            e.preventDefault();
            alert('Pilih minimal satu OPD!');
            return;
        }

        // Show loading state
        submitBtn.disabled = true;
        submitText.textContent = 'Menyimpan...';
        loadingIcon.classList.remove('hidden');
    });

    // Initial count update
    updateSelectedCount();
});
</script>
</x-layout>