<?php

namespace AdminUI\AdminUILinnworks\Actions;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use AdminUI\AdminUILinnworks\Facades\Linnworks;

class GetStockAction
{
    public function execute(Collection|Model $products)
    {
        $products = Collection::wrap($products);
        $ids = $products->pluck('linnworks_stock_item_id')->filter(fn ($item) => !empty($item))->values();
        if ($ids->count() === 0) return;

        try {
            $stockLevels = $this->getStockLevels($ids);
        } catch (\Exception $e) {
            dd($e);
            Log::error($e->getMessage());
            return;
        }

        $ids->each(function ($id) use ($products, $stockLevels) {
            $product = $products->firstWhere('linnworks_stock_item_id', $id);
            $stock = $stockLevels->firstWhere('StockItemId', $id);
            if (!empty($product) && !empty($stock)) {
                $locationStock = Arr::first($stock['StockLevels'], function (mixed $item) {
                    $stockLocation = config('linnworks.live_stock_location');
                    return !empty($stockLocation) ?
                        $item['Location']['StockLocationId'] === $stockLocation :
                        true;
                });
                $product->quantity = $locationStock['Available'] ?? 0;
                $product->save();
            }
        });
    }

    private function getCacheKey(Collection $ids)
    {
        $prefix = "LW-";
        return $prefix . md5($ids->implode("", "."));
    }

    private function getStockLevels(Collection $ids)
    {
        $cacheKey = $this->getCacheKey($ids);
        return Cache::remember($cacheKey, 60 * 10, function () use ($ids) {
            $params = [
                "request" => [
                    "StockItemIds" => $ids->toArray(),
                    "DataRequirements" => [
                        "StockLevels"
                    ]
                ]
            ];

            $response = Linnworks::fetch("post", "Stock/GetStockItemsFullByIds", $params);

            if (isset($response['Message'])) {
                throw new \Exception($response['Message']);
            } else return collect($response['StockItemsFullExtended']);
        });
    }
}
