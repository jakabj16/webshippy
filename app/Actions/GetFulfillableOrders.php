<?php

namespace App\Actions;

use App\Models\Order;
use Illuminate\Support\Collection;
use Lorisleiva\Actions\Concerns\AsAction;

class GetFulfillableOrders
{
    use AsAction;

    /**
     * @param Collection $stock
     * @param Collection $orders
     * @return Collection
     */
    public function handle(Collection $stock, Collection $orders): Collection
    {
        $fulfillables = new Collection();

        if ($stock->isEmpty() || $orders->isEmpty()) {
            return $fulfillables;
        }

        /** @var Order $order */
        foreach ($orders as $order) {
            if (isset($stock[$order->product_id]) && $stock[$order->product_id]->quantity >= $order->quantity) {
                $fulfillables->push($order);
            }
        }

        return $fulfillables;
    }
}
