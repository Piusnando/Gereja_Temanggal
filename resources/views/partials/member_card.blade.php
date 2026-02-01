<div class="flex items-center p-5 border border-gray-100 rounded-xl hover:shadow-xl transition-all duration-300 bg-white group transform hover:-translate-y-1 relative overflow-hidden">
    
    <!-- FOTO -->
    <div class="w-16 h-16 rounded-full overflow-hidden mr-4 border-2 border-gray-100 shadow-sm shrink-0 group-hover:border-logo-blue transition duration-300">
        @if($member->image)
            <img src="{{ asset('storage/' . $member->image) }}" class="w-full h-full object-cover">
        @else
            <div class="w-full h-full bg-gray-100 text-gray-500 flex items-center justify-center font-bold text-xl uppercase group-hover:bg-blue-50 group-hover:text-logo-blue transition">
                {{ substr($member->name, 0, 1) }}
            </div>
        @endif
    </div>

    <!-- DETAIL -->
    <div class="overflow-hidden w-full">
        <h3 class="font-bold text-gray-900 text-lg leading-tight truncate group-hover:text-logo-blue transition">
            {{ $member->name }}
        </h3>
        
        <div class="mt-1 flex flex-wrap gap-1">
            <!-- Jabatan -->
            <span class="inline-block bg-logo-red text-white text-[10px] px-2 py-0.5 rounded uppercase font-bold tracking-wider shadow-sm">
                {{ $member->position }}
            </span>
        </div>
        
        @if($member->lingkungan)
        <p class="text-xs text-gray-500 flex items-center mt-2 truncate">
            <svg class="w-3 h-3 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
            {{ $member->lingkungan->name }}
        </p>
        @endif
    </div>
</div>