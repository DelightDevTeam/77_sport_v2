<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrizeSentTwoDigit extends Model
{
    use HasFactory;

    protected $table = 'two_digits';

    protected $fillable = [
        'two_digit',
    ];

    public function lotteries()
    {
        return $this->belongsToMany(Lottery::class, 'lottery_two_digit_copy')->withPivot('sub_amount');
    }
}
