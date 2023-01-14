<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'product_id',
        'total_price',
        'quantity',
        'status',
    ];

    /**
     * > This function returns the client that owns this project
     * 
     * @return A collection of all the clients that have a project with the id of the project that is being
     * called.
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * > This function returns the product that this review belongs to
     * 
     * @return A collection of all the reviews for the product.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
