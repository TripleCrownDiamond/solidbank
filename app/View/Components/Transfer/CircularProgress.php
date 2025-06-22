<?php

namespace App\View\Components\Transfer;

use Illuminate\View\Component;

class CircularProgress extends Component
{
    public $progress;
    public $statusMessage;
    public $isTransferStarted;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($progress = 0, $statusMessage = '', $isTransferStarted = false)
    {
        $this->progress = $progress;
        $this->statusMessage = $statusMessage;
        $this->isTransferStarted = $isTransferStarted;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.transfer.circular-progress');
    }
}