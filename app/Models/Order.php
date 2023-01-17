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
        'city_id',
        'district_id',
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

    /**
     * > This function returns the city that this order belongs to
     * 
     * @return A collection of all the orders for the city.
     */
    public function city()
    {
        return $this->belongsTo(City::class);
    }

    /**
     * > This function returns the district that this order belongs to
     * 
     * @return A collection of all the orders for the district.
     */
    public function district()
    {
        return $this->belongsTo(District::class);
    }
}
