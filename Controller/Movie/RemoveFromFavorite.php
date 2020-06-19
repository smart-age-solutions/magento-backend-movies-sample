<?php

namespace Peteleco\Movie\Controller\Movie;

use Magento\Customer\Model\Session;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Http\Context as AuthContext;
use Magento\Framework\App\ResponseInterface;
use Peteleco\Movie\Model\MovieFavoriteFactory;
use Peteleco\Movie\Model\ResourceModel\MovieFavorite\CollectionFactory;

class RemoveFromFavorite extends Action
{
    private $customerSession;
    private $authContext;

    /**
     * @var MovieFavoriteFactory
     */
    private $movieFavoriteFactory;

    protected $collectionFactory;

    public function __construct(
        Context $context,
        Session $session,
        AuthContext $authContext,
        MovieFavoriteFactory $movieFavoriteFactory,
        CollectionFactory $collectionFactory
    )
    {
        $this->customerSession = $session;
        $this->authContext = $authContext;

        parent::__construct($context);

        $this->movieFavoriteFactory = $movieFavoriteFactory;
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * Todo: Check if movie was already add
     * @see https://meetanshi.com/blog/check-if-a-customer-is-logged-into-magento-2/
     * @return bool|ResponseInterface|\Magento\Framework\Controller\ResultInterface|void
     */
    public function execute()
    {
        $movieId = ((int)$this->getRequest()->getParam('id')) ?: null;

        $isLoggedIn = $this->authContext->getValue(\Magento\Customer\Model\Context::CONTEXT_AUTH);

        if (!$isLoggedIn) {
            $this->customerSession->setAfterAuthUrl($this->getCurrentUrl());
            return $this->customerSession->authenticate();
        }

        if ($isLoggedIn && $movieId) {
            $customerId = $this->customerSession->getCustomer()->getId();

            // Search for the correct customer x movie
            $collection = $this->collectionFactory->create();
            $collection->addFieldToFilter('movie_id', $movieId);
            $collection->addFieldToFilter('store_id', $this->getStoreId());
            $collection->addFieldToFilter('customer_id', $customerId);
            if ($collection->getSize() > 0) {
                $favoriteMovie = $collection->getItems();
                array_map(function ($item) {
                    $item->delete()->save();
                }, $favoriteMovie);

                // $favoriteMovie->delete();
//                $movie = $this->movieFavoriteFactory->create()->setId($movieId)
//                    ->delete();
            }

        }
        return $this->resultRedirectFactory->create()->setPath('movie/movie/view/' . 'id/' . $movieId);
    }

    /**
     * Get store identifier
     *
     * @return  int
     */
    private function getStoreId()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $storeManager = $objectManager->get('\Magento\Store\Model\StoreManagerInterface');
        return $storeManager->getStore()->getStoreId();
    }

    private function getCurrentUrl()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        return ($objectManager->get('\Magento\Framework\UrlInterface'))->getCurrentUrl();
    }
}
