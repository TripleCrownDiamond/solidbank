<?php

namespace App\Livewire;

use Livewire\Component;

class AlertManager extends Component
{
    public $alerts = [];
    
    protected $listeners = [
        'alert' => 'showAlert',
        'show-alert' => 'showAlert',
        'clear-alerts' => 'clearAlerts'
    ];
    
    public function mount()
    {
        // Check for session flash messages
        $this->checkSessionFlash();
    }
    
    public function checkSessionFlash()
    {
        if (session()->has('alert')) {
            $alert = session('alert');
            $this->alerts[] = [
                'id' => 'alert-' . uniqid(),
                'type' => $alert['type'] ?? 'info',
                'message' => $alert['message'] ?? '',
                'dismissible' => $alert['dismissible'] ?? true
            ];
            session()->forget('alert');
        }
        
        // Check for individual flash message types
        $types = ['success', 'error', 'warning', 'info'];
        foreach ($types as $type) {
            if (session()->has($type)) {
                $this->alerts[] = [
                    'id' => 'alert-' . uniqid(),
                    'type' => $type,
                    'message' => session($type),
                    'dismissible' => true
                ];
                session()->forget($type);
            }
        }
    }
    
    public function showAlert($data)
    {
        $this->alerts[] = [
            'id' => 'alert-' . uniqid(),
            'type' => $data['type'] ?? 'info',
            'message' => $data['message'] ?? '',
            'dismissible' => $data['dismissible'] ?? true
        ];
    }
    
    public function removeAlert($alertId)
    {
        $this->alerts = array_filter($this->alerts, function($alert) use ($alertId) {
            return $alert['id'] !== $alertId;
        });
    }
    
    public function clearAlerts()
    {
        $this->alerts = [];
    }
    
    public function render()
    {
        return view('livewire.alert-manager');
    }
}