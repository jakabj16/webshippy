<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

/**
 * Class Definition
 * @package App\Models
 *
 * @property integer $product_id
 * @property integer $quantity
 * @property integer $priority
 * @property string $created_at
 */
class Order extends Model
{
    const PRIORITY_LOW = 'low';
    const PRIORITY_MEDIUM = 'medium';
    const PRIORITY_HIGH = 'high';

    const PRIORITIES = [
        1 => self::PRIORITY_LOW,
        2 => self::PRIORITY_MEDIUM,
        3 => self::PRIORITY_HIGH,
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

    protected $fillable = [
        'product_id',
        'quantity',
        'priority',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
    ];

    public static function getPriority(int $key): string
    {
        if (isset(self::PRIORITIES[$key])) {
            return self::PRIORITIES[$key];
        }

        throw new \RuntimeException(__(':key priority key does not exists.', ['key' => $key]));
    }

    protected function priorityName(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => self::getPriority($attributes['priority'])
        );
    }

    public function present(string $attribute): string
    {
        $value = $this->{$attribute};

        if ($attribute === 'priority') {
            $value = $this->priorityName;
        }

        return str_pad($value, 20);
    }
}
