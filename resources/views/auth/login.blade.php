<x-layout>
  <x-slot:title>Login Admin</x-slot:title>

  <!-- Pusatkan seluruh konten -->
  <div class="min-h-screen w-full grid place-items-center bg-gray-100 px-4 py-8 sm:px-6 sm:py-12 lg:px-8">
    <div class="w-full max-w-md mx-2">

      <!-- BRAND: logo kiri + teks kanan, rata TENGAH (horizontal) -->
      <div class="flex items-center justify-center gap-3 mb-6">
        <a href="{{ route('landing') }}" class="shrink-0 block" aria-label="Kabupaten Mojokerto">
          <!-- Batasi ukuran logo supaya tidak kebesaran -->
          <div class="h-8 sm:h-9 md:h-10 w-8 sm:w-9 md:w-10">
            <img
              src="https://ppid.mojokertokab.go.id//images/mojokerto_kab.png"
              alt="Logo Kabupaten Mojokerto"
              class="h-full w-full object-contain"
              loading="eager" fetchpriority="high"
            >
          </div>
        </a>

        <div class="text-center leading-tight">
          <h2 class="text-xl sm:text-2xl font-bold tracking-tight text-gray-800">
            SI-AGENDA
          </h2>
          <p class="text-sm text-gray-500">Pemerintah Kabupaten Mojokerto</p>
        </div>
      </div>

      <!-- Kartu Form -->
      <div class="w-full bg-white rounded-lg shadow-md">
        <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
          <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl">
            Login Admin
          </h1>

          {{-- Success Message (e.g., after logout) --}}
          @if(session('message'))
            <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 border border-green-200" role="alert">
              <div class="flex items-center">
                <svg class="flex-shrink-0 w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                <span class="font-medium">{{ session('message') }}</span>
              </div>
            </div>
          @endif

          <form class="space-y-4 md:space-y-6" action="{{ route('login') }}" method="POST">
            @csrf

            <div>
              <label for="admin_id" class="block mb-2 text-sm font-medium text-gray-900">Admin ID</label>
              <input
                type="text"
                name="admin_id"
                id="admin_id"
                value="{{ old('admin_id') }}"
                required
                class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-[#8A0303] focus:border-[#8A0303] block w-full p-2.5 @error('admin_id') border-red-500 @enderror"
              >
              @error('admin_id')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
              @enderror
            </div>

            <div>
              <label for="password" class="block mb-2 text-sm font-medium text-gray-900">Password</label>
              <input
                type="password"
                name="password"
                id="password"
                required
                class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-[#8A0303] focus:border-[#8A0303] block w-full p-2.5 @error('password') border-red-500 @enderror"
              >
              @error('password')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
              @enderror
            </div>

            <button
              type="submit"
              class="w-full text-white bg-[#8A0303] hover:bg-[#6e0202] focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center"
            >
              Login
            </button>
          </form>
        </div>
      </div>

    </div>
  </div>
</x-layout>
