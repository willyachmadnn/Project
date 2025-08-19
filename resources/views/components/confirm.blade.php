@props(['message' => 'Apakah Anda yakin?', 'confirmText' => 'Yakin', 'cancelText' => 'Tidak'])

<div {{ $attributes->merge(['class' => 'fixed inset-0 z-[9999] overflow-y-auto']) }}
     x-cloak
     x-transition:enter="transition ease-out duration-300" 
     x-transition:enter-start="opacity-0" 
     x-transition:enter-end="opacity-100" 
     x-transition:leave="transition ease-in duration-200" 
     x-transition:leave-start="opacity-100" 
     x-transition:leave-end="opacity-0">
    
    {{-- Backdrop --}}
    <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-all duration-300" @click="$dispatch('cancel')"></div>
    
    {{-- Modal Container --}}
    <div class="flex min-h-full items-center justify-center p-4">
        <div x-transition:enter="transition ease-out duration-300 transform" 
             x-transition:enter-start="opacity-0 scale-95" 
             x-transition:enter-end="opacity-100 scale-100" 
             x-transition:leave="transition ease-in duration-200 transform" 
             x-transition:leave-start="opacity-100 scale-100" 
             x-transition:leave-end="opacity-0 scale-95"
             class="relative w-full max-w-md transform overflow-hidden rounded-2xl bg-white/95 backdrop-blur-xl p-6 shadow-2xl transition-all border border-white/20">
            
            {{-- Icon --}}
            <div class="flex items-center justify-center w-16 h-16 mx-auto mb-4 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-full">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
            </div>
            
            {{-- Title --}}
            <h3 class="text-xl font-bold text-gray-900 text-center mb-2">
                Konfirmasi
            </h3>
            
            {{-- Message --}}
            <p class="text-gray-600 text-center mb-6 leading-relaxed">
                {{ $message }}
            </p>
            
            {{-- Buttons --}}
            <div class="flex space-x-3">
                {{-- Cancel Button --}}
                <button type="button" 
                        @click="$dispatch('cancel')"
                        class="flex-1 px-4 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl transition-all duration-200 focus:outline-none focus:ring-4 focus:ring-gray-300/50">
                    {{ $cancelText }}
                </button>
                
                {{-- Confirm Button --}}
                <button type="button" 
                        @click="$dispatch('confirm')"
                        class="flex-1 px-4 py-3 bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white font-semibold rounded-xl transition-all duration-200 focus:outline-none focus:ring-4 focus:ring-red-500/30 shadow-lg hover:shadow-xl transform hover:scale-105">
                    {{ $confirmText }}
                </button>
            </div>
        </div>
    </div>
</div>
