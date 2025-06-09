<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg" wire:click="deselectGroup">
    <!-- Header -->
    <div class="bg-gradient-to-r from-brand-primary to-brand-accent p-6" wire:click.stop>
        <h2 class="text-xl font-semibold dark:text-white mb-2">{{ __('admin.transfer_step_management') }}</h2>
        <div class="w-16 h-1 bg-gray-200 dark:bg-gray-300 rounded-full"></div>
    </div>

    <div class="p-6" wire:click.stop>

        <!-- Search and Filters -->
        <div class="mb-6 grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Search -->
            <div class="md:col-span-2">
                <input wire:model.live.debounce.300ms="search" type="text" 
                       class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md leading-5 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-brand-primary focus:border-brand-primary" 
                       placeholder="{{ __('admin.search_groups_placeholder') }}">
            </div>

            <!-- Status Filter -->
            <div>
                <select wire:model.live="statusFilter" 
                        class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-1 focus:ring-brand-primary focus:border-brand-primary">
                    <option value="all">{{ __('admin.all_statuses') }}</option>
                    <option value="active">{{ __('common.active') }}</option>
                    <option value="inactive">{{ __('common.inactive') }}</option>
                </select>
            </div>
        </div>

        <!-- Create Group Button -->
        <div class="mb-6">
            <button wire:click="createGroup" 
                    class="px-4 py-2 bg-brand-primary text-white rounded-lg hover:bg-brand-primary/90 transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                    wire:loading.attr="disabled" 
                    wire:target="createGroup">
                <span wire:loading.remove wire:target="createGroup">
                    <i class="fa-solid fa-plus mr-2"></i>
                    {{ __('admin.create_group') }}
                </span>
                <span wire:loading wire:target="createGroup">
                    <i class="fa-solid fa-spinner fa-spin mr-2"></i>
                    {{ __('admin.create_group') }}
                </span>
            </button>
        </div>

        <!-- Groups Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Groups List -->
            <div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">{{ __('admin.transfer_step_groups') }}</h3>
                
                @if ($groups->isEmpty())
                    <div class="text-center py-12">
                        <div class="w-16 h-16 mx-auto mb-4 bg-brand-secondary/10 rounded-full flex items-center justify-center">
                            <i class="fa-solid fa-layer-group text-2xl text-brand-secondary"></i>
                        </div>
                        <p class="text-brand-secondary font-medium">{{ __('admin.no_groups_found') }}</p>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach ($groups as $group)
                            <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 hover:shadow-md transition-all duration-200 {{ $selectedGroup == $group->id ? 'ring-2 ring-brand-primary bg-brand-primary/5 border-brand-primary shadow-lg transform scale-[1.02]' : 'hover:border-brand-primary/30' }} cursor-pointer" wire:click="selectGroup({{ $group->id }})">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center space-x-2 mb-2">
                                            <h4 class="font-semibold text-gray-900 dark:text-gray-100">{{ $group->name }}</h4>
                                            @if($group->is_active)
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-brand-success/10 text-brand-success border border-brand-success/20">
                                                    {{ __('common.active') }}
                                                </span>
                                            @else
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800 border border-gray-200">
                                                    {{ __('common.inactive') }}
                                                </span>
                                            @endif
                                        </div>
                                        @if($group->description)
                                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">{{ $group->description }}</p>
                                        @endif
                                        <div class="flex items-center space-x-4 text-xs text-gray-500">
                                            <span><i class="fa-solid fa-list mr-1"></i>{{ $group->transfer_steps_count }} {{ __('admin.steps') }}</span>
                                            <span><i class="fa-solid fa-users mr-1"></i>{{ $group->accounts_count }} {{ __('admin.accounts') }}</span>
                                        </div>
                                    </div>
                                    <div class="flex space-x-2">
                                        <button wire:click.stop="editGroup({{ $group->id }})" 
                                                title="{{ __('common.edit') }}"
                                                class="p-2 rounded-lg bg-brand-primary/10 text-brand-primary hover:bg-brand-primary hover:text-white transition-all duration-200 hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed"
                                                wire:loading.attr="disabled" 
                                                wire:target="editGroup({{ $group->id }})"
                                                @if($editingGroupId == $group->id) disabled @endif>
                                            <div wire:loading.remove wire:target="editGroup({{ $group->id }})">
                                                <i class="fa-solid fa-edit w-4 h-4"></i>
                                            </div>
                                            <div wire:loading wire:target="editGroup({{ $group->id }})" class="animate-spin">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                </svg>
                                            </div>
                                        </button>
                                        <button wire:click.stop="toggleGroupStatus({{ $group->id }})" 
                                                title="{{ $group->is_active ? __('common.deactivate') : __('common.activate') }}"
                                                class="p-2 rounded-lg {{ $group->is_active ? 'bg-brand-warning/10 text-brand-warning hover:bg-brand-warning' : 'bg-brand-success/10 text-brand-success hover:bg-brand-success' }} hover:text-white transition-all duration-200 hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed"
                                                wire:loading.attr="disabled" 
                                                wire:target="toggleGroupStatus({{ $group->id }})"
                                                @if($isTogglingGroupStatus == $group->id) disabled @endif>
                                            <div wire:loading.remove wire:target="toggleGroupStatus({{ $group->id }})">
                                                <i class="fa-solid {{ $group->is_active ? 'fa-pause-circle' : 'fa-check-circle' }} w-4 h-4"></i>
                                            </div>
                                            <div wire:loading wire:target="toggleGroupStatus({{ $group->id }})" class="animate-spin">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 714 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                </svg>
                                            </div>
                                        </button>
                                        <button wire:click.stop="deleteGroup({{ $group->id }})" 
                                                wire:confirm="{{ __('messages.confirm_delete_transfer_group') }}"
                                                title="{{ __('common.delete') }}"
                                                class="p-2 rounded-lg bg-brand-error/10 text-brand-error hover:bg-brand-error hover:text-white transition-all duration-200 hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed"
                                                wire:loading.attr="disabled" 
                                                wire:target="deleteGroup({{ $group->id }})"
                                                @if($isDeletingGroup == $group->id) disabled @endif>
                                            <div wire:loading.remove wire:target="deleteGroup({{ $group->id }})">
                                                <i class="fa-solid fa-trash-can w-4 h-4"></i>
                                            </div>
                                            <div wire:loading wire:target="deleteGroup({{ $group->id }})" class="animate-spin">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 714 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                </svg>
                                            </div>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="mt-6">
                        {{ $groups->links() }}
                    </div>
                @endif
            </div>

            <!-- Steps List -->
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ __('admin.transfer_steps') }}</h3>
                    @if($selectedGroup)
                        <button wire:click="createStep({{ $selectedGroup }})" 
                                class="px-3 py-1 bg-brand-accent text-white text-sm rounded hover:bg-brand-accent/90 transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                                wire:loading.attr="disabled" 
                                wire:target="createStep({{ $selectedGroup }})">
                            <span wire:loading.remove wire:target="createStep({{ $selectedGroup }})">
                                <i class="fa-solid fa-plus mr-1"></i>
                                {{ __('admin.add_step') }}
                            </span>
                            <span wire:loading wire:target="createStep({{ $selectedGroup }})">
                                <i class="fa-solid fa-spinner fa-spin mr-1"></i>
                                {{ __('admin.add_step') }}
                            </span>
                        </button>
                    @endif
                </div>

                @if(!$selectedGroup)
                    <div class="text-center py-12">
                        <div class="w-16 h-16 mx-auto mb-4 bg-brand-secondary/10 rounded-full flex items-center justify-center">
                            <i class="fa-solid fa-hand-pointer text-2xl text-brand-secondary"></i>
                        </div>
                        <p class="text-brand-secondary font-medium">{{ __('admin.select_group_to_view_steps') }}</p>
                    </div>
                @elseif($steps->isEmpty())
                    <div class="text-center py-12">
                        <div class="w-16 h-16 mx-auto mb-4 bg-brand-secondary/10 rounded-full flex items-center justify-center">
                            <i class="fa-solid fa-list text-2xl text-brand-secondary"></i>
                        </div>
                        <p class="text-brand-secondary font-medium">{{ __('admin.no_steps_found') }}</p>
                    </div>
                @else
                    <div class="space-y-3">
                        @foreach ($steps as $step)
                            <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center space-x-2 mb-2">
                                            <span class="w-6 h-6 bg-brand-primary text-white text-xs rounded-full flex items-center justify-center font-semibold">{{ $step->order }}</span>
                                            <h5 class="font-semibold text-gray-900 dark:text-gray-100">{{ $step->title }}</h5>
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-brand-primary/20 text-brand-primary">
                                                {{ ucfirst($step->type) }}
                                            </span>
                                        </div>
                                        @if($step->description)
                                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">{{ $step->description }}</p>
                                        @endif
                                        <p class="text-xs text-gray-500 font-mono">{{ __('admin.code') }}: {{ $step->code }}</p>
                                    </div>
                                    <div class="flex space-x-2 ml-4">
                                        <button wire:click="editStep({{ $step->id }})" 
                                                title="{{ __('common.edit') }}"
                                                class="p-2 rounded-lg bg-brand-primary/10 text-brand-primary hover:bg-brand-primary hover:text-white transition-all duration-200 hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed"
                                                wire:loading.attr="disabled" 
                                                wire:target="editStep({{ $step->id }})">
                                            <div wire:loading.remove wire:target="editStep({{ $step->id }})">
                                                <i class="fa-solid fa-edit w-4 h-4"></i>
                                            </div>
                                            <div wire:loading wire:target="editStep({{ $step->id }})" class="animate-spin">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                </svg>
                                            </div>
                                        </button>
                                        <button wire:click="deleteStep({{ $step->id }})" 
                                                wire:confirm="{{ __('messages.confirm_delete_transfer_step') }}"
                                                title="{{ __('common.delete') }}"
                                                class="p-2 rounded-lg bg-brand-error/10 text-brand-error hover:bg-brand-error hover:text-white transition-all duration-200 hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed"
                                                wire:loading.attr="disabled" 
                                                wire:target="deleteStep({{ $step->id }})"
                                                @if($isDeletingStep == $step->id) disabled @endif>
                                            <div wire:loading.remove wire:target="deleteStep({{ $step->id }})">
                                                <i class="fa-solid fa-trash-can w-4 h-4"></i>
                                            </div>
                                            <div wire:loading wire:target="deleteStep({{ $step->id }})" class="animate-spin">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                </svg>
                                            </div>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Group Modal -->
    @if($showGroupModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" wire:click="closeGroupModal">
            <div class="bg-white dark:bg-gray-800 rounded-lg p-6 w-full max-w-md mx-4" wire:click.stop>
                <div class="mt-3">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                        {{ $editingGroupId ? __('admin.edit_group') : __('admin.create_group') }}
                    </h3>
                    
                    <form wire:submit="saveGroup">
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('admin.group_name') }}</label>
                            <input wire:model="groupName" type="text" 
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-1 focus:ring-brand-primary focus:border-brand-primary">
                            @error('groupName') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('admin.description') }}</label>
                            <textarea wire:model="groupDescription" rows="3" 
                                      class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-1 focus:ring-brand-primary focus:border-brand-primary"></textarea>
                            @error('groupDescription') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        
                        <div class="mb-6">
                            <label class="flex items-center">
                                <input wire:model="groupIsActive" type="checkbox" 
                                       class="rounded border-gray-300 text-brand-primary focus:ring-brand-primary">
                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">{{ __('admin.is_active') }}</span>
                            </label>
                        </div>
                        
                        <div class="flex justify-end space-x-3">
                            <button type="button" wire:click="closeGroupModal" 
                                    class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition-colors duration-200">
                                {{ __('common.cancel') }}
                            </button>
                            <button type="submit" 
                                    class="px-4 py-2 bg-brand-primary text-white rounded-md hover:bg-brand-primary/90 transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                                    wire:loading.attr="disabled" 
                                    wire:target="saveGroup"
                                    @if($isSavingGroup) disabled @endif>
                                <span wire:loading.remove wire:target="saveGroup">
                                    {{ $editingGroupId ? __('common.update') : __('common.create') }}
                                </span>
                                <span wire:loading wire:target="saveGroup">
                                    <i class="fa-solid fa-spinner fa-spin mr-2"></i>
                                    {{ $editingGroupId ? __('common.update') : __('common.create') }}
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- Step Modal -->
    @if($showStepModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" wire:click="closeStepModal">
            <div class="bg-white dark:bg-gray-800 rounded-lg p-6 w-full max-w-md mx-4" wire:click.stop>
                <div class="mt-3">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                        {{ $editingStepId ? __('admin.edit_step') : __('admin.create_step') }}
                    </h3>
                    
                    <form wire:submit="saveStep">
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('admin.step_title') }}</label>
                            <input wire:model="stepTitle" type="text" 
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-1 focus:ring-brand-primary focus:border-brand-primary">
                            @error('stepTitle') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('admin.description') }}</label>
                            <textarea wire:model="stepDescription" rows="3" 
                                      class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-1 focus:ring-brand-primary focus:border-brand-primary"></textarea>
                            @error('stepDescription') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('admin.step_code') }}</label>
                                <input wire:model="stepCode" type="text" 
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-1 focus:ring-brand-primary focus:border-brand-primary">
                                @error('stepCode') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('admin.order') }}</label>
                                <input wire:model="stepOrder" type="number" min="1" 
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-1 focus:ring-brand-primary focus:border-brand-primary">
                                @error('stepOrder') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('admin.step_type') }}</label>
                            <select wire:model="stepType" 
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-1 focus:ring-brand-primary focus:border-brand-primary">
                                <option value="verification">{{ __('admin.verification') }}</option>
                                <option value="document">{{ __('admin.document') }}</option>
                                <option value="payment">{{ __('admin.payment') }}</option>
                                <option value="confirmation">{{ __('admin.confirmation') }}</option>
                            </select>
                            @error('stepType') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        
                        <div class="flex justify-end space-x-3">
                            <button type="button" wire:click="closeStepModal" 
                                    class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition-colors duration-200">
                                {{ __('common.cancel') }}
                            </button>
                            <button type="submit" 
                                    class="px-4 py-2 bg-brand-primary text-white rounded-md hover:bg-brand-primary/90 transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                                    wire:loading.attr="disabled" 
                                    wire:target="saveStep"
                                    @if($isSavingStep) disabled @endif>
                                <span wire:loading.remove wire:target="saveStep">
                                    {{ $editingStepId ? __('common.update') : __('common.create') }}
                                </span>
                                <span wire:loading wire:target="saveStep">
                                    <i class="fa-solid fa-spinner fa-spin mr-2"></i>
                                    {{ $editingStepId ? __('common.update') : __('common.create') }}
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
