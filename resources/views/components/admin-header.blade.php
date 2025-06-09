@props(['title', 'icon' => 'fa-solid fa-home', 'showAdminSpace' => true])

<div class="bg-gradient-to-r from-blue-600 to-indigo-600 dark:from-blue-700 dark:to-indigo-700 text-white p-6 rounded-lg shadow-lg">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold mb-2">
                <i class="{{ $icon }} mr-2"></i>
                {{ $title }}
            </h1>
        </div>
        @if($showAdminSpace && Auth::check() && Auth::user()->is_admin)
             <div class="text-right">
                 <p class="text-blue-100 text-sm font-medium">
                     <i class="fa-solid fa-shield-halved mr-1"></i>
                     {{ __('admin.administrator_space') }}
                 </p>
             </div>
         @endif
    </div>
</div>