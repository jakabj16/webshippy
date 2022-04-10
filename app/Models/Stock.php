<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Definition
 * @package App\Models
 *
 * @property integer $product_id
 * @property integer $quantity
 */
class Stock extends Model
{
    protected $fillable = [
        'product_id',
        'quantity',
    ];
}
