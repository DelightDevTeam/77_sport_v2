<?php

namespace App\Models;

use App\Models\ThreeDigit\Lotto;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ThreeDigit extends Model
{
    use HasFactory;

    protected $fillable = ['three_digit'];

    public function lottos()
    {
        return $this->belongsToMany(Lotto::class, 'lotto_three_digit_over')->withPivot('sub_amount');
    }
}
