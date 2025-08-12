<x-layout>
    <x-slot:title>Login Admin</x-slot:title>

    <div class="flex min-h-screen flex-col justify-center items-center bg-gray-100 px-6 py-12 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-sm">
            <h2 class="text-center text-xl font-bold tracking-tight text-gray-800">SI-AGENDA</h2>
            <p class="text-center text-sm text-gray-500 mb-8">Pemerintah Kota Mojokerto</p>
        </div>

        <div class="w-full bg-white rounded-lg shadow-md sm:max-w-sm">
            <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
                <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl">
                    Login Admin
                </h1>
                <form class="space-y-4 md:space-y-6" action="{{ route('login') }}" method="POST">
                    @csrf
                    <div>
                        <label for="admin_id" class="block mb-2 text-sm font-medium text-gray-900">Admin ID</label>
                        <input type="text" name="admin_id" id="admin_id" value="{{ old('admin_id') }}" required
                               class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-[#8A0303] focus:border-[#8A0303] block w-full p-2.5 @error('admin_id') border-red-500 @enderror">
                        @error('admin_id')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="password" class="block mb-2 text-sm font-medium text-gray-900">Password</label>
                        <input type="password" name="password" id="password" required
                               class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-[#8A0303] focus:border-[#8A0303] block w-full p-2.5 @error('password') border-red-500 @enderror">
                         @error('password')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <button type="submit"
                            class="w-full text-white bg-[#8A0303] hover:bg-[#6e0202] focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                        Login
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-layout>
