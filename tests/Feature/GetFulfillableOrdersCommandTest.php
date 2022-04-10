<?php

namespace Tests\Unit;

use App\Actions\GetFulfillableOrders;
use App\Models\Order;
use App\Models\Stock;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Tests\TestCase;

class GetFulfillableOrdersCommandTest extends TestCase
{
    public function test_not_enough_arguments()
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Not enough arguments (missing: "stock").');

        $this->artisan('webshippy:get-fulfillable-orders')
            ->expectsOutput('Ambiguous number of parameters!')
            ->assertExitCode(Command::FAILURE);
    }

    public function test_bad_arguments()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->artisan('webshippy:get-fulfillable-orders', ['bad' => '{"1":1,"2":1,"3":1}']);
    }

    public function test_invalid_stock_argument()
    {
        $this->artisan('webshippy:get-fulfillable-orders', ['stock' => 'invalid'])
            ->expectsOutput('Invalid json!')
            ->assertExitCode(Command::FAILURE);
    }

    public function test_valid_stock_arguments()
    {
        $this->artisan('webshippy:get-fulfillable-orders', ['stock' => '{"1":1,"2":1,"3":1}'])
            ->assertExitCode(Command::SUCCESS);
    }
}


