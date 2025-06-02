<div class="w-full">
    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">{{ __('common.latest_inactive_accounts') }}</h2>


    @if ($inactiveAccounts->isEmpty())
        <p class="text-gray-600 dark:text-gray-400">{{ __('common.no_inactive_accounts_found') }}</p>
    @else
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('common.account_number') }}</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('common.username') }}</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('common.registration_date') }}</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('common.status') }}</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('common.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach ($inactiveAccounts as $account)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100 flex items-center">
                                <span data-account-number="{{ $account->account_number }}">{{ $account->account_number }}</span>
                                <button x-data x-on:click="$wire.copyAccount('{{ $account->account_number }}')" title="{{ __('common.copy_account_number') }}" class="ml-2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                                    <i class="fa-solid fa-copy w-4 h-4"></i>
                                </button>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ $account->user->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ $account->user->created_at->diffForHumans() }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @php
                                    $statusClass = '';
                                    switch($account->status) {
                                        case 'pending':
                                            $statusClass = 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200';
                                            break;
                                        case 'active':
                                            $statusClass = 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200';
                                            break;
                                        case 'suspended':
                                            $statusClass = 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200';
                                            break;
                                        default:
                                            $statusClass = 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300';
                                            break;
                                    }
                                @endphp
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}" wire:ignore.self>
                                    {{ __(ucfirst($account->status)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium flex space-x-2" wire:ignore>
                                <button wire:click="activateAccount({{ $account->id }})" wire:confirm="{{ __('common.confirm_activate_account') }}" title="{{ __('common.activate') }}" class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-600">
                                    <i class="fa-solid fa-check-circle w-5 h-5"></i>
                                </button>
                                <button wire:click="suspendAccount({{ $account->id }})" wire:confirm="{{ __('common.confirm_suspend_account') }}" title="{{ __('common.suspend') }}" class="text-yellow-600 hover:text-yellow-900 dark:text-yellow-400 dark:hover:text-yellow-600">
                                    <i class="fa-solid fa-pause-circle w-5 h-5"></i>
                                </button>
                                <button wire:click="deleteAccount({{ $account->id }})" wire:confirm="{{ __('common.confirm_delete_account') }}" title="{{ __('common.delete') }}" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-600">
                                    <i class="fa-solid fa-trash-can w-5 h-5"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $inactiveAccounts->links() }}
        </div>
    @endif
</div>
