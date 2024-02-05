<?php

namespace AdminUI\AdminUILinnworks\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use AdminUI\AdminUI\Models\Product;
use AdminUI\AdminUI\Traits\CliTrait;
use AdminUI\AdminUI\Traits\DiscordTrait;
use AdminUI\AdminUILinnworks\Facades\Linnworks;
use AdminUI\AdminUILinnworks\Actions\GetStockAction;
use AdminUI\AdminUILinnworks\Actions\GetStockIdsAction;

class ImportStockLevels extends Command
{
    use CliTrait, DiscordTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'adminui:linnworks-stock-levels';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Match AdminUI Stock Level via SKU Code.';

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
    public function handle(GetStockAction $action)
    {
        $this->cliMessage('Matching Stock to Skus.');
        $file = fopen('not-found-on-linnworks' . date('Y-m-d') . '.csv', 'w');

        $count = 0;
        Product::whereNotNull('linnworks_stock_item_id')->active()->chunk(200, function (Collection $products) use ($action, &$count) {
            $count++;
            $this->cliMessage('Updating Page ' . $count);
            $action->execute($products);
        });

        fclose($file);

        $this->cliMessage('All finished');
    }
}
