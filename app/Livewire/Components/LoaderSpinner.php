<?php

namespace App\Livewire\Components;

use Livewire\Component;

class LoaderSpinner extends Component
{
    /**
     * Le texte à afficher à côté du spinner
     */
    public $text = '';
    
    /**
     * Indique si le spinner est en cours de chargement
     */
    public $loading = false;
    
    /**
     * Le texte à afficher lorsque le spinner n'est pas en cours de chargement
     */
    public $defaultText = '';
    
    /**
     * La taille du spinner (sm, md, lg)
     */
    public $size = 'md';
    
    /**
     * La position du spinner par rapport au texte (left, right)
     */
    public $position = 'left';
    
    /**
     * Rendre le composant
     */
    public function render()
    {
        return view('livewire.components.loader-spinner');
    }
}