<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\Admin\Promotion;

class PromotionController extends Controller
{
    public function promo()
    {
        $promotions = Promotion::latest()->get();

        // return $promotions;
        return view('frontend.promotion', compact('promotions'));
    }

    public function promoDetail($id)
    {
        $promotion = Promotion::find($id);

        return view('frontend.promoDetail', compact('promotion'));
    }
}
