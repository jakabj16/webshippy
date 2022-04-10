<?php

namespace App\Actions;

use App\Models\Stock;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Lorisleiva\Actions\Concerns\AsAction;

class ReadStockFromJson
{
    use AsAction;

    /**
     * @param string $stockString
     * @return Collection
     * @throws \JsonException
     */
    public function handle(string $stockString): Collection
    {
        $stockArray = json_decode(json: $stockString, associative: true, flags: JSON_THROW_ON_ERROR);

        $stock = new Collection();
        foreach ($stockArray as $productId => $quantity) {
            $attributes = ['product_id' => $productId, 'quantity' => $quantity];

            Validator::validate($attributes, [
                'product_id' => ['required', 'integer'],
                'quantity' => ['required', 'integer', 'min:0'],
            ]);

            $stock->put($productId, new Stock($attributes));
        }

        return $stock;
    }
}
