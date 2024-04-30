<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ThreedLotteryEntry extends Model
{
    use HasFactory;

    public $table = 'lottery_match_pivot';

    protected $fillable = [
        'threed_lottery_id',
        'digit_entry',
        'sub_amount',
        'prize_sent',
    ];

    protected $dates = ['created_at', 'updated_at'];

    /**
     * Get the lottery that owns the entry.
     */
    public function threedLottery()
    {
        return $this->belongsTo(ThreedLottery::class, 'threed_lottery_id');
    }
}
