<?php
namespace Sas\Movies\Ui\DataProvider\Movies;

class Favorite extends \Magento\Catalog\Ui\DataProvider\Product\ProductDataProvider
{
    public function getData()
    {
        $this->getCollection()
            ->addFieldToFilter('type_id', 'movie')
            ->setPageSize(10)
            ->addOrder('favorite');

        if (!$this->getCollection()->isLoaded()) {
            $this->getCollection()->load();
        }

        $items = $this->getCollection()->toArray();

        $data = [
            'totalRecords' => $this->getCollection()->getSize(),
            'items' => array_values($items),
        ];

        return $data;
    }
}
