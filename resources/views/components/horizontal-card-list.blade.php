<div class="flex overflow-x-auto space-x-4 p-4 bg-white dark:bg-gray-800 rounded-lg shadow-md">
    @foreach($cards as $card)
        <div class="flex-shrink-0 w-64">
            <x-financial-card 
                :item="$card" 
                type="card"
                :compact="true" 
                :show-actions="false" 
                :brand-color="$brandColor ?? 'brand-primary'"
            />
        </div>
    @endforeach
</div>
   