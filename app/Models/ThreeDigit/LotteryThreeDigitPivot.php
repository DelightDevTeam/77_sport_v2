<?php

namespace App\Models\ThreeDigit;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LotteryThreeDigitPivot extends Model
{
    use HasFactory;

    protected $table = 'lotto_three_digit_pivot';

    protected $fillable = ['result_date_id', 'three_digit_id', 'lotto_id', 'user_id', 'bet_digit', 'sub_amount', 'prize_sent', 'match_status', 'res_date', 'res_time', 'match_start_date', 'admin_log', 'user_log'];

    // This will automatically boot with the model's events

    // This will automatically boot with the model's events
    protected static function booted()
    {
        static::created(function ($pivot) {
            LotteryThreeDigitCopy::create($pivot->toArray());
        });
    }
}
