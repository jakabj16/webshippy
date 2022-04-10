<?php

namespace App\Console\Commands;

use App\Actions\GetFulfillableOrders;
use App\Actions\ReadOrdersFromCsv;
use App\Actions\ReadStockFromJson;
use App\Models\Order;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

class GetFulfillableOrdersCommand extends Command
{
    protected $signature = 'webshippy:get-fulfillable-orders {stock : The stock stored in JSON format.}';

    protected $description = 'The service returns the fulfillable orders by the input stock parameter sorted by priority and created date.';

    /**
     * @return int
     */
    public function handle()
    {
        if (!$this->hasArgument('stock')) {
            $this->error('Ambiguous number of parameters!');
            return Command::FAILURE;
        }

        if ($inputStock = $this->argument('stock')) {
            try {
                $stock = ReadStockFromJson::run($inputStock);
                $orders = ReadOrdersFromCsv::run('orders.csv');

                $fulfillableOrders = GetFulfillableOrders::run($stock, $orders);

                $this->showFulfillableOrders($fulfillableOrders);

                return Command::SUCCESS;
            } catch (\JsonException $exception) {
                $this->error('Invalid json!');
            } catch (\Exception $exception) {
                $this->error($exception->getMessage());
            }
        } else {
            $this->error('Invalid json!');
        }
        return Command::FAILURE;
    }

    /**
     * @param Collection $fulfillableOrders
     * @return void
     */
    private function showFulfillableOrders(Collection $fulfillableOrders)
    {
        $headers = (new Order)->getFillable();

        foreach ($headers as $header) {
            $this->output->write(str_pad($header, 20));
        }
        $this->output->newLine();
        $this->output->writeln(str_repeat('=', count($headers) * 20));

        /** @var Order $fulfillableOrder */
        foreach ($fulfillableOrders as $fulfillableOrder) {
            foreach ($headers as $header) {
                $this->output->write($fulfillableOrder->present($header));
            }
            $this->output->newLine();
        }
    }
}
