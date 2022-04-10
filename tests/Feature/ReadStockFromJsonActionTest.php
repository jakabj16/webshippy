<?php

namespace Tests\Unit;

use App\Actions\ReadStockFromJson;
use App\Models\Stock;
use Illuminate\Support\Collection;
use Tests\TestCase;

class ReadStockFromJsonActionTest extends TestCase
{
    public function test_empty_stock_argument()
    {
        $this->expectException(\JsonException::class);

        ReadStockFromJson::run('');
    }

    public function test_invalid_text_stock_argument()
    {
        $this->expectException(\JsonException::class);

        ReadStockFromJson::run('invalid');
    }

    public function test_invalid_json_stock_argument()
    {
        $this->expectException(\JsonException::class);

        ReadStockFromJson::run('{"1": 1');
    }

    public function test_valid_json_stock_argument()
    {
        /** @var Collection $stock */
        $stock = ReadStockFromJson::run('{"1": 2, "2": 3}');
        $this->assertCount(2, $stock);

        /** @var Stock $firstStock */
        $firstStock = $stock->first();
        $this->assertEquals(1, $firstStock->product_id);
        $this->assertEquals(2, $firstStock->quantity);
    }
}
