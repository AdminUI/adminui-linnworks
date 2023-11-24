<?php

namespace AdminUI\AdminUILinnworks\Actions;

use AdminUI\AdminUILinnworks\Facades\Linnworks;

class GetStockIdsAction
{
    public function execute(int $page = 1, string $sku = null)
    {
        $params = [
            'pageNumber' => $page,
            'entriesPerPage' => 200,
            'searchTypes' => ['SKU'],
            'loadVariationParents' => true,
            'loadCompositeParents' => true
        ];
        if (!empty($sku)) {
            $params['keyword'] = $sku;
        }
        $response = Linnworks::fetch("post", "Stock/GetStockItemsFull", $params);
        if (isset($response['Message'])) {
            throw new \Exception($response['Message']);
        } else return $response;
    }
}
