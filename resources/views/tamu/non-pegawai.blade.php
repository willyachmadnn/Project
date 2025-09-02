<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Form Tamu Non-Pegawai - Kabupaten Mojokerto</title>
    
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
            height: 100%;
            margin: 0;
            padding: 0;
            overflow: hidden;
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 50%, #cbd5e1 100%);
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
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15), 0 0 0 1px rgba(255, 255, 255, 0.2);
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
            border-color: #10b981;
            background: rgba(255, 255, 255, 1);
            box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1), 0 8px 25px rgba(16, 185, 129, 0.15);
            transform: translateY(-2px);
        }
        
        input[type="text"]:hover, input[type="email"]:hover, input[type="tel"]:hover, select:hover, textarea:hover {
            border-color: rgba(16, 185, 129, 0.5);
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
            box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.2);
        }
        
        /* Responsive adjustments */
        @media (max-width: 640px) {
            .form-wrapper {
                width: 95%;
                max-width: none;
                padding: 1.5rem;
                border-radius: 1rem;
                transform: translateY(-1vh);
            }
        }
        
        @media (max-width: 480px) {
            .form-wrapper {
                width: 98%;
                padding: 1rem;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-wrapper">
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="mx-auto h-16 w-16 bg-green-600 rounded-lg flex items-center justify-center mb-4">
                <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Form Tamu Non-Pegawai</h1>
            <p class="text-gray-600">Isi data diri untuk registrasi kehadiran</p>
        </div>

        <!-- Form Card -->
        <div class="rounded-lg shadow-2xl p-8 bg-gradient-to-br from-white/90 to-white/70 backdrop-blur-xl border border-white/30" x-data="nonPegawaiForm()" style="background: linear-gradient(145deg, rgba(255,255,255,0.95) 0%, rgba(255,255,255,0.85) 100%); box-shadow: 0 25px 50px rgba(0,0,0,0.1), inset 0 1px 0 rgba(255,255,255,0.6);">
            <form @submit.prevent="submitForm" class="space-y-6">
                @csrf
                
                <!-- Nama Input -->
                <div class="space-y-2">
                    <label for="nama" class="block text-sm font-semibold text-gray-700">
                        <i class="fas fa-user text-green-600 mr-2"></i>
                        Nama Lengkap
                    </label>
                    <div class="relative">
                        <input 
                            type="text" 
                            id="nama" 
                            name="nama" 
                            x-model="form.nama"
                            @input="validateNama"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200 text-lg"
                            placeholder="Masukkan nama lengkap Anda"
                            required
                            :class="{'border-red-500': errors.nama, 'border-green-500': form.nama.length >= 2 && !errors.nama}"
                        >
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                            <svg x-show="form.nama.length >= 2 && !errors.nama" class="h-5 w-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                    </div>
                    <p x-show="errors.nama" x-text="errors.nama" class="text-red-500 text-sm mt-1"></p>
                </div>

                <!-- Jenis Kelamin Input -->
                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-gray-700">
                        <i class="fas fa-venus-mars text-green-600 mr-2"></i>
                        Jenis Kelamin
                    </label>
                    <div class="grid grid-cols-2 gap-4">
                        <label class="relative flex items-center justify-center p-4 border-2 rounded-lg cursor-pointer transition-all duration-200 hover:bg-gray-50"
                               :class="form.jk === 'L' ? 'border-green-500 bg-green-50' : 'border-gray-300'">
                            <input 
                                type="radio" 
                                name="jk" 
                                value="L" 
                                x-model="form.jk"
                                class="sr-only"
                            >
                            <div class="text-center">
                                <i class="fas fa-mars text-2xl mb-2" :class="form.jk === 'L' ? 'text-green-600' : 'text-gray-400'"></i>
                                <span class="block font-medium" :class="form.jk === 'L' ? 'text-green-800' : 'text-gray-600'">Laki-laki</span>
                            </div>
                        </label>
                        <label class="relative flex items-center justify-center p-4 border-2 rounded-lg cursor-pointer transition-all duration-200 hover:bg-gray-50"
                               :class="form.jk === 'P' ? 'border-green-500 bg-green-50' : 'border-gray-300'">
                            <input 
                                type="radio" 
                                name="jk" 
                                value="P" 
                                x-model="form.jk"
                                class="sr-only"
                            >
                            <div class="text-center">
                                <i class="fas fa-venus text-2xl mb-2" :class="form.jk === 'P' ? 'text-green-600' : 'text-gray-400'"></i>
                                <span class="block font-medium" :class="form.jk === 'P' ? 'text-green-800' : 'text-gray-600'">Perempuan</span>
                            </div>
                        </label>
                    </div>
                    <p x-show="errors.jk" x-text="errors.jk" class="text-red-500 text-sm mt-1"></p>
                </div>

                <!-- Preview Info -->
                <div x-show="form.nama && form.jk" x-transition class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                    <h3 class="text-sm font-semibold text-gray-700 mb-3 flex items-center">
                        <i class="fas fa-eye text-green-600 mr-2"></i>
                        Preview Data
                    </h3>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Nama:</span>
                            <span class="font-medium" x-text="form.nama"></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Jenis Kelamin:</span>
                            <span class="font-medium" x-text="form.jk === 'L' ? 'Laki-laki' : 'Perempuan'"></span>
                        </div>
                        
                        <div class="flex justify-center">
                            <span class="text-gray-600"></span>
                            <span class="font-medium">Masyarakat Umum</span>
                        </div>
                    </div>
                </div>

                <!-- Agenda ID (Hidden) -->
                <input type="hidden" name="agenda_id" :value="agendaId">

                <!-- Submit Button -->
                <button 
                    type="submit" 
                    :disabled="!isFormValid() || submitting"
                    @click="console.log('Submit button clicked!')"
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
                    <div>
                        <p class="text-green-800 font-medium">Kehadiran berhasil didaftarkan!</p>
                        <p class="text-green-700 text-sm mt-1">NIP Anda: <span class="font-mono" x-text="generatedNip"></span></p>
                    </div>
                </div>
            </div>
        </div>

<script>
function nonPegawaiForm() {
    return {
        form: {
            nama: '',
            jk: ''
        },
        errors: {},
        submitting: false,
        success: false,
        generatedNip: '',
        agendaId: {{ $agendaId }}, // Agenda ID dari controller

        validateNama() {
            this.errors.nama = '';
            if (this.form.nama.length < 2) {
                this.errors.nama = 'Nama minimal 2 karakter';
            } else if (this.form.nama.length > 100) {
                this.errors.nama = 'Nama maksimal 100 karakter';
            } else if (!/^[a-zA-Z\s.'-]+$/.test(this.form.nama)) {
                this.errors.nama = 'Nama hanya boleh mengandung huruf, spasi, titik, apostrof, dan tanda hubung';
            }
        },

        isFormValid() {
            const isValid = this.form.nama.length >= 2 && 
                           this.form.jk && 
                           !this.errors.nama && 
                           !this.errors.jk;
            console.log('Form validation check:', {
                namaLength: this.form.nama.length,
                jk: this.form.jk,
                errorsNama: this.errors.nama,
                errorsJk: this.errors.jk,
                isValid: isValid
            });
            return isValid;
        },

        generateNipPreview() {
            return '#tamu<auto_increment>';
        },

        async submitForm() {
            console.log('Form submission started');
            console.log('Form data:', this.form);
            console.log('Is form valid:', this.isFormValid());
            console.log('Agenda ID:', this.agendaId);
            
            if (!this.isFormValid()) {
                console.log('Form is not valid, stopping submission');
                return;
            }
            
            this.submitting = true;
            this.errors = {};
            
            try {
                console.log('Sending request to server...');
                
                const requestData = {
                    nama_lengkap: this.form.nama.trim(),
                    jk: this.form.jk,
                    type: 'non-pegawai',
                    agenda_id: this.agendaId
                };
                
                console.log('Request data:', requestData);
                
                const response = await fetch('{{ route('tamu.store.public') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(requestData)
                });

                console.log('Response status:', response.status);
                console.log('Response headers:', response.headers);
                
                const responseData = await response.json();
                console.log('Response data:', responseData);

                if (response.ok) {
                    console.log('Success! Redirecting to success page...');
                    // Redirect ke halaman success
                    window.location.href = '/tamu/success?nip=' + responseData.nip;
                } else {
                    console.log('Server returned error:', responseData);
                    this.errors = responseData.errors || { general: 'Terjadi kesalahan saat mendaftar' };
                }
            } catch (error) {
                console.log('Network error:', error);
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