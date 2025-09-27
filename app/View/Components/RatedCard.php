<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class RatedCard extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public int $rateNumber,
        public $poster,
        public string $title,
        public string $id,
        public $date,
        public $rating,
        public string $overview,
        public bool $isMovie,
    )
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.rated-card');
    }
}
