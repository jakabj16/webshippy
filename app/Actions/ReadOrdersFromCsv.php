<?php

namespace App\Actions;

use App\Models\Order;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Lorisleiva\Actions\Concerns\AsAction;

class ReadOrdersFromCsv
{
    use AsAction;

    /**
     * @param string $csvPath
     * @return Collection
     */
    public function handle(string $csvPath = 'orders.csv'): Collection
    {
        $orders = new Collection();
        $headers = [];
        $row = 1;

        if (($handle = fopen(Storage::path($csvPath), 'r')) !== false) {
            while (($data = fgetcsv($handle)) !== false) {
                if ($row++ === 1) {
                    $headers = $data;
                } else {
                    $attributes = [];
                    foreach ($headers as $index => $attribute) {
                        $attributes[$attribute] = $data[$index];
                    }

                    Validator::validate($attributes, [
                        'product_id' => ['required', 'integer'],
                        'quantity' => ['required', 'integer', 'min:0'],
                        'priority' => ['required', Rule::in(array_keys(Order::PRIORITIES))],
                        'created_at' => ['required', 'date'],
                    ]);

                    $order = new Order($attributes);
                    $orders->push($order);
                }
            }
            fclose($handle);
        } else {
            throw new \RuntimeException('The order csv file cannot be opened!');
        }

        return $orders->sortBy([
            ['priority', 'desc'],
            ['created_at', 'asc'],
        ]);
    }
}
