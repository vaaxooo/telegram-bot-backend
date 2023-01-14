<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RelationCategory extends Model
{
    use HasFactory;

    protected $table = 'relation_categories';

    protected $fillable = [
        'city_id',
        'category_id',
    ];

    /**
     * > This function returns the city that this address belongs to
     * 
     * @return The city that the user belongs to.
     */
    public function city()
    {
        return $this->belongsTo(City::class);
    }

    /**
     * > This function returns the category that the post belongs to
     * 
     * @return A collection of all the replies for the given question.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
