<nav class="bg-white shadow" x-data="{ isOpen: false }">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
      <div class="flex h-16 items-center justify-between">
        <div class="flex items-center">
          <div class="shrink-0">
            <img src="/img/mokad.png" alt="Mokad" class="size-8" />
          </div>
          <div class="hidden md:block">
          </div>
        </div>
        <div class="hidden md:block">
          <div class="ml-4 flex items-center md:ml-6">
  
            <!-- Profile dropdown -->
            <div class="relative ml-3">
              <div>
                <button id ="user-menu-button" type="button" @click="isOpen = !isOpen"
                 aria-expanded="false" aria-haspopup="true" class="relative flex max-w-xs items-center rounded-full bg-white text-sm focus:outline-hidden focus-visible:ring-2 focus-visible:ring-gray-300 focus-visible:ring-offset-2 focus-visible:ring-offset-white"> 
                  <span class="absolute -inset-1.5"></span>
                  <span class="sr-only">Open user menu</span>
                  <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="" class="size-8 rounded-full" />
                </button>
              </div>
  
              <div  
                x-show="isOpen"
                x-transition:enter="transition ease-out duration-100 transform"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-75 transform"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                role="menu" tabindex="-1" aria-labelledby="user-menu-button" aria-orientation="vertical" class="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black/5 focus:outline-hidden">
                <!-- Active: "bg-gray-100 outline-hidden", Not Active: "" -->
                <a id="user-menu-item-2" role="menuitem" href="#" tabindex="-1" class="block px-4 py-2 text-sm text-gray-700">Sign out</a>
              </div>
            </div>
          </div>
        </div>
        <div class="-mr-2 flex md:hidden">
          <!-- Mobile menu button -->
          <button type="button" @click="isOpen = !isOpen" aria-controls="mobile-menu" aria-expanded="false" class="relative inline-flex items-center justify-center rounded-md bg-white p-2 text-gray-600 hover:bg-gray-100 hover:text-gray-900 focus:ring-2 focus:ring-gray-300 focus:ring-offset-2 focus:ring-offset-white focus:outline-hidden">
            <span class="absolute -inset-0.5"></span>
            <span class="sr-only">Open main menu</span>
            <!-- Menu open: "hidden", Menu closed: "block" -->
            <svg :class="{ 'hidden': isOpen, 'block': !isOpen }" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" data-slot="icon" aria-hidden="true" class="block size-6">
              <path d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
            <!-- Menu open: "block", Menu closed: "hidden" -->
            <svg :class="{ 'block': isOpen, 'hidden': !isOpen }" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" data-slot="icon" aria-hidden="true" class="hidden size-6">
              <path d="M6 18 18 6M6 6l12 12" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
          </button>
        </div>
      </div>
    </div>

    <!-- Mobile menu, show/hide based on menu state. -->
    <div x-show="isOpen" id="mobile-menu" class="md:hidden bg-white">
      <div class="border-t border-gray-200 pt-4 pb-3">
        <div class="flex items-center px-5">
          <div class="shrink-0">
            <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="" class="size-10 rounded-full" />
          </div>
          <div class="ml-3">
            <div class="text-base/5 font-medium text-gray-900">Admin</div>
            <div class="text-sm font-medium text-gray-600">tom@example.com</div>
          </div>  
        </div>
        <div class="mt-3 space-y-1 px-2">
          <a href="#" class="block rounded-md px-3 py-2 text-base font-medium text-gray-600 hover:bg-gray-100 hover:text-gray-900">Sign out</a>
        </div>
      </div>
    </div>
  </nav>