<div class="flex overflow-x-auto space-x-4 p-4 bg-white dark:bg-gray-800 rounded-lg shadow-md">
    @foreach($wallets as $wallet)
        <div class="flex-shrink-0 w-64">
            <x-financial-card 
                :item="$wallet" 
                type="wallet"
                :compact="true" 
                :show-actions="false" 
                :brand-color="$brandColor ?? 'brand-primary'"
            />
        </div>
    @endforeach
</div>
   