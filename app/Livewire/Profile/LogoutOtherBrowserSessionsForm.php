<?php

namespace App\Livewire\Profile;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class LogoutOtherBrowserSessionsForm extends Component
{
    public $confirmingLogout = false;
    public $password = '';

    protected $rules = [
        'password' => 'required|string|current_password:web',
    ];

    protected $messages = [
        'password.required' => 'Le mot de passe est obligatoire.',
        'password.current_password' => 'Le mot de passe fourni ne correspond pas à votre mot de passe actuel.',
    ];

    public function confirmLogout()
    {
        $this->confirmingLogout = true;
    }

    public function logoutOtherBrowserSessions()
    {
        $this->validate();

        Auth::logoutOtherDevices($this->password);

        $this->deleteOtherSessionRecords();

        $this->password = '';
        $this->confirmingLogout = false;

        $this->dispatch('loggedOut');
        
        session()->flash('status', 'other-browser-sessions-logged-out');
    }

    protected function deleteOtherSessionRecords()
    {
        if (config('session.driver') !== 'database') {
            return;
        }

        DB::connection(config('session.connection'))->table(config('session.table', 'sessions'))
            ->where('user_id', Auth::user()->getAuthIdentifier())
            ->where('id', '!=', request()->session()->getId())
            ->delete();
    }

    public function getSessions()
    {
        if (config('session.driver') !== 'database') {
            return collect();
        }

        return collect(
            DB::connection(config('session.connection'))
                ->table(config('session.table', 'sessions'))
                ->where('user_id', Auth::user()->getAuthIdentifier())
                ->orderBy('last_activity', 'desc')
                ->get()
        )->map(function ($session) {
            $agent = $this->createAgent($session);

            return (object) [
                'agent' => [
                    'is_desktop' => $agent->isDesktop(),
                    'platform' => $agent->platform(),
                    'browser' => $agent->browser(),
                ],
                'ip_address' => $session->ip_address,
                'is_current_device' => $session->id === request()->session()->getId(),
                'last_active' => \Carbon\Carbon::createFromTimestamp($session->last_activity)->diffForHumans(),
            ];
        });
    }

    protected function createAgent($session)
    {
        return tap(new \Jenssegers\Agent\Agent, function ($agent) use ($session) {
            $agent->setUserAgent($session->user_agent);
        });
    }

    public function render()
    {
        return view('profile.logout-other-browser-sessions-form', [
            'sessions' => $this->getSessions(),
        ]);
    }
}