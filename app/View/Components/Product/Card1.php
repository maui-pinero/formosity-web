<?php

namespace App\View\Components\Product;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Card1 extends Component
{
    public $product;
    public function __construct($product)
    {
        $this->product = $product;
    }

    public function render(): View|Closure|string
    {
        return view('components.product.card1');
    }
}
