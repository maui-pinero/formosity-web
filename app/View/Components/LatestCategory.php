<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Models\Category;

class LatestCategory extends Component
{
    public $categories;
    public function __construct()
    {
        $this->categories = Category::active()->latest()->limit(5)->get('name');
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.latest-category');
    }
}
