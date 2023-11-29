<?php

declare(strict_types=1);

namespace Tests;

use GildedRose\GildedRose;
use GildedRose\Item;
use PHPUnit\Framework\TestCase;

class GildedRoseTest extends TestCase
{
    private array $items = [];
    private GildedRose $app;
    private string $expectedString;
    public function setUp(): void
    {
        parent::setUp();

        $this->items = [
            new Item('+5 Dexterity Vest', 10, 20),
            new Item('Aged Brie', 2, 0),
            new Item('Elixir of the Mongoose', 5, 7),
            new Item('Sulfuras, Hand of Ragnaros', 0, 80),
            new Item('Sulfuras, Hand of Ragnaros', -1, 80),
            new Item('Backstage passes to a TAFKAL80ETC concert', 15, 20),
            new Item('Backstage passes to a TAFKAL80ETC concert', 10, 49),
            new Item('Backstage passes to a TAFKAL80ETC concert', 5, 49),
            // this conjured item does not work properly yet
            new Item('Conjured Mana Cake', 3, 6),
        ];

        $this->app = new GildedRose($this->items);
        $this->expectedString = file_get_contents(__DIR__ . '/approvals/ApprovalTest.testTestFixture.approved.txt');
    }

    private function batchUpdateQuality(int $days): string
    {
        $output = 'OMGHAI!' . PHP_EOL;
        for ($i = 0; $i < $days; $i++) {
            $output .= "-------- day ${i} --------" . PHP_EOL;
            $output .= 'name, sellIn, quality' . PHP_EOL;
            foreach ($this->items as $item) {
                $output .= $item . PHP_EOL;
            }
            $output .= PHP_EOL;
            $this->app->updateQuality();
        }

        return $output;
    }

    public function test31Days() {
        $output = $this->batchUpdateQuality(31);
        $this->assertEquals($this->expectedString, $output);
    }
}
