<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'comment',
    ];

    /**
     * > This function returns the client that owns this project
     * 
     * @return A collection of all the clients that have a project with the given id.
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
