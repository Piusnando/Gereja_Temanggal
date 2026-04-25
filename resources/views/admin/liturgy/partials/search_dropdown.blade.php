<div class="relative w-full">
    <!-- INPUT PENCARIAN -->
    <div class="relative">
        <input type="text" 
               x-model="search" 
               @focus="isOpen = true" 
               @click.away="isOpen = false"
               @input="selectedId = ''" 
               class="w-full border border-gray-300 rounded-lg py-3 pl-10 pr-4 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition shadow-sm bg-white"
               placeholder="Ketik untuk mencari nama..."
               autocomplete="off">
        
        <!-- Ikon Kaca Pembesar -->
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
        </div>

        <!-- Tombol Clear (X) -->
        <button type="button" x-show="search.length > 0" @click="reset()" class="absolute inset-y-0 right-0 pr-3 flex items-center text-red-400 hover:text-red-600 focus:outline-none">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
    </div>

    <!-- BOX LIST DROPDOWN (MUNCUL SAAT DIKLIK/DIKETIK) -->
    <ul x-show="isOpen" 
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="absolute z-50 w-full mt-1 bg-white border border-gray-200 rounded-lg shadow-xl max-h-60 overflow-y-auto divide-y divide-gray-100"
        x-cloak>
        
        <!-- Opsi Kosong jika tidak ditemukan -->
        <li x-show="filteredOptions.length === 0" class="px-4 py-3 text-sm text-gray-500 text-center italic">
            Nama tidak ditemukan.
        </li>

        <!-- List Opsi -->
        <template x-for="option in filteredOptions" :key="option.id">
            <li @click="selectOption(option)" 
                class="px-4 py-3 hover:bg-blue-50 cursor-pointer flex flex-col group transition">
                <span class="font-bold text-gray-800 group-hover:text-blue-700" x-text="option.name"></span>
                <span class="text-xs text-gray-500 flex items-center mt-0.5">
                    <svg class="w-3 h-3 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                    <span x-text="option.asal"></span>
                </span>
            </li>
        </template>
    </ul>
    
    <!-- Peringatan wajib pilih dari list -->
    <p x-show="search.length > 0 && selectedId === ''" class="text-xs text-red-500 mt-1 italic animate-pulse">
        * Anda belum memilih orang. Klik nama dari daftar di atas!
    </p>
</div>