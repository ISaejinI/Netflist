<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SerieCard extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public string $type,
        public $poster,
        public string $title,
        public string $id,
        public $genres = null,
        public $date,
        public $rating,
        public string $overview,
        public $episodes,
        public bool $watched = false,
    )
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.serie-card');
    }
}
