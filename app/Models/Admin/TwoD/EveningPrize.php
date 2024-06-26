<?php

namespace App\Models\Admin\TwoD;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EveningPrize extends Model
{
    use HasFactory;

    protected $table = 'evening_prizes';

    protected $fillable = ['user_id', 'user_name', 'phone', 'bet_digit', 'sub_amount', 'prize_amount', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
