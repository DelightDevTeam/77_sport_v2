<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ThreedDigit extends Model
{
    use HasFactory;

    protected $fillable = ['three_digit'];
}
