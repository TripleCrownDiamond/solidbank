<?php

namespace App\Livewire;

use App\Models\TransferStep;
use App\Models\TransferStepGroup;
use Livewire\Component;
use Livewire\WithPagination;

class TransferStepManagement extends Component
{
    use WithPagination;

    // Group management
    public $showGroupModal = false;
    public $editingGroupId = null;
    public $groupName = '';
    public $groupDescription = '';
    public $groupIsActive = true;
    public $isCreatingGroup = false;
    public $isSavingGroup = false;
    public $isDeletingGroup = false;
    public $isTogglingGroupStatus = false;
    // Step management
    public $showStepModal = false;
    public $editingStepId = null;
    public $selectedGroupId = null;
    public $stepTitle = '';
    public $stepDescription = '';
    public $stepCode = '';
    public $stepOrder = 1;
    public $stepType = 'verification';
    public $isCreatingStep = false;
    public $isSavingStep = false;
    public $isDeletingStep = false;
    // Filters and search
    public $search = '';
    public $statusFilter = 'all';
    public $selectedGroup = null;
    public $loadingAction = null;

    protected $listeners = ['execute-method' => 'executeMethod'];

    protected $rules = [
        'groupName' => 'required|string|max:255',
        'groupDescription' => 'nullable|string',
        'groupIsActive' => 'boolean',
        'stepTitle' => 'required|string|max:255',
        'stepDescription' => 'nullable|string',
        'stepCode' => 'required|string|max:50|unique:transfer_steps,code',
        'stepOrder' => 'required|integer|min:1',
        'stepType' => 'required|string|in:verification,document,payment,confirmation',
    ];

    public function mount()
    {
        $this->resetPage();
    }

    public function render()
    {
        $groups = TransferStepGroup::query()
            ->when($this->search, function ($query) {
                $query
                    ->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('description', 'like', '%' . $this->search . '%');
            })
            ->when($this->statusFilter !== 'all', function ($query) {
                $query->where('is_active', $this->statusFilter === 'active');
            })
            ->withCount('transferSteps')
            ->withCount('accounts')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $steps = collect();
        if ($this->selectedGroup) {
            $steps = TransferStep::where('transfer_step_group_id', $this->selectedGroup)
                ->orderBy('order')
                ->get();
        }

        return view('livewire.transfer-step-management', [
            'groups' => $groups,
            'steps' => $steps,
        ]);
    }

    // Group management methods
    public function createGroup()
    {
        $this->isCreatingGroup = true;
        $this->resetGroupForm();
        $this->showGroupModal = true;
        $this->isCreatingGroup = false;
    }

    public function editGroup($groupId)
    {
        $group = TransferStepGroup::findOrFail($groupId);
        $this->editingGroupId = $groupId;
        $this->groupName = $group->name;
        $this->groupDescription = $group->description;
        $this->groupIsActive = $group->is_active;
        $this->showGroupModal = true;
    }

    public function saveGroup()
    {
        $this->isSavingGroup = true;

        $this->validate([
            'groupName' => 'required|string|max:255',
            'groupDescription' => 'nullable|string|max:1000',
            'groupIsActive' => 'boolean',
        ]);

        try {
            if ($this->editingGroupId) {
                $group = TransferStepGroup::findOrFail($this->editingGroupId);
                $group->update([
                    'name' => $this->groupName,
                    'description' => $this->groupDescription,
                    'is_active' => $this->groupIsActive,
                ]);
                // Log::info('Dispatching group update alert');
                $this->dispatch('alert', ['type' => 'success', 'message' => __('messages.group_updated_successfully')]);
            } else {
                TransferStepGroup::create([
                    'name' => $this->groupName,
                    'description' => $this->groupDescription,
                    'is_active' => $this->groupIsActive,
                ]);
                // Log::info('Dispatching group create alert');
                $this->dispatch('alert', ['type' => 'success', 'message' => __('messages.group_created_successfully')]);
            }

            $this->closeGroupModal();
            $this->resetPage();
        } catch (\Exception $e) {
            $this->dispatch('alert', ['type' => 'error', 'message' => __('messages.group_save_error')]);
        } finally {
            $this->isSavingGroup = false;
        }
    }

    public function deleteGroup($groupId)
    {
        $this->processDeleteGroup($groupId);
    }

    public function processDeleteGroup($groupId)
    {
        $this->loadingAction = 'delete_group_' . $groupId;

        try {
            $group = TransferStepGroup::findOrFail($groupId);
            $group->delete();

            // Log::info('Dispatching group delete alert');
            $this->dispatch('alert', ['type' => 'success', 'message' => __('messages.group_deleted_successfully')]);

            $this->resetPage();
        } catch (\Exception $e) {
            // Log::info('Dispatching group delete error alert');
            $this->dispatch('alert', ['type' => 'error', 'message' => __('messages.group_deletion_error')]);
        }

        $this->loadingAction = null;
        $this->dispatch('action-completed');
    }

    public function toggleGroupStatus($groupId)
    {
        $this->isTogglingGroupStatus = $groupId;

        try {
            $group = TransferStepGroup::findOrFail($groupId);
            $group->update(['is_active' => !$group->is_active]);

            $message = $group->is_active ? __('messages.group_activated_successfully') : __('messages.group_deactivated_successfully');
            $this->dispatch('alert', ['type' => 'success', 'message' => $message]);
        } catch (\Exception $e) {
            $this->dispatch('alert', ['type' => 'error', 'message' => __('messages.group_status_change_error')]);
        } finally {
            $this->isTogglingGroupStatus = null;
        }
    }

