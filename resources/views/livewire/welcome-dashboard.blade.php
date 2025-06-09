<div>

        @if (Auth::user()->is_admin)
            <div class="flex items-center justify-between">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('common.welcome_name', ['name' => Auth::user()->name]) }}
                </h2>
                <div class="text-sm text-gray-600 dark:text-gray-400">
                    <i class="fa-solid fa-shield-halved mr-1"></i>
                    {{ __('admin.administrator_space') }}
                </div>
            </div>
            
        @else
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('common.welcome_name', ['name' => Auth::user()->name]) }}
            </h2>

            @if ($account)
                <div class="mt-4">
                    @if ($account->status === 'INACTIVE')
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                        {{ __('common.account_creation_under_review') }}
                    </span>
                @elseif ($account->status === 'ACTIVE')
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                        {{ __('common.active') }}
                    </span>
                @elseif ($account->status === 'SUSPENDED')
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                        {{ __('common.suspended') }}
                    </span>
                @endif
                </div>
            @else
                <div class="mt-4 text-gray-600 dark:text-gray-400">
                {{ __('common.no_account_found') }}
            </div>
            @endif
        @endif
</div>
