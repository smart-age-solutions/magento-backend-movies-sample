<?php

namespace Peteleco\Movie\Controller\Movie;

use Magento\Customer\Model\Session;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Http\Context as AuthContext;
use Magento\Framework\App\ResponseInterface;
use Peteleco\Movie\Model\MovieFavoriteFactory;

class AddToFavorite extends Action
{
    private $customerSession;
    private $authContext;

    /**
     * @var MovieFavoriteFactory
     */
    private $movieFavoriteFactory;

    public function __construct(
        Context $context,
        Session $session,
        AuthContext $authContext,
        MovieFavoriteFactory $movieFavoriteFactory
    )
    {
        $this->customerSession = $session;
        $this->authContext = $authContext;

        parent::__construct($context);

        $this->movieFavoriteFactory = $movieFavoriteFactory;
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
            $this->movieFavoriteFactory->create()
                ->setData(['movie_id' => $movieId, 'customer_id' => $customerId, 'store_id' => $this->getStoreId()])
                ->save();
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
