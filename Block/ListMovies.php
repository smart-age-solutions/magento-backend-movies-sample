<?php

namespace Peteleco\Movie\Block;

use Magento\Framework\DataObject;
use Magento\Framework\View\Element\Template;
use Peteleco\Movie\Model\Movie;
use Peteleco\Movie\Model\ResourceModel\Movie\Collection;
use Peteleco\Movie\Model\ResourceModel\Movie\CollectionFactory;

class ListMovies extends Template
{
    protected $collectionFactory;

    protected $movieCollection;

    public function __construct(
        Template\Context $context,
        CollectionFactory $collectionFactory,
        array $data = []
    )
    {
        parent::__construct($context, $data);
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * @param Collection $movieCollection
     * @return ListMovies
     */
    protected function setMovieCollection($movieCollection)
    {
        $this->movieCollection = $movieCollection;
        return $this;
    }

    /**
     * @return Collection
     */
    protected function getMovieCollection()
    {
        return $this->movieCollection;
    }

    /**
     * Get store identifier
     *
     * @return  int
     */
    protected function getStoreId()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $storeManager = $objectManager->get('\Magento\Store\Model\StoreManagerInterface');
        return $storeManager->getStore()->getStoreId();
    }

    protected function prepareMovieCollection()
    {
        $page = ((int)$this->getRequest()->getParam('p')) ?: 1;
        $query = ((string)$this->getRequest()->getParam('q')) ?: null;

        $pageSize = ($this->getRequest()->getParam('limit')) ?: 5;
        $collection = $this->collectionFactory->create();

        if ($query) {
            $collection->addFieldToFilter('title', [
                'like' => '%' . $query . '%'
            ]);
        }

        $collection->addFieldToFilter('enabled', true);
        $collection->addFieldToFilter('store_id', $this->getStoreId());
        $collection->setOrder('title', 'ASC');
        $collection->setPageSize($pageSize);
        $collection->setCurPage($page);

        return $this->setMovieCollection($collection);
    }

    /**
     * @return DataObject[]|Movie[]
     */
    public function getItems()
    {
        return $this->movieCollection->getItems();
    }

    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        $this->prepareMovieCollection();
        if ($this->getMovieCollection()) {
            $pager = $this->getLayout()
                ->createBlock(
                    'Magento\Theme\Block\Html\Pager',
                    'custom.history.pager'
                )->setAvailableLimit([2 => 2, 5 => 5, 10 => 10, 15 => 15, 20 => 20])
                ->setShowPerPage(true)
                ->setCollection($this->getMovieCollection());
            $this->setChild('pager', $pager);
            $this->getMovieCollection()->load();
        }

        return $this;
    }

    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }
}
