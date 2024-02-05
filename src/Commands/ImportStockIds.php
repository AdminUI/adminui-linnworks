<?php

namespace AdminUI\AdminUILinnworks\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use AdminUI\AdminUI\Models\Product;
use AdminUI\AdminUI\Traits\CliTrait;
use AdminUI\AdminUI\Traits\DiscordTrait;
use AdminUI\AdminUILinnworks\Facades\Linnworks;
use AdminUI\AdminUILinnworks\Actions\GetStockIdsAction;

class ImportStockIds extends Command
{
    use CliTrait, DiscordTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'adminui:linnworks-stock';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Match AdminUI Products to Linnworks Products via SKU Code.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(GetStockIdsAction $action)
    {
        $this->cliMessage('Matching SKU Codes with Linnworks for all products.');
        $file = fopen('not-found-' . date('Y-m-d') . '.csv', 'w');

        $page = 0;
        $break = false;
        $notFound = collect();
        while ($break === false) {
            $page++;
            $this->info('Processing page ' . $page);
            try {
                $response = $action->execute($page);
            } catch (\Exception $e) {
                $message = $e->getMessage();
                if (Str::startsWith($message, 'No items found')) {
                    $this->cliInfo("No results found on this page");
                } else {
                    $this->cliFailed($message);
                }
                $response = [];
                $break = true;
            }

            foreach ($response as $item) {
                $product = Product::where('sku_code', $item['ItemNumber'])->first();
                if ($product) {
                    $product->linnworks_stock_item_id = $item['StockItemId'];
                    $product->save();
                } else {
                    $notFound->add($item['StockItemId']);
                    fputcsv($file, [
                        'sku_code' => $item['ItemNumber'],
                        'name' => $item['ItemTitle'],
                        'linnworks_stock_item_id' => $item['StockItemId']
                    ]);
                }
                sleep(0.5);
            }
        }
        $this->cliMessage('Products unable to match: ' . $notFound->count());
        fclose($file);

        $this->cliMessage('All finished');
    }
}
