<?php

namespace Tests\Unit;

use App\Actions\ReadOrdersFromCsv;
use App\Models\Order;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class ReadOrdersFromCsvActionTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        file_put_contents(Storage::path('invalid_1.csv'),
            "invalid\ninvalid\n"
        );

        file_put_contents(Storage::path('invalid_2.csv'),
            "product_id,quantity,priority\n" .
            "1,2,3\n"
        );

        file_put_contents(Storage::path('invalid_3.csv'),
            "product_id,quantity,priority,created_at\n" .
            "1,2,4,2021-03-25 14:51:47\n"
        );

        file_put_contents(Storage::path('valid_1.csv'),
        "product_id,quantity,priority,created_at\n" .
            "1,2,3,2021-03-25 14:51:47\n" .
            "2,1,2,2021-03-21 14:00:26\n" .
            "2,4,1,2021-03-22 17:41:32\n" .
            "3,1,2,2021-03-22 12:31:54\n"
        );

        file_put_contents(Storage::path('valid_2.csv'),
            "product_id,quantity,priority,created_at\n" .
            "1,2,1,2021-03-25 14:51:47\n" .
            "3,1,3,2021-03-21 14:00:26\n" .
            "2,4,3,2021-03-22 17:41:32\n"
        );
    }

    protected function tearDown(): void
    {
        Storage::delete('invalid_1.csv');
        Storage::delete('invalid_2.csv');
        Storage::delete('invalid_3.csv');
        Storage::delete('valid_1.csv');
        Storage::delete('valid_2.csv');

        parent::tearDown();
    }

    public function test_empty_csv_path_parameter()
    {
        $this->expectException(\RuntimeException::class);

        ReadOrdersFromCsv::run('');
    }

    public function test_invalid_csv_path_parameter()
    {
        $this->expectException(\RuntimeException::class);

        ReadOrdersFromCsv::run('invalid');
    }

    public function test_invalid_csv_1()
    {
        $this->expectException(ValidationException::class);

        ReadOrdersFromCsv::run('invalid_1.csv');
    }

    public function test_invalid_csv_2()
    {
        $this->expectException(ValidationException::class);

        ReadOrdersFromCsv::run('invalid_2.csv');
    }

    public function test_invalid_csv_3()
    {
        $this->expectException(ValidationException::class);

        ReadOrdersFromCsv::run('invalid_3.csv');
    }

    public function test_valid_csv_1()
    {
        $orders = ReadOrdersFromCsv::run('valid_1.csv');

        $this->assertCount(4, $orders);

        /** @var Order $firstOrder */
        $firstOrder = $orders->first();
        $this->assertEquals(1, $firstOrder->product_id);
        $this->assertEquals(2, $firstOrder->quantity);
        $this->assertEquals(3, $firstOrder->priority);
        $this->assertEquals('2021-03-25 14:51:47', $firstOrder->created_at);

        $this->assertEquals(Order::PRIORITY_HIGH, $firstOrder->priorityName);
    }

    public function test_valid_csv_2()
    {
        $orders = ReadOrdersFromCsv::run('valid_2.csv');

        $this->assertCount(3, $orders);

        /** @var Order $firstOrder */
        $firstOrder = $orders->first();
        $this->assertEquals(3, $firstOrder->product_id);
        $this->assertEquals(1, $firstOrder->quantity);
        $this->assertEquals(3, $firstOrder->priority);
        $this->assertEquals('2021-03-21 14:00:26', $firstOrder->created_at);

        $this->assertEquals(Order::PRIORITY_HIGH, $firstOrder->priorityName);
    }
}
