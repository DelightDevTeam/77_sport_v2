<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Admin\TwodWiner;

class PrizeNoController extends Controller
{
    public function MorningPrizeNo()
    {
        $prizes = TwodWiner::where('session', 'morning')->orderBy('prize_no', 'desc')->get();

        return view('frontend.morning_history', compact('prizes'));
    }

    public function EveningPrizeNo()
    {
        $prizes = TwodWiner::where('session', 'evening')->orderBy('prize_no', 'desc')->get();

        return view('frontend.evening_history', compact('prizes'));
    }
}
