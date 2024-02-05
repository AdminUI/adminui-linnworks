<?php

namespace AdminUI\AdminUILinnworks\Actions;

use AdminUI\AdminUI\Models\Order;
use AdminUI\AdminUI\Models\OrderItem;
use AdminUI\AdminUILinnworks\Facades\Linnworks;

class PushOrderAction
{
    public function execute(Order $order)
    {
        $stockLocation = config('linnworks.live_stock_location') ?? "Default";

        $params = [
            "location" => $stockLocation,
            "orders" => [
                [
                    "OrderItems" => $order->lines->map(fn ($line) => $this->mapOrderItem($line)),
                    "ReferenceNumber" => $order->id,
                    "DispatchBy" => $order->created_at,
                    "ReceivedDate" => $order->created_at,
                    "Source" => "AdminUI",
                    "SubSource" => "Max Motorcycles Website"
                ]
            ]
        ];

        Linnworks::fetch(method: "post", endpoint: "Orders/CreateOrders", data: $params);
    }

    private function mapOrderItem(OrderItem $item)
    {
        $product = $item->modelItem;
        return [
            'ChannelSKU' => $product->linnworks_stock_item_id,
            'ItemNumber' => $product->id,
            'Qty' => $item->qty,
            'PricePerUnit' => $item->item_exc_tax,
            'TaxRate' => $item->tax_rate,
            'ItemTitle' => $item->product_name
        ];
    }
}
