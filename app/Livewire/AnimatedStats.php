<?php

namespace App\Livewire;

use Livewire\Component;

class AnimatedStats extends Component
{
    public $stats = [];
    public $type = 'recovery'; // 'recovery' ou 'general'

    public function mount($type = 'recovery')
    {
        $this->type = $type;
        $this->generateStats();
    }

    public function generateStats()
    {
        if ($this->type === 'recovery') {
            $this->stats = [
                [
                    'id' => 'compensation',
                    'value' => 64390,
                    'suffix' => '$',
                    'prefix' => '',
                    'label' => 'Montant Total Remboursé',
                    'color' => 'green',
                    'icon' => 'fas fa-dollar-sign',
                    'increment' => 50
                ],
                [
                    'id' => 'cases',
                    'value' => 127,
                    'suffix' => '',
                    'prefix' => '',
                    'label' => 'Dossiers Traités',
                    'color' => 'blue',
                    'icon' => 'fas fa-file-alt',
                    'increment' => 1
                ],
                [
                    'id' => 'success_rate',
                    'value' => 95,
                    'suffix' => '%',
                    'prefix' => '',
                    'label' => 'Taux de Réussite',
                    'color' => 'indigo',
                    'icon' => 'fas fa-chart-line',
                    'increment' => 1
                ],
                [
                    'id' => 'avg_time',
                    'value' => 7,
                    'suffix' => ' jours',
                    'prefix' => '',
                    'label' => 'Délai Moyen',
                    'color' => 'purple',
                    'icon' => 'fas fa-clock',
                    'increment' => 1
                ]
            ];
        } else {
            $this->stats = [
                [
                    'id' => 'users',
                    'value' => 12450,
                    'suffix' => '',
                    'prefix' => '',
                    'label' => 'Utilisateurs Actifs',
                    'color' => 'blue',
                    'icon' => 'fas fa-users',
                    'increment' => 5
                ],
                [
                    'id' => 'transactions',
                    'value' => 89750,
                    'suffix' => '',
                    'prefix' => '',
                    'label' => 'Transactions',
                    'color' => 'green',
                    'icon' => 'fas fa-exchange-alt',
                    'increment' => 10
                ],
                [
                    'id' => 'volume',
                    'value' => 2450000,
                    'suffix' => '€',
                    'prefix' => '',
                    'label' => 'Volume Total',
                    'color' => 'indigo',
                    'icon' => 'fas fa-chart-bar',
                    'increment' => 1000
                ],
                [
                    'id' => 'countries',
                    'value' => 45,
                    'suffix' => '',
                    'prefix' => '',
                    'label' => 'Pays Couverts',
                    'color' => 'purple',
                    'icon' => 'fas fa-globe',
                    'increment' => 1
                ]
            ];
        }
    }

    public function updateStats()
    {
        foreach ($this->stats as &$stat) {
            // Simulation d'augmentation des statistiques
            if (rand(1, 100) <= 30) { // 30% de chance d'augmentation
                $stat['value'] += $stat['increment'];
            }
        }
        
        $this->dispatch('stats-updated', $this->stats);
    }

    public function render()
    {
        return view('livewire.animated-stats');
    }
}