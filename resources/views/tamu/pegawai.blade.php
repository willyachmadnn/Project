<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Form Tamu Pegawai - Kabupaten Mojokerto</title>
    
    <!-- Preload critical resources -->
    <link rel="preload" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" as="style">
    <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" as="style">
    
    <!-- Stylesheets -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <style>
        * {
            box-sizing: border-box;
        }
        
        html, body {
            justify-content: center;
            height: 100%;
            margin: 0;
            padding: 0;
            overflow: hidden;
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 50%, #cbd5e1 100%);
            width: 100%;
        }
        
        /* Hide scrollbar */
        body::-webkit-scrollbar {
            display: none;
        }
        
        body {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        
        .container::-webkit-scrollbar {
            display: none;
        }
        
        .container {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        
        .container {
            height: 100vh;
            width: 100vw;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            margin: 0;
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 50%, #cbd5e1 100%);
            position: fixed;
            top: 0;
            left: 0;
            overflow: auto;
        }
        
        .form-wrapper {
            width: 100%;
            max-width: 420px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 24px;
            box-shadow: 0 32px 64px rgba(0, 0, 0, 0.15), 0 0 0 1px rgba(255, 255, 255, 0.2);
            padding: 2.5rem;
            margin: 0;
            position: relative;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        /* Enhanced input styling */
        input[type="text"], input[type="email"], input[type="tel"], select, textarea {
            background: rgba(255, 255, 255, 0.9);
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 16px;
            padding: 16px 20px;
            font-size: 16px;
            font-weight: 500;
            color: #374151;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            backdrop-filter: blur(10px);
        }
        
        input[type="text"]:focus, input[type="email"]:focus, input[type="tel"]:focus, select:focus, textarea:focus {
            outline: none;
            border-color: #667eea;
            background: rgba(255, 255, 255, 1);
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1), 0 8px 25px rgba(102, 126, 234, 0.15);
            transform: translateY(-2px);
        }
        
        input[type="text"]:hover, input[type="email"]:hover, input[type="tel"]:hover, select:hover, textarea:hover {
            border-color: rgba(102, 126, 234, 0.5);
            background: rgba(255, 255, 255, 0.95);
        }
        
        /* Label styling */
        label {
            font-weight: 600;
            color: #374151;
            font-size: 14px;
            margin-bottom: 8px;
            display: block;
        }
        
        /* Button focus styles */
        button:focus {
            outline: none;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.2);
        }
        
        /* Responsive adjustments */
        @media (max-width: 640px) {
            .form-wrapper {
                width: 95%;
                max-width: none;
                padding: 1.5rem;
                border-radius: 1rem;
                
            }
        }
        
        @media (max-width: 480px) {
            .form-wrapper {
                width: 98%;
                padding: 1rem;
                
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-wrapper">
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="mx-auto h-16 w-16 bg-blue-600 rounded-lg flex items-center justify-center mb-4">
                <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Form Tamu Pegawai</h1>
            <p class="text-gray-600">Masukkan NIP untuk registrasi kehadiran</p>
        </div>

        <!-- Form Card -->
        <div class="rounded-lg shadow-2xl p-8 bg-gradient-to-br from-white/90 to-white/70 backdrop-blur-xl border border-white/30" x-data="pegawaiForm()" style="background: linear-gradient(145deg, rgba(255,255,255,0.95) 0%, rgba(255,255,255,0.85) 100%); box-shadow: 0 25px 50px rgba(0,0,0,0.1), inset 0 1px 0 rgba(255,255,255,0.6);">
            <form @submit.prevent="submitForm" class="space-y-6">
                @csrf
                
                <!-- NIP Input -->
                <div class="space-y-2">
                    <label for="nip" class="block text-sm font-semibold text-gray-700">
                        <i class="fas fa-id-card text-blue-600 mr-2"></i>
                        Nomor Induk Pegawai (NIP)
                    </label>
                    <div class="relative">
                        <input 
                            type="text" 
                            id="nip" 
                            name="nip" 
                            x-model="form.nip"
                            @input="validateNip"
                            @blur="searchPegawai"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 text-lg font-mono"
                            placeholder="Masukkan NIP Anda"
                            required
                            :class="{'border-red-500': errors.nip, 'border-green-500': pegawaiData && !errors.nip}"
                        >
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                            <div x-show="loading" class="animate-spin h-5 w-5 text-blue-600">
                                <svg fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </div>
                            <svg x-show="pegawaiData && !errors.nip && !loading" class="h-5 w-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                    </div>
                    <p x-show="errors.nip" x-text="errors.nip" class="text-red-500 text-sm mt-1"></p>
                </div>

                <!-- Pegawai Info Preview -->
                <div x-show="pegawaiData" x-transition class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-lg p-6 border border-green-200/50 shadow-lg backdrop-blur-sm" style="background: linear-gradient(135deg, rgba(16, 185, 129, 0.1) 0%, rgba(5, 150, 105, 0.05) 100%); border: 1px solid rgba(16, 185, 129, 0.2);">
                    <h3 class="text-sm font-semibold text-gray-700 mb-3 flex items-center">
                        <i class="fas fa-user-check text-green-600 mr-2"></i>
                        Data Pegawai Ditemukan
                    </h3>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Nama:</span>
                            <span class="font-medium" x-text="pegawaiData?.nama_pegawai"></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Jenis Kelamin:</span>
                            <span class="font-medium" x-text="pegawaiData?.jk === 'L' ? 'Laki-laki' : 'Perempuan'"></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Instansi:</span>
                            <span class="font-medium" x-text="pegawaiData?.instansi"></span>
                        </div>
                    </div>
                </div>

                <!-- Agenda ID (Hidden) -->
                <input type="hidden" name="agenda_id" :value="agendaId">

                <!-- Submit Button -->
                <button 
                    type="submit" 
                    :disabled="!pegawaiData || loading || submitting"
                    class="w-full bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 disabled:from-gray-400 disabled:to-gray-500 disabled:cursor-not-allowed text-white font-bold py-4 px-8 rounded-lg transition-all duration-300 transform hover:scale-[1.02] hover:shadow-xl active:scale-[0.98] flex items-center justify-center space-x-2 shadow-lg"
                    style="background: linear-gradient(135deg, #ac1616 0%, #dc2626 50%, #b91c1c 100%); box-shadow: 0 10px 25px rgba(172, 22, 22, 0.3);"
                >
                    <span x-show="!submitting">Daftar Kehadiran</span>
                    <span x-show="submitting" class="flex items-center space-x-2">
                        <div class="animate-spin h-5 w-5 border-2 border-white border-t-transparent rounded-lg"></div>
                        <span>Mendaftar...</span>
                    </span>
                </button>
            </form>

            <!-- Success Message -->
            <div x-show="success" x-transition class="mt-6 bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200/50 rounded-lg p-6 shadow-lg backdrop-blur-sm" style="background: linear-gradient(135deg, rgba(34, 197, 94, 0.1) 0%, rgba(16, 185, 129, 0.05) 100%); border: 1px solid rgba(34, 197, 94, 0.2);">
                <div class="flex items-center">
                    <svg class="h-5 w-5 text-green-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <p class="text-green-800 font-medium">Kehadiran berhasil didaftarkan!</p>
                </div>
            </div>
        </div>

<script>
function pegawaiForm() {
    return {
        form: {
            nip: ''
        },
        pegawaiData: null,
        errors: {},
        loading: false,
        submitting: false,
        success: false,
        agendaId: {{ $agendaId }}, // Agenda ID dari controller

        validateNip() {
            this.errors.nip = '';
            if (this.form.nip.length < 18) {
                this.errors.nip = 'NIP harus 18 karakter';
            } else if (this.form.nip.length > 18) {
                this.errors.nip = 'NIP maksimal 18 karakter';
            }
            if (this.pegawaiData && this.form.nip !== this.pegawaiData.NIP) {
                this.pegawaiData = null;
            }
        },

        async searchPegawai() {
            if (this.form.nip.length !== 18 || this.errors.nip) return;
            
            this.loading = true;
            try {
                const response = await fetch(`/api/pegawai/${this.form.nip}`);
                const responseData = await response.json();
                
                if (response.ok && responseData.success) {
                    this.pegawaiData = responseData.data;
                    this.errors.nip = '';
                } else {
                    this.pegawaiData = null;
                    this.errors.nip = responseData.message || 'Pegawai dengan NIP tersebut tidak ditemukan';
                }
            } catch (error) {
                this.pegawaiData = null;
                this.errors.nip = 'Terjadi kesalahan saat mencari data pegawai';
            } finally {
                this.loading = false;
            }
        },

        async submitForm() {
            if (!this.pegawaiData) return;
            
            this.submitting = true;
            try {
                const response = await fetch('{{ route('tamu.store.public') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        nip: this.form.nip,
                        type: 'pegawai',
                        agenda_id: this.agendaId
                    })
                });

                const responseData = await response.json();

                if (response.ok) {
                    // Redirect ke halaman success
                    window.location.href = '/tamu/success?nip=' + responseData.nip;
                } else {
                    this.errors = responseData.errors || { general: responseData.message || 'Terjadi kesalahan saat mendaftar' };
                }
            } catch (error) {
                this.errors = { general: 'Terjadi kesalahan jaringan' };
            } finally {
                this.submitting = false;
            }
        }
    }
}
</script>
        </div>
    </div>
</body>
</html>