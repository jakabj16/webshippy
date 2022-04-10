<?php

namespace Tests\Unit;

use App\Actions\GetFulfillableOrders;
use App\Models\Order;
use App\Models\Stock;
use Illuminate\Support\Collection;
use Tests\TestCase;

class GetFulfillableOrdersActionTest extends TestCase
{
    protected $stock;
    protected $orders_1;
    protected $orders_2;

    protected function setUp(): void
    {
        parent::setUp();

        $this->stock = new Collection();
        $this->stock->put(1, new Stock(['product_id' => 1, 'quantity' => 2]));
        $this->stock->put(2, new Stock(['product_id' => 2, 'quantity' => 3]));
        $this->stock->put(3, new Stock(['product_id' => 3, 'quantity' => 4]));

        $this->orders_1 = new Collection();
        $this->orders_1->push(new Order(['product_id' => 1, 'quantity' => 1, 'priority' => 1, 'created_at' => '2022-04-10 10:10:10']));
        $this->orders_1->push(new Order(['product_id' => 2, 'quantity' => 2, 'priority' => 2, 'created_at' => '2022-04-10 11:11:11']));
        $this->orders_1->push(new Order(['product_id' => 3, 'quantity' => 3, 'priority' => 3, 'created_at' => '2022-04-10 12:12:12']));

        $this->orders_2 = new Collection();
        $this->orders_2->push(new Order(['product_id' => 1, 'quantity' => 10, 'priority' => 3, 'created_at' => '2022-04-10 10:10:10']));
        $this->orders_2->push(new Order(['product_id' => 2, 'quantity' => 2, 'priority' => 3, 'created_at' => '2022-04-10 11:11:11']));
        $this->orders_2->push(new Order(['product_id' => 3, 'quantity' => 3, 'priority' => 3, 'created_at' => '2022-04-10 12:12:12']));
    }

    public function test_empty_stock_empty_orders()
    {
        /** @var Collection $fulfillables */
        $fulfillables = GetFulfillableOrders::run(new Collection(), new Collection());

        $this->assertEquals(0, $fulfillables->count());
    }

    public function test_empty_stock()
    {
        /** @var Collection $fulfillables */
        $fulfillables = GetFulfillableOrders::run($this->stock, new Collection());

        $this->assertEquals(0, $fulfillables->count());
    }

    public function test_empty_orders()
    {
        /** @var Collection $fulfillables */
        $fulfillables = GetFulfillableOrders::run(new Collection(), $this->orders_1);

        $this->assertEquals(0, $fulfillables->count());
    }

    public function test_valid_stock_valid_orders_1()
    {
        /** @var Collection $fulfillables */
        $fulfillables = GetFulfillableOrders::run($this->stock, $this->orders_1);

        $this->assertEquals(3, $fulfillables->count());

        $this->assertEquals(1, $fulfillables[0]->product_id);
        $this->assertEquals(2, $fulfillables[1]->product_id);
        $this->assertEquals(3, $fulfillables[2]->product_id);
    }

    public function test_valid_stock_valid_orders_2()
    {
        /** @var Collection $fulfillables */
        $fulfillables = GetFulfillableOrders::run($this->stock, $this->orders_2);

        $this->assertEquals(2, $fulfillables->count());

        $this->assertEquals(2, $fulfillables[0]->product_id);
        $this->assertEquals(3, $fulfillables[1]->product_id);
    }
}


