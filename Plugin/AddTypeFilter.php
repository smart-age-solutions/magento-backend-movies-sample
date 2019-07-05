<?php
namespace Sas\Movies\Plugin;

use Magento\Catalog\Model\Layer\ItemCollectionProviderInterface;
use Magento\Catalog\Model\ResourceModel\Product\Collection;
use Magento\Framework\App\RequestInterface;

class AddTypeFilter
{
    /**
     * @var RequestInterface
     */
    private $request;

    public function __construct(RequestInterface $request)
    {
        $this->request = $request;
    }

    public function afterGetCollection(ItemCollectionProviderInterface $collectionProvider, Collection $collection)
    {
        if ($this->request->getModuleName() == 'movies') {
            $collection->getSelect()
                ->where('type_id = ?', 'movie');
        }

        return $collection;
    }
}