    // Step management methods
    public function selectGroup($groupId)
    {
        $this->selectedGroup = $groupId;
    }

    public function deselectGroup()
    {
        $this->selectedGroup = null;
    }

    public function createStep($groupId)
    {
        $this->isCreatingStep = true;
        $this->resetStepForm();
        $this->selectedGroupId = $groupId;
        $this->showStepModal = true;
        $this->isCreatingStep = false;
    }

    public function editStep($stepId)
    {
        $step = TransferStep::findOrFail($stepId);
        $this->editingStepId = $stepId;
        $this->selectedGroupId = $step->transfer_step_group_id;
        $this->stepTitle = $step->title;
        $this->stepDescription = $step->description;
        $this->stepCode = $step->code;
        $this->stepOrder = $step->order;
        $this->stepType = $step->type;
        $this->showStepModal = true;
    }

    public function saveStep()
    {
        $this->isSavingStep = true;

        // Validation de l'unicité de l'ordre au sein du groupe
        $orderRule = 'required|integer|min:1';
        if ($this->editingStepId) {
            // En mode édition, exclure l'étape actuelle de la vérification d'unicité
            $orderRule .= '|unique:transfer_steps,order,' . $this->editingStepId . ',id,transfer_step_group_id,' . $this->selectedGroupId;
        } else {
            // En mode création, vérifier l'unicité dans le groupe
            $orderRule .= '|unique:transfer_steps,order,NULL,id,transfer_step_group_id,' . $this->selectedGroupId;
        }

        $this->validate([
            'stepTitle' => 'required|string|max:255',
            'stepDescription' => 'nullable|string|max:1000',
            'stepCode' => 'required|string|max:50|unique:transfer_steps,code,' . $this->editingStepId,
            'stepOrder' => $orderRule,
            'stepType' => 'required|in:document,verification,payment,confirmation',
        ]);

        try {
            if ($this->editingStepId) {
                $step = TransferStep::findOrFail($this->editingStepId);
                $step->update([
                    'title' => $this->stepTitle,
                    'description' => $this->stepDescription,
                    'code' => $this->stepCode,
                    'order' => $this->stepOrder,
                    'type' => $this->stepType,
                ]);
                // Log::info('Dispatching step update alert');
                $this->dispatch('alert', ['type' => 'success', 'message' => __('messages.step_updated_successfully')]);
            } else {
                TransferStep::create([
                    'transfer_step_group_id' => $this->selectedGroupId,
                    'title' => $this->stepTitle,
                    'description' => $this->stepDescription,
                    'code' => $this->stepCode,
                    'order' => $this->stepOrder,
                    'type' => $this->stepType,
                ]);
                // Log::info('Dispatching step create alert');
                $this->dispatch('alert', ['type' => 'success', 'message' => __('messages.step_created_successfully')]);
            }

            $this->closeStepModal();
        } catch (\Exception $e) {
            // Log::info('Dispatching step save error alert');
            $this->dispatch('alert', ['type' => 'error', 'message' => __('messages.step_save_error')]);
        } finally {
            $this->isSavingStep = false;
        }
    }

    public function deleteStep($stepId)
    {
        $this->processDeleteStep($stepId);
    }

    public function processDeleteStep($stepId)
    {
        $this->loadingAction = 'delete_step_' . $stepId;

        try {
            $step = TransferStep::findOrFail($stepId);
            $step->delete();

            // Log::info('Dispatching step delete alert');
            $this->dispatch('alert', ['type' => 'success', 'message' => __('messages.step_deleted_successfully')]);
        } catch (\Exception $e) {
            $this->dispatch('alert', ['type' => 'error', 'message' => __('messages.step_deletion_error')]);
        }

        $this->loadingAction = null;
        $this->dispatch('action-completed');
    }

    // Helper methods
    public function closeGroupModal()
    {
        $this->showGroupModal = false;
        $this->resetGroupForm();
    }

    public function closeStepModal()
    {
        $this->showStepModal = false;
        $this->resetStepForm();
    }

    private function resetGroupForm()
    {
        $this->editingGroupId = null;
        $this->groupName = '';
        $this->groupDescription = '';
        $this->groupIsActive = true;
        $this->resetErrorBag(['groupName', 'groupDescription', 'groupIsActive']);
    }

    private function resetStepForm()
    {
        $this->editingStepId = null;
        $this->selectedGroupId = null;
        $this->stepTitle = '';
        $this->stepDescription = '';
        $this->stepCode = '';
        $this->stepOrder = 1;
        $this->stepType = 'verification';
        $this->resetErrorBag(['stepTitle', 'stepDescription', 'stepCode', 'stepOrder', 'stepType']);
    }

    public function updatingSearch()
    {
        $this->resetPage();
        // Désélectionner le groupe quand on filtre pour éviter d'afficher des étapes d'un groupe qui pourrait être filtré
        $this->selectedGroup = null;
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
        // Désélectionner le groupe quand on change le filtre de statut
        $this->selectedGroup = null;
    }

    public function executeMethod($method, $params = [])
    {
        if (method_exists($this, $method)) {
            call_user_func_array([$this, $method], $params);
        }
    }
}
