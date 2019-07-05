<?php
namespace Sas\Movies\Controller\Index;

use Magento\Catalog\Model\Layer\Resolver;
use Magento\Catalog\Model\Session;
use Magento\Framework\App\Action\Context;
use Magento\Search\Model\QueryFactory;
use Magento\Store\Model\StoreManagerInterface;

class Index extends \Magento\CatalogSearch\Controller\Result\Index
{
    /**
     * @var Resolver
     */
    private $layerResolver;
    /**
     * @var QueryFactory
     */
    private $queryFactory;

    public function __construct(
        Context $context,
        Session $catalogSession,
        StoreManagerInterface $storeManager,
        QueryFactory $queryFactory,
        Resolver $layerResolver
    ) {
        parent::__construct($context, $catalogSession, $storeManager, $queryFactory, $layerResolver);
        $this->layerResolver = $layerResolver;
        $this->queryFactory = $queryFactory;
    }

    public function execute()
    {
        $this->layerResolver->create(Resolver::CATALOG_LAYER_SEARCH);

        /* @var $query \Magento\Search\Model\Query */
        $query = $this->queryFactory->get();

        $storeId = $this->_storeManager->getStore()->getId();
        $query->setStoreId($storeId);

        $getAdditionalRequestParameters = $this->getRequest()->getParams();
        unset($getAdditionalRequestParameters[QueryFactory::QUERY_VAR_NAME]);

        $handles = null;
        if ($query->getNumResults() == 0) {
            $this->_view->getPage()->initLayout();
            $handles = $this->_view->getLayout()->getUpdate()->getHandles();
            $handles[] = static::DEFAULT_NO_RESULT_HANDLE;
        }

        $this->_view->loadLayout($handles);
        $this->_view->renderLayout();
    }
}
