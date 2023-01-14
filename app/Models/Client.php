<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'telegram_id',
        'nickname',
        'first_name',
        'last_name',
        'language_code',
        'phone_number',
        'balance',
        'referral',
        'is_banned',
    ];

    protected $casts = [
        'is_banned' => 'boolean',
    ];
}
