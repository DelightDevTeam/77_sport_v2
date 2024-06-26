<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BetLotteryMatching extends Model
{
    use HasFactory;

    protected $table = 'bet_lottery_matching';

    protected $fillable = ['matching_id', 'bet_lottery_id', 'digit_entry', 'sub_amount', 'prize_sent'];
}
